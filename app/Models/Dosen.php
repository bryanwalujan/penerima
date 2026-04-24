<?php
// app/Models/Dosen.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Dosen extends Authenticatable
{
    protected $fillable = ['nidn', 'nip', 'nuptk', 'nama', 'email', 'foto', 'jabatan'];

    // Relasi ke Penelitian
    public function penelitians()
    {
        return $this->hasMany(Penelitian::class);
    }

    // Relasi ke Pengabdian
    public function pengabdians()
    {
        return $this->hasMany(Pengabdian::class);
    }

    // Relasi ke Haki
    public function hakis()
    {
        return $this->hasMany(Haki::class);
    }

    // Relasi ke Paten
    public function patens()
    {
        return $this->hasMany(Paten::class);
    }

    // ========== RELASI KE SKRIPSI ==========
    
    public function skripsiSebagaiPembimbing1()
    {
        return $this->hasMany(Skripsi::class, 'dosen_pembimbing1_id');
    }

    public function skripsiSebagaiPembimbing2()
    {
        return $this->hasMany(Skripsi::class, 'dosen_pembimbing2_id');
    }

    public function semuaSkripsiBimbingan()
    {
        return $this->skripsiSebagaiPembimbing1->merge($this->skripsiSebagaiPembimbing2);
    }

    // ========== RELASI KE SK PEMBIMBING (via Skripsi) ==========
    
    public function skPembimbingSebagaiPembimbing1()
    {
        return $this->hasMany(Skripsi::class, 'dosen_pembimbing1_id')->whereNotNull('file_sk_pembimbing');
    }

    public function skPembimbingSebagaiPembimbing2()
    {
        return $this->hasMany(Skripsi::class, 'dosen_pembimbing2_id')->whereNotNull('file_sk_pembimbing');
    }

    // ========== RELASI KE PROPOSAL (via Skripsi) ==========
    
    public function proposalSebagaiPembimbing1()
    {
        return $this->hasMany(Skripsi::class, 'dosen_pembimbing1_id')->whereNotNull('file_proposal');
    }

    public function proposalSebagaiPembimbing2()
    {
        return $this->hasMany(Skripsi::class, 'dosen_pembimbing2_id')->whereNotNull('file_proposal');
    }
}