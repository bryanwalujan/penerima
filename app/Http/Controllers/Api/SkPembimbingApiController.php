<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SkPembimbing;
use App\Models\Dosen;
use Illuminate\Http\Request;

class SkPembimbingApiController extends Controller
{
    /**
     * Menerima data SK Pembimbing dari e-service
     * Hanya menerima file_surat_sk sebagai file penting
     */
    public function receive(Request $request)
    {
        $request->validate([
            'pengajuan_sk_pembimbing_id' => 'nullable|integer',
            'mahasiswa_id'               => 'required|integer',
            'judul_skripsi'              => 'required|string',
            'file_surat_sk'              => 'required|string',        // Hanya ini yang wajib dikirim

            'dosen_pembimbing_1' => 'required|array',
            'dosen_pembimbing_2' => 'nullable|array',
        ]);

        try {
            // Sinkronisasi Dosen Pembimbing 1
            $dosen1 = $this->syncDosen($request->dosen_pembimbing_1);

            // Sinkronisasi Dosen Pembimbing 2 (opsional)
            $dosen2 = null;
            if ($request->filled('dosen_pembimbing_2')) {
                $dosen2 = $this->syncDosen($request->dosen_pembimbing_2);
            }

            // Simpan / Update SK Pembimbing
            $sk = SkPembimbing::updateOrCreate(
                ['pengajuan_sk_pembimbing_id' => $request->pengajuan_sk_pembimbing_id],
                [
                    'mahasiswa_id'          => $request->mahasiswa_id,
                    'dosen_pembimbing_1_id' => $dosen1->id,
                    'dosen_pembimbing_2_id' => $dosen2?->id,
                    'judul_skripsi'         => $request->judul_skripsi,
                    'file_surat_sk'         => $request->file_surat_sk,     // Hanya ini yang disimpan
                    'status'                => 'draft',
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Data SK Pembimbing berhasil diterima di Repodosen',
                'sk_id'   => $sk->id,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sinkronisasi Dosen berdasarkan NIP atau Nama
     */
    private function syncDosen(array $dosenData)
    {
        $dosen = Dosen::where('nip', $dosenData['nip'] ?? null)
                      ->orWhere('nama', $dosenData['nama'])
                      ->first();

        if (!$dosen) {
            $dosen = Dosen::create([
                'nip'  => $dosenData['nip'] ?? null,
                'nama' => $dosenData['nama'],
                'nidn' => $dosenData['nidn'] ?? null,
            ]);
        } else {
            $dosen->update([
                'nama' => $dosenData['nama'],
                'nip'  => $dosenData['nip'] ?? $dosen->nip,
            ]);
        }

        return $dosen;
    }
}