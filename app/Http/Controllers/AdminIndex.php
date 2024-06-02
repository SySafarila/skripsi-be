<?php

namespace App\Http\Controllers;

// use App\Models\Blog;
use App\Models\KpiPeriod;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminIndex extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $users = User::all();
        $roles = Role::all();
        $permissions = Permission::all();
        $kpis = KpiPeriod::with(['points'])->withCount(['feedbacks'])->orderBy('start_date', 'asc')->limit(12)->get();
        $userResult = $this->monthCount($users);

        // return $kpis;
        return view('admin.index', compact('users', 'roles', 'permissions', 'userResult', 'kpis'));
    }

    private function monthCount($datas)
    {
        $yearNow = now()->year;

        $jan = [];
        $feb = [];
        $mar = [];
        $apr = [];
        $may = [];
        $jun = [];
        $jul = [];
        $aug = [];
        $sep = [];
        $oct = [];
        $nov = [];
        $des = [];

        foreach ($datas as $data) {
            if (Carbon::parse($data->created_at)->year == $yearNow && Carbon::parse($data->created_at)->month == 1) {
                array_push($jan, $data);
            }
            if (Carbon::parse($data->created_at)->year == $yearNow && Carbon::parse($data->created_at)->month == 2) {
                array_push($feb, $data);
            }
            if (Carbon::parse($data->created_at)->year == $yearNow && Carbon::parse($data->created_at)->month == 3) {
                array_push($mar, $data);
            }
            if (Carbon::parse($data->created_at)->year == $yearNow && Carbon::parse($data->created_at)->month == 4) {
                array_push($apr, $data);
            }
            if (Carbon::parse($data->created_at)->year == $yearNow && Carbon::parse($data->created_at)->month == 5) {
                array_push($may, $data);
            }
            if (Carbon::parse($data->created_at)->year == $yearNow && Carbon::parse($data->created_at)->month == 6) {
                array_push($jun, $data);
            }
            if (Carbon::parse($data->created_at)->year == $yearNow && Carbon::parse($data->created_at)->month == 7) {
                array_push($jul, $data);
            }
            if (Carbon::parse($data->created_at)->year == $yearNow && Carbon::parse($data->created_at)->month == 8) {
                array_push($aug, $data);
            }
            if (Carbon::parse($data->created_at)->year == $yearNow && Carbon::parse($data->created_at)->month == 9) {
                array_push($sep, $data);
            }
            if (Carbon::parse($data->created_at)->year == $yearNow && Carbon::parse($data->created_at)->month == 10) {
                array_push($oct, $data);
            }
            if (Carbon::parse($data->created_at)->year == $yearNow && Carbon::parse($data->created_at)->month == 11) {
                array_push($nov, $data);
            }
            if (Carbon::parse($data->created_at)->year == $yearNow && Carbon::parse($data->created_at)->month == 12) {
                array_push($des, $data);
            }
        }

        $dataResults = [count($jan), count($feb), count($mar), count($apr), count($may), count($jun), count($jul), count($aug), count($sep), count($oct), count($nov), count($des)];

        return $dataResults;
    }
}
