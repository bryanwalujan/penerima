<?php
// app/Services/SkripsiSyncService.php

namespace App\Services;

use App\Models\Dosen;
use App\Models\Skripsi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SkripsiSyncService
{
    /**
     * Entry point — proses satu payload sync dari e-service.
     *
     * Payload yang diharapkan:
     * {
     *   "source": "presma",
     *   "pendaftaran_id": "123",
     *   "mahasiswa": { "nama": "...", "nim": "...", "angkatan": "..." },
     *   "judul_skripsi": "...",
     *   "dosen_list": [
     *     { "nama": "...", "nip": "...", "nidn": "...", "role": "pembimbing_1" },
     *     { "nama": "...", "nip": "...", "nidn": "...", "role": "pembimbing_2" }
     *   ],
     *   "files": {
     *     "skripsi":        "base64...",
     *     "sk_pembimbing":  "base64...",
     *     "proposal":       "base64..."
     *   }
     * }
     */
    public function process(array $payload): array
    {
        $mahasiswa   = $payload['mahasiswa']      ?? [];
        $judulRaw    = $payload['judul_skripsi']  ?? 'Skripsi';
        $dosenList   = $payload['dosen_list']     ?? [];
        $filesBase64 = $payload['files']          ?? [];
        $source      = $payload['source']         ?? 'presma';
        $pendaftaranId = (string) ($payload['pendaftaran_id'] ?? '');

        // --- 1. Resolve dosen ---
        $resolved = $this->resolveDosenList($dosenList);

        // --- 2. Upsert record skripsi ---
        $skripsi = Skripsi::updateOrCreate(
            ['pendaftaran_id' => $pendaftaranId, 'source' => $source],
            [
                'nama_mahasiswa'       => $mahasiswa['nama']     ?? 'Unknown',
                'nim'                  => $mahasiswa['nim']      ?? null,
                'angkatan'             => $mahasiswa['angkatan'] ?? null,
                'judul_skripsi'        => $judulRaw,

                'dosen_pembimbing1_id' => $resolved['pembimbing_1']['dosen_id'],
                'dosen_pembimbing2_id' => $resolved['pembimbing_2']['dosen_id'],

                'raw_nama_pembimbing1' => $resolved['pembimbing_1']['raw_nama'],
                'raw_nip_pembimbing1'  => $resolved['pembimbing_1']['raw_nip'],
                'raw_nama_pembimbing2' => $resolved['pembimbing_2']['raw_nama'],
                'raw_nip_pembimbing2'  => $resolved['pembimbing_2']['raw_nip'],

                'match_status_pb1'     => $resolved['pembimbing_1']['status'],
                'match_status_pb2'     => $resolved['pembimbing_2']['status'],

                'last_synced_at'       => now(),
            ]
        );

        // --- 3. Simpan file ---
        $savedFiles = $this->saveFiles($skripsi, $filesBase64);

        // Update path file ke DB
        $skripsi->update(array_filter([
            'file_skripsi'       => $savedFiles['skripsi']       ?? null,
            'file_sk_pembimbing' => $savedFiles['sk_pembimbing'] ?? null,
            'file_proposal'      => $savedFiles['proposal']      ?? null,
        ]));

        Log::info('[SkripsiSync] Record skripsi disimpan', [
            'skripsi_id'    => $skripsi->id,
            'pendaftaran_id'=> $pendaftaranId,
            'match_pb1'     => $resolved['pembimbing_1']['status'],
            'match_pb2'     => $resolved['pembimbing_2']['status'],
            'files_saved'   => array_keys(array_filter($savedFiles)),
        ]);

        return [
            'success'    => true,
            'skripsi_id' => $skripsi->id,
            'message'    => 'Data skripsi berhasil disimpan.',
            'results'    => [
                'pembimbing_1' => $resolved['pembimbing_1'],
                'pembimbing_2' => $resolved['pembimbing_2'],
                'files'        => array_keys(array_filter($savedFiles)),
            ],
        ];
    }

    /**
     * Resolve dosen dari dosen_list berdasarkan role.
     * Return: array dengan key 'pembimbing_1' dan 'pembimbing_2'.
     */
    private function resolveDosenList(array $dosenList): array
    {
        $result = [
            'pembimbing_1' => ['dosen_id' => null, 'status' => 'unmatched', 'raw_nama' => null, 'raw_nip' => null],
            'pembimbing_2' => ['dosen_id' => null, 'status' => 'unmatched', 'raw_nama' => null, 'raw_nip' => null],
        ];

        foreach ($dosenList as $item) {
            $role = $item['role'] ?? null;
            if (!in_array($role, ['pembimbing_1', 'pembimbing_2'])) continue;

            $nama = $item['nama'] ?? null;
            $nip  = $item['nip']  ?? null;

            $result[$role]['raw_nama'] = $nama;
            $result[$role]['raw_nip']  = $nip;

            $dosen = $this->matchDosen($nama, $nip);

            if ($dosen) {
                $result[$role]['dosen_id'] = $dosen->id;
                $result[$role]['status']   = 'matched';
            }
        }

        return $result;
    }

    /**
     * Matching dosen:
     * 1. Case-insensitive exact match pada kolom `nama`
     * 2. Fallback ke NIP jika nama tidak cocok
     */
    private function matchDosen(?string $nama, ?string $nip): ?Dosen
    {
        // Step 1: match by nama (case-insensitive exact)
        if ($nama) {
            $dosen = Dosen::whereRaw('LOWER(nama) = ?', [strtolower(trim($nama))])->first();
            if ($dosen) {
                Log::debug('[SkripsiSync] Match by nama', ['nama' => $nama, 'dosen_id' => $dosen->id]);
                return $dosen;
            }
        }

        // Step 2: fallback ke NIP
        if ($nip) {
            $dosen = Dosen::whereRaw('LOWER(nip) = ?', [strtolower(trim($nip))])->first();
            if ($dosen) {
                Log::debug('[SkripsiSync] Match by NIP', ['nip' => $nip, 'dosen_id' => $dosen->id]);
                return $dosen;
            }
        }

        Log::warning('[SkripsiSync] Dosen tidak ditemukan', ['nama' => $nama, 'nip' => $nip]);
        return null;
    }

    /**
     * Simpan file base64 ke storage/app/private/{folder}/
     * Return: array path relatif yang tersimpan.
     */
    private function saveFiles(Skripsi $skripsi, array $filesBase64): array
    {
        $saved  = [];
        $folder = 'skripsi/' . Str::slug($skripsi->folder_name);

        $map = [
            'skripsi'       => ['key' => 'skripsi',       'ext' => 'pdf', 'label' => 'Skripsi'],
            'sk_pembimbing' => ['key' => 'sk_pembimbing', 'ext' => 'pdf', 'label' => 'SK_Pembimbing'],
            'proposal'      => ['key' => 'proposal',      'ext' => 'pdf', 'label' => 'Proposal'],
        ];

        foreach ($map as $fileKey => $meta) {
            $base64 = $filesBase64[$fileKey] ?? null;
            if (!$base64) continue;

            try {
                $decoded = base64_decode($base64, strict: true);
                if ($decoded === false) {
                    Log::warning("[SkripsiSync] Base64 decode gagal untuk {$fileKey}");
                    continue;
                }

                // Path: skripsi/{folder}/{Label}.pdf
                $path = "{$folder}/{$meta['label']}.{$meta['ext']}";

                // Simpan ke disk 'local' → storage/app/private/
                Storage::disk('local')->put($path, $decoded);

                $saved[$fileKey] = $path;

                Log::info("[SkripsiSync] File tersimpan: {$path}");

            } catch (\Exception $e) {
                Log::error("[SkripsiSync] Gagal simpan file {$fileKey}: " . $e->getMessage());
            }
        }

        return $saved;
    }
}