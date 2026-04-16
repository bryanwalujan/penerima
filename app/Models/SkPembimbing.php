<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SkPembimbing extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sk_pembimbing';

    protected $fillable = [
        'pengajuan_sk_pembimbing_id',
        'mahasiswa_id',
        'dosen_pembimbing_1_id',
        'dosen_pembimbing_2_id',
        'judul_skripsi',
        'file_surat_permohonan',
        'file_slip_ukt',
        'file_proposal_revisi',
        'file_surat_sk',
        'status',
        'nomor_surat',
        'tanggal_surat',
        'catatan_staff',
        'alasan_ditolak',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    // Relations
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function dosenPembimbing1()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_1_id');
    }

    public function dosenPembimbing2()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_2_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function ttdKajurBy()
    {
        return $this->belongsTo(User::class, 'ttd_kajur_by');
    }

    public function ttdKorprodiBy()
    {
        return $this->belongsTo(User::class, 'ttd_korprodi_by');
    }

    // ==================== HELPER ====================

    public function isApproved()
    {
        return $this->status === 'selesai' || 
               ($this->ttd_kajur_at && $this->ttd_korprodi_at);
    }

    public function getDosenPembimbingAttribute()
    {
        $d1 = $this->dosenPembimbing1?->nama ?? '-';
        $d2 = $this->dosenPembimbing2?->nama ?? '';
        return $d2 ? "{$d1}, {$d2}" : $d1;
    }
}