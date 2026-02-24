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

        // Normalize: trim whitespace, strip invisible chars (BOM etc), uppercase
        $cleanMatNo = strtoupper(trim(preg_replace('/[\x{FEFF}\x{200B}\x{00EF}\x{00BB}\x{00BF}]/u', '', $this->mat_no)));
        $this->mat_no = $cleanMatNo;

        $preReg = PreRegistration::whereRaw('UPPER(TRIM(mat_no)) = ?', [$cleanMatNo])->first();

        if (!$preReg) {
            $this->addError('mat_no', 'Invalid matriculation number.');
            $this->verificationMessage = 'Invalid matriculation number.';
            $this->isVerified = false;
            return;
        }

        // Auto-fix corrupted mat_no in the database
        if ($preReg->mat_no !== $cleanMatNo) {
            $preReg->update(['mat_no' => $cleanMatNo]);
        }

        if (User::whereRaw('UPPER(TRIM(mat_no)) = ?', [$cleanMatNo])->exists()) {
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
