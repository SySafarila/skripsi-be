<?php

namespace App\Http\Controllers;

// use App\Models\Major;

use App\Models\KpiPeriod;
use App\Models\TendikPosition;
// use App\Models\Semester;
// use App\Models\Subject;
// use Carbon\Carbon;
use Illuminate\Http\Request;
// use Illuminate\Validation\Rule;
// use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class TendikPositionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:tendik-positions-create')->only(['create', 'store']);
        $this->middleware('can:tendik-positions-read')->only(['index', 'show']);
        $this->middleware('can:tendik-positions-update')->only(['edit', 'update']);
        $this->middleware('can:tendik-positions-delete')->only(['destroy', 'massDestroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return DataTables::of(TendikPosition::query())
                ->addColumn('options', 'admin.tendik_positions.datatables.options')
                ->setRowAttr([
                    'data-model-id' => function ($model) {
                        return $model->id;
                    }
                ])
                ->rawColumns(['options'])
                ->toJson();
        }

        return view('admin.tendik_positions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tendik_positions.create');
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
            // 'name' => ['required', 'string', 'max:255'],
            'division' => ['required', 'string', 'max:255']
        ]);

        TendikPosition::create([
            // 'name' => $request->name,
            'division' => $request->division
        ]);

        return redirect()->route('admin.tendik-positions.index')->with('success', 'Posisi berhasil dibuat !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $position = TendikPosition::findOrFail($id);

        // $user = User::with('roles')->where('id', $id)->firstOrFail();
        // $roles = $user->roles->pluck('name');
        $points = KpiPeriod::with(['points' => function ($q) use ($position) {
            return $q->where('tendik_position_id', $position->id);
        }])->orderBy('end_date', 'desc')->limit(5)->get();
        // $achievements = Achievement::where('user_id', $user->id)->where('position', '<=', 5)->latest()->get();

        return view('admin.tendik_positions.show', compact('position', 'points'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tendik = TendikPosition::findOrFail($id);

        return view('admin.tendik_positions.edit', compact('tendik'));
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
            // 'name' => ['required', 'string', 'max:255'],
            'division' => ['required', 'string', 'max:255']
        ]);

        $subject = TendikPosition::findOrFail($id);
        if ($subject->id == 1) {
            return redirect()->route('admin.tendik-positions.index')->with('warning', 'Data ini tidak bisa diperbarui !');
        }

        $subject->update([
            // 'name' => $request->name,
            'division' => $request->division
        ]);

        return redirect()->route('admin.tendik-positions.index')->with('success', 'Posisi diperbarui !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TendikPosition $tendik)
    {
        if ($tendik->id == 1) {
            return response()->json([
                'message' => 'You cannot delete this position'
            ], 400);
        };
        TendikPosition::destroy($tendik);

        if (request()->ajax()) {
            return response()->json(true);
        }

        return redirect()->route('admin.tendik-positions.index')->with('status', 'Permission deleted !');
    }

    public function massDestroy(Request $request)
    {
        $arr = explode(',', $request->ids);

        foreach ($arr as $key => $data) {
            if ($data == 1) {
                unset($arr[$key]);
            }
        }

        TendikPosition::destroy($arr);

        if (request()->ajax()) {
            return response()->json(true);
        }

        return redirect()->route('admin.tendik-positions.index')->with('status', 'Bulk delete success');
    }
}
