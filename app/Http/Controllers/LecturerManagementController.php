<?php

namespace App\Http\Controllers;

use App\Models\KpiPeriod;
use App\Models\Subject;
use App\Models\User;
use App\Models\UsersHasSubject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class LecturerManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:lecturer-managements-create')->only(['create', 'store']);
        $this->middleware('can:lecturer-managements-read')->only('index');
        $this->middleware('can:lecturer-managements-update')->only(['edit', 'update']);
        $this->middleware('can:lecturer-managements-delete')->only(['destroy', 'massDestroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $model = UsersHasSubject::query();
            if (request()->user_id) {
                $model->where('user_id', request()->user_id);
            }
            if (request()->subject_id) {
                $model->where('subject_id', request()->subject_id);
            }
            return DataTables::of($model->with('user', 'subject'))
                ->addColumn('options', 'admin.lecturer_managements.datatables.options')
                ->setRowAttr([
                    'data-model-id' => function ($model) {
                        return $model->id;
                    }
                ])
                ->rawColumns(['options'])
                ->toJson();
        }
        $lecturers = User::role(['dosen', 'tendik', 'staff'])->orderBy('name')->get();
        $subjects = Subject::orderBy('name', 'asc')->get();

        return view('admin.lecturer_managements.index', compact('lecturers', 'subjects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lecturers = User::role(['dosen', 'tendik', 'staff'])->orderBy('name')->get();
        $subjects = Subject::orderBy('name', 'asc')->get();

        return view('admin.lecturer_managements.create', compact('lecturers', 'subjects'));
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
            'user_id' => ['required', 'exists:users,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'quota' => ['required', 'numeric', 'min:1']
        ]);
        $check = UsersHasSubject::where('user_id', $request->user_id)->where('subject_id', $request->subject_id)->first();
        if (!$check) {
            UsersHasSubject::create([
                'user_id' => $request->user_id,
                'subject_id' => $request->subject_id,
                'quota' => $request->quota
            ]);
        }

        return redirect()->route('admin.lecturer-managements.index')->with('success', 'Quota Absensi berhasil dibuat !');
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
        $subject = UsersHasSubject::findOrFail($id);
        // return $subject;
        $lecturers = User::role(['dosen', 'tendik', 'staff'])->get();
        $subjects = Subject::orderBy('name', 'asc')->get();

        return view('admin.lecturer_managements.edit', compact('subject', 'lecturers', 'subjects'));
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
            'user_id' => ['required', 'exists:users,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'quota' => ['required', 'numeric', 'min:1']
        ]);

        $check = UsersHasSubject::where('user_id', $request->user_id)->where('subject_id', $request->subject_id)->first();
        $kpi = KpiPeriod::where('is_active', true)->firstOrFail();
        if ($check) {
            DB::beginTransaction();
            try {
                $check->update([
                    'quota' => $request->quota
                ]);
                $this->setPoint($kpi, $check->user);
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
            return redirect()->route('admin.lecturer-managements.index')->with('success', 'Quota Absensi diperbarui !');
        }

        $subject = UsersHasSubject::findOrFail($id);
        DB::beginTransaction();
        try {
            $subject->update([
                'user_id' => $request->user_id,
                'subject_id' => $request->subject_id,
                'quota' => $request->quota
            ]);
            $this->setPoint($kpi, $check->user);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->route('admin.lecturer-managements.index')->with('success', 'Quota Absensi diperbarui !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $check = UsersHasSubject::find($id);
        if ($check) {
            DB::beginTransaction();
            try {
                $kpi = KpiPeriod::where('is_active', true)->first();
                // $user = User::findOrFail($check->user_id)
                $check->delete();
                $this->setPoint($kpi, $check->user);
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                //throw $th;
                Log::error('LecturerManagementController: ' . $th->getMessage());
            }
        }

        if (request()->ajax()) {
            return response()->json(true);
        }

        return redirect()->route('admin.lecturer-managements.index')->with('status', 'Permission deleted !');
    }

    public function massDestroy(Request $request)
    {
        $arr = explode(',', $request->ids);

        foreach ($arr as $id) {
            $check = UsersHasSubject::find($id);
            if ($check) {
                DB::beginTransaction();
                try {
                    $kpi = KpiPeriod::where('is_active', true)->first();
                    // $user = User::findOrFail($check->user_id)
                    $check->delete();
                    $this->setPoint($kpi, $check->user);
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    //throw $th;
                    Log::error('LecturerManagementController: ' . $th->getMessage());
                }
            }
        }

        // UsersHasSubject::destroy($arr);

        if (request()->ajax()) {
            return response()->json(true);
        }

        return redirect()->route('admin.lecturer-managements.index')->with('status', 'Bulk delete success');
    }
}
