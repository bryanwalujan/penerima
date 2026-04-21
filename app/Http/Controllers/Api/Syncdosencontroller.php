<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * SyncDosenController — Web Penerima (repodosen)
 *
 * Menerima data dosen pembimbing dari presma dan melakukan
 * upsert (insert atau update) ke tabel `dosens`.
 *
 * Autentikasi: API Key via header X-Sync-Token
 */
class SyncDosenController extends Controller
{
    /**
     * Terima dan sync data dosen pembimbing dari presma.
     *
     * POST /api/sync/dosen-pembimbing
     *
     * Header yang wajib:
     *   X-Sync-Token: <nilai dari PRESMA_SYNC_TOKEN di .env>
     *
     * Body JSON:
     * {
     *   "source": "presma",
     *   "dosen_list": [
     *     {
     *       "nip":  "198501012010122001",
     *       "nidn": "0001018501",
     *       "nama": "Dr. Irene Realyta ...",
     *       "role": "pembimbing_1"   // opsional, untuk keperluan log
     *     },
     *     { ... }
     *   ]
     * }
     */
    public function syncDosenPembimbing(Request $request): JsonResponse
    {
        // ── 1. Autentikasi ─────────────────────────────────────────────────
        $token = $request->header('X-Sync-Token');

        if (!$token || $token !== config('services.presma_sync.token')) {
            Log::warning('[SyncDosen] Unauthorized sync attempt', [
                'ip'    => $request->ip(),
                'token' => substr((string) $token, 0, 8) . '...',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Token tidak valid.',
            ], 401);
        }

        // ── 2. Validasi payload ────────────────────────────────────────────
        $validated = $request->validate([
            'source'            => 'required|string',
            'dosen_list'        => 'required|array|min:1',
            'dosen_list.*.nama' => 'required|string|max:255',
            'dosen_list.*.nip'  => 'nullable|string|max:50',
            'dosen_list.*.nidn' => 'nullable|string|max:20',
            'dosen_list.*.role' => 'nullable|string|max:50',
        ]);

        // ── 3. Proses setiap dosen ─────────────────────────────────────────
        $results = [];

        foreach ($validated['dosen_list'] as $dosenData) {
            try {
                $dosen  = $this->syncDosen($dosenData);
                $action = $dosen->wasRecentlyCreated ? 'created' : 'updated';

                $results[] = [
                    'nama'   => $dosen->nama,
                    'nip'    => $dosen->nip,
                    'action' => $action,
                ];

                Log::info("[SyncDosen] Dosen {$action}", [
                    'id'   => $dosen->id,
                    'nama' => $dosen->nama,
                    'nip'  => $dosen->nip,
                ]);

            } catch (\Exception $e) {
                Log::error('[SyncDosen] Gagal sync dosen', [
                    'data'  => $dosenData,
                    'error' => $e->getMessage(),
                ]);

                $results[] = [
                    'nama'   => $dosenData['nama'],
                    'nip'    => $dosenData['nip'] ?? null,
                    'action' => 'error',
                    'reason' => $e->getMessage(),
                ];
            }
        }

        // ── 4. Respons ─────────────────────────────────────────────────────
        $hasError = collect($results)->contains('action', 'error');

        return response()->json([
            'success' => !$hasError,
            'message' => $hasError
                ? 'Sync selesai dengan beberapa error.'
                : 'Sync berhasil. Semua data dosen diperbarui.',
            'synced'  => count(array_filter($results, fn($r) => $r['action'] !== 'error')),
            'failed'  => count(array_filter($results, fn($r) => $r['action'] === 'error')),
            'results' => $results,
        ], $hasError ? 207 : 200);
    }

    /**
     * Upsert satu data dosen.
     *
     * Logika:
     * - Jika ada NIP → cari by NIP
     * - Jika tidak ada NIP → cari by nama
     * - Tidak ditemukan → buat baru
     * - Ditemukan → update nama & NIP (data lain tidak ditimpa)
     */
    private function syncDosen(array $dosenData): Dosen
    {
        $query = Dosen::query();

        if (!empty($dosenData['nip'])) {
            $query->where('nip', $dosenData['nip']);
        } else {
            $query->where('nama', $dosenData['nama']);
        }

        $dosen = $query->first();

        if (!$dosen) {
            $dosen = Dosen::create([
                'nip'  => $dosenData['nip']  ?? null,
                'nidn' => $dosenData['nidn'] ?? null,
                'nama' => $dosenData['nama'],
            ]);
        } else {
            $dosen->update([
                'nama' => $dosenData['nama'],
                'nip'  => $dosenData['nip'] ?? $dosen->nip,
                'nidn' => $dosenData['nidn'] ?? $dosen->nidn,
            ]);
        }

        return $dosen;
    }
}