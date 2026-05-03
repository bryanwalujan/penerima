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
     * Preview SK Proposal - Perbaikan untuk membaca file dari folder proposal
     */
    public function preview(Skripsi $skripsi)
    {
        if (!$skripsi->file_proposal) {
            abort(404, 'File SK Proposal tidak ditemukan');
        }

        $filePath = $skripsi->file_proposal;
        
        // Log untuk debugging
        Log::info('Mencoba preview SK Proposal', [
            'skripsi_id' => $skripsi->id,
            'file_path' => $filePath,
            'mahasiswa' => $skripsi->nama_mahasiswa
        ]);

        // Cek di disk local (storage/app/private/)
        if (Storage::disk('local')->exists($filePath)) {
            $file = Storage::disk('local')->get($filePath);
            $mimeType = Storage::disk('local')->mimeType($filePath);
            
            Log::info('File ditemukan di disk local', [
                'path' => $filePath,
                'size' => strlen($file)
            ]);
            
            return response($file, 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline; filename="SK_Proposal_' . $skripsi->nama_mahasiswa . '.pdf"');
        }
        
        // Cek di disk public sebagai fallback
        if (Storage::disk('public')->exists($filePath)) {
            $file = Storage::disk('public')->get($filePath);
            $mimeType = Storage::disk('public')->mimeType($filePath);
            
            Log::info('File ditemukan di disk public', [
                'path' => $filePath,
                'size' => strlen($file)
            ]);
            
            return response($file, 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline; filename="SK_Proposal_' . $skripsi->nama_mahasiswa . '.pdf"');
        }
        
        // Cek langsung dengan file system (absolute path)
        $fullPath = storage_path('app/private/' . $filePath);
        if (file_exists($fullPath)) {
            $file = file_get_contents($fullPath);
            $mimeType = mime_content_type($fullPath);
            
            Log::info('File ditemukan via absolute path', [
                'path' => $fullPath,
                'size' => strlen($file)
            ]);
            
            return response($file, 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline; filename="SK_Proposal_' . $skripsi->nama_mahasiswa . '.pdf"');
        }
        
        // Coba cek di folder proposal dengan path yang berbeda
        $alternativePaths = [
            'proposal/' . basename($filePath),
            'proposal/' . $skripsi->folder_name . '/SK_Proposal.pdf',
            'sk_proposal/' . $skripsi->folder_name . '/SK_Proposal.pdf',
        ];
        
        foreach ($alternativePaths as $altPath) {
            if (Storage::disk('local')->exists($altPath)) {
                $file = Storage::disk('local')->get($altPath);
                $mimeType = Storage::disk('local')->mimeType($altPath);
                
                Log::info('File ditemukan di alternative path', [
                    'path' => $altPath
                ]);
                
                return response($file, 200)
                    ->header('Content-Type', $mimeType)
                    ->header('Content-Disposition', 'inline; filename="SK_Proposal_' . $skripsi->nama_mahasiswa . '.pdf"');
            }
        }
        
        Log::error('File SK Proposal tidak ditemukan', [
            'skripsi_id' => $skripsi->id,
            'file_path' => $filePath,
            'checked_paths' => [
                'local_exists' => Storage::disk('local')->exists($filePath),
                'public_exists' => Storage::disk('public')->exists($filePath),
                'absolute_path' => $fullPath,
                'absolute_exists' => file_exists($fullPath)
            ]
        ]);
        
        abort(404, 'File SK Proposal tidak ditemukan di storage: ' . $filePath);
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