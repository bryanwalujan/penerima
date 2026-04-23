<?php
// database/migrations/2025_01_01_000001_create_skripsis_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skripsis', function (Blueprint $table) {
            $table->id();

            // Data mahasiswa (tidak FK, hanya informasi)
            $table->string('nama_mahasiswa');
            $table->string('nim', 20)->nullable()->index();
            $table->string('angkatan', 4)->nullable();
            $table->text('judul_skripsi');

            // Tagged dosen (nullable — jika tidak ada match)
            $table->foreignId('dosen_pembimbing1_id')
                  ->nullable()
                  ->constrained('dosens')
                  ->nullOnDelete();
            $table->foreignId('dosen_pembimbing2_id')
                  ->nullable()
                  ->constrained('dosens')
                  ->nullOnDelete();

            // Raw data asli dari e-service (audit trail)
            $table->string('raw_nama_pembimbing1')->nullable();
            $table->string('raw_nip_pembimbing1')->nullable();
            $table->string('raw_nama_pembimbing2')->nullable();
            $table->string('raw_nip_pembimbing2')->nullable();

            // Status matching
            $table->enum('match_status_pb1', ['matched', 'unmatched'])->default('unmatched');
            $table->enum('match_status_pb2', ['matched', 'unmatched'])->default('unmatched');

            // Files — path relatif dari storage/app/private/
            $table->string('file_skripsi')->nullable();
            $table->string('file_sk_pembimbing')->nullable();
            $table->string('file_proposal')->nullable();

            // Metadata sync
            $table->string('source')->default('presma');
            $table->string('pendaftaran_id')->nullable()->index(); // ID dari e-service
            $table->timestamp('last_synced_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skripsis');
    }
};