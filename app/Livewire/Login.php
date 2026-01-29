<?php

namespace App\Livewire;

use App\Enums\PreRegistrationStatus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Login extends Component
{
    public $identifier = '';
    public $password = '';
    public $code = '';

    public $isAdmin = false;
    public $showCodeField = false;
    public $errorMessage = '';

    public function updatedIdentifier($value)
    {
        $this->reset(['password', 'code', 'isAdmin', 'showCodeField']);
        $value = trim($value);
        if (empty($value)) {
            return;
        }

        $admin = User::where('email', $value)
            ->where('role', 'admin')
            ->first();

        if ($admin) {
            $this->isAdmin = true;
            $this->showCodeField = false;
            return;
        }

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
            $this->errorMessage = 'User has voted';
            return;
        }


        if (!$user->preRegistration || $user->preRegistration->status !== PreRegistrationStatus::APPROVED) {
            $this->errorMessage = 'Your registration is not yet approved';
            return;
        }

        if ($user->code) {
            $this->code = $user->getRawOriginal('code') ?? $user->code;
            $this->showCodeField = true;
            $this->isAdmin = false;
            return;
        }

        $plainCode = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));

        $user->update(['code' => $plainCode]);

        $this->code = $plainCode;
        $this->showCodeField = true;
        $this->isAdmin = false;
    }

    public function login()
    {

        $this->errorMessage = '';
        $this->validate([
            'identifier' => 'required|string',
        ]);

        if ($this->isAdmin) {
            $this->validate([
                'password' => 'required|string',
            ]);

            $admin = User::where('email', $this->identifier)
                ->where('role', 'admin')
                ->first();

            if ($admin && Hash::check($this->password, $admin->password)) {
                Auth::login($admin);
                session()->flash('success', 'Welcome back, Admin!');
                return redirect()->route('admin-index');
            } else {
                $this->addError('identifier', 'Invalid admin credentials');
                return;
            }
        }

        if (!$this->showCodeField || empty($this->code)) {
            $this->addError('identifier', 'User not found or no voting code assigned');
            return;
        }
        $user = User::where(function ($query) {
            $query->where('mat_no', $this->identifier)
                ->orWhere('email', $this->identifier);
        })
            ->where('role', 'user')
            ->with('preRegistration')
            ->first();

            if (!$user) {
                $this->addError('identifier', 'User not found');
                return;
            }
    
            if ($user->code !== $this->code) {
                $this->addError('identifier', 'Invalid voting code');
                return;
            }
    
            // Final checks before login
            if ($user->has_voted) {
                $this->addError('identifier', 'You have already voted');
                return;
            }
    
            if (!$user->preRegistration || $user->preRegistration->status !== PreRegistrationStatus::APPROVED) {
                $this->addError('identifier', 'Your registration is not approved');
                return;
            }
    
            // ✅ All good - login user
            Auth::login($user);
            session()->flash('success', 'Login successful!');
            return redirect('/vote');
    }


    // protected function generateVotingCode()
    // {
    //     $user = User::where('mat_no', $this->identifier)
    //         ->with('preRegistration')
    //         ->first();

    //     if (!$user || $user->has_voted || $user->preRegistration?->status !== PreRegistrationStatus::APPROVED) {
    //         return;
    //     }

    //     $plainCode = strtoupper(str()->random(6));

    //     $user->update([
    //         'code' => $plainCode
    //     ]);

    //     $this->code = $plainCode;
    // }

    // protected function adminLogin()
    // {
    //     $user = User::where('email', $this->identifier)
    //         ->where('role', 'admin')
    //         ->first();

    //     if (!$user || !Hash::check($this->password, $user->password)) {
    //         $this->addError('identifier', 'Invalid admin credentials');
    //         return;
    //     }

    //     Auth::login($user);
    //     redirect('/admin/dashboard');
    // }

    // protected function userLogin()
    // {
    //     $user = User::where('mat_no', $this->identifier)
    //         ->where('role', 'user')
    //         ->with('PreRegistration');

    //     if (
    //         !$user ||
    //         $user->has_voted ||
    //         !Hash::check($this->code, $user->code)
    //     ) {
    //         $this->addError('identifier', 'Invalid voting credentials');
    //         return;
    //     }

    //     Auth::login($user);
    //     redirect('/vote');
    // }

    public function render()
    {
        return view('livewire.login');
    }
}
