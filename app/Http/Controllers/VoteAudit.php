<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoteAudit extends Controller
{

public function voteAudit()
{
    // Main paginated audit query
    $votes = DB::table('votes as v')
        ->join('users as u', 'u.id', '=', 'v.user_id')
        ->join('candidates as c', 'c.id', '=', 'v.candidate_id')
        ->select(
            'u.mat_no',
            DB::raw('COUNT(*) as total_votes_cast'),
            DB::raw("GROUP_CONCAT(DISTINCT v.position ORDER BY v.position SEPARATOR ', ') as positions_voted"),
            DB::raw("GROUP_CONCAT(CONCAT(v.position, ' → ', c.full_name) ORDER BY v.position SEPARATOR ' | ') as candidates_voted")
        )
        ->groupBy('u.mat_no')
        ->orderBy('u.mat_no')
        ->paginate(20); // ✅ Pagination instead of get()

    // Total votes in system
    $totalVotes = DB::table('votes')->count();

    // Count of voters that actually voted (distinct users)
    $totalVoters = DB::table('votes')
        ->distinct('user_id')
        ->count('user_id');

    // Sum of votes from paginated collection (current page only)
    $calculatedTotal = DB::table('votes')->count();

    return view('vote-audit', compact(
        'votes',
        'totalVotes',
        'totalVoters',
        'calculatedTotal'
    ));
}}
