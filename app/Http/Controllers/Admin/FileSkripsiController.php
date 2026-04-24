<?php
// app/Http/Controllers/Admin/FileSkripsiController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Skripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileSkripsiController extends Controller
{
    /**
     * Menampilkan daftar skripsi per dosen pembimbing
     */
    public function index(Request $request)
    {
        // Ambil dosen yang memiliki file skripsi (baik sebagai pembimbing 1 atau 2)
        $dosens = Dosen::whereHas('skripsiSebagaiPembimbing1', function($q) {
                $q->whereNotNull('file_skripsi');
            })
            ->orWhereHas('skripsiSebagaiPembimbing2', function($q) {
                $q->whereNotNull('file_skripsi');
            })
            ->with(['skripsiSebagaiPembimbing1' => function($q) {
                $q->whereNotNull('file_skripsi');
            }, 'skripsiSebagaiPembimbing2' => function($q) {
                $q->whereNotNull('file_skripsi');
            }])
            ->get();

        $stats = [
            'total_dosen' => $dosens->count(),
            'total_skripsi' => Skripsi::whereNotNull('file_skripsi')->count(),
            'from_presma' => Skripsi::whereNotNull('file_skripsi')->where('source', 'presma')->count(),
        ];

        return view('admin.file.skripsi.index', compact('dosens', 'stats'));
    }

    /**
     * Detail skripsi per dosen
     */
    public function show(Dosen $dosen)
    {
        $dosen->load(['skripsiSebagaiPembimbing1' => function($q) {
            $q->whereNotNull('file_skripsi');
        }, 'skripsiSebagaiPembimbing2' => function($q) {
            $q->whereNotNull('file_skripsi');
        }]);

        $skripsiList = $dosen->skripsiSebagaiPembimbing1->merge($dosen->skripsiSebagaiPembimbing2);

        return view('admin.file.skripsi.show', compact('dosen', 'skripsiList'));
    }

    /**
     * Preview file skripsi
     */
    public function preview(Skripsi $skripsi)
    {
        if (!$skripsi->file_skripsi) {
            abort(404);
        }

        // Cek di disk local dan public
        if (Storage::disk('local')->exists($skripsi->file_skripsi)) {
            $file = Storage::disk('local')->get($skripsi->file_skripsi);
            $mimeType = Storage::disk('local')->mimeType($skripsi->file_skripsi);
        } elseif (Storage::disk('public')->exists($skripsi->file_skripsi)) {
            $file = Storage::disk('public')->get($skripsi->file_skripsi);
            $mimeType = Storage::disk('public')->mimeType($skripsi->file_skripsi);
        } else {
            abort(404);
        }
        
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

        // Cek di disk local dan public
        if (Storage::disk('local')->exists($skripsi->file_skripsi)) {
            $disk = 'local';
        } elseif (Storage::disk('public')->exists($skripsi->file_skripsi)) {
            $disk = 'public';
        } else {
            abort(404);
        }

        $fileName = "Skripsi_{$skripsi->nama_mahasiswa}_{$skripsi->nim}.pdf";
        
        return Storage::disk($disk)->download($skripsi->file_skripsi, $fileName);
    }
}