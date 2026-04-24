<?php

use App\Http\Controllers\Admin\AdminDosenController;
use App\Http\Controllers\Admin\DosenExportController;
use App\Http\Controllers\Dosen\DosenProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Api\SyncDosenController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\SkripsiController;
use App\Http\Controllers\Admin\FileSkripsiController;
use App\Http\Controllers\Admin\FileSkPembimbingController;
use App\Http\Controllers\Admin\FileProposalController;

// ── Rute Publik ───────────────────────────────────────────────────────────────
Route::get('/', [PublicController::class, 'index'])->name('public.index');
Route::post('/search', [PublicController::class, 'search'])->name('public.search');
Route::get('/category/{category}', [PublicController::class, 'category'])->name('public.category');

// ── Autentikasi Admin ─────────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Autentikasi Dosen (SSO Google) ───────────────────────────────────────────
Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// ── Admin Routes ──────────────────────────────────────────────────────────────
Route::prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        if (!Auth::guard('web')->check() || Auth::guard('web')->user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Akses ditolak. Hanya admin yang diizinkan.');
        }
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // ── CRUD Dosen ────────────────────────────────────────────────────────────
    // Urutan penting: route statis (/export, /export-template, /import)
    // harus didaftarkan SEBELUM route dinamis (/{id}) agar tidak bentrok.
    Route::get('/dosen/export',           [DosenExportController::class, 'export'])         ->name('admin.dosen.export');
    Route::get('/dosen/export-template',  [DosenExportController::class, 'exportTemplate']) ->name('admin.dosen.exportTemplate');
    Route::post('/dosen/import',          [DosenExportController::class, 'import'])         ->name('admin.dosen.import');

    Route::get('/dosen',                  [AdminDosenController::class, 'index'])            ->name('admin.dosen.index');
    Route::get('/dosen/create',           [AdminDosenController::class, 'create'])           ->name('admin.dosen.create');
    Route::post('/dosen',                 [AdminDosenController::class, 'store'])            ->name('admin.dosen.store');
    Route::get('/dosen/{id}',             [AdminDosenController::class, 'show'])             ->name('admin.dosen.show');
    Route::get('/dosen/{id}/edit',        [AdminDosenController::class, 'edit'])             ->name('admin.dosen.edit');
    Route::put('/dosen/{id}',             [AdminDosenController::class, 'update'])           ->name('admin.dosen.update');
    Route::delete('/dosen/{id}',          [AdminDosenController::class, 'destroy'])          ->name('admin.dosen.destroy');
    Route::get('/dosen/{id}/recommend',   [AdminDosenController::class, 'recommend'])        ->name('admin.dosen.recommend');

    // ── Destroy per-relasi ────────────────────────────────────────────────────
    Route::delete('/penelitian/{id}',     [AdminDosenController::class, 'destroyPenelitian'])->name('admin.penelitian.destroy');
    Route::delete('/pengabdian/{id}',     [AdminDosenController::class, 'destroyPengabdian'])->name('admin.pengabdian.destroy');
    Route::delete('/haki/{id}',           [AdminDosenController::class, 'destroyHaki'])      ->name('admin.haki.destroy');
    Route::delete('/paten/{id}',          [AdminDosenController::class, 'destroyPaten'])     ->name('admin.paten.destroy');

    // ── Analytics ─────────────────────────────────────────────────────────────
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('admin.analytics.index');

    // CRUD Skripsi
    Route::get('/skripsi', [SkripsiController::class, 'index'])->name('admin.skripsi.index');
    Route::get('/skripsi/{skripsi}', [SkripsiController::class, 'show'])->name('admin.skripsi.show');
    Route::get('/skripsi/{skripsi}/download/{fileType}', [SkripsiController::class, 'downloadFile'])->name('admin.skripsi.download');
    Route::get('/skripsi/{skripsi}/preview/{fileType}', [SkripsiController::class, 'previewFile'])->name('admin.skripsi.preview');
    Route::delete('/skripsi/{skripsi}/file/{fileType}', [SkripsiController::class, 'deleteFile'])->name('admin.skripsi.delete-file');

    // Serve file dari private storage
    Route::get('/storage/skripsi/{filename}', [SkripsiController::class, 'serveFile'])
        ->name('admin.skripsi.serve')
        ->where('filename', '.*');

    // File Skripsi Routes
    Route::prefix('file/skripsi')->name('admin.file.skripsi.')->group(function () {
        Route::get('/', [FileSkripsiController::class, 'index'])->name('index');
        Route::get('/dosen/{dosen}', [FileSkripsiController::class, 'show'])->name('dosen');
        Route::get('/preview/{skripsi}', [FileSkripsiController::class, 'preview'])->name('preview');
        Route::get('/download/{skripsi}', [FileSkripsiController::class, 'download'])->name('download');
    });
    
    // File SK Pembimbing Routes
    Route::prefix('file/sk-pembimbing')->name('admin.file.sk-pembimbing.')->group(function () {
        Route::get('/', [FileSkPembimbingController::class, 'index'])->name('index');
        Route::get('/dosen/{dosen}', [FileSkPembimbingController::class, 'show'])->name('dosen');
        Route::get('/preview/{skripsi}', [FileSkPembimbingController::class, 'preview'])->name('preview');
        Route::get('/download/{skripsi}', [FileSkPembimbingController::class, 'download'])->name('download');
    });
    
    // File Proposal Routes
    Route::prefix('file/proposal')->name('admin.file.proposal.')->group(function () {
        Route::get('/', [FileProposalController::class, 'index'])->name('index');
        Route::get('/dosen/{dosen}', [FileProposalController::class, 'show'])->name('dosen');
        Route::get('/preview/{skripsi}', [FileProposalController::class, 'preview'])->name('preview');
        Route::get('/download/{skripsi}', [FileProposalController::class, 'download'])->name('download');
    });
});

// ── Dosen Self-Service Routes ─────────────────────────────────────────────────
Route::middleware(['auth:dosen'])->group(function () {

    Route::get('/dosen/dashboard', function () {
        return view('dosen.dashboard');
    })->name('dosen.dashboard');

    // Profil
    Route::get('/dosen/edit',    [DosenProfileController::class, 'editProfile'])   ->name('dosen.edit');
    Route::put('/dosen/update',  [DosenProfileController::class, 'updateProfile']) ->name('dosen.update');

    // Penelitian
    Route::get('/dosen/penelitian/edit',    [DosenProfileController::class, 'editPenelitian'])   ->name('dosen.penelitian.edit');
    Route::put('/dosen/penelitian/update',  [DosenProfileController::class, 'updatePenelitian']) ->name('dosen.penelitian.update');

    // Pengabdian
    Route::get('/dosen/pengabdian/edit',    [DosenProfileController::class, 'editPengabdian'])   ->name('dosen.pengabdian.edit');
    Route::put('/dosen/pengabdian/update',  [DosenProfileController::class, 'updatePengabdian']) ->name('dosen.pengabdian.update');

    // HAKI
    Route::get('/dosen/haki/edit',    [DosenProfileController::class, 'editHaki'])   ->name('dosen.haki.edit');
    Route::put('/dosen/haki/update',  [DosenProfileController::class, 'updateHaki']) ->name('dosen.haki.update');

    // Paten
    Route::get('/dosen/paten/edit',   [DosenProfileController::class, 'editPaten'])   ->name('dosen.paten.edit');
    Route::put('/dosen/paten/update', [DosenProfileController::class, 'updatePaten']) ->name('dosen.paten.update');
});