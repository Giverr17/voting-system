<?php

namespace App\Livewire;

use App\Enums\PreRegistrationStatus;
use App\Jobs\SendOtp as JobsSendOtp;
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
                Mail::to($user->email)->queue(new SendOTP($user));
                $this->emailMessage = 'OTP has been sent to your email';
                return true;
            } catch (\Exception $e) {
                $lastException = $e;
                \Illuminate\Support\Facades\Log::warning("OTP send attempt {$attempt}/{$maxAttempts} failed for {$user->email}: " . $e->getMessage());

                if ($attempt < $maxAttempts) {
                    usleep(500000); // 0.5 second pause between retries
                }
            }
        }

        // All attempts failed
        \Illuminate\Support\Facades\Log::error("OTP send FAILED after {$maxAttempts} attempts for {$user->email}: " . ($lastException ? $lastException->getMessage() : 'Unknown error'));
        $this->errorMessage = 'Failed to send OTP. Please try again.';
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
            $this->isAdmin = true;

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

        // --- Step 1: If no code field shown yet, send OTP ---
        if (!$this->showCodeField) {
            // User already has a code from a previous attempt
            if ($user->code) {
                $this->showCodeField = true;
                $this->emailMessage = 'An OTP was already sent to your email. Enter it below.';
                return;
            }

            // Generate and send new OTP
            if ($this->sendOtp($user)) {
                $this->showCodeField = true;
            }
            return;
        }

        // --- Step 2: Verify OTP code ---
        if (empty($this->code)) {
            $this->addError('identifier', 'Please enter the voting code sent to your email');
            return;
        }

        if ($user->code !== strtoupper(trim($this->code))) {
            $this->addError('identifier', 'Invalid voting code');
            return;
        }

        // All good — login user
        Auth::login($user);
        session()->flash('success', 'Login successful!');
        return redirect('/vote');
    }

    public function render()
    {
        return view('livewire.login');
    }
}
