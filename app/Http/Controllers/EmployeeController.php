<?php

namespace App\Http\Controllers;

use App\Imports\EmployeesImport;
use App\Models\TendikPosition;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
// use App\Jobs\SendEmailVerification;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// use Illuminate\Auth\Events\Registered;
// use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
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
            // $model = User::query();
            switch (request()->type) {
                case 'dosen':
                    $model = User::role('dosen');
                    break;

                case 'tendik':
                    $model = User::role('tendik');
                    break;

                default:
                    $model = User::role(['dosen', 'tendik']);
                    break;
            }

            if (request()->position) {
                $model = $model->where('tendik_position_id', request()->position);
            }
            // if (request()->type == 'dosen') {
            //     $model = User::role('dosen');
            // }
            // $model = User::role(['dosen', 'tendik']);
            return DataTables::of($model->with('position'))
                ->addColumn('roles', 'admin.employees.datatables.roles')
                // ->addColumn('created_at', function ($model) {
                //     return $model->created_at->diffForHumans();
                // })
                ->addColumn('options', 'admin.employees.datatables.options')
                ->editColumn('identifier_number', function ($query) {
                    return $query->identifier_number ? $query->identifier_number . " - " . Str::upper($query->identifier) : '-';
                })
                ->editColumn('tendik_position_id', function ($query) {
                    return $query->tendik_position_id ? $query->position->division . ' - ' . $query->position->name : '-';
                })
                ->setRowAttr([
                    'data-model-id' => function ($model) {
                        return $model->id;
                    }
                ])
                ->rawColumns(['roles', 'options'])
                ->toJson();
        }

        $positions = TendikPosition::orderBy('name')->get();

        return view('admin.employees.index', compact('positions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (request()->type == 'import') {
            return view('admin.employees.create');
        }

        $roles = Role::whereIn('name', ['dosen', 'tendik'])->orderBy('name')->get();
        $positions = TendikPosition::orderBy('name')->get();

        return view('admin.employees.create', compact('roles', 'positions'));
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
            'identifier' => ['required', 'string', 'in:nidn,nip'],
            'role' => ['required', 'string', 'in:dosen,staff,tendik'],
            'position' => ['nullable']
        ]);
        if ($request->position != '-') {
            $request->validate([
                'position' => ['exists:tendik_positions,id']
            ]);
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'identifier' => $request->identifier,
                'identifier_number' => $request->identifier_number,
                'tendik_position_id' => !$request->position ? 1 : $request->position
            ]);

            $user->syncRoles($request->role);
            $user->markEmailAsVerified();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->route('admin.employees.index')->with('success', 'User created !');
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
        $roles = Role::whereIn('name', ['dosen', 'tendik'])->orderBy('name')->get();
        $positions = TendikPosition::orderBy('name')->get();

        return view('admin.employees.edit', compact('user', 'roles', 'positions'));
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
            'identifier' => ['required', 'string', 'in:nidn,nip'],
            'role' => ['required', 'string', 'in:dosen,staff,tendik'],
            'position' => ['nullable']
        ]);

        if ($request->position != '-') {
            $request->validate([
                'position' => ['exists:tendik_positions,id']
            ]);
        }

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
                // 'password' => Hash::make($request->password),
                'identifier' => $request->identifier,
                'identifier_number' => $request->identifier_number,
                'tendik_position_id' => !$request->position ? 1 : $request->position
            ]);

            if ($request->password || $request->password_confirmation) {
                $user->update([
                    'password' => Hash::make($request->password)
                ]);
            }

            $user->syncRoles($request->role);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->route('admin.employees.index')->with('success', 'User updated !');
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

        return redirect()->route('admin.employees.index')->with('success', 'User deleted !');
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

        return redirect()->route('admin.employees.index')->with('success', 'Bulk delete success');
    }

    private function checkUserRole($id)
    {
        $user = User::find($id);
        if (Auth::user()->id == $id) {
            throw new Exception('You cannot delete your self !', 403);
            // return redirect()->route('admin.employees.index')->with('warning', 'You cannot delete your self !');
        }

        if (Auth::user()->hasRole('admin') && $user->hasRole('super admin')) {
            throw new Exception('You cannot delete user who had Super Admin role !', 403);
            // return redirect()->route('admin.employees.index')->with('warning', 'You cannot delete user who had Super Admin role !');
        }

        if (Auth::user()->hasRole('admin') && $user->hasRole('admin')) {
            throw new Exception('You cannot delete user with same role level with you !', 403);
            // return redirect()->route('admin.employees.index')->with('warning', 'You cannot delete user with same role level with you !');
        }
    }

    private function import(Request $request)
    {
        $request->validate([
            'excel' => ['required', 'file']
        ]);
        $excel = $request->file('excel');

        $array = Excel::toArray(new EmployeesImport, $excel);
        $positions = TendikPosition::all();
        $tendik_position_ids = [];
        $employees = [];
        $identifier_numbers = [];

        foreach ($positions as $key => $position) {
            $tendik_position_ids[$position->division] = $position->id;
        }

        foreach ($array[0] as $index => $data) {
            if ($index > 0) {
                if ($data[0]) {
                    array_push($employees, [
                        'name' => $data[2],
                        'email' => null,
                        'password' => Hash::make($data[4]),
                        'identifier' => Str::lower($data[0]),
                        'identifier_number' => $data[1],
                        'tendik_position_id' => $tendik_position_ids[$data[3]] ?? null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    array_push($identifier_numbers, $data[1]);
                }
            }
        }
        DB::beginTransaction();
        try {
            DB::table('users')->insert($employees);
            $users = User::whereIn('identifier_number', $identifier_numbers)->get();
            foreach ($users as $user) {
                $user->syncRoles($user->tendik_position_id == 1 ? 'dosen' : 'tendik');
                $user->markEmailAsVerified();
            }
            DB::commit();
            return redirect()->route('admin.employees.index')->with('success', 'Import berhasil');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
