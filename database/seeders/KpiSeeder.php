<?php

namespace Database\Seeders;

use App\Models\KpiPeriod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KpiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KpiPeriod::create([
            'title' => 'Testing KPI',
            'start_date' => now('Asia/Jakarta'),
            'end_date' => now('Asia/Jakarta')->addMonths(6),
            'is_active' => true,
        ]);
    }
}
