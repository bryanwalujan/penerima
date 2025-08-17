<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Dosen;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class EncryptDosenData extends Command
{
    protected $signature = 'dosen:encrypt-data';
    protected $description = 'Mengenkripsi data nama dosen yang belum terenkripsi di tabel dosens';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Memulai proses enkripsi data nama dosen...');

        $dosens = Dosen::all();
        $encryptedCount = 0;

        foreach ($dosens as $dosen) {
            $needsUpdate = false;
            $attribute = 'nama'; // Hanya enkripsi nama

            $value = $dosen->getRawOriginal($attribute); // Ambil nilai asli dari database
            if ($value && !$this->isEncrypted($value)) {
                $dosen->$attribute = $value; // Gunakan mutator untuk mengenkripsi
                $needsUpdate = true;
            }

            if ($needsUpdate) {
                $dosen->save();
                $encryptedCount++;
                $this->info("Data nama dosen ID: {$dosen->id} berhasil dienkripsi.");
            }
        }

        $this->info("Proses selesai. {$encryptedCount} baris data nama dienkripsi.");
    }

    protected function isEncrypted($value)
    {
        try {
            Crypt::decryptString($value);
            return true;
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return false;
        }
    }
}