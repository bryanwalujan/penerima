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
    
    /**
     * Relasi sebagai pembimbing 1
     */
    public function skripsiSebagaiPembimbing1()
    {
        return $this->hasMany(Skripsi::class, 'dosen_pembimbing1_id');
    }

    /**
     * Relasi sebagai pembimbing 2
     */
    public function skripsiSebagaiPembimbing2()
    {
        return $this->hasMany(Skripsi::class, 'dosen_pembimbing2_id');
    }

    /**
     * Semua skripsi yang dibimbing (baik sebagai pembimbing 1 maupun 2)
     */
    public function semuaSkripsiBimbingan()
    {
        return $this->skripsiSebagaiPembimbing1->merge($this->skripsiSebagaiPembimbing2);
    }
}