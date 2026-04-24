<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Login Admin
public function login(Request $request)
{
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $request->session()->regenerate();

        // Redirect berdasarkan role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'dosen') {
            return redirect()->route('dosen.dashboard');
        }

        // Role tidak dikenal — logout paksa
        Auth::logout();
        return back()->withErrors(['email' => 'Role akun tidak dikenali.']);
    }

    return back()
        ->withErrors(['email' => 'Email atau password salah.'])
        ->withInput($request->only('email'));
}

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}