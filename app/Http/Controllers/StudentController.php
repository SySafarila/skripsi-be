<?php

namespace App\Http\Controllers;

use App\Imports\StudentsImport;
use App\Models\FeedbackQuestion;
use App\Models\KpiPeriod;
use App\Models\Major;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
// use App\Jobs\SendEmailVerification;
use Exception;
use Illuminate\Support\Facades\DB;
// use Illuminate\Auth\Events\Registered;
// use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    protected $domain = 'domain.com';
    public function __construct()
    {
        $this->middleware('can:employees-create')->only(['create', 'store', 'import']);
        $this->middleware('can:employees-read')->only('index');
        $this->middleware('can:employees-update')->only(['edit', 'update']);
        $this->middleware('can:employees-delete')->only(['destroy', 'massDestroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $questions = FeedbackQuestion::get();
            $active_kpi = KpiPeriod::where('is_active', true)->first();
            if (!$active_kpi) {
                $active_kpi = KpiPeriod::latest()->first();
            }
            $model = User::role('mahasiswa')->with('hasMajor.major', 'sent_feedbacks');
            if (request()->semester) {
                $model->whereRelation('hasMajor', 'semester', request()->semester);
            }
            if (request()->major_id) {
                $model->whereRelation('hasMajor', 'major_id', request()->major_id);
            }
            return DataTables::of($model)
                // ->addColumn('roles', 'admin.students.datatables.roles')
                // ->addColumn('created_at', function ($model) {
                //     return $model->created_at->diffForHumans();
                // })
                ->addColumn('options', 'admin.students.datatables.options')
                ->editColumn('identifier_number', function ($query) {
                    return $query->identifier_number ? $query->identifier_number : '-';
                })
                ->addColumn('feedback', function ($query) use ($questions, $active_kpi) {
                    if ($query->hasMajor) {
                        $courses = $query->hasMajor->major->courses->where('semester', $query->hasMajor->semester);
                        $sent = $query->sent_feedbacks->where('kpi_period_id', @$active_kpi->id)->count();
                        $edu_quota = $questions->where('tendik_position_id', 1)->count() * $courses->count();
                        $nonedu_quota = $questions->where('tendik_position_id', '!=', 1)->count();
                        $quota = $edu_quota + $nonedu_quota;
                        return "$quota/$sent";
                    }
                    return '-';
                })
                ->editColumn('semester', function ($model) {
                    if ($model->hasMajor) {
                        return $model->hasMajor->semester;
                    }
                    return '-';
                })
                ->editColumn('semester', function ($model) {
                    if ($model->hasMajor) {
                        return $model->hasMajor->semester;
                    }
                    return '-';
                })
                ->editColumn('major', function ($model) {
                    if ($model->hasMajor) {
                        return $model->hasMajor->major->major;
                    }
                    return '-';
                })
                ->setRowAttr([
                    'data-model-id' => function ($model) {
                        return $model->id;
                    }
                ])
                ->rawColumns(['options'])
                ->toJson();
        }

        $majors = Major::orderBy('major', 'asc')->get();

        return view('admin.students.index', compact('majors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (request()->type == 'import') {
            return view('admin.students.create');
        }
        // $roles = Role::whereIn('name', ['dosen', 'tendik'])->orderBy('name')->get();
        $majors = Major::orderBy('major', 'asc')->get();

        return view('admin.students.create', compact('majors'));
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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'identifier_number' => ['required', 'numeric', 'unique:users,identifier_number'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'identifier' => ['required', 'string', 'in:nim'],
            'major_id' => ['required', 'exists:majors,id'],
            'semester' => ['required', 'numeric', 'min:1']
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'identifier' => $request->identifier,
                'identifier_number' => $request->identifier_number
            ]);

            $user->syncRoles('mahasiswa');
            $user->markEmailAsVerified();

            if (!$user->hasMajor) {
                $user->hasMajor()->create([
                    'major_id' => $request->major_id,
                    'semester' => $request->semester
                ]);
            } else {
                $user->hasMajor()->update([
                    'major_id' => $request->major_id,
                    'semester' => $request->semester
                ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->route('admin.students.index')->with('success', 'User created !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        // $roles = Role::whereIn('name', ['dosen', 'tendik'])->orderBy('name')->get();
        $majors = Major::orderBy('major', 'asc')->get();

        return view('admin.students.edit', compact('user', 'majors'));
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
            'name' => ['required', 'string', 'max:255'],
            'identifier_number' => ['required', 'numeric', "unique:users,identifier_number,$id"],
            // 'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'identifier' => ['required', 'string', 'in:nim'],
            'major_id' => ['required', 'exists:majors,id'],
            'semester' => ['required', 'numeric', 'min:1']
            // 'role' => ['required', 'string', 'in:dosen,staff,tendik']
        ]);

        if ($request->password || $request->password_confirmation) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'password_confirmation' => ['required']
            ]);
        }

        $user = User::findOrFail($id);

        DB::beginTransaction();
        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'identifier' => $request->identifier,
                'identifier_number' => $request->identifier_number
            ]);

            if ($request->password || $request->password_confirmation) {
                $user->update([
                    'password' => Hash::make($request->password)
                ]);
            }

            $user->syncRoles('mahasiswa');

            if (!$user->hasMajor) {
                $user->hasMajor()->create([
                    'major_id' => $request->major_id,
                    'semester' => $request->semester
                ]);
            } else {
                $user->hasMajor()->update([
                    'major_id' => $request->major_id,
                    'semester' => $request->semester
                ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->route('admin.students.index')->with('success', 'User updated !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->checkUserRole($id);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), $th->getCode());
        }

        User::destroy($id);

        if (request()->ajax()) {
            return response()->json(true);
        }

        return redirect()->route('admin.students.index')->with('success', 'User deleted !');
    }

    public function massDestroy(Request $request)
    {
        $arr = explode(',', $request->ids);
        $willDeleted = [];

        foreach ($arr as $id) {
            $user =  User::find($id);
            if ($user) {
                try {
                    $this->checkUserRole($id);
                    array_push($willDeleted, $user->id);
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
        }

        User::destroy($willDeleted);

        if (request()->ajax()) {
            return response()->json(true);
        }

        return redirect()->route('admin.students.index')->with('success', 'Bulk delete success');
    }

    private function checkUserRole($id)
    {
        $user = User::find($id);
        if (Auth::user()->id == $id) {
            throw new Exception('You cannot delete your self !', 403);
            // return redirect()->route('admin.students.index')->with('warning', 'You cannot delete your self !');
        }

        if (Auth::user()->hasRole('admin') && $user->hasRole('super admin')) {
            throw new Exception('You cannot delete user who had Super Admin role !', 403);
            // return redirect()->route('admin.students.index')->with('warning', 'You cannot delete user who had Super Admin role !');
        }

        if (Auth::user()->hasRole('admin') && $user->hasRole('admin')) {
            throw new Exception('You cannot delete user with same role level with you !', 403);
            // return redirect()->route('admin.students.index')->with('warning', 'You cannot delete user with same role level with you !');
        }
    }

    private function import(Request $request)
    {
        $request->validate([
            'excel' => ['required', 'file']
        ]);
        $excel = $request->file('excel');

        try {
            Excel::import(new StudentsImport, $excel);
            return redirect()->route('admin.students.index')->with('success', 'Import berhasil');
        } catch (\Throwable $th) {
            throw $th;
            return redirect()->route('admin.students.index')->with('error', 'Import gagal');
        }
    }
}
