<?php

use App\Livewire\AdminController;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function makeAdmin(): User
{
    return User::create([
        'username' => 'Admin',
        'email' => 'admin@election.com',
        'password' => Hash::make('secret123'),
        'role' => 'admin',
    ]);
}

it('toggles election status and persists it to the database', function () {
    $admin = makeAdmin();
    Setting::set('election_status', 'open');

    Livewire::actingAs($admin)->test(AdminController::class)
        ->assertSet('electionOpen', true)
        ->call('toggleElection')
        ->assertSet('electionOpen', false);

    expect(Setting::get('election_status'))->toBe('closed');
    expect(Setting::isElectionOpen())->toBeFalse();
});

it('toggles registration status and persists it to the database', function () {
    $admin = makeAdmin();
    Setting::set('registration_status', 'open');

    Livewire::actingAs($admin)->test(AdminController::class)
        ->assertSet('registrationOpen', true)
        ->call('toggleRegistration')
        ->assertSet('registrationOpen', false);

    expect(Setting::get('registration_status'))->toBe('closed');
});

it('rejects a wrong current password', function () {
    $admin = makeAdmin();

    Livewire::actingAs($admin)->test(AdminController::class)
        ->set('current_password', 'WRONG')
        ->set('new_password', 'NewPass@123')
        ->set('new_password_confirmation', 'NewPass@123')
        ->call('updatePassword')
        ->assertHasErrors('current_password');

    expect(Hash::check('secret123', $admin->fresh()->password))->toBeTrue();
});

it('updates the password with the correct current password', function () {
    $admin = makeAdmin();

    Livewire::actingAs($admin)->test(AdminController::class)
        ->set('current_password', 'secret123')
        ->set('new_password', 'NewPass@123')
        ->set('new_password_confirmation', 'NewPass@123')
        ->call('updatePassword')
        ->assertHasNoErrors();

    expect(Hash::check('NewPass@123', $admin->fresh()->password))->toBeTrue();
});

it('blocks registration when closed and allows it when open', function () {
    Setting::set('registration_status', 'closed');
    $this->get('/register')->assertForbidden();

    Setting::set('registration_status', 'open');
    $this->get('/register')->assertOk();
});

it('blocks voting route when election is closed', function () {
    $voter = User::create([
        'username' => 'Voter',
        'email' => 'voter@example.com',
        'password' => Hash::make('secret123'),
        'role' => 'user',
    ]);

    Setting::set('election_status', 'closed');
    $this->actingAs($voter)->get('/vote')->assertForbidden();

    Setting::set('election_status', 'open');
    $this->actingAs($voter)->get('/vote')->assertOk();
});
