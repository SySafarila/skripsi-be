<?php

namespace App\Http\Controllers;

use App\Models\KpiPeriod;
use App\Models\Point;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public function index()
    {
        $request = request();
        switch ($request->filter) {
            case 'dosen':
                $users = User::role('dosen')->get()->pluck('id');
                break;

            case 'tendik':
                $users = User::role('tendik')->get()->pluck('id');
                break;

            default:
                if (Auth::user()->hasRole('tendik')) {
                    return redirect()->route('employees.leaderboard.index', ['filter' => 'tendik']);
                }
                $users = User::role(Auth::user()->roles[0]->name)->get()->pluck('id');
                break;
        }
        $kpis = KpiPeriod::limit(10)->orderBy('start_date', 'desc')->limit(5)->get();
        if ($request->kpi_period_id) {
            $kpi = KpiPeriod::where('id', $request->kpi_period_id)->first();
        } else {
            $kpi = KpiPeriod::where('is_active', true)->first();
            if (!$kpi) {
                $kpi = KpiPeriod::orderBy('start_date', 'desc')->first();
            }
        }
        if (!$kpi) {
            return abort(404, 'KPI Not found');
        }
        if (!Point::where('kpi_period_id', $kpi->id)->where('user_id', $request->user()->id)->first()) {
            Point::create([
                'kpi_period_id' => $kpi->id,
                'user_id' => $request->user()->id,
                'points' => 0
            ]);

            return redirect()->route('employees.leaderboard.index', ['kpi_period_id' => $request->kpi_period_id]);
        }
        $points = Point::with('user.roles')->where('kpi_period_id', $kpi->id)->whereIn('user_id', $users->toArray())->orderBy('points', 'desc')->orderBy('updated_at', 'asc')->get();
        $n = 4;
        // return $points;
        return view('employees.leaderboard', compact('points', 'n', 'kpi', 'kpis'));
    }
}
