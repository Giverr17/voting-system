<?php

namespace App\Livewire;

use App\Enums\CandidatePosition;
use App\Models\Vote;
use App\Models\Candidate;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;


#[Layout('layouts.app')]
class LiveResults extends Component
{
    public $positions = [];
    public $resultsData = [];
    public string $token;
    
    public function mount($token)
    {
        $user = User::where('results_token', $token)->firstOrFail();

        if (!$user->has_voted) {
            abort(403);
        }
        $this->token = $token;
        $this->loadResults();
    }

    
    public function loadResults()
    {
        $allPositions = collect(CandidatePosition::cases())
        ->map(fn($case) => $case->value)
        ->toArray();

    // Filter to only positions that have candidates
    $existingPositions = DB::table('candidates')
        ->select('position_applied')
        ->distinct()
        ->pluck('position_applied')
        ->toArray();

    // ✅ Keep enum order, but only include existing positions
    $this->positions = collect($allPositions)
        ->filter(fn($position) => in_array($position, $existingPositions))
        ->values()
        ->toArray();

        $this->resultsData = [];

        foreach ($this->positions as $position) {
            // Get votes for this position
            $results = Vote::where('position_applied', $position)
                ->join('candidates', 'votes.candidate_id', '=', 'candidates.id')
                ->select('candidates.full_name', 'candidates.id', DB::raw('count(*) as total'))
                ->groupBy('candidates.id', 'candidates.full_name')
                ->orderBy('total', 'desc')
                ->get();

            $labels = $results->pluck('full_name')->toArray();
            $data = $results->pluck('total')->toArray();

            $this->resultsData[$position] = [
                'labels' => $labels,
                'data' => $data,
                'leader' => $results->first() ? $results->first()->full_name : 'No votes yet',
                'leader_votes' => $results->first() ? $results->first()->total : 0,
            ];
        }

        // Send all data to JavaScript
        $this->dispatch('chartUpdated', ['resultsData' => $this->resultsData]);
    }



    public function render()
    {
        return view('livewire.live-results');
    }
}