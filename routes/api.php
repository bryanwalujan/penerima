<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SyncDosenController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ── Sync dari Presma (tanpa session/sanctum, auth via X-Sync-Token) ──────────
Route::post('/sync/dosen-pembimbing', [SyncDosenController::class, 'syncDosenPembimbing'])
    ->name('api.sync.dosen-pembimbing');