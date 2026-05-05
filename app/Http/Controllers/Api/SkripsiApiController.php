<?php
// app/Http/Controllers/Api/SkripsiApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SkripsiSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SkripsiApiController extends Controller
{
    public function __construct(private SkripsiSyncService $syncService) {}

    public function sync(Request $request)
{
    // Auth via token header
    $token = config('services.repodosen_sync.receive_token');
    if ($request->header('X-Sync-Token') !== $token) {
        return response()->json(['message' => 'Unauthorized.'], 401);
    }

    // Validasi dengan aturan yang lebih fleksibel
    $validated = $request->validate([
        'source'                  => 'required|string',
        'pendaftaran_id'          => 'required|string',
        'mahasiswa'               => 'required|array',
        'mahasiswa.nama'          => 'required|string|max:255',
        'mahasiswa.nim'           => 'nullable|string|max:20',
        'mahasiswa.angkatan'      => 'nullable|string|max:4',
        'judul_skripsi'           => 'required|string',
        'dosen_list'              => 'required|array|min:1',
        'dosen_list.*.nama'       => 'nullable|string',
        'dosen_list.*.nip'        => 'nullable|string',
        'dosen_list.*.role'       => 'required|in:pembimbing_1,pembimbing_2',
        'files'                   => 'nullable|array',
        'files.skripsi'           => 'nullable',
        'files.sk_pembimbing'     => 'nullable',
        'files.proposal'          => 'nullable',
    ]);

    if (!empty($validated['files'])) {
        foreach ($validated['files'] as $fileKey => $fileValue) {
            if (is_array($fileValue) && isset($fileValue['content'])) {
                Log::info("[SkripsiApi] Received file '{$fileKey}' in array format", [
                    'size' => strlen($fileValue['content']),
                    'folder' => $fileValue['folder'] ?? null
                ]);
            } elseif (is_string($fileValue)) {
                Log::info("[SkripsiApi] Received file '{$fileKey}' in string format", [
                    'size' => strlen($fileValue)
                ]);
            }
        }
    }

    try {
        $result = $this->syncService->process($validated);

        return response()->json([
            'message' => $result['message'],
            'synced'  => 1,
            'failed'  => 0,
            'results' => $result['results'],
        ], 200);

    } catch (\Exception $e) {
        Log::error('[SkripsiApi] Sync error: ' . $e->getMessage());

        return response()->json([
            'message' => 'Terjadi kesalahan server.',
            'synced'  => 0,
            'failed'  => 1,
        ], 500);
    }
}
}