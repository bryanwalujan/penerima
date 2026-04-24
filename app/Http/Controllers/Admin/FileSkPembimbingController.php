<?php
// app/Http/Controllers/Admin/FileSkPembimbingController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Skripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileSkPembimbingController extends Controller
{
    public function index(Request $request)
    {
        $dosens = Dosen::whereHas('skPembimbingSebagaiPembimbing1', function($q) {
                $q->whereNotNull('file_sk_pembimbing');
            })
            ->orWhereHas('skPembimbingSebagaiPembimbing2', function($q) {
                $q->whereNotNull('file_sk_pembimbing');
            })
            ->with(['skPembimbingSebagaiPembimbing1', 'skPembimbingSebagaiPembimbing2'])
            ->get();

        $stats = [
            'total_dosen' => $dosens->count(),
            'total_sk' => Skripsi::whereNotNull('file_sk_pembimbing')->count(),
            'from_presma' => Skripsi::whereNotNull('file_sk_pembimbing')->where('source', 'presma')->count(),
        ];

        return view('admin.file.sk-pembimbing.index', compact('dosens', 'stats'));
    }

    public function show(Dosen $dosen)
    {
        $dosen->load([
            'skPembimbingSebagaiPembimbing1', 
            'skPembimbingSebagaiPembimbing2'
        ]);

        $skripsiList = $dosen->skPembimbingSebagaiPembimbing1->merge($dosen->skPembimbingSebagaiPembimbing2);

        return view('admin.file.sk-pembimbing.show', compact('dosen', 'skripsiList'));
    }

    public function preview(Skripsi $skripsi)
    {
        if (!$skripsi->file_sk_pembimbing) {
            abort(404);
        }

        if (Storage::disk('local')->exists($skripsi->file_sk_pembimbing)) {
            $file = Storage::disk('local')->get($skripsi->file_sk_pembimbing);
            $mimeType = Storage::disk('local')->mimeType($skripsi->file_sk_pembimbing);
        } elseif (Storage::disk('public')->exists($skripsi->file_sk_pembimbing)) {
            $file = Storage::disk('public')->get($skripsi->file_sk_pembimbing);
            $mimeType = Storage::disk('public')->mimeType($skripsi->file_sk_pembimbing);
        } else {
            abort(404);
        }
        
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline');
    }

    public function download(Skripsi $skripsi)
    {
        if (!$skripsi->file_sk_pembimbing) {
            abort(404);
        }

        if (Storage::disk('local')->exists($skripsi->file_sk_pembimbing)) {
            $disk = 'local';
        } elseif (Storage::disk('public')->exists($skripsi->file_sk_pembimbing)) {
            $disk = 'public';
        } else {
            abort(404);
        }

        $fileName = "SK_Pembimbing_{$skripsi->nama_mahasiswa}_{$skripsi->nim}.pdf";
        
        return Storage::disk($disk)->download($skripsi->file_sk_pembimbing, $fileName);
    }
}