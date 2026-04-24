<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // 1. Coba login sebagai Admin (guard web / tabel users)
        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')->user();
            $request->session()->regenerate();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Bukan admin, logout dari web guard
            Auth::guard('web')->logout();
        }

        // 2. Coba login sebagai Dosen (guard dosen / tabel dosens)
        if (Auth::guard('dosen')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dosen.dashboard');
        }

        // 3. Gagal semua
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