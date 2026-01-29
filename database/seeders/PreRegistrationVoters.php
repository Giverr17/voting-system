<?php

namespace Database\Seeders;

use App\Models\PreRegistration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PreRegistrationVoters extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $voters = [
            ['mat_no' => 'ENG2102751', 'full_name' => 'Double A'],
            ['mat_no' => 'ENG2102760', 'full_name' => 'Eboka Michael'],
            ['mat_no' => 'ENG2102771', 'full_name' => ' Neche Ilo'],
            ['mat_no' => 'ENG2102774', 'full_name' => 'Khale Madara'],
            ['mat_no' => 'ENG2102775', 'full_name' => 'Fume Smoke'],
            ['mat_no' => 'ENG2102776', 'full_name' => 'Anthony Olowu'],
        ];

        PreRegistration::insert($voters); 
    }
}
