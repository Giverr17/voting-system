<?php

use App\Enums\PreRegistrationStatus;
use App\Livewire\Login;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function importAdmin(): User
{
    return User::create([
        'username' => 'Admin',
        'email'    => 'admin@election.com',
        'password' => Hash::make('secret123'),
        'role'     => 'admin',
    ]);
}

function votersCsv(): UploadedFile
{
    $csv = "full_name,mat_no,email,spe_id,department,level\n"
        . "John Doe,MAT100,john@example.com,SPE100,Computer Engineering,400\n"
        . "Jane Roe,MAT200,jane@example.com,SPE200,Civil Engineering,300\n"
        . ",MAT300,bad@example.com,,Mechanical,200\n"; // missing spe_id -> skipped

    return UploadedFile::fake()->createWithContent('voters.csv', $csv);
}

it('bulk-imports voters as already-approved and skips rows without an SPE ID', function () {
    $admin = importAdmin();

    $this->actingAs($admin)
        ->post(route('add-full-users'), ['full_users_csv' => votersCsv()])
        ->assertRedirect();

    // Two valid rows imported, the SPE-less row skipped.
    expect(User::where('role', 'user')->count())->toBe(2);

    $john = User::where('spe_id', 'SPE100')->with('preRegistration')->first();
    expect($john)->not->toBeNull();
    expect($john->username)->toBe('John Doe');
    expect($john->mat_no)->toBe('MAT100');
    expect($john->email)->toBe('john@example.com');
    expect($john->has_voted)->toBeFalsy();
    // Already approved -> passes the login gate with no self-registration.
    expect($john->preRegistration->status)->toBe(PreRegistrationStatus::APPROVED);

    expect(User::where('mat_no', 'MAT300')->exists())->toBeFalse();
});

it('lets an imported voter log in with their SPE ID', function () {
    Mail::fake();
    $admin = importAdmin();

    $this->actingAs($admin)
        ->post(route('add-full-users'), ['full_users_csv' => votersCsv()]);

    // Log out the admin so the Login component acts for a guest.
    auth()->logout();

    Livewire::test(Login::class)
        ->set('identifier', 'SPE100')      // logging in by SPE ID
        ->call('login')
        ->assertSet('showCodeField', true) // eligible -> entry password step reached
        ->assertHasNoErrors();

    // An entry password was generated for that voter.
    expect(User::where('spe_id', 'SPE100')->first()->code)->not->toBeNull();
});
