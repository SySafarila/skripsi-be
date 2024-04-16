<?php

namespace Database\Seeders;

use App\Models\Major;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MajorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Major::create([
            'major' => 'Teknik Informatika'
        ]);
        Major::create([
            'major' => 'Manajemen Informatika'
        ]);
        Major::create([
            'major' => 'Manajemen'
        ]);
        Major::create([
            'major' => 'Akuntansi'
        ]);
        Major::create([
            'major' => 'Sastra Inggris'
        ]);
        Major::create([
            'major' => 'Ilmu Komunikasi'
        ]);
    }
}
