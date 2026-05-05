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
     */
    public function process(array $payload): array
    {
        $mahasiswa   = $payload['mahasiswa']      ?? [];
        $judulRaw    = $payload['judul_skripsi']  ?? 'Skripsi';
        $dosenList   = $payload['dosen_list']     ?? [];
        $filesBase64 = $payload['files']          ?? [];
        $source      = $payload['source']         ?? 'presma';
        $pendaftaranId = (string) ($payload['pendaftaran_id'] ?? '');
        $folderNameFromPayload = $payload['folder_name'] ?? null;
        $type = $payload['type'] ?? 'skripsi';
        $nomorSkProposal = $payload['nomor_sk_proposal'] ?? null;
        $nomorSkUjianHasil = $payload['nomor_sk_ujian_hasil'] ?? null; // ✅ TAMBAHKAN

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

        // Jika folder_name dikirim dari payload, gunakan itu
        if ($folderNameFromPayload) {
            $skripsi->folder_name = $folderNameFromPayload;
            $skripsi->save();
        }

        // --- 3. Simpan file ke folder terpisah ---
        $savedFiles = $this->saveFilesToSeparateFolders($skripsi, $filesBase64, $type);

        // Update path file ke DB berdasarkan tipe
        $updateData = [];
        
        if ($type === 'sk_proposal') {
            $updateData['file_proposal'] = $savedFiles['sk_proposal'] ?? null;
            if ($nomorSkProposal) {
                $skripsi->raw_nama_pembimbing1 = $nomorSkProposal . ' | ' . ($skripsi->raw_nama_pembimbing1 ?? '');
                $skripsi->save();
            }
        } 
        // ✅ TAMBAHKAN INI
        elseif ($type === 'sk_ujian_hasil') {
            $updateData['file_skripsi'] = $savedFiles['skripsi'] ?? null;
            if ($nomorSkUjianHasil) {
                $skripsi->raw_nama_pembimbing1 = $nomorSkUjianHasil . ' | ' . ($skripsi->raw_nama_pembimbing1 ?? '');
                $skripsi->save();
            }
        }
        else {
            $updateData = array_filter([
                'file_skripsi'       => $savedFiles['skripsi']       ?? null,
                'file_sk_pembimbing' => $savedFiles['sk_pembimbing'] ?? null,
                'file_proposal'      => $savedFiles['proposal']      ?? null,
            ]);
        }
        
        if (!empty($updateData)) {
            $skripsi->update($updateData);
        }

        Log::info('[SkripsiSync] Record skripsi disimpan', [
            'skripsi_id'    => $skripsi->id,
            'pendaftaran_id'=> $pendaftaranId,
            'type'          => $type,
            'folder_name'   => $skripsi->folder_name,
            'match_pb1'     => $resolved['pembimbing_1']['status'],
            'match_pb2'     => $resolved['pembimbing_2']['status'],
            'files_saved'   => array_keys(array_filter($savedFiles)),
            'nomor_sk_proposal' => $nomorSkProposal,
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
     * Simpan file ke folder yang terpisah
     */
    private function saveFilesToSeparateFolders(Skripsi $skripsi, array $filesBase64, string $type = 'skripsi'): array
    {
        $saved = [];
        $slug = Str::slug($skripsi->folder_name);
        
        $map = [];
        
        if ($type === 'sk_proposal') {
            $map = [
                'sk_proposal' => [
                    'folder' => 'proposal',
                    'filename' => 'SK_Proposal.pdf',
                    'label' => 'SK Proposal',
                    'db_field' => 'file_proposal'
                ],
            ];
        } 
        // ✅ TAMBAHKAN INI
        elseif ($type === 'sk_ujian_hasil') {
            $map = [
                'skripsi' => [
                    'folder' => 'skripsi',
                    'filename' => 'Skripsi.pdf',
                    'label' => 'SK Ujian Hasil',
                    'db_field' => 'file_skripsi'
                ],
            ];
        }
        else {
            $map = [
                'skripsi' => [
                    'folder' => 'skripsi',
                    'filename' => 'Skripsi.pdf',
                    'label' => 'Skripsi',
                    'db_field' => 'file_skripsi'
                ],
                'sk_pembimbing' => [
                    'folder' => 'sk_pembimbing',
                    'filename' => 'SK_Pembimbing.pdf',
                    'label' => 'SK_Pembimbing',
                    'db_field' => 'file_sk_pembimbing'
                ],
                'proposal' => [
                    'folder' => 'proposal',
                    'filename' => 'Proposal.pdf',
                    'label' => 'Proposal',
                    'db_field' => 'file_proposal'
                ],
            ];
        }

        foreach ($map as $fileKey => $config) {
            $fileData = $filesBase64[$fileKey] ?? null;
            
            if (!$fileData) {
                Log::info("[SkripsiSync] File {$fileKey} tidak ada dalam payload");
                continue;
            }

            $base64 = null;
            
            if (is_string($fileData)) {
                $base64 = $fileData;
                Log::info("[SkripsiSync] Format string untuk {$fileKey}");
            } elseif (is_array($fileData) && isset($fileData['content'])) {
                $base64 = $fileData['content'];
                Log::info("[SkripsiSync] Format array untuk {$fileKey}", [
                    'folder' => $fileData['folder'] ?? $config['folder'],
                    'filename' => $fileData['filename'] ?? $config['filename']
                ]);
            } else {
                Log::warning("[SkripsiSync] Format tidak dikenal untuk {$fileKey}");
                continue;
            }

            if (!$base64) {
                Log::warning("[SkripsiSync] Base64 kosong untuk {$fileKey}");
                continue;
            }

            try {
                $decoded = base64_decode($base64, strict: true);
                if ($decoded === false) {
                    Log::warning("[SkripsiSync] Base64 decode gagal untuk {$fileKey}");
                    continue;
                }

                $fullFolderPath = storage_path("app/private/{$config['folder']}/{$slug}");
                if (!file_exists($fullFolderPath)) {
                    mkdir($fullFolderPath, 0755, true);
                }

                $path = "{$config['folder']}/{$slug}/{$config['filename']}";
                Storage::disk('local')->put($path, $decoded);
                $saved[$fileKey] = $path;

                Log::info("[SkripsiSync] File tersimpan: {$path} (Size: " . strlen($decoded) . " bytes)");

            } catch (\Exception $e) {
                Log::error("[SkripsiSync] Gagal simpan file {$fileKey}: " . $e->getMessage());
            }
        }

        return $saved;
    }

    /**
     * Resolve dosen dari dosen_list berdasarkan role.
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
     * Matching dosen.
     */
    private function matchDosen(?string $nama, ?string $nip): ?Dosen
    {
        if ($nama) {
            $dosen = Dosen::whereRaw('LOWER(nama) = ?', [strtolower(trim($nama))])->first();
            if ($dosen) {
                Log::debug('[SkripsiSync] Match by nama', ['nama' => $nama, 'dosen_id' => $dosen->id]);
                return $dosen;
            }
        }

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
}