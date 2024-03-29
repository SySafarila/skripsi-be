<?php

namespace App\Http\Controllers;

use App\Models\Major;
// use App\Models\Semester;
// use App\Models\Subject;
// use Carbon\Carbon;
use Illuminate\Http\Request;
// use Illuminate\Validation\Rule;
// use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class MajorController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:majors-create')->only(['create', 'store']);
        $this->middleware('can:majors-read')->only('index');
        $this->middleware('can:majors-update')->only(['edit', 'update']);
        $this->middleware('can:majors-delete')->only(['destroy', 'massDestroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return DataTables::of(Major::query())
                ->addColumn('options', 'admin.majors.datatables.options')
                ->setRowAttr([
                    'data-model-id' => function ($model) {
                        return $model->id;
                    }
                ])
                ->rawColumns(['options'])
                ->toJson();
        }

        return view('admin.majors.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.majors.create');
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
            'major' => ['required', 'string', 'max:255', 'unique:majors,major']
        ]);

        Major::create([
            'major' => $request->major
        ]);

        return redirect()->route('admin.majors.index')->with('success', 'Jurusan berhasil dibuat !');
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
        $major = Major::findOrFail($id);

        return view('admin.majors.edit', compact('major'));
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
            'major' => ['required', 'string', 'max:255', "unique:majors,major,$id"]
        ]);

        $subject = Major::findOrFail($id);

        $subject->update([
            'major' => $request->major,
        ]);

        return redirect()->route('admin.majors.index')->with('success', 'Jurusan diperbarui !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Major::destroy($id);

        if (request()->ajax()) {
            return response()->json(true);
        }

        return redirect()->route('admin.majors.index')->with('status', 'Permission deleted !');
    }

    public function massDestroy(Request $request)
    {
        $arr = explode(',', $request->ids);

        // foreach ($arr as $data) {
        // Major::destroy($data);
        // }

        Major::destroy($arr);

        if (request()->ajax()) {
            return response()->json(true);
        }

        return redirect()->route('admin.majors.index')->with('status', 'Bulk delete success');
    }
}
