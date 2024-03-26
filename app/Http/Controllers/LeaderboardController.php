<?php

namespace App\Http\Controllers;

use App\Models\KpiPeriod;
use App\Models\Point;
use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index()
    {
        $request = request();
        $kpi = KpiPeriod::where('is_active', true)->first();
        switch ($request->filter) {
            case 'dosen':
                $users = User::role('dosen')->get()->pluck('id');
                break;

            case 'tendik':
                $users = User::role('tendik')->get()->pluck('id');
                break;

            case 'staff':
                $users = User::role('staff')->get()->pluck('id');
                break;

            default:
                $users = User::role(['dosen', 'tendik', 'staff'])->get()->pluck('id');
                break;
        }
        $points = Point::with('user.roles')->where('kpi_period_id', $kpi->id)->whereIn('user_id', $users->toArray())->orderBy('points', 'desc')->get();
        $n = 1;
        // return $points;
        return view('leaderboard.index', compact('points', 'n', 'kpi'));
    }
}
