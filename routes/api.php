<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SyncDosenController;
use App\Http\Controllers\Api\SkripsiApiController;

Route::post('/sync/skripsi', [SkripsiApiController::class, 'sync'])
    ->middleware('throttle:30,1'); // max 30 req/menit

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ── Sync dari Presma (tanpa session/sanctum, auth via X-Sync-Token) ──────────
Route::post('/sync/dosen-pembimbing', [SyncDosenController::class, 'syncDosenPembimbing'])
    ->name('api.sync.dosen-pembimbing');