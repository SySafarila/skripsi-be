<?php

namespace App\Http\Controllers;

// use App\Models\KpiPeriod;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use Illuminate\Validation\Rule;
// use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:presence-scopes-create')->only(['create', 'store']);
        $this->middleware('can:presence-scopes-read')->only('index');
        $this->middleware('can:presence-scopes-update')->only(['edit', 'update']);
        $this->middleware('can:presence-scopes-delete')->only(['destroy', 'massDestroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return DataTables::of(Subject::query())
                ->editColumn('is_active', function ($model) {
                    return $model->is_active ? 'Aktif' : 'Tidak';
                })
                ->editColumn('start_date', function ($model) {
                    return Carbon::parse($model->start_date)->format('d-m-Y');
                })
                ->editColumn('end_date', function ($model) {
                    return Carbon::parse($model->end_date)->format('d-m-Y');
                })
                ->addColumn('options', 'admin.presence_scopes.datatables.options')
                ->setRowAttr([
                    'data-model-id' => function ($model) {
                        return $model->id;
                    }
                ])
                ->rawColumns(['options'])
                ->toJson();
        }

        return view('admin.presence_scopes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.presence_scopes.create');
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
            'name' => ['required', 'string', 'max:255', 'unique:subjects,name']
        ]);

        Subject::create([
            'name' => $request->name
        ]);

        return redirect()->route('admin.presence-scopes.index')->with('success', 'Objektif Absensi berhasil dibuat !');
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
        $subject = Subject::findOrFail($id);

        return view('admin.presence_scopes.edit', compact('subject'));
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
            'name' => ['required', 'string', 'max:255', "unique:subjects,name,$id"]
        ]);

        $subject = Subject::findOrFail($id);

        $subject->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.presence-scopes.index')->with('success', 'Objektif Absensi diperbarui !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Subject::destroy($id);

        if (request()->ajax()) {
            return response()->json(true);
        }

        return redirect()->route('admin.presence-scopes.index')->with('status', 'Permission deleted !');
    }

    public function massDestroy(Request $request)
    {
        $arr = explode(',', $request->ids);

        // foreach ($arr as $data) {
        // Subject::destroy($data);
        // }

        Subject::destroy($arr);

        if (request()->ajax()) {
            return response()->json(true);
        }

        return redirect()->route('admin.presence-scopes.index')->with('status', 'Bulk delete success');
    }
}
