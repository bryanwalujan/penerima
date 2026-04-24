<?php
// app/Http/Controllers/Admin/SkripsiController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SkripsiController extends Controller
{
    /**
     * Menampilkan daftar semua skripsi
     */
    public function index(Request $request)
    {
        $query = Skripsi::with(['dosenPembimbing1', 'dosenPembimbing2'])
            ->latest('created_at');

        // Filter pencarian
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_mahasiswa', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('judul_skripsi', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan source
        if ($source = $request->query('source')) {
            $query->where('source', $source);
        }

        $skripsiList = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Skripsi::count(),
            'from_presma' => Skripsi::where('source', 'presma')->count(),
            'with_files' => Skripsi::whereNotNull('file_skripsi')->count(),
            'synced_today' => Skripsi::whereDate('last_synced_at', today())->count(),
        ];

        return view('admin.skripsi.index', compact('skripsiList', 'stats'));
    }

    /**
     * Menampilkan detail skripsi beserta file-file
     */
    public function show(Skripsi $skripsi)
    {
        $skripsi->load(['dosenPembimbing1', 'dosenPembimbing2']);
        
        // Cek keberadaan file
        $files = [
            'skripsi' => $skripsi->file_skripsi ? Storage::disk('public')->exists($skripsi->file_skripsi) : false,
            'sk_pembimbing' => $skripsi->file_sk_pembimbing ? Storage::disk('public')->exists($skripsi->file_sk_pembimbing) : false,
            'proposal' => $skripsi->file_proposal ? Storage::disk('public')->exists($skripsi->file_proposal) : false,
        ];

        return view('admin.skripsi.show', compact('skripsi', 'files'));
    }

    /**
     * Download file skripsi
     */
    public function downloadFile(Skripsi $skripsi, $fileType)
    {
        $fileField = null;
        $fileName = null;
        
        switch ($fileType) {
            case 'skripsi':
                $fileField = $skripsi->file_skripsi;
                $fileName = "Skripsi_{$skripsi->nama_mahasiswa}.pdf";
                break;
            case 'sk_pembimbing':
                $fileField = $skripsi->file_sk_pembimbing;
                $fileName = "SK_Pembimbing_{$skripsi->nama_mahasiswa}.pdf";
                break;
            case 'proposal':
                $fileField = $skripsi->file_proposal;
                $fileName = "Proposal_{$skripsi->nama_mahasiswa}.pdf";
                break;
            default:
                abort(404, 'Tipe file tidak ditemukan');
        }

        if (!$fileField) {
            abort(404, 'File tidak ditemukan di database');
        }

        // Coba cek di disk public dulu, lalu local
        $disk = 'public';
        if (!Storage::disk($disk)->exists($fileField)) {
            $disk = 'local';
            if (!Storage::disk($disk)->exists($fileField)) {
                abort(404, 'File tidak ditemukan di storage');
            }
        }

        return Storage::disk($disk)->download($fileField, $fileName);
    }

    /**
     * Preview file (untuk menampilkan PDF di browser)
     */
    public function previewFile(Skripsi $skripsi, $fileType)
    {
        $fileField = null;
        
        switch ($fileType) {
            case 'skripsi':
                $fileField = $skripsi->file_skripsi;
                break;
            case 'sk_pembimbing':
                $fileField = $skripsi->file_sk_pembimbing;
                break;
            case 'proposal':
                $fileField = $skripsi->file_proposal;
                break;
            default:
                abort(404, 'Tipe file tidak valid');
        }

        if (!$fileField) {
            abort(404, 'File tidak ditemukan di database');
        }

        // Coba cek di disk public dulu, lalu local
        $disk = 'public';
        if (!Storage::disk($disk)->exists($fileField)) {
            $disk = 'local';
            if (!Storage::disk($disk)->exists($fileField)) {
                abort(404, 'File tidak ditemukan di storage: ' . $fileField);
            }
        }

        $file = Storage::disk($disk)->get($fileField);
        $mimeType = Storage::disk($disk)->mimeType($fileField);
        
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . basename($fileField) . '"');
    }

    /**
     * Hapus file tertentu
     */
    public function deleteFile(Skripsi $skripsi, $fileType)
    {
        $fileField = null;
        
        switch ($fileType) {
            case 'skripsi':
                $fileField = 'file_skripsi';
                break;
            case 'sk_pembimbing':
                $fileField = 'file_sk_pembimbing';
                break;
            case 'proposal':
                $fileField = 'file_proposal';
                break;
            default:
                return response()->json(['error' => 'Tipe file tidak valid'], 400);
        }

        if ($skripsi->$fileField) {
            // Hapus dari kedua disk
            if (Storage::disk('public')->exists($skripsi->$fileField)) {
                Storage::disk('public')->delete($skripsi->$fileField);
            }
            if (Storage::disk('local')->exists($skripsi->$fileField)) {
                Storage::disk('local')->delete($skripsi->$fileField);
            }
            
            $skripsi->update([$fileField => null]);
            
            return response()->json(['message' => 'File berhasil dihapus']);
        }

        return response()->json(['error' => 'File tidak ditemukan'], 404);
    }
}