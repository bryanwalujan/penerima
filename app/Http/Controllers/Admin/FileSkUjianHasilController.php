<?php
// app/Http/Controllers/Admin/FileSkUjianHasilController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Skripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileSkUjianHasilController extends Controller
{
    public function index(Request $request)
    {
        // Ambil dosen yang memiliki SK Ujian Hasil (file_skripsi terisi)
        // SK Ujian Hasil ditandai dengan adanya file_skripsi dan raw_nama_pembimbing1 mengandung angka/bukan SK_ dari proposal
        $dosens = Dosen::whereHas('skripsiSebagaiPembimbing1', function($q) {
                $q->whereNotNull('file_skripsi')
                  ->where('file_skripsi', 'like', 'skripsi/%');  // File skripsi ada di folder skripsi
            })
            ->orWhereHas('skripsiSebagaiPembimbing2', function($q) {
                $q->whereNotNull('file_skripsi')
                  ->where('file_skripsi', 'like', 'skripsi/%');
            })
            ->with([
                'skripsiSebagaiPembimbing1' => function($q) {
                    $q->whereNotNull('file_skripsi')
                      ->where('file_skripsi', 'like', 'skripsi/%');
                },
                'skripsiSebagaiPembimbing2' => function($q) {
                    $q->whereNotNull('file_skripsi')
                      ->where('file_skripsi', 'like', 'skripsi/%');
                }
            ])
            ->get();

        $stats = [
            'total_dosen' => $dosens->count(),
            'total_sk_ujian_hasil' => Skripsi::whereNotNull('file_skripsi')
                ->where('file_skripsi', 'like', 'skripsi/%')
                ->count(),
            'from_presma' => Skripsi::whereNotNull('file_skripsi')
                ->where('source', 'presma')
                ->where('file_skripsi', 'like', 'skripsi/%')
                ->count(),
        ];

        return view('admin.file.sk-ujian-hasil.index', compact('dosens', 'stats'));
    }

    public function show(Dosen $dosen)
    {
        $dosen->load([
            'skripsiSebagaiPembimbing1' => function($q) {
                $q->whereNotNull('file_skripsi')
                  ->where('file_skripsi', 'like', 'skripsi/%');
            },
            'skripsiSebagaiPembimbing2' => function($q) {
                $q->whereNotNull('file_skripsi')
                  ->where('file_skripsi', 'like', 'skripsi/%');
            }
        ]);

        $skripsiList = $dosen->skripsiSebagaiPembimbing1->merge($dosen->skripsiSebagaiPembimbing2);

        return view('admin.file.sk-ujian-hasil.show', compact('dosen', 'skripsiList'));
    }

    public function preview(Skripsi $skripsi)
    {
        if (!$skripsi->file_skripsi) {
            abort(404, 'File SK Ujian Hasil tidak ditemukan');
        }

        if (Storage::disk('local')->exists($skripsi->file_skripsi)) {
            $file = Storage::disk('local')->get($skripsi->file_skripsi);
            $mimeType = Storage::disk('local')->mimeType($skripsi->file_skripsi);
        } elseif (Storage::disk('public')->exists($skripsi->file_skripsi)) {
            $file = Storage::disk('public')->get($skripsi->file_skripsi);
            $mimeType = Storage::disk('public')->mimeType($skripsi->file_skripsi);
        } else {
            abort(404, 'File tidak ditemukan di storage');
        }
        
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline');
    }

    public function download(Skripsi $skripsi)
    {
        if (!$skripsi->file_skripsi) {
            abort(404, 'File SK Ujian Hasil tidak ditemukan');
        }

        if (Storage::disk('local')->exists($skripsi->file_skripsi)) {
            $disk = 'local';
        } elseif (Storage::disk('public')->exists($skripsi->file_skripsi)) {
            $disk = 'public';
        } else {
            abort(404, 'File tidak ditemukan di storage');
        }

        $fileName = "SK_UjianHasil_{$skripsi->nama_mahasiswa}_{$skripsi->nim}.pdf";
        return Storage::disk($disk)->download($skripsi->file_skripsi, $fileName);
    }
}