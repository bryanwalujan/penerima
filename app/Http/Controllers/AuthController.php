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
        if ($user->role === 'admin') {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }
        Auth::logout();
        return back()
            ->withErrors(['email' => 'Akun ini bukan akun admin.'])
            ->with('active_tab', 'admin');
    }

    return back()
        ->withErrors(['email' => 'Email atau password salah.'])
        ->withInput($request->only('email'))
        ->with('active_tab', 'admin');
}

// Login Dosen (manual)
public function loginDosen(Request $request)
{
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        if ($user->role === 'dosen') {
            $request->session()->regenerate();
            return redirect()->route('dosen.dashboard');
        }
        Auth::logout();
        return back()
            ->withErrors(['email_dosen' => 'Akun ini bukan akun dosen.'])
            ->with('active_tab', 'dosen');
    }

    return back()
        ->withErrors(['email_dosen' => 'Email atau password salah.'])
        ->withInput($request->only('email'))
        ->with('active_tab', 'dosen');
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