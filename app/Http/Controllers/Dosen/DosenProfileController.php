<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HasDosenHelpers;
use App\Models\AuditLog;
use App\Models\Dosen;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Models\Haki;
use App\Models\Paten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * DosenProfileController
 *
 * Dipakai oleh dosen yang login via guard 'dosen'
 * untuk mengelola profil dan data riset milik sendiri.
 */
class DosenProfileController extends Controller
{
    use HasDosenHelpers;

    // =========================================================================
    // PROFIL
    // =========================================================================

    public function editProfile()
    {
        $dosen = $this->authDosen();

        return view('dosen.edit', compact('dosen'));
    }

    public function updateProfile(Request $request)
    {
        $dosen = $this->authDosen();

        $request->validate([
            'nama'  => 'required|string|max:255',
            'email' => 'required|email|unique:dosens,email,' . $dosen->id,
            'nidn'  => 'required|string|max:20|unique:dosens,nidn,' . $dosen->id,
            'nip'   => 'nullable|string|max:20',
            'nuptk' => 'nullable|string|max:20',
            'foto'  => 'nullable|image|mimes:jpeg,png,jpg|max:10000',
        ]);

        $data = $request->only(['nama', 'email', 'nidn', 'nip', 'nuptk']);

        if ($foto = $this->handleFotoUpload($request, $dosen)) {
            $data['foto'] = $foto;
        }

        $dosen->update($data);

        $this->auditLog(
            'update_profile',
            "Dosen updated profile: {$data['nama']} (NIDN: {$data['nidn']})",
            Dosen::class,
            $dosen->id,
            $dosen->id
        );

        return redirect()->route('dosen.dashboard')->with('success', 'Profil berhasil diperbarui.');
    }

    // =========================================================================
    // PENELITIAN
    // =========================================================================

    public function editPenelitian()
    {
        $dosen = $this->authDosen();
        $penelitians = $dosen->penelitians;

        return view('dosen.edit-penelitian', compact('dosen', 'penelitians'));
    }

    public function updatePenelitian(Request $request)
    {
        $dosen = $this->authDosen();

        $request->validate([
            'penelitians.*.skema'            => 'nullable|string',
            'penelitians.*.posisi'           => 'nullable|string',
            'penelitians.*.judul_penelitian' => 'nullable|string',
            'penelitians.*.sumber_dana'      => 'nullable|string',
            'penelitians.*.status'           => 'nullable|string|in:Selesai,Berjalan,Diajukan',
            'penelitians.*.tahun'            => 'nullable|integer',
            'penelitians.*.link_luaran'      => 'nullable|url',
        ]);

        $dosen->penelitians()->delete();
        $this->syncRelations($dosen, ['penelitians' => $request->penelitians ?? []]);

        $this->auditLog('update_penelitian', 'Dosen updated penelitian data', Penelitian::class, null, $dosen->id);

        return redirect()->route('dosen.dashboard')->with('success', 'Penelitian berhasil diperbarui.');
    }

    // =========================================================================
    // PENGABDIAN
    // =========================================================================

    public function editPengabdian()
    {
        $dosen = $this->authDosen();
        $pengabdians = $dosen->pengabdians;

        return view('dosen.edit-pengabdian', compact('dosen', 'pengabdians'));
    }

    public function updatePengabdian(Request $request)
    {
        $dosen = $this->authDosen();

        $request->validate([
            'pengabdians.*.skema'            => 'nullable|string',
            'pengabdians.*.posisi'           => 'nullable|string',
            'pengabdians.*.judul_pengabdian' => 'nullable|string',
            'pengabdians.*.sumber_dana'      => 'nullable|string',
            'pengabdians.*.status'           => 'nullable|string|in:Selesai,Berjalan,Diajukan',
            'pengabdians.*.tahun'            => 'nullable|integer',
            'pengabdians.*.link_luaran'      => 'nullable|url',
        ]);

        $dosen->pengabdians()->delete();
        $this->syncRelations($dosen, ['pengabdians' => $request->pengabdians ?? []]);

        $this->auditLog('update_pengabdian', 'Dosen updated pengabdian data', Pengabdian::class, null, $dosen->id);

        return redirect()->route('dosen.dashboard')->with('success', 'Pengabdian berhasil diperbarui.');
    }

    // =========================================================================
    // HAKI
    // =========================================================================

    public function editHaki()
    {
        $dosen = $this->authDosen();
        $hakis = $dosen->hakis;

        return view('dosen.edit-haki', compact('dosen', 'hakis'));
    }

    public function updateHaki(Request $request)
    {
        $dosen = $this->authDosen();

        $request->validate([
            'hakis.*.judul_haki' => 'nullable|string',
            'hakis.*.expired'    => 'nullable|date',
            'hakis.*.link'       => 'nullable|url',
        ]);

        $dosen->hakis()->delete();
        $this->syncRelations($dosen, ['hakis' => $request->hakis ?? []]);

        $this->auditLog('update_haki', 'Dosen updated haki data', Haki::class, null, $dosen->id);

        return redirect()->route('dosen.dashboard')->with('success', 'HAKI berhasil diperbarui.');
    }

    // =========================================================================
    // PATEN
    // =========================================================================

    public function editPaten()
    {
        $dosen = $this->authDosen();
        $patens = $dosen->patens;

        return view('dosen.edit-paten', compact('dosen', 'patens'));
    }

    public function updatePaten(Request $request)
    {
        $dosen = $this->authDosen();

        $request->validate([
            'patens.*.judul_paten'  => 'nullable|string',
            'patens.*.jenis_paten'  => 'nullable|string',
            'patens.*.expired'      => 'nullable|date',
            'patens.*.link'         => 'nullable|url',
        ]);

        $dosen->patens()->delete();
        $this->syncRelations($dosen, ['patens' => $request->patens ?? []]);

        $this->auditLog('update_paten', 'Dosen updated paten data', Paten::class, null, $dosen->id);

        return redirect()->route('dosen.dashboard')->with('success', 'Paten berhasil diperbarui.');
    }

    // =========================================================================
    // PRIVATE HELPER
    // =========================================================================

    /**
     * Ambil dosen yang sedang login via guard 'dosen'.
     * Abort 403 jika tidak terautentikasi.
     */
    private function authDosen(): Dosen
{
    $user = Auth::guard('web')->user();

    if (!$user || $user->role !== 'dosen') {
        abort(403, 'Anda tidak memiliki akses.');
    }

    // Ambil data dosen dari tabel dosens berdasarkan email
    $dosen = Dosen::where('email', $user->email)->first();

    if (!$dosen) {
        abort(404, 'Data dosen tidak ditemukan.');
    }

    return $dosen;
}

public function editPassword()
{
    $dosen = $this->authDosen();
    return view('dosen.edit-password', compact('dosen'));
}

public function updatePassword(Request $request)
{
    $user = Auth::guard('web')->user();

    $request->validate([
        'current_password'          => 'required|string',
        'password'                  => 'required|string|min:8|confirmed',
        'password_confirmation'     => 'required|string',
    ], [
        'password.min'              => 'Password baru minimal 8 karakter.',
        'password.confirmed'        => 'Konfirmasi password tidak cocok.',
        'current_password.required' => 'Password saat ini wajib diisi.',
    ]);

    // Verifikasi password lama
    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors([
            'current_password' => 'Password saat ini tidak sesuai.',
        ])->withInput();
    }

    $user->update([
        'password' => Hash::make($request->password),
    ]);

    $dosen = $this->authDosen();
    $this->auditLog(
        'change_password',
        "Dosen changed password: {$dosen->nama} (NIDN: {$dosen->nidn})",
        \App\Models\User::class,
        $user->id,
        $dosen->id
    );

    return redirect()->route('dosen.dashboard')
                     ->with('success', 'Password berhasil diperbarui.');
}
}