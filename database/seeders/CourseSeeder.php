<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::create([
            'name' => 'Kalkulus 1',
            'user_id' => 3
        ]);

        Course::create([
            'name' => 'Kalkulus 2',
            'user_id' => 3
        ]);

        Course::create([
            'name' => 'Mobile Programming 1',
            'user_id' => 3
        ]);
    }
}
