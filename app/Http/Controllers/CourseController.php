<?php

namespace App\Http\Controllers;

use App\Imports\CoursesImport;
use App\Models\Course;
use App\Models\Major;
use App\Models\User;
// use App\Models\Major;
// use App\Models\Semester;
// use App\Models\Subject;
// use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Illuminate\Validation\Rule;
// use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
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
            if (request()->major_id) {
                $model->where('major_id', request()->major_id);
            }
            return DataTables::of($model->with('user', 'major'))
                ->addColumn('options', 'admin.courses.datatables.options')
                ->editColumn('user_id', function ($model) {
                    return $model->user ? $model->user->name : '-';
                })
                ->editColumn('major_id', function ($model) {
                    return $model->major ? $model->major->major : '-';
                })
                ->setRowAttr([
                    'data-model-id' => function ($model) {
                        return $model->id;
                    }
                ])
                ->rawColumns(['options'])
                ->toJson();
        }
        $lecturers = User::role('dosen')->orderBy('name')->get();
        $majors = Major::orderBy('major', 'asc')->get();

        return view('admin.courses.index', compact('lecturers', 'majors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (request()->type == 'import') {
            return view('admin.courses.create');
        }
        $dosens = User::role('dosen')->orderBy('name', 'asc')->get();
        $majors = Major::orderBy('major', 'asc')->get();
        return view('admin.courses.create', compact('dosens', 'majors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->type == 'import') {
            return $this->import($request);
        }

        if ($request->user_id == '-') {
            $user_id_validation = ['string', 'in:-'];
        } else {
            $user_id_validation = ['required', 'exists:users,id'];
        }
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:courses,name'],
            'user_id' => $user_id_validation,
            'semester' => ['required', 'numeric', 'min:1'],
            'major_id' => ['required', 'exists:majors,id']
        ]);

        Course::create([
            'name' => $request->name,
            'user_id' => $request->user_id == '-' ? null : $request->user_id,
            'semester' => $request->semester,
            'major_id' => $request->major_id
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
        $majors = Major::orderBy('major', 'asc')->get();

        return view('admin.courses.edit', compact('course', 'dosens', 'majors'));
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
        if ($request->user_id == '-') {
            $user_id_validation = ['string', 'in:-'];
        } else {
            $user_id_validation = ['required', 'exists:users,id'];
        }
        $request->validate([
            'name' => ['required', 'string', 'max:255', "unique:courses,name,$id"],
            'user_id' => $user_id_validation,
            'semester' => ['required', 'numeric', 'min:1'],
            'major_id' => ['required', 'exists:majors,id']
        ]);

        $subject = Course::findOrFail($id);

        $subject->update([
            'name' => $request->name,
            'user_id' => $request->user_id == '-' ? null : $request->user_id,
            'semester' => $request->semester,
            'major_id' => $request->major_id
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

    private function import(Request $request)
    {
        $request->validate([
            'excel' => ['required', 'file']
        ]);
        $excel = $request->file('excel');

        $raw_majors = Major::all();
        $majors = [];
        foreach ($raw_majors as $raw_major) {
            $majors[Str::upper($raw_major->major)] = $raw_major->id;
        }

        $raw_users = User::role('dosen')->get(['name', 'id']);
        $users = [];
        foreach ($raw_users as $raw_user) {
            $users[Str::upper($raw_user->name)] = $raw_user->id;
        }

        $array = Excel::toArray(new CoursesImport, $excel);

        $courses = Course::all('name');
        $exists_courses = [];

        foreach ($courses as $course) {
            if ($course != null) {
                array_push($exists_courses, $course->name);
            }
        }

        foreach ($array[0] as $key => $row) {
            // skrip first row
            if ($key > 0 && $row[0] != null && in_array($row[0], $exists_courses) == false) {
                $name = $row[0];
                $semester = $row[1];
                $major = $row[2];
                $dosen = $row[3];

                DB::beginTransaction();
                try {
                    Course::create([
                        'name' => $name,
                        'user_id' => $users[$dosen] ?? null,
                        'semester' => $semester,
                        'major_id' => $majors[$major]
                    ]);
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    // throw $th;
                }
            }
        }

        return redirect()->route('admin.courses.index')->with('success', 'Import berhasil');
    }
}
