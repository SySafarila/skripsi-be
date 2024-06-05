<?php

namespace App\Http\Controllers;

use App\Models\KpiPeriod;
use App\Models\Point;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class KpiController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:kpi-create')->only(['create', 'store']);
        $this->middleware('can:kpi-read')->only('index');
        $this->middleware('can:kpi-update')->only(['edit', 'update']);
        $this->middleware('can:kpi-delete')->only(['destroy', 'massDestroy']);
    }

    public function leaderboard(KpiPeriod $kpi_id) {
        if (request()->ajax()) {
            if (request()->show == 'tendik') {
                $model = Point::query()->where('kpi_period_id', $kpi_id->id)->where('tendik_position_id', '!=', null)->with('user.subjects', 'user.presences', 'user.feedback', 'tendik');
            } else {
                $model = Point::query()->where('kpi_period_id', $kpi_id->id)->where('tendik_position_id', '=', null)->with('user.subjects', 'user.presences', 'user.feedback', 'tendik');
            }
            return DataTables::of($model)
                ->addColumn('name', function($query) {
                    if ($query->user) {
                        return $query->user->name;
                    }
                    if ($query->tendik) {
                        return $query->tendik->division;
                    }
                    return '-';
                })
                ->editColumn('points', function($query) {
                    return number_format($query->points, 2);
                })
                ->editColumn('presence_points', function($query) {
                    $percentage = number_format($query->presence_points, 2) . '%';
                    $total_quota = $query->user->subjects->pluck('quota')->toArray();
                    $sum_total_quota = array_sum($total_quota);
                    $total_presences = $query->user->presences->where('kpi_period_id', $query->kpi_period_id)->count();
                    return "($sum_total_quota/$total_presences) " . "$percentage";
                })
                ->editColumn('feedback_points', function($query) {
                    $point = $query->feedback_points == 5 ? $query->feedback_points : number_format($query->feedback_points, 2);
                    $total_feedback = $query->user->feedback->where('kpi_period_id', $query->kpi_period_id)->count();
                    return "5/$point ($total_feedback feedback)";
                })
                // ->addColumn('options', 'admin.kpi_periods.datatables.options')
                // ->setRowAttr([
                //     'data-model-id' => function ($model) {
                //         return $model->id;
                //     }
                // ])
                // ->rawColumns(['options'])
                ->toJson();
        }
        return view('admin.kpi_periods.leaderboard', ['kpi_id' => $kpi_id->id, 'kpi' => $kpi_id]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return DataTables::of(KpiPeriod::query()->with('achievements'))
                ->editColumn('is_active', function ($model) {
                    return $model->is_active ? 'Aktif' : 'Tidak';
                })
                ->editColumn('start_date', function ($model) {
                    return Carbon::parse($model->start_date)->format('d-m-Y');
                })
                ->editColumn('end_date', function ($model) {
                    return Carbon::parse($model->end_date)->format('d-m-Y');
                })
                ->editColumn('receive_feedback', function ($model) {
                    return $model->receive_feedback ? 'YA' : 'TIDAK';
                })
                ->addColumn('options', 'admin.kpi_periods.datatables.options')
                ->setRowAttr([
                    'data-model-id' => function ($model) {
                        return $model->id;
                    }
                ])
                ->rawColumns(['options'])
                ->toJson();
        }

        return view('admin.kpi_periods.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.kpi_periods.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'is_active' => ['required', 'in:1,0'],
            'receive_feedback' => ['required', 'in:1,0']
        ]);

        DB::beginTransaction();
        try {
            if ($request->is_active === '1') {
                $checkKpi = KpiPeriod::where('is_active', true)->first();
                if ($checkKpi) {
                    $checkKpi->update([
                        'is_active' => false
                    ]);
                }
            }

            $kpi = KpiPeriod::create([
                'title' => $request->title,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => $request->is_active === '0' ? false : true,
                'receive_feedback' => $request->receive_feedback === '0' ? false : true,
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
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->route('admin.kpi.index')->with('success', 'Periode KPI created !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kpi = KpiPeriod::findOrFail($id);

        return view('admin.kpi_periods.edit', compact('kpi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'is_active' => ['required', 'in:1,0'],
            'receive_feedback' => ['required', 'in:1,0']
        ]);

        DB::beginTransaction();
        try {
            if ($request->is_active === '1') {
                $checkKpi = KpiPeriod::where('is_active', true)->first();
                if ($checkKpi) {
                    $checkKpi->update([
                        'is_active' => false
                    ]);
                }
            }

            $kpi = KpiPeriod::findOrFail($id);

            $kpi->update([
                'title' => $request->title,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => $request->is_active === '0' ? false : true,
                'receive_feedback' => $request->receive_feedback === '0' ? false : true,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
        }

        return redirect()->route('admin.kpi.index')->with('success', 'Periode KPI updated !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        KpiPeriod::destroy($id);

        if (request()->ajax()) {
            return response()->json(true);
        }

        return redirect()->route('admin.kpi.index')->with('status', 'Permission deleted !');
    }

    public function massDestroy(Request $request)
    {
        $arr = explode(',', $request->ids);

        // foreach ($arr as $data) {
        // KpiPeriod::destroy($data);
        // }

        KpiPeriod::destroy($arr);

        if (request()->ajax()) {
            return response()->json(true);
        }

        return redirect()->route('admin.kpi.index')->with('status', 'Bulk delete success');
    }
}
