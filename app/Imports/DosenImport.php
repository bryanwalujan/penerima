<?php
namespace App\Imports;

use App\Models\Dosen;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Models\Haki;
use App\Models\Paten;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Carbon\Carbon;

class DosenImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Penelitian' => new PenelitianSheetImport(),
            'Pengabdian' => new PengabdianSheetImport(),
            'HAKI' => new HakiSheetImport(),
            'PATEN' => new PatenSheetImport(),
        ];
    }
}

class PenelitianSheetImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (!isset($row['nama']) || !isset($row['skema'])) {
            return null; // Lewati jika baris tidak valid
        }

        // Cari dosen berdasarkan kombinasi nama dan NIDN untuk menghindari duplikasi
        $dosen = $this->findOrCreateDosen($row);

        return new Penelitian([
            'dosen_id' => $dosen->id,
            'skema' => $row['skema'],
            'posisi' => $row['posisi'],
            'judul_penelitian' => $row['judul_penelitian'],
            'sumber_dana' => $row['sumber_dana'],
            'status' => $row['status'],
            'tahun' => $row['tahun'],
            'link_luaran' => $row['link_luaran'] ?? null,
        ]);
    }

    private function findOrCreateDosen($row)
    {
        $nama = trim($row['nama']);
        $nidn = isset($row['nidn']) && !empty(trim($row['nidn'])) ? trim($row['nidn']) : null;
        
        // Jika NIDN tersedia, cari berdasarkan NIDN terlebih dahulu
        if ($nidn) {
            $dosen = Dosen::where('nidn', $nidn)->first();
            
            // Jika dosen ditemukan dengan NIDN yang sama, update nama jika berbeda
            if ($dosen) {
                if ($dosen->nama !== $nama) {
                    $dosen->update(['nama' => $nama]);
                }
                return $dosen;
            }
        }
        
        // Jika tidak ditemukan berdasarkan NIDN, cari berdasarkan nama
        $dosenByName = Dosen::where('nama', $nama)->first();
        
        if ($dosenByName) {
            // Jika NIDN kosong pada dosen yang ada, update dengan NIDN baru
            if (!$dosenByName->nidn && $nidn) {
                $dosenByName->update(['nidn' => $nidn]);
            }
            return $dosenByName;
        }
        
        // Jika tidak ditemukan sama sekali, buat dosen baru
        $newNidn = $nidn ?: 'NIDN-' . time() . '-' . rand(1000, 9999);
        
        return Dosen::create([
            'nama' => $nama,
            'nidn' => $newNidn,
            'nip' => isset($row['nip']) ? trim($row['nip']) : null,
            'nuptk' => isset($row['nuptk']) ? trim($row['nuptk']) : null,
        ]);
    }
}

class PengabdianSheetImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (!isset($row['nama']) || !isset($row['skema'])) {
            return null;
        }

        $dosen = $this->findOrCreateDosen($row);

        return new Pengabdian([
            'dosen_id' => $dosen->id,
            'skema' => $row['skema'],
            'posisi' => $row['posisi'],
            'judul_pengabdian' => $row['judul_pengabdian'],
            'sumber_dana' => $row['sumber_dana'],
            'status' => $row['status'],
            'tahun' => $row['tahun'],
            'link_luaran' => $row['link_luaran'] ?? null,
        ]);
    }

    private function findOrCreateDosen($row)
    {
        $nama = trim($row['nama']);
        $nidn = isset($row['nidn']) && !empty(trim($row['nidn'])) ? trim($row['nidn']) : null;
        
        // Jika NIDN tersedia, cari berdasarkan NIDN terlebih dahulu
        if ($nidn) {
            $dosen = Dosen::where('nidn', $nidn)->first();
            
            // Jika dosen ditemukan dengan NIDN yang sama, update nama jika berbeda
            if ($dosen) {
                if ($dosen->nama !== $nama) {
                    $dosen->update(['nama' => $nama]);
                }
                return $dosen;
            }
        }
        
        // Jika tidak ditemukan berdasarkan NIDN, cari berdasarkan nama
        $dosenByName = Dosen::where('nama', $nama)->first();
        
        if ($dosenByName) {
            // Jika NIDN kosong pada dosen yang ada, update dengan NIDN baru
            if (!$dosenByName->nidn && $nidn) {
                $dosenByName->update(['nidn' => $nidn]);
            }
            return $dosenByName;
        }
        
        // Jika tidak ditemukan sama sekali, buat dosen baru
        $newNidn = $nidn ?: 'NIDN-' . time() . '-' . rand(1000, 9999);
        
        return Dosen::create([
            'nama' => $nama,
            'nidn' => $newNidn,
            'nip' => isset($row['nip']) ? trim($row['nip']) : null,
            'nuptk' => isset($row['nuptk']) ? trim($row['nuptk']) : null,
        ]);
    }
}

class HakiSheetImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (!isset($row['nama']) || !isset($row['judul_haki'])) {
            return null;
        }

        $dosen = $this->findOrCreateDosen($row);

        $expired = null;
        if (!empty($row['expired'])) {
            try {
                $expired = Carbon::parse($row['expired'])->format('Y-m-d');
            } catch (\Exception $e) {
                $expired = null;
            }
        }

        return new Haki([
            'dosen_id' => $dosen->id,
            'judul_haki' => $row['judul_haki'],
            'expired' => $expired,
            'link' => $row['link'] ?? null,
        ]);
    }

    private function findOrCreateDosen($row)
    {
        $nama = trim($row['nama']);
        $nidn = isset($row['nidn']) && !empty(trim($row['nidn'])) ? trim($row['nidn']) : null;
        
        // Jika NIDN tersedia, cari berdasarkan NIDN terlebih dahulu
        if ($nidn) {
            $dosen = Dosen::where('nidn', $nidn)->first();
            
            // Jika dosen ditemukan dengan NIDN yang sama, update nama jika berbeda
            if ($dosen) {
                if ($dosen->nama !== $nama) {
                    $dosen->update(['nama' => $nama]);
                }
                return $dosen;
            }
        }
        
        // Jika tidak ditemukan berdasarkan NIDN, cari berdasarkan nama
        $dosenByName = Dosen::where('nama', $nama)->first();
        
        if ($dosenByName) {
            // Jika NIDN kosong pada dosen yang ada, update dengan NIDN baru
            if (!$dosenByName->nidn && $nidn) {
                $dosenByName->update(['nidn' => $nidn]);
            }
            return $dosenByName;
        }
        
        // Jika tidak ditemukan sama sekali, buat dosen baru
        $newNidn = $nidn ?: 'NIDN-' . time() . '-' . rand(1000, 9999);
        
        return Dosen::create([
            'nama' => $nama,
            'nidn' => $newNidn,
            'nip' => isset($row['nip']) ? trim($row['nip']) : null,
            'nuptk' => isset($row['nuptk']) ? trim($row['nuptk']) : null,
        ]);
    }
}

class PatenSheetImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (!isset($row['nama']) || !isset($row['judul_paten'])) {
            return null;
        }

        $dosen = $this->findOrCreateDosen($row);

        $expired = null;
        if (!empty($row['expired'])) {
            try {
                $expired = Carbon::parse($row['expired'])->format('Y-m-d');
            } catch (\Exception $e) {
                $expired = null;
            }
        }

        return new Paten([
            'dosen_id' => $dosen->id,
            'judul_paten' => $row['judul_paten'],
            'jenis_paten' => $row['jenis_paten'],
            'expired' => $expired,
            'link' => $row['link'] ?? null,
        ]);
    }

    private function findOrCreateDosen($row)
    {
        $nama = trim($row['nama']);
        $nidn = isset($row['nidn']) && !empty(trim($row['nidn'])) ? trim($row['nidn']) : null;
        
        // Jika NIDN tersedia, cari berdasarkan NIDN terlebih dahulu
        if ($nidn) {
            $dosen = Dosen::where('nidn', $nidn)->first();
            
            // Jika dosen ditemukan dengan NIDN yang sama, update nama jika berbeda
            if ($dosen) {
                if ($dosen->nama !== $nama) {
                    $dosen->update(['nama' => $nama]);
                }
                return $dosen;
            }
        }
        
        // Jika tidak ditemukan berdasarkan NIDN, cari berdasarkan nama
        $dosenByName = Dosen::where('nama', $nama)->first();
        
        if ($dosenByName) {
            // Jika NIDN kosong pada dosen yang ada, update dengan NIDN baru
            if (!$dosenByName->nidn && $nidn) {
                $dosenByName->update(['nidn' => $nidn]);
            }
            return $dosenByName;
        }
        
        // Jika tidak ditemukan sama sekali, buat dosen baru
        $newNidn = $nidn ?: 'NIDN-' . time() . '-' . rand(1000, 9999);
        
        return Dosen::create([
            'nama' => $nama,
            'nidn' => $newNidn,
            'nip' => isset($row['nip']) ? trim($row['nip']) : null,
            'nuptk' => isset($row['nuptk']) ? trim($row['nuptk']) : null,
        ]);
    }
}