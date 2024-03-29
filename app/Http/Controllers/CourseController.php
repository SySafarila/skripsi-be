<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
// use App\Models\Major;
// use App\Models\Semester;
// use App\Models\Subject;
// use Carbon\Carbon;
use Illuminate\Http\Request;
// use Illuminate\Validation\Rule;
// use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:courses-create')->only(['create', 'store']);
        $this->middleware('can:courses-read')->only('index');
        $this->middleware('can:courses-update')->only(['edit', 'update']);
        $this->middleware('can:courses-delete')->only(['destroy', 'massDestroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $model = Course::query();
            if (request()->user_id) {
                $model->where('user_id', request()->user_id);
            }
            return DataTables::of($model->with('user'))
                ->addColumn('options', 'admin.courses.datatables.options')
                ->setRowAttr([
                    'data-model-id' => function ($model) {
                        return $model->id;
                    }
                ])
                ->rawColumns(['options'])
                ->toJson();
        }
        $lecturers = User::role('dosen')->orderBy('name')->get();
        return view('admin.courses.index', compact('lecturers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dosens = User::role('dosen')->orderBy('name', 'asc')->get();
        return view('admin.courses.create', compact('dosens'));
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
            'name' => ['required', 'string', 'max:255', 'unique:courses,name'],
            'user_id' => ['required', 'exists:users,id']
        ]);

        Course::create([
            'name' => $request->name,
            'user_id' => $request->user_id
        ]);

        return redirect()->route('admin.courses.index')->with('success', 'Mata Kuliah berhasil dibuat !');
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
        $course = Course::findOrFail($id);
        $dosens = User::role('dosen')->orderBy('name', 'asc')->get();

        return view('admin.courses.edit', compact('course', 'dosens'));
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
            'name' => ['required', 'string', 'max:255', "unique:courses,name,$id"],
            'user_id' => ['required', 'exists:users,id']
        ]);

        $subject = Course::findOrFail($id);

        $subject->update([
            'name' => $request->name,
            'user_id' => $request->user_id
        ]);

        return redirect()->route('admin.courses.index')->with('success', 'Mata Kuliah diperbarui !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Course::destroy($id);

        if (request()->ajax()) {
            return response()->json(true);
        }

        return redirect()->route('admin.courses.index')->with('status', 'Permission deleted !');
    }

    public function massDestroy(Request $request)
    {
        $arr = explode(',', $request->ids);

        // foreach ($arr as $data) {
        // Course::destroy($data);
        // }

        Course::destroy($arr);

        if (request()->ajax()) {
            return response()->json(true);
        }

        return redirect()->route('admin.courses.index')->with('status', 'Bulk delete success');
    }
}
