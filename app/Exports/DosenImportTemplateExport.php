<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;

class DosenImportTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Penelitian' => new PenelitianTemplateSheet(),
            'Pengabdian' => new PengabdianTemplateSheet(),
            'HAKI' => new HakiTemplateSheet(),
            'Paten' => new PatenTemplateSheet(),
        ];
    }
}

class PenelitianTemplateSheet implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return new Collection([]);
    }

    public function headings(): array
    {
        return [
            'no',
            'nidn',
            'nip',
            'nuptk',
            'nama',
            'skema',
            'posisi',
            'judul_penelitian',
            'sumber_dana',
            'status',
            'tahun',
            'link_luaran',
        ];
    }

    public function title(): string
    {
        return 'Penelitian';
    }
}

class PengabdianTemplateSheet implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return new Collection([]);
    }

    public function headings(): array
    {
        return [
            'no',
            'nidn',
            'nip',
            'nuptk',
            'nama',
            'skema',
            'posisi',
            'judul_pengabdian',
            'sumber_dana',
            'status',
            'tahun',
            'link_luaran',
        ];
    }

    public function title(): string
    {
        return 'Pengabdian';
    }
}

class HakiTemplateSheet implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return new Collection([]);
    }

    public function headings(): array
    {
        return [
            'no',
            'nidn',
            'nip',
            'nuptk',
            'nama',
            'judul_haki',
            'expired',
            'link',
        ];
    }

    public function title(): string
    {
        return 'HAKI';
    }
}

class PatenTemplateSheet implements FromCollection, WithHeadings, WithTitle
{
    public function collection()
    {
        return new Collection([]);
    }

    public function headings(): array
    {
        return [
            'no',
            'nidn',
            'nip',
            'nuptk',
            'nama',
            'judul_paten',
            'jenis_paten',
            'expired',
            'link',
        ];
    }

    public function title(): string
    {
        return 'Paten';
    }
}