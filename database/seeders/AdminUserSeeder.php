<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@election.com'],
            [
                'username' => 'Election Admin',
                'password' => Hash::make('34571411@Ab'),
                'role' => 'admin',
            ]
        );

        // Second admin — EDIT these placeholder credentials before seeding.
        User::firstOrCreate(
            ['email' => 'ebony@election.com'],
            [
                'username' => 'Election Admin 2',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ] 
        );
    }
}  
