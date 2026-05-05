<?php
// app/Http/Controllers/Dosen/DosenSkripsiController.php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Skripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DosenSkripsiController extends Controller
{
    /**
     * Mendapatkan data dosen yang sedang login dari model Dosen
     */
    private function getAuthDosen()
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'dosen') {
            abort(403, 'Anda tidak memiliki akses.');
        }
        
        // Cari dosen berdasarkan email atau nama
        $dosen = Dosen::where('email', $user->email)->first();
        
        if (!$dosen) {
            // Coba cari berdasarkan nama
            $dosen = Dosen::where('nama', $user->name)->first();
        }
        
        if (!$dosen) {
            abort(404, 'Data dosen tidak ditemukan. Silakan hubungi admin.');
        }
        
        return $dosen;
    }

    /**
     * Menampilkan semua file skripsi bimbingan dosen
     */
    public function skripsiIndex()
    {
        $dosen = $this->getAuthDosen();
        
        $dosen->load([
            'skripsiSebagaiPembimbing1' => function($q) {
                $q->whereNotNull('file_skripsi');
            },
            'skripsiSebagaiPembimbing2' => function($q) {
                $q->whereNotNull('file_skripsi');
            }
        ]);
        
        $skripsiList = $dosen->skripsiSebagaiPembimbing1->merge($dosen->skripsiSebagaiPembimbing2);
        
        $stats = [
            'total_mahasiswa' => $skripsiList->count(),
            'total_file' => $skripsiList->count(),
            'as_pembimbing_1' => $dosen->skripsiSebagaiPembimbing1->count(),
            'as_pembimbing_2' => $dosen->skripsiSebagaiPembimbing2->count(),
        ];
        
        return view('dosen.skripsi.index', compact('dosen', 'skripsiList', 'stats'));
    }

    /**
     * Menampilkan semua file SK pembimbing bimbingan dosen
     */
    public function skPembimbingIndex()
    {
        $dosen = $this->getAuthDosen();
        
        $dosen->load([
            'skripsiSebagaiPembimbing1' => function($q) {
                $q->whereNotNull('file_sk_pembimbing');
            },
            'skripsiSebagaiPembimbing2' => function($q) {
                $q->whereNotNull('file_sk_pembimbing');
            }
        ]);
        
        $skripsiList = $dosen->skripsiSebagaiPembimbing1->merge($dosen->skripsiSebagaiPembimbing2);
        
        $stats = [
            'total_mahasiswa' => $skripsiList->count(),
            'total_file' => $skripsiList->count(),
            'as_pembimbing_1' => $dosen->skripsiSebagaiPembimbing1->count(),
            'as_pembimbing_2' => $dosen->skripsiSebagaiPembimbing2->count(),
        ];
        
        return view('dosen.sk-pembimbing.index', compact('dosen', 'skripsiList', 'stats'));
    }

    /**
     * Menampilkan semua file SK Proposal bimbingan dosen
     */
    public function skProposalIndex()
    {
        $dosen = $this->getAuthDosen();
        
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
        
        $stats = [
            'total_mahasiswa' => $skripsiList->count(),
            'total_file' => $skripsiList->count(),
            'as_pembimbing_1' => $dosen->skripsiSebagaiPembimbing1->count(),
            'as_pembimbing_2' => $dosen->skripsiSebagaiPembimbing2->count(),
        ];
        
        return view('dosen.sk-proposal.index', compact('dosen', 'skripsiList', 'stats'));
    }

    /**
     * Menampilkan semua file SK Ujian Hasil bimbingan dosen
     */
    public function skUjianHasilIndex()
    {
        $dosen = $this->getAuthDosen();
        
        $dosen->load([
            'skripsiSebagaiPembimbing1' => function($q) {
                $q->whereNotNull('file_skripsi')
                  ->where('raw_nama_pembimbing1', 'not like', 'SK_%');
            },
            'skripsiSebagaiPembimbing2' => function($q) {
                $q->whereNotNull('file_skripsi')
                  ->where('raw_nama_pembimbing1', 'not like', 'SK_%');
            }
        ]);
        
        $skripsiList = $dosen->skripsiSebagaiPembimbing1->merge($dosen->skripsiSebagaiPembimbing2);
        
        $stats = [
            'total_mahasiswa' => $skripsiList->count(),
            'total_file' => $skripsiList->count(),
            'as_pembimbing_1' => $dosen->skripsiSebagaiPembimbing1->count(),
            'as_pembimbing_2' => $dosen->skripsiSebagaiPembimbing2->count(),
        ];
        
        return view('dosen.sk-ujian-hasil.index', compact('dosen', 'skripsiList', 'stats'));
    }

    /**
     * Preview file skripsi
     */
    public function previewSkripsi(Skripsi $skripsi)
    {
        $dosen = $this->getAuthDosen();
        
        if ($skripsi->dosen_pembimbing1_id != $dosen->id && $skripsi->dosen_pembimbing2_id != $dosen->id) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }
        
        if (!$skripsi->file_skripsi) {
            abort(404);
        }
        
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
     * Preview file SK pembimbing
     */
    public function previewSkPembimbing(Skripsi $skripsi)
    {
        $dosen = $this->getAuthDosen();
        
        if ($skripsi->dosen_pembimbing1_id != $dosen->id && $skripsi->dosen_pembimbing2_id != $dosen->id) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }
        
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

    /**
     * Preview file SK Proposal
     */
    public function previewSkProposal(Skripsi $skripsi)
    {
        $dosen = $this->getAuthDosen();
        
        if ($skripsi->dosen_pembimbing1_id != $dosen->id && $skripsi->dosen_pembimbing2_id != $dosen->id) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }
        
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

    /**
     * Preview file SK Ujian Hasil
     */
    public function previewSkUjianHasil(Skripsi $skripsi)
    {
        $dosen = $this->getAuthDosen();
        
        if ($skripsi->dosen_pembimbing1_id != $dosen->id && $skripsi->dosen_pembimbing2_id != $dosen->id) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }
        
        if (!$skripsi->file_skripsi) {
            abort(404);
        }
        
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
    public function downloadSkripsi(Skripsi $skripsi)
    {
        $dosen = $this->getAuthDosen();
        
        if ($skripsi->dosen_pembimbing1_id != $dosen->id && $skripsi->dosen_pembimbing2_id != $dosen->id) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }
        
        if (!$skripsi->file_skripsi) {
            abort(404);
        }
        
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

    /**
     * Download file SK pembimbing
     */
    public function downloadSkPembimbing(Skripsi $skripsi)
    {
        $dosen = $this->getAuthDosen();
        
        if ($skripsi->dosen_pembimbing1_id != $dosen->id && $skripsi->dosen_pembimbing2_id != $dosen->id) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }
        
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

    /**
     * Download file SK Proposal
     */
    public function downloadSkProposal(Skripsi $skripsi)
    {
        $dosen = $this->getAuthDosen();
        
        if ($skripsi->dosen_pembimbing1_id != $dosen->id && $skripsi->dosen_pembimbing2_id != $dosen->id) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }
        
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
        
        $fileName = "SK_Proposal_{$skripsi->nama_mahasiswa}_{$skripsi->nim}.pdf";
        
        return Storage::disk($disk)->download($skripsi->file_proposal, $fileName);
    }

    /**
     * Download file SK Ujian Hasil
     */
    public function downloadSkUjianHasil(Skripsi $skripsi)
    {
        $dosen = $this->getAuthDosen();
        
        if ($skripsi->dosen_pembimbing1_id != $dosen->id && $skripsi->dosen_pembimbing2_id != $dosen->id) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }
        
        if (!$skripsi->file_skripsi) {
            abort(404);
        }
        
        if (Storage::disk('local')->exists($skripsi->file_skripsi)) {
            $disk = 'local';
        } elseif (Storage::disk('public')->exists($skripsi->file_skripsi)) {
            $disk = 'public';
        } else {
            abort(404);
        }
        
        $fileName = "SK_UjianHasil_{$skripsi->nama_mahasiswa}_{$skripsi->nim}.pdf";
        
        return Storage::disk($disk)->download($skripsi->file_skripsi, $fileName);
    }
}