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
     * Import pre-registered voters from a CSV file.
     *
     * Expected CSV columns (with header row):
     *   full_name, mat_no, etc.
     *
     * Each imported voter is created in the pre_registrations table with PENDING status.
     * They must register themselves at /register, after which an admin verifies them.
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

        // mat_no is the identifier — it must be present.
        if (!isset($col['mat_no'])) {
            fclose($file);
            return back()->with('error-full-users', 'CSV must include a "mat_no" column. Found headers: ' . implode(', ', $header));
        }

        $get = function (array $row, $field) use ($col) {
            return isset($col[$field], $row[$col[$field]]) ? trim((string) $row[$col[$field]]) : '';
        };

        $created = 0;
        $updated = 0;
        $skipped = 0;

        while (($row = fgetcsv($file)) !== false) {
            $rawMatNo = $get($row, 'mat_no');
            $matNo = strtoupper(trim(preg_replace('/[\x{FEFF}\x{200B}\x{00EF}\x{00BB}\x{00BF}]/u', '', $rawMatNo)));

            if ($matNo === '') {
                $skipped++;
                continue;
            }

            $fullName = $get($row, 'full_name');

            try {
                $pre = PreRegistration::where('mat_no', $matNo)->first();

                if ($pre) {
                    $pre->update([
                        'full_name' => $fullName !== '' ? $fullName : $pre->full_name,
                    ]);
                    $updated++;
                } else {
                    PreRegistration::create([
                        'mat_no'    => $matNo,
                        'full_name' => $fullName !== '' ? $fullName : 'Voter ' . $matNo,
                        'status'    => PreRegistrationStatus::PENDING,
                    ]);
                    $created++;
                }
            } catch (\Throwable $th) {
                Log::warning("Pre-registration CSV import skipped row (mat_no {$matNo}): " . $th->getMessage());
                $skipped++;
            }
        }

        fclose($file);
        return back()->with('add-full-users', "Pre-registration CSV imported successfully: {$created} added, {$updated} updated, {$skipped} skipped.");
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
