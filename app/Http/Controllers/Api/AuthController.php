<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailVerification;
use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use App\Http\Controllers\NotificationsController as Notif;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['user', 'logout']);
    }

    public function user(Request $request)
    {
        $user = [];

        $raw_user = $request->user();
        $user_permissions_arr = $raw_user->getAllPermissions()->pluck('name')->toArray();
        $user_roles_arr = $raw_user->getRoleNames()->toArray();

        $user['id'] = $raw_user->id;
        $user['name'] = $raw_user->name;
        $user['email'] = $raw_user->email;
        $user['image'] = $raw_user->image;
        $user['permissions'] = $user_permissions_arr;
        $user['roles'] = $user_roles_arr;
        $user['message'] = 'Success';

        return response()->json($user);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // registering user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // send email verification
        SendEmailVerification::dispatch($user);

        return response()->json(['message' => 'Success']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // start authentication
        if (Auth::attempt($credentials)) {
            $user = auth()->user();

            // store a notification
            (new Notif())->store($user->id, 'Login Info', 'Your account has been logged in');

            return response()->json($this->createToken($user));
        }

        return response()->json([
            'message' => 'The user credentials were incorrect.'
        ], 401);
    }

    public function logout(Request $request)
    {
        // delete used access token
        try {
            DB::beginTransaction();

            $token = $request->user()->currentAccessToken();
            RefreshToken::where('personal_access_token_id', $token->id)->delete();
            $token->delete();

            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json(['message' => 'Logout failed'], 500);
        }

        return response()->json(['message' => 'Logout success']);
    }

    public function refresh_token(Request $request)
    {
        $refresh_token = RefreshToken::with('user')->where('refresh_token', $request->refresh_token)->first();
        if (!$refresh_token) {
            return response()->json(['message' => 'Refresh token invalid'], 401);
        }
        $user = $refresh_token->user;

        // check if refresh token is expired
        if ($refresh_token->expired_at < now()) {
            $refresh_token->delete();

            return response()->json([
                'message' => 'Refresh token has been expired'
            ], 401);
        }

        $token = $user->tokens()->where('id', $refresh_token->personal_access_token_id);
        if ($token) {
            // delete token
            $token->delete();
        }

        // delete refresh token
        $refresh_token->delete();

        return response()->json($this->createToken($user));
    }

    private function createToken($user)
    {
        // create token
        $token_expired_at = now()->addDays(1);
        $refresh_token_expired_at = now()->addDays(28);
        $token = $user->createToken('token_name', $user->getApiPermissions(), $token_expired_at);
        $token_id = $token->accessToken->id;

        // create refresh token
        $refresh_token = RefreshToken::create([
            'personal_access_token_id' => $token_id,
            'refresh_token' => Hash::make(Str::uuid()),
            'user_id' => $user->id,
            'expired_at' => $refresh_token_expired_at
        ]);

        return [
            'message' => 'Success',
            'token' => $token->plainTextToken,
            'token_expired_at' => $token_expired_at->toJSON(),
            'refresh_token' => $refresh_token->refresh_token,
            'refresh_token_expired_at' => $refresh_token->expired_at->toJSON(),
            'permissions' => $user->getApiPermissions(),
            'roles' => $user->getApiRoles()
        ];
    }
}
