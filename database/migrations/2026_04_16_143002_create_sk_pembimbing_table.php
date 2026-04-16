<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sk_pembimbing', function (Blueprint $table) {
            $table->id();

            // Data referensi dari e-service (tanpa foreign key dulu)
            $table->unsignedBigInteger('pengajuan_sk_pembimbing_id')->nullable();
            $table->unsignedBigInteger('berita_acara_id')->nullable();

            // Relasi utama
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('dosen_pembimbing_1_id')->nullable();
            $table->unsignedBigInteger('dosen_pembimbing_2_id')->nullable();

            $table->text('judul_skripsi');

            // File
            $table->string('file_surat_permohonan');
            $table->string('file_slip_ukt');
            $table->string('file_proposal_revisi');
            $table->string('file_surat_sk')->nullable();

            // Status
            $table->string('status')->default('draft');
            $table->string('nomor_surat')->nullable();
            $table->date('tanggal_surat')->nullable();

            $table->text('catatan_staff')->nullable();
            $table->text('alasan_ditolak')->nullable();

            // Verifikasi & TTD
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();

            $table->unsignedBigInteger('ps_assigned_by')->nullable();
            $table->timestamp('ps_assigned_at')->nullable();

            $table->unsignedBigInteger('ttd_kajur_by')->nullable();
            $table->timestamp('ttd_kajur_at')->nullable();

            $table->unsignedBigInteger('ttd_korprodi_by')->nullable();
            $table->timestamp('ttd_korprodi_at')->nullable();

            $table->string('verification_code')->unique()->nullable();
            $table->text('qr_code_kajur')->nullable();
            $table->text('qr_code_korprodi')->nullable();

            $table->json('override_info')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['mahasiswa_id', 'status']);
            $table->index('dosen_pembimbing_1_id');
            $table->index('dosen_pembimbing_2_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sk_pembimbing');
    }
};