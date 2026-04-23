<?php

use App\Http\Controllers\Admin\AdminDosenController;
use App\Http\Controllers\Admin\DosenExportController;
use App\Http\Controllers\Admin\DosenSyncLogController;
use App\Http\Controllers\Dosen\DosenProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Api\SyncDosenController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ══════════════════════════════════════════════════════════════════════════════
// PUBLIK
// ══════════════════════════════════════════════════════════════════════════════

Route::get('/',              [PublicController::class, 'index'])  ->name('public.index');
Route::post('/search',       [PublicController::class, 'search']) ->name('public.search');
Route::get('/category/{category}', [PublicController::class, 'category'])->name('public.category');

// ══════════════════════════════════════════════════════════════════════════════
// AUTENTIKASI
// ══════════════════════════════════════════════════════════════════════════════

Route::get('/login',         [AuthController::class, 'showLoginForm'])      ->name('login');
Route::post('/login',        [AuthController::class, 'login'])              ->name('login.post');
Route::post('/logout',       [AuthController::class, 'logout'])             ->name('logout');
Route::get('/login/google',  [AuthController::class, 'redirectToGoogle'])   ->name('login.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// ══════════════════════════════════════════════════════════════════════════════
// ADMIN — satu group, satu middleware
// ══════════════════════════════════════════════════════════════════════════════

Route::middleware(['auth', 'role:admin|staff'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // ── CRUD Dosen ────────────────────────────────────────────────────────
        // Route statis didaftarkan SEBELUM route dinamis {id} agar tidak bentrok
        Route::get('/dosen/export',          [DosenExportController::class, 'export'])         ->name('dosen.export');
        Route::get('/dosen/export-template', [DosenExportController::class, 'exportTemplate']) ->name('dosen.exportTemplate');
        Route::post('/dosen/import',         [DosenExportController::class, 'import'])         ->name('dosen.import');

        Route::get('/dosen',                 [AdminDosenController::class, 'index'])            ->name('dosen.index');
        Route::get('/dosen/create',          [AdminDosenController::class, 'create'])           ->name('dosen.create');
        Route::post('/dosen',                [AdminDosenController::class, 'store'])            ->name('dosen.store');
        Route::get('/dosen/{id}',            [AdminDosenController::class, 'show'])             ->name('dosen.show');
        Route::get('/dosen/{id}/edit',       [AdminDosenController::class, 'edit'])             ->name('dosen.edit');
        Route::put('/dosen/{id}',            [AdminDosenController::class, 'update'])           ->name('dosen.update');
        Route::delete('/dosen/{id}',         [AdminDosenController::class, 'destroy'])          ->name('dosen.destroy');
        Route::get('/dosen/{id}/recommend',  [AdminDosenController::class, 'recommend'])        ->name('dosen.recommend');

        // ── Destroy per-relasi ────────────────────────────────────────────────
        Route::delete('/penelitian/{id}',    [AdminDosenController::class, 'destroyPenelitian'])->name('penelitian.destroy');
        Route::delete('/pengabdian/{id}',    [AdminDosenController::class, 'destroyPengabdian'])->name('pengabdian.destroy');
        Route::delete('/haki/{id}',          [AdminDosenController::class, 'destroyHaki'])      ->name('haki.destroy');
        Route::delete('/paten/{id}',         [AdminDosenController::class, 'destroyPaten'])     ->name('paten.destroy');

        // ── Analytics ─────────────────────────────────────────────────────────
        Route::get('/analytics',             [AnalyticsController::class, 'index'])             ->name('analytics.index');

        // ── Data Dosen Sync (lihat data masuk dari e-Service) ─────────────────
        Route::get('/dosen-sync',            [DosenSyncLogController::class, 'index'])          ->name('dosen-sync.index');
        Route::get('/dosen-sync/{dosen}',    [DosenSyncLogController::class, 'show'])           ->name('dosen-sync.show');
    });

// ══════════════════════════════════════════════════════════════════════════════
// DOSEN SELF-SERVICE
// ══════════════════════════════════════════════════════════════════════════════

Route::middleware(['auth:dosen'])
    ->prefix('dosen')
    ->name('dosen.')
    ->group(function () {

        Route::get('/dashboard', fn() => view('dosen.dashboard'))->name('dashboard');

        // Profil
        Route::get('/edit',    [DosenProfileController::class, 'editProfile'])   ->name('edit');
        Route::put('/update',  [DosenProfileController::class, 'updateProfile']) ->name('update');

        // Penelitian
        Route::get('/penelitian/edit',   [DosenProfileController::class, 'editPenelitian'])   ->name('penelitian.edit');
        Route::put('/penelitian/update', [DosenProfileController::class, 'updatePenelitian']) ->name('penelitian.update');

        // Pengabdian
        Route::get('/pengabdian/edit',   [DosenProfileController::class, 'editPengabdian'])   ->name('pengabdian.edit');
        Route::put('/pengabdian/update', [DosenProfileController::class, 'updatePengabdian']) ->name('pengabdian.update');

        // HAKI
        Route::get('/haki/edit',   [DosenProfileController::class, 'editHaki'])   ->name('haki.edit');
        Route::put('/haki/update', [DosenProfileController::class, 'updateHaki']) ->name('haki.update');

        // Paten
        Route::get('/paten/edit',   [DosenProfileController::class, 'editPaten'])   ->name('paten.edit');
        Route::put('/paten/update', [DosenProfileController::class, 'updatePaten']) ->name('paten.update');
    });