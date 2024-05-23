<?php

namespace Database\Seeders;

use App\Models\KpiPeriod;
use App\Models\Point;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KpiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kpi = KpiPeriod::create([
            'title' => 'Testing KPI',
            'start_date' => now('Asia/Jakarta'),
            'end_date' => now('Asia/Jakarta')->addMonths(6),
            'is_active' => true,
            'receive_feedback' => false
        ]);

        $users = User::role(['dosen', 'tendik'])->get();
        $arr = [];
        foreach ($users as $user) {
            $check = Point::where('user_id', $user->id)->where('kpi_period_id', $kpi->id)->first();
            if (!$check) {
                $time = now();
                array_push($arr, [
                    'user_id' => $user->id,
                    'kpi_period_id' => $kpi->id,
                    'points' => 0,
                    'presence_points' => 0,
                    'feedback_points' => 0,
                    'created_at' => $time,
                    'updated_at' => $time
                ]);
            }
        }
        DB::table('points')->insert($arr);
    }
}
