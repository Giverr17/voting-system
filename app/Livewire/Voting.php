<?php

namespace App\Livewire;

use App\Enums\CandidatePosition;
use App\Jobs\SendLiveResultsEmail;
use App\Mail\LiveResults;
use App\Models\Candidate;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

         if(Auth::user()->has_voted){
            $this->votingCompleted=true;
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

        // check for the voted positions
        if (in_array($position, $this->votedPositions)) {
            $this->message = "You have already voted for {$this->positionLabel($position)}!";
            $this->messageType = 'error';
            return;
        }

        try {

            Vote::create([
                'user_id' => Auth::id(),
                'candidate_id' => $this->selectedCandidates[$position],
                'mat_no' => Auth::user()->mat_no,
                'position' => $position,
            ]);

            $this->votedPositions[] = $position;
            unset($this->selectedCandidates[$position]);




            $this->message = "Vote submitted successfully for {$this->positionLabel($position)}!";
            $this->messageType = 'success';


            // Auth::logout();

        } catch (\Throwable $e) {
            DB::rollBack();
            $this->message = "Error submitting vote. Please try again.";
            $this->messageType = 'error';
        }
    }
    public function finalizeVoting()
    {
        if (!$this->hasVotedAll()) {
            $this->message = "You must vote for all positions before finishing.";
            $this->messageType = 'error';
            return;
        }

       
    
        if ($this->votingCompleted) {
            return; 
        }
    
        $user = User::findOrFail(Auth::id());
        if (!$user->results_token) {
            $user->results_token = Str::uuid();
        }
    
        $user->has_voted = true;
        $user->save();
    
        try {
            SendLiveResultsEmail::dispatch($user);
            $this->votingCompleted = true;
    
            $this->message = "Voting completed successfully!";
            $this->messageType = 'success';
            $this->emailMessage = 'Please check your email to see the election result.';
    
            Log::info('Email sent successfully to: ' . $user->email);
    
        } catch (\Throwable $e) {
            $this->message = "Voting completed, but email failed to send.";
            $this->messageType = 'error';
    
            Log::error('Email send failed: ' . $e->getMessage());
        }
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
