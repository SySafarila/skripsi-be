<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendEmailVerification;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    protected $domain = 'domain.com';
    public function __construct()
    {
        $this->middleware('can:users-create')->only(['create', 'store']);
        $this->middleware('can:users-read')->only('index');
        $this->middleware('can:users-update')->only(['edit', 'update']);
        $this->middleware('can:users-delete')->only(['destroy', 'massDestroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return User::query()->role('dosen')->get();
        if (request()->ajax()) {
            // $model = User::query();
            switch (request()->type) {
                case 'dosen':
                    $model = User::role('dosen');
                    break;

                case 'staff':
                    $model = User::role('staff');
                    break;

                case 'tendik':
                    $model = User::role('tendik');
                    break;

                default:
                    $model = User::role(['dosen', 'tendik', 'staff']);
                    break;
            }
            // if (request()->type == 'dosen') {
            //     $model = User::role('dosen');
            // }
            // $model = User::role(['dosen', 'tendik', 'staff']);
            return DataTables::of($model)
                ->addColumn('roles', 'admin.employees.datatables.roles')
                // ->addColumn('created_at', function ($model) {
                //     return $model->created_at->diffForHumans();
                // })
                ->addColumn('options', 'admin.employees.datatables.options')
                ->editColumn('identifier_number', function($query) {
                    return $query->identifier_number ? $query->identifier_number . " - " . Str::upper($query->identifier) : '-';
                })
                ->setRowAttr([
                    'data-model-id' => function ($model) {
                        return $model->id;
                    }
                ])
                ->rawColumns(['roles', 'options'])
                ->toJson();
        }

        return view('admin.employees.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::whereIn('name', ['dosen', 'tendik', 'staff'])->orderBy('name')->get();

        return view('admin.employees.create', compact('roles'));
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
            'name' => ['required', 'string', 'max:255'],
            'identifier_number' => ['required', 'numeric', 'unique:users,identifier_number'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'identifier' => ['required', 'string', 'in:nidn,nip']
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'identifier' => $request->identifier,
            'identifier_number' => $request->identifier_number
        ]);

        $user->syncRoles($request->role);

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
        $roles = Role::whereIn('name', ['dosen', 'tendik', 'staff'])->orderBy('name')->get();

        return view('admin.employees.edit', compact('user', 'roles'));
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
            'identifier' => ['required', 'string', 'in:nidn,nip']
        ]);

        if ($request->password || $request->password_confirmation) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'password_confirmation' => ['required']
            ]);
        }

        $user = User::findOrFail($id);

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

        $user->syncRoles($request->role);

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
}
