<?php

namespace App\Livewire;

use App\Enums\CandidatePosition;
use App\Models\Vote;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;


#[Layout('layouts.app')]
class LiveResults extends Component
{
    public $positions = [];
    public $resultsData = [];
    public int $totalVotes = 0;
    public ?string $token = null;

    public function mount($token = null)
    {
        // Admin view: the route is protected by the role:admin middleware, so
        // an admin reaches this without a token and sees the full results.
        if ($token === null) {
            $this->loadResults();
            return;
        }

        // Legacy token-based access (kept for safety; no route currently exposes it).
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

        // Only positions that actually have candidates, kept in enum order.
        $existingPositions = DB::table('candidates')
            ->distinct()
            ->pluck('position_applied')
            ->toArray();

        $this->positions = collect($allPositions)
            ->filter(fn($position) => in_array($position, $existingPositions))
            ->values()
            ->toArray();

        $this->resultsData = [];
        $this->totalVotes = 0;

        foreach ($this->positions as $position) {
            $results = Vote::where('position_applied', $position)
                ->join('candidates', 'votes.candidate_id', '=', 'candidates.id')
                ->select('candidates.full_name', 'candidates.id', DB::raw('count(*) as total'))
                ->groupBy('candidates.id', 'candidates.full_name')
                ->orderBy('total', 'desc')
                ->get();

            $positionTotal = (int) $results->sum('total');
            $leaderVotes = $results->first() ? (int) $results->first()->total : 0;

            $candidates = $results->map(function ($row) use ($positionTotal, $leaderVotes) {
                $votes = (int) $row->total;
                return [
                    'name'  => $row->full_name,
                    'votes' => $votes,
                    // Share of the position's votes (for the label).
                    'share' => $positionTotal > 0 ? round($votes / $positionTotal * 100) : 0,
                    // Bar width relative to the leader, so the winner's bar is full.
                    'width' => $leaderVotes > 0 ? round($votes / $leaderVotes * 100) : 0,
                ];
            })->toArray();

            $this->totalVotes += $positionTotal;

            $this->resultsData[$position] = [
                'candidates'   => $candidates,
                'total'        => $positionTotal,
                'leader'       => $results->first() ? $results->first()->full_name : 'No votes yet',
                'leader_votes' => $leaderVotes,
            ];
        }
    }

    public function render()
    {
        return view('livewire.live-results');
    }
}
