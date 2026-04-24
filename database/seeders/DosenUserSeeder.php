<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DosenUserSeeder extends Seeder
{
    public function run(): void
    {
        $dosens = DB::table('dosens')->whereNotNull('email')->get();
        $count  = 0;

        foreach ($dosens as $dosen) {
            if (empty(trim($dosen->email ?? ''))) continue;

            $exists = DB::table('users')->where('email', $dosen->email)->exists();
            if ($exists) continue;

            // Generate ID manual karena kolom id tidak auto_increment
            $maxId = DB::table('users')->max('id') ?? 0;

            DB::table('users')->insert([
                'id'                => $maxId + 1,
                'name'              => $dosen->nama ?? 'Dosen',
                'email'             => $dosen->email,
                'role'              => 'dosen',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            $count++;
        }

        $this->command->info("Selesai: {$count} dosen berhasil ditambahkan ke tabel users.");
    }
}