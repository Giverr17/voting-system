<?php

namespace App\Livewire;

use App\Enums\PreRegistrationStatus;
use App\Models\PreRegistration;
use App\Models\User;
use Livewire\Component;
use PDO;

class LoginCheck extends Component
{
    public $mat_no = '';
    public $isVerified = false;
    public $verificationMessage = '';
    public $name = '';
    public $username = '';
    public $email = '';
    public $department = '';
    public $departments = 'Computer Engineering';
    public $level = '';
    public $levels = [
        200,
        300,
        400,
        500,
    ];

    public function checkMatNo()
    {
        $this->validate([
            'mat_no' => 'required|string'
        ]);
        $preReg = PreRegistration::where('mat_no', $this->mat_no)->first();

        if (!$preReg) {
            $this->addError('mat_no', 'Invalid matriculation number.');
            $this->verificationMessage = 'Invalid matriculation number.';
            $this->isVerified = false;
            return;
        }

        if (User::where('mat_no', $this->mat_no)->exists()) {
            $this->addError('mat_no', 'This matriculation number is already registered.');
            $this->verificationMessage = 'This matriculation number is already registered.';
            $this->isVerified = false;
            return;
        }

        $this->isVerified = true;
        $this->name = $preReg->full_name;
        $this->verificationMessage = 'Matriculation number verified! Please complete your registration.';
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'mat_no' => 'required|string',
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'department' => 'required|string',
            'level' => 'required|string',
        ]);
    }



    public function register()
    {
        if (!$this->isVerified) {
            session()->flash('error', 'Please verify your matriculation number first.');
            return;
        }
        $this->validate([
            'mat_no' => 'required|string',
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'department' => 'required|string',
            'level' => 'required|string',
        ]);

        $preId = PreRegistration::where('mat_no', $this->mat_no)->first();

        $preId->user()->create([
            'mat_no' => $this->mat_no,
            'pre_registration_id' => $preId->id,
            'username' => $this->username,
            'email' => $this->email,
            'department' => $this->department,
            'level' => $this->level,
        ]);
        PreRegistration::where('mat_no', $this->mat_no)->update(['status' => PreRegistrationStatus::REGISTERED]);

        session()->flash('success', 'Registration completed successfully!');
        return redirect()->route('welcome');
        $this->reset();
    }

    public function resetVerification()
    {
        $this->reset(['isVerified', 'verificationMessage', 'username', 'email', 'department', 'level']);
    }

    public function render()
    {
        return view('livewire.login-check');
    }
}
