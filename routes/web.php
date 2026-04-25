<?php

use App\Http\Controllers\Admin\AdminDosenController;
use App\Http\Controllers\Admin\DosenExportController;
use App\Http\Controllers\Dosen\DosenProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\SkripsiController;
use App\Http\Controllers\Admin\FileSkripsiController;
use App\Http\Controllers\Admin\FileSkPembimbingController;
use App\Http\Controllers\Admin\FileProposalController;
use App\Http\Controllers\Dosen\DosenSkripsiController;

// ── Rute Publik ───────────────────────────────────────────────────────────────
Route::get('/', [PublicController::class, 'index'])->name('public.index');
Route::post('/search', [PublicController::class, 'search'])->name('public.search');
Route::get('/category/{category}', [PublicController::class, 'category'])->name('public.category');

// ── Autentikasi (satu form untuk semua role) ──────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

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

Route::middleware(['auth'])->group(function () {
    Route::get('/dosen/dashboard', function () {
        abort_if(Auth::user()->role !== 'dosen', 403);
        $dosen = \App\Models\Dosen::where('email', Auth::user()->email)->first();
        return view('dosen.dashboard', compact('dosen'));
    })->name('dosen.dashboard');

    // Profile routes
    Route::get('/dosen/edit',    [DosenProfileController::class, 'editProfile'])->name('dosen.edit');
    Route::put('/dosen/update',  [DosenProfileController::class, 'updateProfile'])->name('dosen.update');

    // Password routes
    Route::get('/dosen/password/edit',   [DosenProfileController::class, 'editPassword'])->name('dosen.password.edit');
    Route::put('/dosen/password/update', [DosenProfileController::class, 'updatePassword'])->name('dosen.password.update');

    // Penelitian routes (CRUD)
    Route::get('/dosen/penelitian/edit',      [DosenProfileController::class, 'editPenelitian'])->name('dosen.penelitian.edit');
    Route::post('/dosen/penelitian/store',    [DosenProfileController::class, 'storePenelitian'])->name('dosen.penelitian.store');
    Route::put('/dosen/penelitian/update/{id}', [DosenProfileController::class, 'updatePenelitian'])->name('dosen.penelitian.update');
    Route::delete('/dosen/penelitian/destroy/{id}', [DosenProfileController::class, 'destroyPenelitian'])->name('dosen.penelitian.destroy');

    // Pengabdian routes (CRUD)
    Route::get('/dosen/pengabdian/edit',        [DosenProfileController::class, 'editPengabdian'])->name('dosen.pengabdian.edit');
    Route::post('/dosen/pengabdian/store',      [DosenProfileController::class, 'storePengabdian'])->name('dosen.pengabdian.store');
    Route::put('/dosen/pengabdian/update/{id}', [DosenProfileController::class, 'updatePengabdian'])->name('dosen.pengabdian.update');
    Route::delete('/dosen/pengabdian/destroy/{id}', [DosenProfileController::class, 'destroyPengabdian'])->name('dosen.pengabdian.destroy');

    // HAKI routes (CRUD)
    Route::get('/dosen/haki/edit',      [DosenProfileController::class, 'editHaki'])->name('dosen.haki.edit');
    Route::post('/dosen/haki/store',    [DosenProfileController::class, 'storeHaki'])->name('dosen.haki.store');
    Route::put('/dosen/haki/update/{id}', [DosenProfileController::class, 'updateHaki'])->name('dosen.haki.update');
    Route::delete('/dosen/haki/destroy/{id}', [DosenProfileController::class, 'destroyHaki'])->name('dosen.haki.destroy');

    // Paten routes (CRUD)
    Route::get('/dosen/paten/edit',      [DosenProfileController::class, 'editPaten'])->name('dosen.paten.edit');
    Route::post('/dosen/paten/store',    [DosenProfileController::class, 'storePaten'])->name('dosen.paten.store');
    Route::put('/dosen/paten/update/{id}', [DosenProfileController::class, 'updatePaten'])->name('dosen.paten.update');
    Route::delete('/dosen/paten/destroy/{id}', [DosenProfileController::class, 'destroyPaten'])->name('dosen.paten.destroy');

    // Routes untuk file skripsi bimbingan
    Route::prefix('dosen/skripsi')->name('dosen.skripsi.')->group(function () {
        Route::get('/', [DosenSkripsiController::class, 'skripsiIndex'])->name('index');
        Route::get('/preview/{skripsi}', [DosenSkripsiController::class, 'previewSkripsi'])->name('preview');
        Route::get('/download/{skripsi}', [DosenSkripsiController::class, 'downloadSkripsi'])->name('download');
    });
    
    // Routes untuk file SK pembimbing
    Route::prefix('dosen/sk-pembimbing')->name('dosen.sk-pembimbing.')->group(function () {
        Route::get('/', [DosenSkripsiController::class, 'skPembimbingIndex'])->name('index');
        Route::get('/preview/{skripsi}', [DosenSkripsiController::class, 'previewSkPembimbing'])->name('preview');
        Route::get('/download/{skripsi}', [DosenSkripsiController::class, 'downloadSkPembimbing'])->name('download');
    });
    
    // Routes untuk file proposal
    Route::prefix('dosen/proposal')->name('dosen.proposal.')->group(function () {
        Route::get('/', [DosenSkripsiController::class, 'proposalIndex'])->name('index');
        Route::get('/preview/{skripsi}', [DosenSkripsiController::class, 'previewProposal'])->name('preview');
        Route::get('/download/{skripsi}', [DosenSkripsiController::class, 'downloadProposal'])->name('download');
    });
});