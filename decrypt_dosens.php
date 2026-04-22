<?php
// decrypt_dosens.php — jalankan dari root project Laravel
// php decrypt_dosens.php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

$dosens = DB::table('dosens')->get();
$updated = 0;
$skipped = 0;

foreach ($dosens as $dosen) {
    $data = [];

    foreach (['nama', 'nip', 'nuptk'] as $field) {
        $raw = $dosen->$field;

        if (!$raw) {
            continue;
        }

        try {
            $data[$field] = Crypt::decryptString($raw);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            // Sudah plaintext, skip
        }
    }

    if (!empty($data)) {
        DB::table('dosens')->where('id', $dosen->id)->update($data);
        $updated++;
        echo "✓ Dosen ID {$dosen->id} ({$dosen->nidn}) — di-decrypt: " . implode(', ', array_keys($data)) . "\n";
    } else {
        $skipped++;
        echo "- Dosen ID {$dosen->id} ({$dosen->nidn}) — sudah plaintext, skip.\n";
    }
}

echo "\nSelesai. Updated: {$updated}, Skipped: {$skipped}\n";