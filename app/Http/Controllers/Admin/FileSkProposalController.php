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
     * Preview SK Proposal - Perbaikan untuk menampilkan file
     */
    public function preview(Skripsi $skripsi)
    {
        if (!$skripsi->file_proposal) {
            Log::error('Preview SK Proposal: file_proposal kosong', [
                'skripsi_id' => $skripsi->id,
                'nama_mahasiswa' => $skripsi->nama_mahasiswa
            ]);
            abort(404, 'File SK Proposal tidak ditemukan');
        }

        $filePath = $skripsi->file_proposal;
        
        Log::info('Preview SK Proposal', [
            'skripsi_id' => $skripsi->id,
            'file_path' => $filePath,
            'mahasiswa' => $skripsi->nama_mahasiswa
        ]);

        // Coba baca file dari berbagai kemungkinan lokasi
        $content = null;
        $mimeType = 'application/pdf';
        
        // 1. Coba dari disk local (storage/app/private/)
        if (Storage::disk('local')->exists($filePath)) {
            $content = Storage::disk('local')->get($filePath);
            $mimeType = Storage::disk('local')->mimeType($filePath);
            Log::info('File ditemukan di disk local', ['path' => $filePath]);
        }
        // 2. Coba dari disk public
        elseif (Storage::disk('public')->exists($filePath)) {
            $content = Storage::disk('public')->get($filePath);
            $mimeType = Storage::disk('public')->mimeType($filePath);
            Log::info('File ditemukan di disk public', ['path' => $filePath]);
        }
        // 3. Coba dengan absolute path
        else {
            $fullPath = storage_path('app/private/' . $filePath);
            if (file_exists($fullPath)) {
                $content = file_get_contents($fullPath);
                $mimeType = mime_content_type($fullPath);
                Log::info('File ditemukan via absolute path', ['path' => $fullPath]);
            }
        }
        
        // 4. Coba cari di folder proposal berdasarkan folder_name
        if (!$content && $skripsi->folder_name) {
            $proposalPath = 'proposal/' . $skripsi->folder_name . '/SK_Proposal.pdf';
            if (Storage::disk('local')->exists($proposalPath)) {
                $content = Storage::disk('local')->get($proposalPath);
                $mimeType = Storage::disk('local')->mimeType($proposalPath);
                Log::info('File ditemukan di folder proposal', ['path' => $proposalPath]);
            }
        }
        
        // 5. Coba cari dengan pola path lainnya
        if (!$content) {
            $pathsToTry = [
                'proposal/' . basename($filePath),
                'sk_proposal/' . $skripsi->folder_name . '/SK_Proposal.pdf',
                'private/proposal/' . $skripsi->folder_name . '/SK_Proposal.pdf',
            ];
            
            foreach ($pathsToTry as $tryPath) {
                if (Storage::disk('local')->exists($tryPath)) {
                    $content = Storage::disk('local')->get($tryPath);
                    $mimeType = Storage::disk('local')->mimeType($tryPath);
                    Log::info('File ditemukan di alternative path', ['path' => $tryPath]);
                    break;
                }
            }
        }

        if (!$content) {
            Log::error('File SK Proposal tidak ditemukan setelah semua percobaan', [
                'skripsi_id' => $skripsi->id,
                'original_path' => $filePath,
                'folder_name' => $skripsi->folder_name
            ]);
            abort(404, 'File SK Proposal tidak ditemukan di storage');
        }
        
        return response($content, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="SK_Proposal_' . $skripsi->nama_mahasiswa . '.pdf"');
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