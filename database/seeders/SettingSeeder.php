<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Seed the initial election controls.
     *
     * The starting values come from env (ELECTION_STATUS / REGISTRATION_STATUS)
     * so the very first state matches your .env. After this, the admin toggles
     * control the live values in the database — env is only the initial default.
     *
     * firstOrCreate is used so re-seeding never clobbers a value an admin has
     * already toggled.
     */
    public function run(): void
    {
        Setting::firstOrCreate(
            ['key' => 'election_status'],
            ['value' => env('ELECTION_STATUS', 'closed')]
        );

        Setting::firstOrCreate(
            ['key' => 'registration_status'],
            ['value' => env('REGISTRATION_STATUS', 'open')]
        );
    }
}
