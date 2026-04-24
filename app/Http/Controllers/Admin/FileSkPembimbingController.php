<?php
// app/Http/Controllers/Admin/FileSkPembimbingController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileSkPembimbingController extends Controller
{
    public function index(Request $request)
    {
        $query = Skripsi::with(['dosenPembimbing1', 'dosenPembimbing2'])
            ->whereNotNull('file_sk_pembimbing')
            ->latest('created_at');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_mahasiswa', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $skripsiList = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Skripsi::whereNotNull('file_sk_pembimbing')->count(),
            'from_presma' => Skripsi::whereNotNull('file_sk_pembimbing')->where('source', 'presma')->count(),
        ];

        return view('admin.file.sk-pembimbing.index', compact('skripsiList', 'stats'));
    }

    public function show(Skripsi $skripsi)
    {
        if (!$skripsi->file_sk_pembimbing) {
            abort(404, 'File SK Pembimbing tidak ditemukan');
        }

        $skripsi->load(['dosenPembimbing1', 'dosenPembimbing2']);
        
        $fileExists = Storage::disk('local')->exists($skripsi->file_sk_pembimbing) || 
                      Storage::disk('public')->exists($skripsi->file_sk_pembimbing);

        return view('admin.file.sk-pembimbing.show', compact('skripsi', 'fileExists'));
    }

    public function preview(Skripsi $skripsi)
    {
        if (!$skripsi->file_sk_pembimbing) {
            abort(404);
        }

        $disk = Storage::disk('local')->exists($skripsi->file_sk_pembimbing) ? 'local' : 'public';
        
        if (!Storage::disk($disk)->exists($skripsi->file_sk_pembimbing)) {
            abort(404);
        }

        $file = Storage::disk($disk)->get($skripsi->file_sk_pembimbing);
        $mimeType = Storage::disk($disk)->mimeType($skripsi->file_sk_pembimbing);
        
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline');
    }

    public function download(Skripsi $skripsi)
    {
        if (!$skripsi->file_sk_pembimbing) {
            abort(404);
        }

        $disk = Storage::disk('local')->exists($skripsi->file_sk_pembimbing) ? 'local' : 'public';
        
        if (!Storage::disk($disk)->exists($skripsi->file_sk_pembimbing)) {
            abort(404);
        }

        $fileName = "SK_Pembimbing_{$skripsi->nama_mahasiswa}_{$skripsi->nim}.pdf";
        
        return Storage::disk($disk)->download($skripsi->file_sk_pembimbing, $fileName);
    }
}