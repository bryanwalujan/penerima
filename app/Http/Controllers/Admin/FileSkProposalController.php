<?php
// app/Http/Controllers/Admin/FileSkProposalController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Skripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileSkProposalController extends Controller
{
    public function index(Request $request)
    {
        // Ambil dosen yang memiliki SK Proposal (file_proposal terisi)
        $dosens = Dosen::whereHas('skripsiSebagaiPembimbing1', function($q) {
                $q->whereNotNull('file_proposal');
            })
            ->orWhereHas('skripsiSebagaiPembimbing2', function($q) {
                $q->whereNotNull('file_proposal');
            })
            ->with([
                'skripsiSebagaiPembimbing1' => function($q) {
                    $q->whereNotNull('file_proposal');
                },
                'skripsiSebagaiPembimbing2' => function($q) {
                    $q->whereNotNull('file_proposal');
                }
            ])
            ->get();

        $stats = [
            'total_dosen' => $dosens->count(),
            'total_sk_proposal' => Skripsi::whereNotNull('file_proposal')->count(),
            'from_presma' => Skripsi::whereNotNull('file_proposal')
                ->where('source', 'presma')
                ->count(),
        ];

        return view('admin.file.sk-proposal.index', compact('dosens', 'stats'));
    }

    public function show(Dosen $dosen)
    {
        $dosen->load([
            'skripsiSebagaiPembimbing1' => function($q) {
                $q->whereNotNull('file_proposal');
            },
            'skripsiSebagaiPembimbing2' => function($q) {
                $q->whereNotNull('file_proposal');
            }
        ]);

        $skripsiList = $dosen->skripsiSebagaiPembimbing1->merge($dosen->skripsiSebagaiPembimbing2);

        return view('admin.file.sk-proposal.show', compact('dosen', 'skripsiList'));
    }

    /**
     * Download SK Proposal
     */
    public function download(Skripsi $skripsi)
    {
        if (!$skripsi->file_proposal) {
            abort(404, 'File SK Proposal tidak ditemukan');
        }

        $filePath = $skripsi->file_proposal;
        
        // Cari file di berbagai lokasi
        if (Storage::disk('local')->exists($filePath)) {
            $disk = 'local';
        } elseif (Storage::disk('public')->exists($filePath)) {
            $disk = 'public';
        } else {
            // Cek absolute path
            $fullPath = storage_path('app/private/' . $filePath);
            if (file_exists($fullPath)) {
                $fileName = "SK_Proposal_{$skripsi->nama_mahasiswa}_{$skripsi->nim}.pdf";
                return response()->download($fullPath, $fileName);
            }
            
            // Cek alternative paths
            $alternativePaths = [
                'proposal/' . basename($filePath),
                'proposal/' . $skripsi->folder_name . '/SK_Proposal.pdf',
            ];
            
            foreach ($alternativePaths as $altPath) {
                if (Storage::disk('local')->exists($altPath)) {
                    $fileName = "SK_Proposal_{$skripsi->nama_mahasiswa}_{$skripsi->nim}.pdf";
                    return Storage::disk('local')->download($altPath, $fileName);
                }
            }
            
            abort(404, 'File SK Proposal tidak ditemukan di storage');
        }

        $fileName = "SK_Proposal_{$skripsi->nama_mahasiswa}_{$skripsi->nim}.pdf";
        return Storage::disk($disk)->download($filePath, $fileName);
    }
}