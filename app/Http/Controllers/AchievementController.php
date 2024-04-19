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
        $user_ids = $points->pluck('user_id')->toArray();

        $dosens = User::role('dosen')->whereIn('id', $user_ids)->get();
        $tendiks = User::role('tendik')->whereIn('id', $user_ids)->get();
        $staffs = User::role('staff')->whereIn('id', $user_ids)->get();

        $employees = [];
        foreach ($points as $index => $point) {
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

        // dosen
        $dosenArr = [];
        foreach ($dosens as $index => $dosen) {
            $index = $index + 1;
            array_push($dosenArr, [
                'user_id' => $dosen->id,
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
                'user_id' => $tendik->id,
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
                'user_id' => $staff->id,
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
