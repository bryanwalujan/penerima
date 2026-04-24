<?php
// app/Http/Controllers/Admin/FileSkripsiController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileSkripsiController extends Controller
{
    /**
     * Menampilkan daftar skripsi yang memiliki file skripsi
     */
    public function index(Request $request)
    {
        $query = Skripsi::with(['dosenPembimbing1', 'dosenPembimbing2'])
            ->whereNotNull('file_skripsi')
            ->latest('created_at');

        // Filter pencarian
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_mahasiswa', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('judul_skripsi', 'like', "%{$search}%");
            });
        }

        $skripsiList = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Skripsi::whereNotNull('file_skripsi')->count(),
            'from_presma' => Skripsi::whereNotNull('file_skripsi')->where('source', 'presma')->count(),
            'synced_today' => Skripsi::whereNotNull('file_skripsi')->whereDate('last_synced_at', today())->count(),
        ];

        return view('admin.file.skripsi.index', compact('skripsiList', 'stats'));
    }

    /**
     * Detail file skripsi
     */
    public function show(Skripsi $skripsi)
    {
        if (!$skripsi->file_skripsi) {
            abort(404, 'File skripsi tidak ditemukan');
        }

        $skripsi->load(['dosenPembimbing1', 'dosenPembimbing2']);
        
        $fileExists = Storage::disk('local')->exists($skripsi->file_skripsi) || 
                      Storage::disk('public')->exists($skripsi->file_skripsi);

        return view('admin.file.skripsi.show', compact('skripsi', 'fileExists'));
    }

    /**
     * Preview file skripsi
     */
    public function preview(Skripsi $skripsi)
    {
        if (!$skripsi->file_skripsi) {
            abort(404);
        }

        $disk = Storage::disk('local')->exists($skripsi->file_skripsi) ? 'local' : 'public';
        
        if (!Storage::disk($disk)->exists($skripsi->file_skripsi)) {
            abort(404);
        }

        $file = Storage::disk($disk)->get($skripsi->file_skripsi);
        $mimeType = Storage::disk($disk)->mimeType($skripsi->file_skripsi);
        
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline');
    }

    /**
     * Download file skripsi
     */
    public function download(Skripsi $skripsi)
    {
        if (!$skripsi->file_skripsi) {
            abort(404);
        }

        $disk = Storage::disk('local')->exists($skripsi->file_skripsi) ? 'local' : 'public';
        
        if (!Storage::disk($disk)->exists($skripsi->file_skripsi)) {
            abort(404);
        }

        $fileName = "Skripsi_{$skripsi->nama_mahasiswa}_{$skripsi->nim}.pdf";
        
        return Storage::disk($disk)->download($skripsi->file_skripsi, $fileName);
    }
}