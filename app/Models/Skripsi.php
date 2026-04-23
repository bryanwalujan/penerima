<?php
// app/Models/Skripsi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Skripsi extends Model
{
    protected $fillable = [
        'nama_mahasiswa',
        'nim',
        'angkatan',
        'judul_skripsi',
        'dosen_pembimbing1_id',
        'dosen_pembimbing2_id',
        'raw_nama_pembimbing1',
        'raw_nip_pembimbing1',
        'raw_nama_pembimbing2',
        'raw_nip_pembimbing2',
        'match_status_pb1',
        'match_status_pb2',
        'file_skripsi',
        'file_sk_pembimbing',
        'file_proposal',
        'source',
        'pendaftaran_id',
        'last_synced_at',
    ];

    protected $casts = [
        'last_synced_at' => 'datetime',
    ];

    public function dosenPembimbing1()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing1_id');
    }

    public function dosenPembimbing2()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing2_id');
    }

    public function getFolderNameAttribute(): string
    {
        $nama  = preg_replace('/[^a-zA-Z0-9\s]/', '', $this->nama_mahasiswa);
        $judul = preg_replace('/[^a-zA-Z0-9\s]/', '', $this->judul_skripsi);

        $nama  = str_replace(' ', '_', trim($nama));
        $judul = implode('_', array_slice(explode(' ', trim($judul)), 0, 5)); // 5 kata pertama

        return "{$nama}_{$judul}";
    }
}