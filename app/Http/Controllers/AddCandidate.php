<?php

namespace App\Http\Controllers;

use App\Enums\CandidatePosition;
use App\Http\Requests\AddCandidate as RequestsAddCandidate;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AddCandidate extends Controller
{

    public function viewCandidate()
    {
        $positions = CandidatePosition::cases();
        return view('admin.add-candidate', compact('positions'));
    }

    public function store(RequestsAddCandidate $request)
    {

        dd([
            'hasFile' => $request->hasFile('image'),
            'file' => $request->file('image'),
            'files' => $_FILES,
            'post' => $_POST,
        ]);
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            try {
                // Ensure directory exists
                $path = storage_path('app/public/candidate');
                if (!file_exists($path)) {
                    mkdir($path, 0775, true);
                }

                $image = $request->file('image')->store('candidate', 'public');
                // Or with custom name:
                // $image = $request->image->storeAs('candidate', time() . '_' . $request->image->getClientOriginalName(), 'public');
            } catch (\Exception $e) {
                Log::error('Image upload failed: ' . $e->getMessage());
                return back()->withErrors(['image' => 'Failed to upload image']);
            }
        }

        Candidate::create([
            'full_name' => $request->full_name,
            'position_applied' => CandidatePosition::from($request->position_applied),
            'mat_no' => $request->mat_no,
            'department' => $request->department,
            'level' => $request->level,
            'slogan' => $request->slogan,
            'image' => $image,
        ]);

        return back()->with('success', 'You have successfully added a candidate');
    }
}
