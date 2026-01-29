<?php

namespace App\Http\Controllers;

use App\Enums\PreRegistrationStatus;
use App\Http\Requests\AddCandidate;
use App\Http\Requests\EditPreUsers;
use App\Http\Requests\EditUser;
use App\Models\Candidate;
use App\Models\PreRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{

    public function addPreUsers(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = fopen($request->file('csv_file'), 'r');
        fgetcsv($file);

        try {
            while (($row = fgetcsv($file)) !== false) {
                [$mat_no, $full_name] = $row;

                if (PreRegistration::where('mat_no', $mat_no)->exists()) {
                        continue;
                }
                PreRegistration::create([
                    'mat_no' => $mat_no,
                    'full_name' => $full_name,
                    'status' => PreRegistrationStatus::PENDING,
                ]);
            }
        } catch (\Throwable $th) {
            fclose($file);
            return back()->with('error-pre-users', 'CSV upload failed: ' . $th->getMessage());
        }
        fclose($file);
        return back()->with('add-pre-users', 'CSV uploaded successfully');
    }


    public function edit($id)
    {

        $candidate = Candidate::where('id', $id)->first();

        return view('admin.edit-candidate', compact('candidate'));
    }

    public function update(AddCandidate $request, $id)
    {
        $candidate = Candidate::findOrFail($id);

        // Prepare data for update
        $data = [
            'full_name' => $request->full_name,
            'position_applied' => $request->position_applied,
            'mat_no' => $request->mat_no,
            'department' => $request->department,
            'level' => $request->level,
            'slogan' => $request->slogan,
        ];

        // Handle image upload only if a new image is provided
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($candidate->image) {
                Storage::disk('public')->delete($candidate->image);
            }

            // Store the new image
            $data['image'] = $request->file('image')->storeAs(
                'candidate',
                time() . '_' . $request->file('image')->getClientOriginalName(),
                'public'
            );
        }

        // Update the candidate
        $candidate->update($data);

        return back()->with('success', 'You have successfully edited this candidate');
    }

    public function editUser($id)
    {

        $user = User::with('preRegistration')->where('id', $id)->firstOrFail();

        return view('admin.edit-users', compact('user'));
    }

    public function updateUser($id, EditUser $edit)
    {
        $user = User::with('preRegistration')->findOrFail($id);

        $user->update([
            'username' => $edit->username,
            'department' => $edit->department,
            'email' => $edit->email,
            'mat_no' => $edit->mat_no,
            'level' => $edit->level,
        ]);

        $user->preRegistration->update([
            'status' => $edit->status
        ]);

        return back()->with('success', 'You have successfully edited this User');
    }

    public function checkPreUsers($id)
    {
        $user = PreRegistration::where('id', $id)->firstOrFail();
        return view('admin.check-preRegister', compact('user'));
    }

    public function editPreUsers($id, EditPreUsers $request)
    {
        $user = PreRegistration::where('id', $id)
            ->first();

        $user->update([
            'mat_no' => $request->mat_no,
            'full_name' => $request->full_name,
        ]);
        return back()->with('success', 'You have successfully edited this User');
    }
}
