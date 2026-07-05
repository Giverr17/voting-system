<?php

use App\Enums\CandidatePosition;
use App\Livewire\Voting;
use App\Models\Candidate;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function makeVoter(): User
{
    return User::create([
        'username' => 'Voter',
        'email'    => 'voter@example.com',
        'mat_no'   => 'MAT001',
        'password' => Hash::make('secret123'),
        'role'     => 'user',
    ]);
}

function seedCandidatesForTwoPositions(): array
{
    $president = Candidate::create([
        'full_name'        => 'Alice',
        'position_applied' => CandidatePosition::PRESIDENT_ELECT->value,
        'mat_no'           => 'CAND001',
        'department'       => 'Computer Engineering',
        'level'            => '400',
        'slogan'           => 'Vote for me',
        'image'            => 'candidates/placeholder.png',
    ]);
    $vp = Candidate::create([
        'full_name'        => 'Bob',
        'position_applied' => CandidatePosition::VICE_PRESIDENT->value,
        'mat_no'           => 'CAND002',
        'department'       => 'Computer Engineering',
        'level'            => '400',
        'slogan'           => 'Vote for me',
        'image'            => 'candidates/placeholder.png',
    ]);

    return [$president, $vp];
}

it('does NOT count a vote when only some positions are selected', function () {
    $voter = makeVoter();
    [$president] = seedCandidatesForTwoPositions();

    Livewire::actingAs($voter)->test(Voting::class)
        // select + lock in ONLY the president position
        ->call('selectCandidate', CandidatePosition::PRESIDENT_ELECT->value, $president->id)
        ->call('submitVote', CandidatePosition::PRESIDENT_ELECT->value)
        // try to finish without selecting the other position
        ->call('finalizeVoting')
        ->assertSet('votingCompleted', false);

    // Nothing was written, and the voter is not marked as voted.
    expect(Vote::count())->toBe(0);
    expect($voter->fresh()->has_voted)->toBeFalsy();
});

it('counts all votes atomically only when every position is selected', function () {
    $voter = makeVoter();
    [$president, $vp] = seedCandidatesForTwoPositions();

    Livewire::actingAs($voter)->test(Voting::class)
        ->call('selectCandidate', CandidatePosition::PRESIDENT_ELECT->value, $president->id)
        ->call('submitVote', CandidatePosition::PRESIDENT_ELECT->value)
        ->call('selectCandidate', CandidatePosition::VICE_PRESIDENT->value, $vp->id)
        ->call('submitVote', CandidatePosition::VICE_PRESIDENT->value)
        ->call('finalizeVoting')
        ->assertSet('votingCompleted', true);

    // Exactly one vote per position, and the voter is now marked as voted.
    expect(Vote::count())->toBe(2);
    expect(Vote::where('position', CandidatePosition::PRESIDENT_ELECT->value)->count())->toBe(1);
    expect(Vote::where('position', CandidatePosition::VICE_PRESIDENT->value)->count())->toBe(1);
    expect($voter->fresh()->has_voted)->toBeTruthy();
});
