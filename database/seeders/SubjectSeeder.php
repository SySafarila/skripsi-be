<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            [
                'name' => 'Kalkulus 1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Kalkulus 2',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mobile Programming 1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mobile Programming 2',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('subjects')->insert($subjects);
    }
}
