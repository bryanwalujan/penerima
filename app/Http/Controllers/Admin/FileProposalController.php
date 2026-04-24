<?php
// app/Http/Controllers/Admin/FileProposalController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileProposalController extends Controller
{
    public function index(Request $request)
    {
        $query = Skripsi::with(['dosenPembimbing1', 'dosenPembimbing2'])
            ->whereNotNull('file_proposal')
            ->latest('created_at');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_mahasiswa', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $skripsiList = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Skripsi::whereNotNull('file_proposal')->count(),
            'from_presma' => Skripsi::whereNotNull('file_proposal')->where('source', 'presma')->count(),
        ];

        return view('admin.file.proposal.index', compact('skripsiList', 'stats'));
    }

    public function show(Skripsi $skripsi)
    {
        if (!$skripsi->file_proposal) {
            abort(404, 'File Proposal tidak ditemukan');
        }

        $skripsi->load(['dosenPembimbing1', 'dosenPembimbing2']);
        
        $fileExists = Storage::disk('local')->exists($skripsi->file_proposal) || 
                      Storage::disk('public')->exists($skripsi->file_proposal);

        return view('admin.file.proposal.show', compact('skripsi', 'fileExists'));
    }

    public function preview(Skripsi $skripsi)
    {
        if (!$skripsi->file_proposal) {
            abort(404);
        }

        $disk = Storage::disk('local')->exists($skripsi->file_proposal) ? 'local' : 'public';
        
        if (!Storage::disk($disk)->exists($skripsi->file_proposal)) {
            abort(404);
        }

        $file = Storage::disk($disk)->get($skripsi->file_proposal);
        $mimeType = Storage::disk($disk)->mimeType($skripsi->file_proposal);
        
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline');
    }

    public function download(Skripsi $skripsi)
    {
        if (!$skripsi->file_proposal) {
            abort(404);
        }

        $disk = Storage::disk('local')->exists($skripsi->file_proposal) ? 'local' : 'public';
        
        if (!Storage::disk($disk)->exists($skripsi->file_proposal)) {
            abort(404);
        }

        $fileName = "Proposal_{$skripsi->nama_mahasiswa}_{$skripsi->nim}.pdf";
        
        return Storage::disk($disk)->download($skripsi->file_proposal, $fileName);
    }
}