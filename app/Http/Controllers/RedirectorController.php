<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectorController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        if ($user->hasRole(['dosen', 'tendik'])) {
            return redirect()->route('employees.welcome');
        }
        if ($user->hasRole(['admin', 'super admin'])) {
            return redirect()->route('admin.index');
        }
        return redirect()->route('student.index');
    }
}
