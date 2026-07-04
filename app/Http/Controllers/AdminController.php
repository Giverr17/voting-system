<?php

namespace App\Http\Controllers;

use App\Enums\PreRegistrationStatus;
use App\Enums\Role;
use App\Http\Requests\AddCandidate;
use App\Http\Requests\EditPreUsers;
use App\Http\Requests\EditUser;
use App\Models\Candidate;
use App\Models\PreRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{

    public function addPreUsers(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = fopen($request->file('csv_file'), 'r');

        // Skip BOM if present (common in Excel-exported CSVs)
        $bom = fread($file, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($file);
        }

        fgetcsv($file); // skip header row

        $imported = 0;
        $skipped = 0;

        try {
            while (($row = fgetcsv($file)) !== false) {
                // Skip empty or malformed rows
                if (count($row) < 2 || empty(trim($row[0]))) {
                    $skipped++;
                    continue;
                }

                $mat_no = trim($row[0]);
                $full_name = trim($row[1]);

                PreRegistration::updateOrCreate(
                    ['mat_no' => $mat_no],
                    [
                        'full_name' => $full_name,
                        'status' => PreRegistrationStatus::PENDING,
                    ]
                );
                $imported++;
            }
        } catch (\Throwable $th) {
            fclose($file);
            Log::info($th->getMessage());
            return back()->with('error-pre-users', 'CSV upload failed: ' . $th->getMessage());
        }
        fclose($file);
        return back()->with('add-pre-users', "CSV uploaded successfully. {$imported} imported, {$skipped} skipped.");
    }

    /**
     * Bulk-import fully-onboarded voters who skip the registration stage.
     *
     * Expected CSV columns (with header row):
     *   full_name, mat_no, email, spe_id, department, level
     *
     * Each imported voter is created already APPROVED (via an APPROVED
     * PreRegistration), so they only need to log in with their SPE ID, receive
     * an entry password and vote.
     */
    public function addFullUsers(Request $request)
    {
        $request->validate([
            'full_users_csv' => 'required|file|mimes:csv,txt'
        ]);

        $file = fopen($request->file('full_users_csv'), 'r');

        // Skip BOM if present (common in Excel-exported CSVs)
        $bom = fread($file, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($file);
        }

        fgetcsv($file); // skip header row

        $imported = 0;
        $skipped = 0;

        while (($row = fgetcsv($file)) !== false) {
            $matNo = isset($row[1]) ? strtoupper(trim($row[1])) : '';
            $speId = isset($row[3]) ? trim($row[3]) : '';

            // mat_no and spe_id are mandatory (spe_id is the login key).
            if (count($row) < 6 || $matNo === '' || $speId === '') {
                $skipped++;
                continue;
            }

            $fullName   = trim($row[0]);
            $email      = trim($row[2]);
            $department = trim($row[4]);
            $level      = trim($row[5]);

            try {
                DB::transaction(function () use ($fullName, $matNo, $email, $speId, $department, $level) {
                    $pre = PreRegistration::updateOrCreate(
                        ['mat_no' => $matNo],
                        ['full_name' => $fullName, 'status' => PreRegistrationStatus::APPROVED]
                    );

                    User::updateOrCreate(
                        ['mat_no' => $matNo],
                        [
                            'pre_registration_id' => $pre->id,
                            'username'   => $fullName,
                            'email'      => $email !== '' ? $email : null,
                            'spe_id'     => $speId,
                            'department' => $department,
                            'level'      => $level,
                            'role'       => Role::USER->value,
                            'has_voted'  => false,
                        ]
                    );
                });

                $imported++;
            } catch (\Throwable $th) {
                // A duplicate email/spe_id or bad row is skipped, not fatal.
                Log::warning("Full-user import skipped row (mat_no {$matNo}): " . $th->getMessage());
                $skipped++;
            }
        }

        fclose($file);
        return back()->with('add-full-users', "Voters imported successfully. {$imported} imported, {$skipped} skipped.");
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
