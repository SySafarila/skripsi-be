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
        UsersHasSubject::create([
            'user_id' => 3,
            'subject_id' => 2,
            'quota' => 16
        ]);
        UsersHasSubject::create([
            'user_id' => 5,
            'subject_id' => 5,
            'quota' => 18
        ]);
        UsersHasSubject::create([
            'user_id' => 4,
            'subject_id' => 3,
            'quota' => 18
        ]);
        UsersHasSubject::create([
            'user_id' => 4,
            'subject_id' => 4,
            'quota' => 18
        ]);
        UsersHasSubject::create([
            'user_id' => 6,
            'subject_id' => 5,
            'quota' => 22
        ]);
    }
}
