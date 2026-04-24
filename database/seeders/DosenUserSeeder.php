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

        foreach ($dosens as $dosen) {
            // Cek apakah user dengan email ini sudah ada
            $exists = DB::table('users')->where('email', $dosen->email)->exists();

            if (!$exists) {
                DB::table('users')->insert([
                    'name'              => $dosen->nama ?? 'Dosen',
                    'email'             => $dosen->email,
                    'role'              => 'dosen',
                    'password'          => Hash::make('password'),
                    'email_verified_at' => now(),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }
        }

        $this->command->info('Seeder dosen selesai: ' . $dosens->count() . ' dosen diproses.');
    }
}