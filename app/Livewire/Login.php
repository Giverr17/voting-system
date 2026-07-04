<?php

namespace App\Livewire;

use App\Enums\PreRegistrationStatus;
use App\Mail\SendOTP;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Login extends Component
{
    public $identifier = '';
    public $password = '';
    public $code = '';

    public $isAdmin = false;
    public $showCodeField = false;
    public $errorMessage = '';
    public $emailMessage = '';

    // Resend throttling: how long (seconds) the resend button stays disabled
    // after each send, and the epoch timestamp when it becomes available again.
    public $resendCooldown = 60;
    public $resendEndsAt = 0;

    /**
     * Removed wire:model.live — no more auto-lookup while typing.
     * Everything now happens when the user clicks Login.
     */

    private function sendOtp($user)
    {
        if (!$user) return false;

        $plainCode = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
        $user->update(['code' => $plainCode]);

        // Retry up to 5 times in case email fails
        $maxAttempts = 5;
        $lastException = null;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                Mail::to($user->email)->send(new SendOTP($user));
                $this->emailMessage = 'Your entry password has been sent to your email';
                return true;
            } catch (\Exception $e) {
                $lastException = $e;
                \Illuminate\Support\Facades\Log::warning("Entry password send attempt {$attempt}/{$maxAttempts} failed for {$user->email}: " . $e->getMessage());

                if ($attempt < $maxAttempts) {
                    usleep(500000); // 0.5 second pause between retries
                }
            }
        }

        // All attempts failed
        \Illuminate\Support\Facades\Log::error("Entry password send FAILED after {$maxAttempts} attempts for {$user->email}: " . ($lastException ? $lastException->getMessage() : 'Unknown error'));
        $this->errorMessage = 'Failed to send entry password. Please try again.';
        // Reset the code so user can retry
        $user->update(['code' => null]);
        return false;
    }

    public function login()
    {
        $this->resetValidation();
        $this->errorMessage = '';
        $this->emailMessage = '';

        $this->validate([
            'identifier' => 'required|string',
        ]);

        $value = trim($this->identifier);

        // --- Admin login ---
        $admin = User::where('email', $value)
            ->where('role', 'admin')
            ->first();

        if ($admin) {
            // Step 1: First "Continue" just reveals the password field — no error yet.
            if (!$this->isAdmin) {
                $this->isAdmin = true;
                return;
            }

            // Step 2: Password field is visible — now verify it.
            $this->validate([
                'password' => 'required|string',
            ]);

            if (Hash::check($this->password, $admin->password)) {
                Auth::login($admin);
                session()->flash('success', 'Welcome back, Admin!');
                return redirect()->route('admin-index');
            } else {
                $this->addError('identifier', 'Invalid admin credentials');
                return;
            }
        }

        // --- Regular user login ---
        $user = User::where(function ($query) use ($value) {
            $query->where('mat_no', $value)
                ->orWhere('email', $value);
        })->where('role', 'user')
            ->with('preRegistration')
            ->first();

        if (!$user) {
            $this->errorMessage = 'User not found';
            return;
        }

        if ($user->has_voted) {
            $this->errorMessage = 'User has already voted';
            return;
        }

        if (!$user->preRegistration || $user->preRegistration->status !== PreRegistrationStatus::APPROVED) {
            $this->errorMessage = 'Your registration is not yet approved';
            return;
        }

        // --- Step 1: If no entry-password field shown yet, send it ---
        if (!$this->showCodeField) {
            // User already has an entry password from a previous attempt
            if ($user->code) {
                $this->showCodeField = true;
                $this->emailMessage = 'An entry password was already sent to your email. Enter it below.';
                return;
            }

            // Generate and send a new entry password
            if ($this->sendOtp($user)) {
                $this->showCodeField = true;
                // Start the resend cooldown — they just received an email.
                $this->resendEndsAt = now()->addSeconds($this->resendCooldown)->timestamp;
            }
            return;
        }

        // --- Step 2: Verify entry password ---
        if (empty($this->code)) {
            $this->addError('identifier', 'Please enter the entry password sent to your email');
            return;
        }

        if ($user->code !== strtoupper(trim($this->code))) {
            $this->addError('identifier', 'Invalid entry password');
            return;
        }

        // All good — login user
        Auth::login($user);
        session()->flash('success', 'Login successful!');
        return redirect('/vote');
    }

    public function resendCode()
    {
        $this->resetValidation();
        $this->errorMessage = '';
        $this->emailMessage = '';

        // Only relevant once the entry-password step is active.
        if (!$this->showCodeField) {
            return;
        }

        // Server-side cooldown guard (the button is also disabled client-side).
        if ($this->resendEndsAt && now()->timestamp < $this->resendEndsAt) {
            $this->errorMessage = 'Please wait before requesting another entry password.';
            return;
        }

        $value = trim($this->identifier);

        $user = User::where(function ($query) use ($value) {
            $query->where('mat_no', $value)
                ->orWhere('email', $value);
        })->where('role', 'user')
            ->with('preRegistration')
            ->first();

        // Re-run the same eligibility checks as login().
        if (!$user) {
            $this->errorMessage = 'User not found';
            return;
        }

        if ($user->has_voted) {
            $this->errorMessage = 'User has already voted';
            return;
        }

        if (!$user->preRegistration || $user->preRegistration->status !== PreRegistrationStatus::APPROVED) {
            $this->errorMessage = 'Your registration is not yet approved';
            return;
        }

        // Generate a fresh entry password (this replaces any previous one) and email it.
        if ($this->sendOtp($user)) {
            $this->emailMessage = 'A new entry password has been sent to your email';
            $this->resendEndsAt = now()->addSeconds($this->resendCooldown)->timestamp;
        }
    }

    public function render()
    {
        return view('livewire.login');
    }
}
