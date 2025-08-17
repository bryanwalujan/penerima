<?php

namespace App\Exports;

use App\Models\Dosen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class RepositoryExport implements FromCollection, WithHeadings, WithMapping
{
    protected $format;

    public function __construct($format = 'excel')
    {
        $this->format = $format;
    }

    /**
     * Mengambil data untuk ekspor
     */
    public function collection()
    {
        return Dosen::with(['penelitians', 'pengabdians', 'hakis', 'patens'])->get();
    }

    /**
     * Menentukan header untuk file Excel atau CSV
     */
    public function headings(): array
    {
        if ($this->format === 'excel' || $this->format === 'csv') {
            return [
                'Nama',
                'NIDN',
                'NIP',
                'NUPTK',
                'Penelitian',
                'Pengabdian',
                'HAKI',
                'Paten',
            ];
        }
        return [];
    }

    /**
     * Memetakan data untuk setiap baris
     */
    public function map($dosen): array
    {
        if ($this->format === 'excel' || $this->format === 'csv') {
            return [
                $dosen->nama,
                $dosen->nidn,
                $dosen->nip ?? '-',
                $dosen->nuptk ?? '-',
                $dosen->penelitians->pluck('judul_penelitian')->implode(', ') ?: '-',
                $dosen->pengabdians->pluck('judul_pengabdian')->implode(', ') ?: '-',
                $dosen->hakis->pluck('judul_haki')->implode(', ') ?: '-',
                $dosen->patens->pluck('judul_paten')->implode(', ') ?: '-',
            ];
        }
        return [];
    }

    /**
     * Menangani ekspor dalam format RIS atau BibTeX
     */
    public function exportCustomFormat($dosens)
    {
        $output = '';
        if ($this->format === 'ris') {
            foreach ($dosens as $dosen) {
                foreach ($dosen->penelitians as $penelitian) {
                    $output .= "TY  - JOUR\n";
                    $output .= "AU  - {$dosen->nama}\n";
                    $output .= "TI  - {$penelitian->judul_penelitian}\n";
                    $output .= "PY  - {$penelitian->tahun}\n";
                    $output .= "KW  - {$penelitian->keywords}\n";
                    $output .= "ER  - \n\n";
                }
            }
        } elseif ($this->format === 'bib') {
            foreach ($dosens as $dosen) {
                foreach ($dosen->penelitians as $index => $penelitian) {
                    $output .= "@article{penelitian{$dosen->id}_{$index},\n";
                    $output .= "  author = \"{$dosen->nama}\",\n";
                    $output .= "  title = \"{$penelitian->judul_penelitian}\",\n";
                    $output .= "  year = \"{$penelitian->tahun}\",\n";
                    $output .= "  keywords = \"{$penelitian->keywords}\"\n";
                    $output .= "}\n\n";
                }
            }
        }
        return $output;
    }
}