<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arr = [];
        for ($i=1; $i <= 8; $i++) {
            array_push($arr, [
                'semester' => $i,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        DB::table('semesters')->insert($arr);
    }
}
