<?php

namespace App\Http\Controllers;

use App\Enums\CandidatePosition;
use App\Http\Requests\AddCandidate as RequestsAddCandidate;
use App\Models\Candidate;
use Illuminate\Http\Request;

class AddCandidate extends Controller
{

    public function viewCandidate(){
        $positions=CandidatePosition::cases();
        return view('admin.add-candidate',compact('positions'));
    }

    public function store(RequestsAddCandidate $request)
    {

        if ($request->image) {
            $image = $request->image->storeAs('candidate', time() . '_' . $request->image->getClientOriginalName(), 'public');
        }
        
        Candidate::create([
            'full_name' => $request->full_name,
            'position_applied' => CandidatePosition::from($request->position_applied),
            'mat_no'=>$request->mat_no,
            'department' => $request->department,
            'level' => $request->level,
            'slogan'=>$request->slogan,
            'image' => $image,
        ]);

        return back()->with('success','You have successfully added a candidate');
    }
   


}
