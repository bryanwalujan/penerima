<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\File;

use App\Models\SkPembimbing;
use App\Models\Dosen;

use App\Http\Controllers\SkPembimbingController;

// Route SK Pembimbing
Route::get('/sk-pembimbing', [SkPembimbingController::class, 'index'])
     ->name('sk-pembimbing.index');

Route::get('/sk-pembimbing/{skPembimbing}', [SkPembimbingController::class, 'show'])
     ->name('sk-pembimbing.show');

// API untuk menerima data SK Pembimbing dari e-service
Route::post('/api/sk-pembimbing/receive', function (Request $request) {
    $request->validate([
        'pengajuan_sk_pembimbing_id' => 'nullable|integer',
        'mahasiswa_id'               => 'required|integer',
        'judul_skripsi'              => 'required|string',
        'file_surat_permohonan'      => 'required|string',
        'file_slip_ukt'              => 'required|string',
        'file_proposal_revisi'       => 'required|string',
        'file_surat_sk'              => 'nullable|string',

        'dosen_pembimbing_1' => 'required|array',
        'dosen_pembimbing_2' => 'nullable|array',
    ]);

    // === Sinkronisasi Dosen Berdasarkan NIP atau Nama ===
    $dosen1 = $this->syncDosen($request->dosen_pembimbing_1);
    $dosen2 = $request->dosen_pembimbing_2 
              ? $this->syncDosen($request->dosen_pembimbing_2) 
              : null;

    // Simpan / Update SK Pembimbing
    $sk = SkPembimbing::updateOrCreate(
        ['pengajuan_sk_pembimbing_id' => $request->pengajuan_sk_pembimbing_id],
        [
            'mahasiswa_id'           => $request->mahasiswa_id,
            'dosen_pembimbing_1_id'  => $dosen1->id,
            'dosen_pembimbing_2_id'  => $dosen2?->id,
            'judul_skripsi'          => $request->judul_skripsi,
            'file_surat_permohonan'  => $request->file_surat_permohonan,
            'file_slip_ukt'          => $request->file_slip_ukt,
            'file_proposal_revisi'   => $request->file_proposal_revisi,
            'file_surat_sk'          => $request->file_surat_sk ?? null,
            'status'                 => 'draft',
        ]
    );

    return response()->json([
        'success' => true,
        'message' => 'Data SK Pembimbing berhasil diterima di Repodosen',
        'sk_pembimbing_id' => $sk->id
    ]);
});

// Helper function untuk sinkronisasi dosen
function syncDosen($dosenData)
{
    $dosen = Dosen::where('nip', $dosenData['nip'])
                  ->orWhere('nama', $dosenData['nama'])
                  ->first();

    if (!$dosen) {
        $dosen = Dosen::create([
            'nip'  => $dosenData['nip'] ?? null,
            'nama' => $dosenData['nama'],
            'nidn' => $dosenData['nidn'] ?? null,
        ]);
    } else {
        // Update jika ada perubahan
        $dosen->update([
            'nama' => $dosenData['nama'],
            'nip'  => $dosenData['nip'] ?? $dosen->nip,
        ]);
    }

    return $dosen;
}


// ==================== API RECEIVE UPLOAD ====================
Route::post('/api/receive-upload', function (Request $request) {
    $request->validate([
        'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
    ]);

    $file = $request->file('file');
    $filename = time() . '_' . $file->getClientOriginalName();

    // Simpan ke storage/app/private/uploads-from-presma
    $path = $file->storeAs('private/uploads-from-presma', $filename);

    return response()->json([
        'success'  => true,
        'filename' => $filename,
        'url'      => route('download.from.presma', $filename)
    ]);
});

// ==================== DOWNLOAD FILE (Private Storage) ====================
Route::get('/download/presma/{filename}', function ($filename) {
    $filePath = storage_path('app/private/uploads-from-presma/' . $filename);

    if (!file_exists($filePath)) {
        abort(404, 'File tidak ditemukan');
    }

    return response()->download($filePath, $filename);
})->name('download.from.presma');

// ==================== HALAMAN DAFTAR FILE ====================
Route::get('/uploads-from-presma', function () {
    $directory = storage_path('app/private/uploads-from-presma');

    if (!file_exists($directory)) {
        mkdir($directory, 0775, true);
    }

    $files = collect(File::files($directory))
        ->map(function ($file) {
            $filename = $file->getFilename();
            return [
                'name'     => $filename,
                'size'     => round($file->getSize() / 1024, 2) . ' KB',
                'modified' => date('d M Y H:i', $file->getMTime()),
                'url'      => route('download.from.presma', $filename)
            ];
        })
        ->sortByDesc('modified');

    return view('uploads-from-presma', compact('files'));
});


// Rute Publik
Route::get('/', [PublicController::class, 'index'])->name('public.index');
Route::post('/search', [PublicController::class, 'search'])->name('public.search');
Route::get('/category/{category}', [PublicController::class, 'category'])->name('public.category');

// Rute Autentikasi Admin
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Autentikasi Dosen (SSO)
Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Rute Admin (tanpa middleware, otorisasi dilakukan di controller)
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        if (!Auth::guard('web')->check() || Auth::guard('web')->user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Akses ditolak. Hanya admin yang diizinkan.');
        }
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/dosen', [DosenController::class, 'index'])->name('admin.dosen.index');
    Route::get('/dosen/create', [DosenController::class, 'create'])->name('admin.dosen.create');
    Route::post('/dosen', [DosenController::class, 'store'])->name('admin.dosen.store');
    Route::get('/dosen/{id}/edit', [DosenController::class, 'edit'])->name('admin.dosen.edit');
    Route::put('/dosen/{id}', [DosenController::class, 'update'])->name('admin.dosen.update');
    Route::delete('/dosen/{id}', [DosenController::class, 'destroy'])->name('admin.dosen.destroy');
    Route::post('/dosen/import', [DosenController::class, 'import'])->name('admin.dosen.import');
    Route::get('/dosen/export', [DosenController::class, 'export'])->name('admin.dosen.export');
    Route::get('/dosen/export-template', [DosenController::class, 'exportTemplate'])->name('admin.dosen.exportTemplate');
    Route::get('/dosen/{id}', [DosenController::class, 'show'])->name('admin.dosen.show');
    Route::get('/dosen/{id}/recommend', [DosenController::class, 'recommend'])->name('admin.dosen.recommend');
    Route::delete('/penelitian/{id}', [DosenController::class, 'destroyPenelitian'])->name('admin.penelitian.destroy');
    Route::delete('/pengabdian/{id}', [DosenController::class, 'destroyPengabdian'])->name('admin.pengabdian.destroy');
    Route::delete('/haki/{id}', [DosenController::class, 'destroyHaki'])->name('admin.haki.destroy');
    Route::delete('/paten/{id}', [DosenController::class, 'destroyPaten'])->name('admin.paten.destroy');

    // Rute untuk Dashboard Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('admin.analytics.index');
});

// Rute Dosen (memerlukan autentikasi dengan guard dosen)
Route::middleware(['auth:dosen'])->group(function () {
    Route::get('/dosen/dashboard', function () {
        return view('dosen.dashboard');
    })->name('dosen.dashboard');
    Route::get('/dosen/edit', [DosenController::class, 'editProfile'])->name('dosen.edit');
    Route::put('/dosen/update', [DosenController::class, 'updateProfile'])->name('dosen.update');
    Route::get('/dosen/penelitian/edit', [DosenController::class, 'editPenelitian'])->name('dosen.penelitian.edit');
    Route::put('/dosen/penelitian/update', [DosenController::class, 'updatePenelitian'])->name('dosen.penelitian.update');
    Route::get('/dosen/pengabdian/edit', [DosenController::class, 'editPengabdian'])->name('dosen.pengabdian.edit');
    Route::put('/dosen/pengabdian/update', [DosenController::class, 'updatePengabdian'])->name('dosen.pengabdian.update');
    Route::get('/dosen/haki/edit', [DosenController::class, 'editHaki'])->name('dosen.haki.edit');
    Route::put('/dosen/haki/update', [DosenController::class, 'updateHaki'])->name('dosen.haki.update');
    Route::get('/dosen/paten/edit', [DosenController::class, 'editPaten'])->name('dosen.paten.edit');
    Route::put('/dosen/paten/update', [DosenController::class, 'updatePaten'])->name('dosen.paten.update');
});     