<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SkPembimbing;
use App\Models\Dosen;
use Illuminate\Http\Request;

class SkPembimbingApiController extends Controller
{
    /**
     * API untuk menerima data SK Pembimbing dari e-service
     */
    public function receive(Request $request)
    {
        $request->validate([
            'pengajuan_sk_pembimbing_id' => 'nullable|integer',
            'mahasiswa_id'               => 'required|integer',
            'judul_skripsi'              => 'required|string',
            'file_surat_permohonan'      => 'required|string',
            'file_slip_ukt'              => 'required|string',
            'file_proposal_revisi'       => 'required|string',
            'file_surat_sk'              => 'nullable|string',

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

            // Simpan / Update data SK Pembimbing
            $sk = SkPembimbing::updateOrCreate(
                ['pengajuan_sk_pembimbing_id' => $request->pengajuan_sk_pembimbing_id],
                [
                    'mahasiswa_id'           => $request->mahasiswa_id,
                    'dosen_pembimbing_1_id'  => $dosen1->id,
                    'dosen_pembimbing_2_id'  => $dosen2?->id ?? null,
                    'judul_skripsi'          => $request->judul_skripsi,
                    'file_surat_permohonan'  => $request->file_surat_permohonan,
                    'file_slip_ukt'          => $request->file_slip_ukt,
                    'file_proposal_revisi'   => $request->file_proposal_revisi,
                    'file_surat_sk'          => $request->file_surat_sk,
                    'status'                 => 'draft',
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Data SK Pembimbing berhasil diterima di Repodosen',
                'sk_id'   => $sk->id
            ], 200);

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
    private function syncDosen(array $data)
    {
        $dosen = Dosen::where('nip', $data['nip'] ?? null)
                      ->orWhere('nama', $data['nama'])
                      ->first();

        if (!$dosen) {
            $dosen = Dosen::create([
                'nip'  => $data['nip'] ?? null,
                'nama' => $data['nama'],
                'nidn' => $data['nidn'] ?? null,
            ]);
        } else {
            $dosen->update([
                'nama' => $data['nama'],
                'nip'  => $data['nip'] ?? $dosen->nip,
            ]);
        }

        return $dosen;
    }
}