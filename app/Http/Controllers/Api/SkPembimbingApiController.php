<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SkPembimbing;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SkPembimbingApiController extends Controller
{
    /**
     * Menerima data SK Pembimbing dari e-service
     */
    public function receive(Request $request)
    {
        // ✅ Validasi DULU sebelum melakukan apapun
        $validated = $request->validate([
            'pengajuan_sk_pembimbing_id' => 'nullable|integer',
            'mahasiswa_id'               => 'required|integer',
            'judul_skripsi'              => 'required|string',
            'file_surat_sk'              => 'required|file|mimes:pdf|max:10240',
            'dosen_pembimbing_1'         => 'required|array',
            'dosen_pembimbing_1.nama'    => 'required|string',
            'dosen_pembimbing_1.nip'     => 'nullable|string',
            'dosen_pembimbing_2'         => 'nullable|array',
            'dosen_pembimbing_2.nama'    => 'required_with:dosen_pembimbing_2|string',
            'dosen_pembimbing_2.nip'     => 'nullable|string',
        ]);

        try {
            // ✅ Simpan file ke storage/app/private/sk-pembimbing/
            //    gunakan disk 'local' agar masuk ke storage/app/private
            $file     = $request->file('file_surat_sk');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path     = $file->storeAs('sk-pembimbing', $filename, 'local');
            // path hasil: storage/app/private/sk-pembimbing/filename.pdf

            // Sinkronisasi Dosen Pembimbing 1
            $dosen1 = $this->syncDosen($validated['dosen_pembimbing_1']);

            // Sinkronisasi Dosen Pembimbing 2 (opsional)
            $dosen2 = null;
            if (!empty($validated['dosen_pembimbing_2'])) {
                $dosen2 = $this->syncDosen($validated['dosen_pembimbing_2']);
            }

            // ✅ Tentukan kondisi updateOrCreate dengan aman
            $pengajuanId = $validated['pengajuan_sk_pembimbing_id'] ?? null;

            // Jika ada pengajuan_id, update berdasarkan itu
            // Jika tidak ada, selalu buat baru (hindari update record random)
            if ($pengajuanId) {
                $sk = SkPembimbing::updateOrCreate(
                    ['pengajuan_sk_pembimbing_id' => $pengajuanId],
                    [
                        'mahasiswa_id'          => $validated['mahasiswa_id'],
                        'dosen_pembimbing_1_id' => $dosen1->id,
                        'dosen_pembimbing_2_id' => $dosen2?->id,
                        'judul_skripsi'         => $validated['judul_skripsi'],
                        'file_surat_sk'         => $path, // ✅ simpan path string, bukan objek file
                        'status'                => 'draft',
                    ]
                );
            } else {
                $sk = SkPembimbing::create([
                    'pengajuan_sk_pembimbing_id' => null,
                    'mahasiswa_id'               => $validated['mahasiswa_id'],
                    'dosen_pembimbing_1_id'      => $dosen1->id,
                    'dosen_pembimbing_2_id'      => $dosen2?->id,
                    'judul_skripsi'              => $validated['judul_skripsi'],
                    'file_surat_sk'              => $path, // ✅ simpan path string
                    'status'                     => 'draft',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data SK Pembimbing berhasil diterima di Repodosen',
                'sk_id'   => $sk->id,
            ]);

        } catch (\Exception $e) {
            // ✅ Hapus file yang sudah terlanjur tersimpan jika proses DB gagal
            if (isset($path) && Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sinkronisasi Dosen berdasarkan NIP atau Nama
     */
    private function syncDosen(array $dosenData): Dosen
    {
        // ✅ Hindari orWhere tanpa kondisi NIP — bisa match dosen yang salah
        $query = Dosen::query();

        if (!empty($dosenData['nip'])) {
            $query->where('nip', $dosenData['nip']);
        } else {
            $query->where('nama', $dosenData['nama']);
        }

        $dosen = $query->first();

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