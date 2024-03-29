<?php

namespace Database\Seeders;

use App\Models\UserHasMajor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserHasMajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserHasMajor::create([
            'user_id' => 7,
            'major_id' => 1,
            'semester' => 2
        ]);
    }
}
