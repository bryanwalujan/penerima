<?php
// app/Http/Controllers/Dosen/DosenProfileController.php

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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
            'nidn'  => 'required|string|max:20|unique:dosens,nidn,' . $dosen->id,
            'nip'   => 'nullable|string|max:20',
            'nuptk' => 'nullable|string|max:20',
            'foto'  => 'nullable|image|mimes:jpeg,png,jpg|max:10000',
        ]);

        $data = $request->only(['nama', 'nidn', 'nip', 'nuptk']);

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
    // PENELITIAN - CRUD lengkap
    // =========================================================================

    public function editPenelitian()
    {
        $dosen = $this->authDosen();
        $penelitians = $dosen->penelitians()->orderBy('tahun', 'desc')->get();

        return view('dosen.edit-penelitian', compact('dosen', 'penelitians'));
    }

    public function storePenelitian(Request $request)
    {
        $dosen = $this->authDosen();

        $request->validate([
            'skema'            => 'nullable|string',
            'posisi'           => 'nullable|string',
            'judul_penelitian' => 'required|string|max:500',
            'sumber_dana'      => 'nullable|string',
            'status'           => 'nullable|string|in:Selesai,Berjalan,Diajukan',
            'tahun'            => 'nullable|integer|min:2000|max:' . (date('Y') + 5),
            'link_luaran'      => 'nullable|url',
        ]);

        $penelitian = $dosen->penelitians()->create($request->all());

        $this->auditLog(
            'create_penelitian',
            "Dosen created penelitian: {$penelitian->judul_penelitian}",
            Penelitian::class,
            $penelitian->id,
            $dosen->id
        );

        return redirect()->route('dosen.penelitian.edit')->with('success', 'Penelitian berhasil ditambahkan.');
    }

    public function updatePenelitian(Request $request, $id)
    {
        $dosen = $this->authDosen();
        $penelitian = Penelitian::where('dosen_id', $dosen->id)->findOrFail($id);

        $request->validate([
            'skema'            => 'nullable|string',
            'posisi'           => 'nullable|string',
            'judul_penelitian' => 'required|string|max:500',
            'sumber_dana'      => 'nullable|string',
            'status'           => 'nullable|string|in:Selesai,Berjalan,Diajukan',
            'tahun'            => 'nullable|integer|min:2000|max:' . (date('Y') + 5),
            'link_luaran'      => 'nullable|url',
        ]);

        $penelitian->update($request->all());

        $this->auditLog(
            'update_penelitian',
            "Dosen updated penelitian: {$penelitian->judul_penelitian}",
            Penelitian::class,
            $penelitian->id,
            $dosen->id
        );

        return redirect()->route('dosen.penelitian.edit')->with('success', 'Penelitian berhasil diperbarui.');
    }

    public function destroyPenelitian($id)
    {
        $dosen = $this->authDosen();
        $penelitian = Penelitian::where('dosen_id', $dosen->id)->findOrFail($id);
        $judul = $penelitian->judul_penelitian;
        $penelitian->delete();

        $this->auditLog(
            'delete_penelitian',
            "Dosen deleted penelitian: {$judul}",
            Penelitian::class,
            $id,
            $dosen->id
        );

        return redirect()->route('dosen.penelitian.edit')->with('success', 'Penelitian berhasil dihapus.');
    }

    // =========================================================================
    // PENGABDIAN - CRUD lengkap
    // =========================================================================

    public function editPengabdian()
    {
        $dosen = $this->authDosen();
        $pengabdians = $dosen->pengabdians()->orderBy('tahun', 'desc')->get();

        return view('dosen.edit-pengabdian', compact('dosen', 'pengabdians'));
    }

    public function storePengabdian(Request $request)
    {
        $dosen = $this->authDosen();

        $request->validate([
            'skema'             => 'nullable|string',
            'posisi'            => 'nullable|string',
            'judul_pengabdian'  => 'required|string|max:500',
            'sumber_dana'       => 'nullable|string',
            'status'            => 'nullable|string|in:Selesai,Berjalan,Diajukan',
            'tahun'             => 'nullable|integer|min:2000|max:' . (date('Y') + 5),
            'link_luaran'       => 'nullable|url',
        ]);

        $pengabdian = $dosen->pengabdians()->create($request->all());

        $this->auditLog(
            'create_pengabdian',
            "Dosen created pengabdian: {$pengabdian->judul_pengabdian}",
            Pengabdian::class,
            $pengabdian->id,
            $dosen->id
        );

        return redirect()->route('dosen.pengabdian.edit')->with('success', 'Pengabdian berhasil ditambahkan.');
    }

    public function updatePengabdian(Request $request, $id)
    {
        $dosen = $this->authDosen();
        $pengabdian = Pengabdian::where('dosen_id', $dosen->id)->findOrFail($id);

        $request->validate([
            'skema'             => 'nullable|string',
            'posisi'            => 'nullable|string',
            'judul_pengabdian'  => 'required|string|max:500',
            'sumber_dana'       => 'nullable|string',
            'status'            => 'nullable|string|in:Selesai,Berjalan,Diajukan',
            'tahun'             => 'nullable|integer|min:2000|max:' . (date('Y') + 5),
            'link_luaran'       => 'nullable|url',
        ]);

        $pengabdian->update($request->all());

        $this->auditLog(
            'update_pengabdian',
            "Dosen updated pengabdian: {$pengabdian->judul_pengabdian}",
            Pengabdian::class,
            $pengabdian->id,
            $dosen->id
        );

        return redirect()->route('dosen.pengabdian.edit')->with('success', 'Pengabdian berhasil diperbarui.');
    }

    public function destroyPengabdian($id)
    {
        $dosen = $this->authDosen();
        $pengabdian = Pengabdian::where('dosen_id', $dosen->id)->findOrFail($id);
        $judul = $pengabdian->judul_pengabdian;
        $pengabdian->delete();

        $this->auditLog(
            'delete_pengabdian',
            "Dosen deleted pengabdian: {$judul}",
            Pengabdian::class,
            $id,
            $dosen->id
        );

        return redirect()->route('dosen.pengabdian.edit')->with('success', 'Pengabdian berhasil dihapus.');
    }

    // =========================================================================
    // HAKI - CRUD lengkap
    // =========================================================================

    public function editHaki()
    {
        $dosen = $this->authDosen();
        $hakis = $dosen->hakis()->orderBy('expired', 'desc')->get();

        return view('dosen.edit-haki', compact('dosen', 'hakis'));
    }

    public function storeHaki(Request $request)
    {
        $dosen = $this->authDosen();

        $request->validate([
            'judul_haki' => 'required|string|max:500',
            'expired'    => 'nullable|date',
            'link'       => 'nullable|url',
        ]);

        $haki = $dosen->hakis()->create($request->all());

        $this->auditLog(
            'create_haki',
            "Dosen created haki: {$haki->judul_haki}",
            Haki::class,
            $haki->id,
            $dosen->id
        );

        return redirect()->route('dosen.haki.edit')->with('success', 'HAKI berhasil ditambahkan.');
    }

    public function updateHaki(Request $request, $id)
    {
        $dosen = $this->authDosen();
        $haki = Haki::where('dosen_id', $dosen->id)->findOrFail($id);

        $request->validate([
            'judul_haki' => 'required|string|max:500',
            'expired'    => 'nullable|date',
            'link'       => 'nullable|url',
        ]);

        $haki->update($request->all());

        $this->auditLog(
            'update_haki',
            "Dosen updated haki: {$haki->judul_haki}",
            Haki::class,
            $haki->id,
            $dosen->id
        );

        return redirect()->route('dosen.haki.edit')->with('success', 'HAKI berhasil diperbarui.');
    }

    public function destroyHaki($id)
    {
        $dosen = $this->authDosen();
        $haki = Haki::where('dosen_id', $dosen->id)->findOrFail($id);
        $judul = $haki->judul_haki;
        $haki->delete();

        $this->auditLog(
            'delete_haki',
            "Dosen deleted haki: {$judul}",
            Haki::class,
            $id,
            $dosen->id
        );

        return redirect()->route('dosen.haki.edit')->with('success', 'HAKI berhasil dihapus.');
    }

    // =========================================================================
    // PATEN - CRUD lengkap
    // =========================================================================

    public function editPaten()
    {
        $dosen = $this->authDosen();
        $patens = $dosen->patens()->orderBy('expired', 'desc')->get();

        return view('dosen.edit-paten', compact('dosen', 'patens'));
    }

    public function storePaten(Request $request)
    {
        $dosen = $this->authDosen();

        $request->validate([
            'judul_paten'  => 'required|string|max:500',
            'jenis_paten'  => 'nullable|string',
            'expired'      => 'nullable|date',
            'link'         => 'nullable|url',
        ]);

        $paten = $dosen->patens()->create($request->all());

        $this->auditLog(
            'create_paten',
            "Dosen created paten: {$paten->judul_paten}",
            Paten::class,
            $paten->id,
            $dosen->id
        );

        return redirect()->route('dosen.paten.edit')->with('success', 'Paten berhasil ditambahkan.');
    }

    public function updatePaten(Request $request, $id)
    {
        $dosen = $this->authDosen();
        $paten = Paten::where('dosen_id', $dosen->id)->findOrFail($id);

        $request->validate([
            'judul_paten'  => 'required|string|max:500',
            'jenis_paten'  => 'nullable|string',
            'expired'      => 'nullable|date',
            'link'         => 'nullable|url',
        ]);

        $paten->update($request->all());

        $this->auditLog(
            'update_paten',
            "Dosen updated paten: {$paten->judul_paten}",
            Paten::class,
            $paten->id,
            $dosen->id
        );

        return redirect()->route('dosen.paten.edit')->with('success', 'Paten berhasil diperbarui.');
    }

    public function destroyPaten($id)
    {
        $dosen = $this->authDosen();
        $paten = Paten::where('dosen_id', $dosen->id)->findOrFail($id);
        $judul = $paten->judul_paten;
        $paten->delete();

        $this->auditLog(
            'delete_paten',
            "Dosen deleted paten: {$judul}",
            Paten::class,
            $id,
            $dosen->id
        );

        return redirect()->route('dosen.paten.edit')->with('success', 'Paten berhasil dihapus.');
    }

    // =========================================================================
    // PASSWORD
    // =========================================================================

    public function editPassword()
    {
        $dosen = $this->authDosen();
        return view('dosen.edit-password', compact('dosen'));
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password'          => 'required|string',
            'password'                  => 'required|string|min:8|confirmed',
            'password_confirmation'     => 'required|string',
        ], [
            'password.min'              => 'Password baru minimal 8 karakter.',
            'password.confirmed'        => 'Konfirmasi password tidak cocok.',
            'current_password.required' => 'Password saat ini wajib diisi.',
        ]);

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

    // =========================================================================
    // PRIVATE HELPER
    // =========================================================================

    private function authDosen(): Dosen
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'dosen') {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $dosen = Dosen::where('email', $user->email)->first();

        if (!$dosen) {
            abort(404, 'Data dosen tidak ditemukan.');
        }

        return $dosen;
    }
}