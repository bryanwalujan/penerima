<?php
// app/Http/Controllers/Admin/FileProposalController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Skripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileProposalController extends Controller
{
    public function index(Request $request)
    {
        $dosens = Dosen::whereHas('proposalSebagaiPembimbing1', function($q) {
                $q->whereNotNull('file_proposal');
            })
            ->orWhereHas('proposalSebagaiPembimbing2', function($q) {
                $q->whereNotNull('file_proposal');
            })
            ->with(['proposalSebagaiPembimbing1', 'proposalSebagaiPembimbing2'])
            ->get();

        $stats = [
            'total_dosen' => $dosens->count(),
            'total_proposal' => Skripsi::whereNotNull('file_proposal')->count(),
            'from_presma' => Skripsi::whereNotNull('file_proposal')->where('source', 'presma')->count(),
        ];

        return view('admin.file.proposal.index', compact('dosens', 'stats'));
    }

    public function show(Dosen $dosen)
    {
        $dosen->load([
            'proposalSebagaiPembimbing1', 
            'proposalSebagaiPembimbing2'
        ]);

        $skripsiList = $dosen->proposalSebagaiPembimbing1->merge($dosen->proposalSebagaiPembimbing2);

        return view('admin.file.proposal.show', compact('dosen', 'skripsiList'));
    }

    public function preview(Skripsi $skripsi)
    {
        if (!$skripsi->file_proposal) {
            abort(404);
        }

        if (Storage::disk('local')->exists($skripsi->file_proposal)) {
            $file = Storage::disk('local')->get($skripsi->file_proposal);
            $mimeType = Storage::disk('local')->mimeType($skripsi->file_proposal);
        } elseif (Storage::disk('public')->exists($skripsi->file_proposal)) {
            $file = Storage::disk('public')->get($skripsi->file_proposal);
            $mimeType = Storage::disk('public')->mimeType($skripsi->file_proposal);
        } else {
            abort(404);
        }
        
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline');
    }

    public function download(Skripsi $skripsi)
    {
        if (!$skripsi->file_proposal) {
            abort(404);
        }

        if (Storage::disk('local')->exists($skripsi->file_proposal)) {
            $disk = 'local';
        } elseif (Storage::disk('public')->exists($skripsi->file_proposal)) {
            $disk = 'public';
        } else {
            abort(404);
        }

        $fileName = "Proposal_{$skripsi->nama_mahasiswa}_{$skripsi->nim}.pdf";
        
        return Storage::disk($disk)->download($skripsi->file_proposal, $fileName);
    }
}