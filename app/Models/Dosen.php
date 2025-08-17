<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Crypt;

class Dosen extends Authenticatable
{
    protected $fillable = ['nidn', 'nip', 'nuptk', 'nama', 'email', 'foto'];

    // Mutator untuk mengenkripsi data sensitif sebelum disimpan

    public function setNipAttribute($value)
    {
        $this->attributes['nip'] = $value ? Crypt::encryptString($value) : null;
    }

    public function setNuptkAttribute($value)
    {
        $this->attributes['nuptk'] = $value ? Crypt::encryptString($value) : null;
    }

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value ? Crypt::encryptString($value) : null;
    }

    // Accessor untuk mendekripsi data saat diambil

    public function getNipAttribute($value)
    {
        if (!$value) {
            return null;
        }
        try {
            return Crypt::decryptString($value);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return $value;
        }
    }

    public function getNuptkAttribute($value)
    {
        if (!$value) {
            return null;
        }
        try {
            return Crypt::decryptString($value);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return $value;
        }
    }

    public function getNamaAttribute($value)
    {
        if (!$value) {
            return null;
        }
        try {
            return Crypt::decryptString($value);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return $value;
        }
    }

    public function penelitians()
    {
        return $this->hasMany(Penelitian::class);
    }

    public function pengabdians()
    {
        return $this->hasMany(Pengabdian::class);
    }

    public function hakis()
    {
        return $this->hasMany(Haki::class);
    }

    public function patens()
    {
        return $this->hasMany(Paten::class);
    }
}