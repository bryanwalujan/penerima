<?php
// app/Http/Controllers/Admin/FileSkProposalController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Skripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileSkProposalController extends Controller
{
    public function index(Request $request)
    {
        // Ambil dosen yang memiliki SK Proposal (file_proposal terisi dan memiliki metadata sk_proposal)
        $dosens = Dosen::whereHas('skripsiSebagaiPembimbing1', function($q) {
                $q->whereNotNull('file_proposal')
                  ->where('raw_nama_pembimbing1', 'like', 'SK_%');
            })
            ->orWhereHas('skripsiSebagaiPembimbing2', function($q) {
                $q->whereNotNull('file_proposal')
                  ->where('raw_nama_pembimbing1', 'like', 'SK_%');
            })
            ->with([
                'skripsiSebagaiPembimbing1' => function($q) {
                    $q->whereNotNull('file_proposal')
                      ->where('raw_nama_pembimbing1', 'like', 'SK_%');
                },
                'skripsiSebagaiPembimbing2' => function($q) {
                    $q->whereNotNull('file_proposal')
                      ->where('raw_nama_pembimbing1', 'like', 'SK_%');
                }
            ])
            ->get();

        $stats = [
            'total_dosen' => $dosens->count(),
            'total_sk_proposal' => Skripsi::whereNotNull('file_proposal')
                ->where('raw_nama_pembimbing1', 'like', 'SK_%')
                ->count(),
            'from_presma' => Skripsi::whereNotNull('file_proposal')
                ->where('source', 'presma')
                ->where('raw_nama_pembimbing1', 'like', 'SK_%')
                ->count(),
        ];

        return view('admin.file.sk-proposal.index', compact('dosens', 'stats'));
    }

    public function show(Dosen $dosen)
    {
        $dosen->load([
            'skripsiSebagaiPembimbing1' => function($q) {
                $q->whereNotNull('file_proposal')
                  ->where('raw_nama_pembimbing1', 'like', 'SK_%');
            },
            'skripsiSebagaiPembimbing2' => function($q) {
                $q->whereNotNull('file_proposal')
                  ->where('raw_nama_pembimbing1', 'like', 'SK_%');
            }
        ]);

        $skripsiList = $dosen->skripsiSebagaiPembimbing1->merge($dosen->skripsiSebagaiPembimbing2);

        return view('admin.file.sk-proposal.show', compact('dosen', 'skripsiList'));
    }

    public function preview(Skripsi $skripsi)
    {
        if (!$skripsi->file_proposal) {
            abort(404, 'File SK Proposal tidak ditemukan');
        }

        if (Storage::disk('local')->exists($skripsi->file_proposal)) {
            $file = Storage::disk('local')->get($skripsi->file_proposal);
            $mimeType = Storage::disk('local')->mimeType($skripsi->file_proposal);
        } elseif (Storage::disk('public')->exists($skripsi->file_proposal)) {
            $file = Storage::disk('public')->get($skripsi->file_proposal);
            $mimeType = Storage::disk('public')->mimeType($skripsi->file_proposal);
        } else {
            abort(404, 'File tidak ditemukan di storage');
        }
        
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline');
    }

    public function download(Skripsi $skripsi)
    {
        if (!$skripsi->file_proposal) {
            abort(404, 'File SK Proposal tidak ditemukan');
        }

        if (Storage::disk('local')->exists($skripsi->file_proposal)) {
            $disk = 'local';
        } elseif (Storage::disk('public')->exists($skripsi->file_proposal)) {
            $disk = 'public';
        } else {
            abort(404, 'File tidak ditemukan di storage');
        }

        // Ambil nomor SK dari raw_nama_pembimbing1
        $nomorSk = explode(' | ', $skripsi->raw_nama_pembimbing1 ?? '')[0];
        $fileName = "SK_Proposal_{$skripsi->nama_mahasiswa}_{$skripsi->nim}.pdf";
        
        return Storage::disk($disk)->download($skripsi->file_proposal, $fileName);
    }

    /**
     * Extract nomor SK dari raw_nama_pembimbing1
     */
    private function extractNomorSk($rawData)
    {
        if (empty($rawData)) {
            return '-';
        }
        $parts = explode(' | ', $rawData);
        return $parts[0] ?? '-';
    }
}