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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return DataTables::of(KpiPeriod::query())
                ->editColumn('is_active', function ($model) {
                    return $model->is_active ? 'Aktif' : 'Tidak';
                })
                ->editColumn('start_date', function ($model) {
                    return Carbon::parse($model->start_date)->format('d-m-Y');
                })
                ->editColumn('end_date', function ($model) {
                    return Carbon::parse($model->end_date)->format('d-m-Y');
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
            ]);

            $users = User::role(['dosen', 'tendik', 'staff'])->get();
            $arr = [];
            foreach ($users as $user) {
                $check = Point::where('user_id', $user->id)->where('kpi_period_id', $kpi->id)->first();
                if (!$check) {
                    $time = now();
                    array_push($arr, [
                        'user_id' => $user->id,
                        'kpi_period_id' => $kpi->id,
                        'points' => 0,
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
