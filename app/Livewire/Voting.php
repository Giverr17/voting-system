<?php

namespace App\Livewire;

use App\Enums\CandidatePosition;
use App\Models\Candidate;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;

class Voting extends Component
{
    public $selectedCandidates = [];
    public $positions = [];
    public $candidatesByPosition = [];
    public $votedPositions = [];

    public $message = '';
    public $messageType = '';
    public $emailMessage = '';
    public $votingCompleted = false;



    public function mount()
    {
        $this->positions = collect(CandidatePosition::cases())
            ->filter(fn($position) => Candidate::where('position_applied', $position->value)->exists())
            ->values()
            ->toArray();

        foreach ($this->positions as $position) {
            $this->candidatesByPosition[$position->value] = Candidate::where('position_applied', $position->value)
                ->orderBy('full_name')
                ->get();
        };

        if (Auth::user()->has_voted) {
            $this->votingCompleted = true;
        }

        $this->checkVotedPosition();
    }
    //check if the user has voted and not allowed to vote
    public  function checkVotedPosition()
    {
        $userMatNo = Auth::user()->mat_no;

        $this->votedPositions = Vote::where('mat_no', $userMatNo)
            ->pluck('position')
            ->toArray();
    }

    public function selectCandidate($position, $candidate_id)
    {
        // Check if user already voted for this position
        if (in_array($position, $this->votedPositions)) {
            $this->message = "You have already voted for {$this->positionLabel($position)}!";
            $this->messageType = 'error';
            return;
        }

        $this->selectedCandidates[$position] = $candidate_id;

        $this->message = '';
    }

    private function positionLabel(string $position): string
    {
        return CandidatePosition::from($position)->label();
    }

    public function submitVote($position)
    {
        //check if a candidate was selected
        if (!isset($this->selectedCandidates[$position])) {
            $this->message = "Please select a candidate for {$this->positionLabel($position)}";
            $this->messageType = 'error';
            return;
        }

        // already locked in for this position
        if (in_array($position, $this->votedPositions)) {
            $this->message = "You have already selected a candidate for {$this->positionLabel($position)}!";
            $this->messageType = 'error';
            return;
        }

        // Lock the choice in memory only — nothing is written to the database
        // and nothing counts until every position is selected and the voter
        // finalizes (see finalizeVoting).
        $this->votedPositions[] = $position;

        $this->message = "Choice locked in for {$this->positionLabel($position)}. Your vote is not cast until you submit all positions.";
        $this->messageType = 'success';
    }
    public function finalizeVoting()
    {
        if ($this->votingCompleted) {
            return;
        }

        // A candidate must be selected for EVERY position — otherwise nothing counts.
        if (count($this->selectedCandidates) !== count($this->positions) || !$this->hasVotedAll()) {
            $this->message = "You must select a candidate for all positions before your vote can count.";
            $this->messageType = 'error';
            return;
        }

        $user = User::findOrFail(Auth::id());

        // Data-layer guard against double voting.
        if ($user->has_voted) {
            $this->votingCompleted = true;
            return;
        }

        try {
            // All votes are written together — if any one fails, none are saved.
            DB::transaction(function () use ($user) {
                foreach ($this->selectedCandidates as $position => $candidateId) {
                    Vote::create([
                        'user_id' => $user->id,
                        'candidate_id' => $candidateId,
                        'mat_no' => $user->mat_no,
                        'position' => $position,
                    ]);
                }

                if (!$user->results_token) {
                    $user->results_token = Str::uuid();
                }
                $user->has_voted = true;
                $user->save();
            });
        } catch (\Throwable $e) {
            Log::error('Vote finalize failed for ' . $user->mat_no . ': ' . $e->getMessage());
            $this->message = "Error submitting your votes. Nothing was saved — please try again.";
            $this->messageType = 'error';
            return;
        }

        $this->votingCompleted = true;
        $this->message = "Voting completed successfully!";
        $this->messageType = 'success';

        Log::info('Vote finalized for: ' . $user->mat_no);
    }

    public function hasVotedForPosition($position)
    {
        return in_array($position, $this->votedPositions);
    }

    public function hasVotedAll()
    {
        return count($this->votedPositions) === count($this->positions);
    }

    public function render()
    {
        return view('livewire.voting');
    }
}
