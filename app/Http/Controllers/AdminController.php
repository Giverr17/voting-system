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

        // --- Map columns by HEADER NAME (order-independent) ---
        $header = fgetcsv($file);
        if ($header === false) {
            fclose($file);
            return back()->with('error-full-users', 'The CSV file is empty.');
        }

        $normalize = fn($h) => preg_replace('/[^a-z0-9]/', '', strtolower((string) $h));

        $aliases = [
            'full_name'  => ['fullname', 'name', 'fullnames'],
            'mat_no'     => ['matno', 'matricno', 'matricnumber', 'matriculation', 'matriculationnumber', 'matric', 'matnumber'],
            'email'      => ['email', 'emailaddress', 'mail'],
            'spe_id'     => ['speid', 'spe', 'speno', 'spenumber', 'speidno', 'speidnumber'],
            'department' => ['department', 'dept', 'departmentname'],
            'level'      => ['level', 'levels', 'classlevel'],
        ];

        $normalizedHeader = array_map($normalize, $header);
        $col = [];
        foreach ($aliases as $field => $names) {
            foreach ($normalizedHeader as $i => $h) {
                if (in_array($h, $names, true)) {
                    $col[$field] = $i;
                    break;
                }
            }
        }

        // spe_id is the login identity — it must be present.
        if (!isset($col['spe_id'])) {
            fclose($file);
            return back()->with('error-full-users', 'CSV must include an "spe_id" column. Found headers: ' . implode(', ', $header));
        }

        $get = function (array $row, $field) use ($col) {
            return isset($col[$field], $row[$col[$field]]) ? trim((string) $row[$col[$field]]) : '';
        };

        $created = 0;
        $updated = 0;
        $skipped = 0;

        while (($row = fgetcsv($file)) !== false) {
            $speId = $get($row, 'spe_id');

            // spe_id is mandatory; everything else is optional.
            if ($speId === '') {
                $skipped++;
                continue;
            }

            $fullName   = $get($row, 'full_name');
            $matNo      = strtoupper($get($row, 'mat_no'));
            $email      = $get($row, 'email');
            $department = $get($row, 'department');
            $level      = $get($row, 'level');

            try {
                // Dedupe on spe_id (the unique login key), NOT mat_no.
                $outcome = DB::transaction(function () use ($speId, $matNo, $fullName, $email, $department, $level) {
                    $existing = User::where('spe_id', $speId)->first();

                    // Each voter gets their own APPROVED pre-registration so they
                    // pass the existing login gate with no self-registration.
                    $pre = $existing && $existing->pre_registration_id
                        ? PreRegistration::find($existing->pre_registration_id)
                        : null;

                    if ($pre) {
                        $pre->update([
                            'full_name' => $fullName !== '' ? $fullName : $pre->full_name,
                            'mat_no'    => $matNo !== '' ? $matNo : $pre->mat_no,
                            'status'    => PreRegistrationStatus::APPROVED,
                        ]);
                    } else {
                        $pre = PreRegistration::create([
                            'mat_no'    => $matNo !== '' ? $matNo : null,
                            'full_name' => $fullName !== '' ? $fullName : 'SPE ' . $speId,
                            'status'    => PreRegistrationStatus::APPROVED,
                        ]);
                    }

                    User::updateOrCreate(
                        ['spe_id' => $speId],
                        [
                            'pre_registration_id' => $pre->id,
                            'username'   => $fullName !== '' ? $fullName : ($existing->username ?? 'Voter'),
                            'email'      => $email !== '' ? $email : null,
                            'mat_no'     => $matNo !== '' ? $matNo : null,
                            'department' => $department,
                            'level'      => $level,
                            'role'       => Role::USER->value,
                            'has_voted'  => $existing->has_voted ?? false,
                        ]
                    );

                    return $existing ? 'updated' : 'created';
                });

                $outcome === 'created' ? $created++ : $updated++;
            } catch (\Throwable $th) {
                // e.g. a duplicate email shared with a different voter — skip that row only.
                Log::warning("Full-user import skipped row (spe_id {$speId}): " . $th->getMessage());
                $skipped++;
            }
        }

        fclose($file);
        return back()->with('add-full-users', "Import complete: {$created} added, {$updated} updated, {$skipped} skipped.");
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
