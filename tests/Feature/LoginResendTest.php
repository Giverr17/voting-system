<?php

use App\Enums\PreRegistrationStatus;
use App\Livewire\Login;
use App\Models\PreRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function makeEligibleVoter(): User
{
    $pre = PreRegistration::create([
        'mat_no'    => 'MAT777',
        'full_name' => 'Resend Voter',
        'status'    => PreRegistrationStatus::APPROVED,
    ]);

    return User::create([
        'pre_registration_id' => $pre->id,
        'username'            => 'Resend Voter',
        'email'              => 'resend@example.com',
        'mat_no'             => 'MAT777',
        'department'         => 'Computer Engineering',
        'level'              => '400',
        'password'           => Hash::make('secret123'),
        'role'               => 'user',
        'code'               => 'OLD123',
        'has_voted'          => false,
    ]);
}

it('resends a fresh entry password and starts the cooldown', function () {
    Mail::fake();
    $voter = makeEligibleVoter();

    Livewire::test(Login::class)
        ->set('identifier', 'MAT777')
        ->set('showCodeField', true)
        ->set('resendEndsAt', 0) // cooldown elapsed
        ->call('resendCode')
        ->assertSet('emailMessage', 'A new entry password has been sent to your email');

    // A brand-new code replaced the old one, and the cooldown is now in the future.
    $fresh = $voter->fresh();
    expect($fresh->code)->not->toBe('OLD123');
    expect($fresh->code)->not->toBeNull();
    expect($fresh->code)->toHaveLength(6);
});

it('blocks resend while the cooldown is still active', function () {
    Mail::fake();
    $voter = makeEligibleVoter();

    Livewire::test(Login::class)
        ->set('identifier', 'MAT777')
        ->set('showCodeField', true)
        ->set('resendEndsAt', now()->addSeconds(45)->timestamp) // still cooling down
        ->call('resendCode')
        ->assertSet('errorMessage', 'Please wait before requesting another entry password.');

    // The code was NOT regenerated, and no mail went out.
    expect($voter->fresh()->code)->toBe('OLD123');
    Mail::assertNothingQueued();
});
