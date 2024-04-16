<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        // $request->authenticate();

        // $request->session()->regenerate();

        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        $email_login = Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember ? true : false);
        $identifier_login = Auth::attempt(['identifier_number' => $request->email, 'password' => $request->password], $request->remember ? true : false);
        $remember_check = Auth::viaRemember();

        if ($remember_check) {
            $request->session()->regenerate();

            return redirect()->intended(RouteServiceProvider::HOME);
        } else if ($email_login) {
            $request->session()->regenerate();

            return redirect()->intended(RouteServiceProvider::HOME);
        } else if ($identifier_login) {
            $request->session()->regenerate();

            return redirect()->intended(RouteServiceProvider::HOME);
        }

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
        // return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
