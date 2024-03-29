<?php

namespace App\Http\Controllers;

// use App\Models\KpiPeriod;

use App\Models\Semester;
// use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use Illuminate\Validation\Rule;
// use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class SemesterController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:semesters-create')->only(['create', 'store']);
        $this->middleware('can:semesters-read')->only('index');
        $this->middleware('can:semesters-update')->only(['edit', 'update']);
        $this->middleware('can:semesters-delete')->only(['destroy', 'massDestroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return DataTables::of(Semester::query())
                ->addColumn('options', 'admin.semesters.datatables.options')
                ->setRowAttr([
                    'data-model-id' => function ($model) {
                        return $model->id;
                    }
                ])
                ->rawColumns(['options'])
                ->toJson();
        }

        return view('admin.semesters.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.semesters.create');
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
            'semester' => ['required', 'numeric', 'max:255', 'min:1', 'unique:semesters,semester']
        ]);

        Semester::create([
            'semester' => $request->semester
        ]);

        return redirect()->route('admin.semesters.index')->with('success', 'Semester berhasil dibuat !');
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
        $semester = Semester::findOrFail($id);

        return view('admin.semesters.edit', compact('semester'));
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
            'semester' => ['required', 'numeric', 'max:255', 'min:1', "unique:semesters,semester,$id"]
        ]);

        $subject = Semester::findOrFail($id);

        $subject->update([
            'semester' => $request->semester,
        ]);

        return redirect()->route('admin.semesters.index')->with('success', 'Semester diperbarui !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Semester::destroy($id);

        if (request()->ajax()) {
            return response()->json(true);
        }

        return redirect()->route('admin.semesters.index')->with('status', 'Permission deleted !');
    }

    public function massDestroy(Request $request)
    {
        $arr = explode(',', $request->ids);

        // foreach ($arr as $data) {
        // Semester::destroy($data);
        // }

        Semester::destroy($arr);

        if (request()->ajax()) {
            return response()->json(true);
        }

        return redirect()->route('admin.semesters.index')->with('status', 'Bulk delete success');
    }
}
