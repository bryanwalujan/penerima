<?php
// app/Http/Controllers/Dosen/DosenSkripsiController.php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Skripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DosenSkripsiController extends Controller
{
    /**
     * Mendapatkan data dosen yang sedang login
     */
    private function authDosen()
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'dosen') {
            abort(403, 'Anda tidak memiliki akses.');
        }
        
        $dosen = Dosen::where('email', $user->email)->first();
        
        if (!$dosen) {
            abort(404, 'Data dosen tidak ditemukan.');
        }
        
        return $dosen;
    }

    /**
     * Menampilkan semua file skripsi bimbingan dosen
     */
    public function skripsiIndex()
    {
        $dosen = $this->authDosen();
        
        // Load semua skripsi bimbingan
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
            'total_skripsi' => $skripsiList->count(),
        ];
        
        return view('dosen.skripsi.index', compact('dosen', 'skripsiList', 'stats'));
    }

    /**
     * Menampilkan semua file SK pembimbing bimbingan dosen
     */
    public function skPembimbingIndex()
    {
        $dosen = $this->authDosen();
        
        $dosen->load([
            'skPembimbingSebagaiPembimbing1',
            'skPembimbingSebagaiPembimbing2'
        ]);
        
        $skripsiList = $dosen->skPembimbingSebagaiPembimbing1->merge($dosen->skPembimbingSebagaiPembimbing2);
        
        $stats = [
            'total_mahasiswa' => $skripsiList->count(),
            'total_sk' => $skripsiList->count(),
        ];
        
        return view('dosen.sk-pembimbing.index', compact('dosen', 'skripsiList', 'stats'));
    }

    /**
     * Menampilkan semua file proposal bimbingan dosen
     */
    public function proposalIndex()
    {
        $dosen = $this->authDosen();
        
        $dosen->load([
            'proposalSebagaiPembimbing1',
            'proposalSebagaiPembimbing2'
        ]);
        
        $skripsiList = $dosen->proposalSebagaiPembimbing1->merge($dosen->proposalSebagaiPembimbing2);
        
        $stats = [
            'total_mahasiswa' => $skripsiList->count(),
            'total_proposal' => $skripsiList->count(),
        ];
        
        return view('dosen.proposal.index', compact('dosen', 'skripsiList', 'stats'));
    }

    /**
     * Preview file skripsi
     */
    public function previewSkripsi(Skripsi $skripsi)
    {
        $dosen = $this->authDosen();
        
        // Validasi bahwa dosen ini adalah pembimbing dari skripsi tersebut
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
        $dosen = $this->authDosen();
        
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
     * Preview file proposal
     */
    public function previewProposal(Skripsi $skripsi)
    {
        $dosen = $this->authDosen();
        
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
     * Download file skripsi
     */
    public function downloadSkripsi(Skripsi $skripsi)
    {
        $dosen = $this->authDosen();
        
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
        $dosen = $this->authDosen();
        
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
     * Download file proposal
     */
    public function downloadProposal(Skripsi $skripsi)
    {
        $dosen = $this->authDosen();
        
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
        
        $fileName = "Proposal_{$skripsi->nama_mahasiswa}_{$skripsi->nim}.pdf";
        
        return Storage::disk($disk)->download($skripsi->file_proposal, $fileName);
    }
}