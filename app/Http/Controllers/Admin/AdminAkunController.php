<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAkunController extends Controller
{
    private function checkAdmin(): void
    {
        if (!Auth::guard('web')->check() || Auth::guard('web')->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya admin yang diizinkan.');
        }
    }

    /**
     * Tampilkan daftar semua akun (admin + dosen yang punya akun user).
     */
    public function index()
    {
        $this->checkAdmin();

        // Ambil semua user dengan role dosen, join ke tabel dosens via email
        $users = User::with([])->orderBy('role')->orderBy('name')->get()->map(function ($user) {
            // Cari data dosen jika role-nya dosen
            $user->dosen = ($user->role === 'dosen')
                ? Dosen::where('email', $user->email)->first()
                : null;
            return $user;
        });

        return view('admin.akun.index', compact('users'));
    }

    /**
     * Form reset password untuk user tertentu.
     */
    public function editResetPassword($id)
    {
        $this->checkAdmin();

        $user = User::findOrFail($id);

        // Admin tidak boleh reset password admin lain melalui halaman ini
        // (gunakan halaman ganti password sendiri)
        if ($user->role === 'admin' && $user->id === Auth::guard('web')->id()) {
            return redirect()->route('admin.password.edit')
                ->with('info', 'Gunakan halaman Ganti Password untuk mengubah password Anda sendiri.');
        }

        $dosen = ($user->role === 'dosen')
            ? Dosen::where('email', $user->email)->first()
            : null;

        return view('admin.akun.reset-password', compact('user', 'dosen'));
    }

    /**
     * Proses reset password oleh admin.
     */
    public function resetPassword(Request $request, $id)
    {
        $this->checkAdmin();

        $user = User::findOrFail($id);

        $request->validate([
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ], [
            'password.required'              => 'Password baru wajib diisi.',
            'password.min'                   => 'Password minimal 8 karakter.',
            'password.confirmed'             => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $label = $user->role === 'dosen' ? "dosen {$user->name}" : "admin {$user->name}";

        // Audit log jika trait tersedia — di sini kita log manual
        // karena controller ini tidak use HasDosenHelpers
        \App\Models\AuditLog::create([
            'action'         => 'admin_reset_password',
            'description'    => "Admin reset password for {$label} (ID: {$user->id})",
            'auditable_type' => User::class,
            'auditable_id'   => $user->id,
            'user_id'        => Auth::guard('web')->id(),
        ]);

        return redirect()->route('admin.akun.index')
            ->with('success', "Password akun {$user->name} berhasil direset.");
    }
}