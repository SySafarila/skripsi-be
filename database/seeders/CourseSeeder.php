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
            'user_id' => 3,
            'semester' => 1,
            'major_id' => 1
        ]);

        Course::create([
            'name' => 'Kalkulus 2',
            'user_id' => 3,
            'semester' => 2,
            'major_id' => 1
        ]);

        Course::create([
            'name' => 'Mobile Programming 1',
            'user_id' => 4,
            'semester' => 1,
            'major_id' => 1
        ]);

        Course::create([
            'major_id' => 1,
            'name' => 'Mobile Programming 2',
            'user_id' => 4,
            'semester' => 2
        ]);
    }
}
