<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserRole;

class AuthController extends Controller
{
    public function showLogin()
    {
        if(Auth::check()) {
            return redirect('/dashboard');
        }
        return view('/auth/login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            session(['userName' => $user->name]);
            return redirect('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Login information is incorrect.',
        ]);
    }

    public function logout()
    {
        session()->forget('user');
        Auth::logout();
        return redirect('/login');
    }
}
