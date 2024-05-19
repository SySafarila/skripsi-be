<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\KpiPeriod;
use App\Models\Point;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AchievementController extends Controller
{
    public function generate($kpi_id)
    {
        $kpi = KpiPeriod::where('id', $kpi_id)->where('is_active', true)->firstOrFail();
        $points = Point::with('user.roles')->where('kpi_period_id', $kpi_id)->orderBy('points', 'desc')->orderBy('updated_at', 'asc')->get();
        // return $points;

        $dosens = [];
        $tendiks = [];
        $staffs = [];

        foreach ($points as $index => $point) {
            if ($point->points > 0) {
                // dosens
                if ($point->user->roles[0]->name == 'dosen') {
                    array_push($dosens, $point->user_id);
                }
                // tendiks
                if ($point->user->roles[0]->name == 'tendik') {
                    array_push($tendiks, $point->user_id);
                }
                // staffs
                if ($point->user->roles[0]->name == 'staff') {
                    array_push($staffs, $point->user_id);
                }
            }
        }

        $employees = [];
        foreach ($points as $index => $point) {
            if ($point->points > 0) {
                $index = $index + 1;
                array_push($employees, [
                    'user_id' => $point->user_id,
                    'kpi_period_id' => $kpi_id,
                    'title' => "Karyawan #$index periode " . Carbon::parse($kpi->start_date)->format('d/m/Y') . ' - ' . Carbon::parse($kpi->end_date)->format('d/m/Y'),
                    'position' => $index,
                    'created_at' => $kpi->end_date,
                    'updated_at' => $kpi->end_date
                ]);
            }
        }

        // dosen
        $dosenArr = [];
        foreach ($dosens as $index => $dosen) {
            $index = $index + 1;
            array_push($dosenArr, [
                'user_id' => $dosen,
                'kpi_period_id' => $kpi_id,
                'title' => "Dosen #$index periode " . Carbon::parse($kpi->start_date)->format('d/m/Y') . ' - ' . Carbon::parse($kpi->end_date)->format('d/m/Y'),
                'position' => $index,
                'created_at' => $kpi->end_date,
                'updated_at' => $kpi->end_date
            ]);
        }

        // tendik
        $tendikArr = [];
        foreach ($tendiks as $index => $tendik) {
            $index = $index + 1;
            array_push($tendikArr, [
                'user_id' => $tendik,
                'kpi_period_id' => $kpi_id,
                'title' => "Tendik #$index periode " . Carbon::parse($kpi->start_date)->format('d/m/Y') . ' - ' . Carbon::parse($kpi->end_date)->format('d/m/Y'),
                'position' => $index,
                'created_at' => $kpi->end_date,
                'updated_at' => $kpi->end_date
            ]);
        }

        // staff
        $staffArr = [];
        foreach ($staffs as $index => $staff) {
            $index = $index + 1;
            array_push($staffArr, [
                'user_id' => $staff,
                'kpi_period_id' => $kpi_id,
                'title' => "Staff #$index periode " . Carbon::parse($kpi->start_date)->format('d/m/Y') . ' - ' . Carbon::parse($kpi->end_date)->format('d/m/Y'),
                'position' => $index,
                'created_at' => $kpi->end_date,
                'updated_at' => $kpi->end_date
            ]);
        }

        DB::beginTransaction();
        try {
            Achievement::where('kpi_period_id', $kpi_id)->delete();
            DB::table('achievements')->insert($employees); // pegawai
            DB::table('achievements')->insert($dosenArr); // dosen
            DB::table('achievements')->insert($tendikArr); // tendik
            DB::table('achievements')->insert($staffArr); // staff
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
        }

        return back()->with('success', 'Pencapaian berhasil digenerate!');
    }
}
