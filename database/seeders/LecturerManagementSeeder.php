<?php

namespace Database\Seeders;

use App\Models\UsersHasSubject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LecturerManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UsersHasSubject::create([
            'user_id' => 3,
            'subject_id' => 1,
            'quota' => 16
        ]);
    }
}