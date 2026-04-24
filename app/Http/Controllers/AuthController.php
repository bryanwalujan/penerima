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

    // Coba guard web dulu (admin/user biasa)
    if (Auth::guard('web')->attempt($credentials)) {
        $user = Auth::guard('web')->user();
        $request->session()->regenerate();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        Auth::guard('web')->logout();
    }

    // Coba guard dosen
    if (Auth::guard('dosen')->attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->route('dosen.dashboard');
    }

    return back()
        ->withErrors(['email' => 'Email atau password salah.'])
        ->withInput($request->only('email'));
}

public function logout(Request $request)
{
    Auth::guard('web')->logout();
    Auth::guard('dosen')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
}
}