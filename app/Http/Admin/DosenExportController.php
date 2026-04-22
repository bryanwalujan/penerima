<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HasDosenHelpers;
use App\Exports\DosenImportTemplateExport;
use App\Exports\RepositoryExport;
use App\Imports\DosenImport;
use App\Models\Dosen;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

/**
 * DosenExportController
 *
 * Mengelola import dan export data repository dosen
 * dalam berbagai format: Excel, RIS, BibTeX, CSV.
 */
class DosenExportController extends Controller
{
    use HasDosenHelpers;

    // =========================================================================
    // IMPORT
    // =========================================================================

    public function import(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        try {
            Excel::import(new DosenImport, $request->file('file'));

            $this->auditLog('import_dosen', 'Admin imported dosen data via Excel');

            return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil diimpor.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // EXPORT TEMPLATE
    // =========================================================================

    public function exportTemplate()
    {
        $this->checkAdmin();

        $filename = 'dosen_import_template_' . Carbon::now()->format('Y-m-d') . '.xlsx';

        $this->auditLog('export_template', 'Admin exported dosen import template');

        return Excel::download(new DosenImportTemplateExport, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    // =========================================================================
    // EXPORT REPOSITORY
    // =========================================================================

    public function export(Request $request)
    {
        $this->checkAdmin();

        $format   = $request->query('format', 'excel');
        $ext      = $format === 'excel' ? 'xlsx' : $format;
        $filename = 'unima_repository_' . Carbon::now()->format('Y-m-d') . '.' . $ext;

        $this->auditLog('export_data', "Admin exported repository data to {$format}");

        if ($format === 'excel') {
            return Excel::download(new RepositoryExport, $filename, \Maatwebsite\Excel\Excel::XLSX);
        }

        $dosens  = Dosen::with(['penelitians', 'pengabdians', 'hakis', 'patens'])->get();
        $content = match ($format) {
            'ris'   => $this->buildRis($dosens),
            'bib'   => $this->buildBib($dosens),
            'csv'   => $this->buildCsv($dosens),
            default => null,
        };

        if ($content === null) {
            return redirect()->back()->with('error', 'Format ekspor tidak didukung.');
        }

        $mimeType = $format === 'csv' ? 'text/csv' : 'text/plain';

        return Response::streamDownload(function () use ($content) {
            echo $content;
        }, $filename, ['Content-Type' => $mimeType]);
    }

    // =========================================================================
    // PRIVATE BUILDERS
    // =========================================================================

    private function buildRis($dosens): string
    {
        $out = '';

        foreach ($dosens as $dosen) {
            foreach ($dosen->penelitians as $p) {
                $out .= "TY  - MISC\nTI  - {$p->judul_penelitian}\nAU  - {$dosen->nama}\nPY  - {$p->tahun}\n";
                if ($p->keywords) {
                    $out .= 'KW  - ' . (is_array($p->keywords) ? implode(', ', $p->keywords) : $p->keywords) . "\n";
                }
                if ($p->skema)      $out .= "KW  - {$p->skema}\n";
                if ($p->link_luaran) $out .= "UR  - {$p->link_luaran}\n";
                $out .= "ER  -\n\n";
            }
            foreach ($dosen->pengabdians as $p) {
                $out .= "TY  - MISC\nTI  - {$p->judul_pengabdian}\nAU  - {$dosen->nama}\nPY  - {$p->tahun}\n";
                if ($p->skema)       $out .= "KW  - {$p->skema}\n";
                if ($p->link_luaran) $out .= "UR  - {$p->link_luaran}\n";
                $out .= "ER  -\n\n";
            }
            foreach ($dosen->hakis as $h) {
                $out .= "TY  - MISC\nTI  - {$h->judul_haki}\nAU  - {$dosen->nama}\n";
                if ($h->expired) $out .= "DA  - {$h->expired}\n";
                if ($h->link)    $out .= "UR  - {$h->link}\n";
                $out .= "ER  -\n\n";
            }
            foreach ($dosen->patens as $p) {
                $out .= "TY  - MISC\nTI  - {$p->judul_paten}\nAU  - {$dosen->nama}\n";
                if ($p->expired)    $out .= "DA  - {$p->expired}\n";
                if ($p->jenis_paten) $out .= "KW  - {$p->jenis_paten}\n";
                if ($p->link)       $out .= "UR  - {$p->link}\n";
                $out .= "ER  -\n\n";
            }
        }

        return $out;
    }

    private function buildBib($dosens): string
    {
        $out = '';

        foreach ($dosens as $dosen) {
            foreach ($dosen->penelitians as $p) {
                $kw = $p->keywords ? (is_array($p->keywords) ? implode(', ', $p->keywords) : $p->keywords) : '';
                $kw .= $p->skema ? ($kw ? ', ' : '') . $p->skema : '';
                $out .= "@misc{penelitian_{$p->id},\n  title = {\"{$p->judul_penelitian}\"},\n  author = {\"{$dosen->nama}\"},\n  year = {\"{$p->tahun}\"},\n  keywords = {\"{$kw}\"},\n";
                if ($p->link_luaran) $out .= "  url = {\"{$p->link_luaran}\"}\n";
                $out .= "}\n\n";
            }
            foreach ($dosen->pengabdians as $p) {
                $out .= "@misc{pengabdian_{$p->id},\n  title = {\"{$p->judul_pengabdian}\"},\n  author = {\"{$dosen->nama}\"},\n  year = {\"{$p->tahun}\"},\n";
                if ($p->skema)       $out .= "  keywords = {\"{$p->skema}\"},\n";
                if ($p->link_luaran) $out .= "  url = {\"{$p->link_luaran}\"}\n";
                $out .= "}\n\n";
            }
            foreach ($dosen->hakis as $h) {
                $year = $h->expired ? Carbon::parse($h->expired)->year : '';
                $out .= "@misc{haki_{$h->id},\n  title = {\"{$h->judul_haki}\"},\n  author = {\"{$dosen->nama}\"},\n  year = {\"{$year}\"},\n";
                if ($h->link) $out .= "  url = {\"{$h->link}\"}\n";
                $out .= "}\n\n";
            }
            foreach ($dosen->patens as $p) {
                $year = $p->expired ? Carbon::parse($p->expired)->year : '';
                $out .= "@misc{paten_{$p->id},\n  title = {\"{$p->judul_paten}\"},\n  author = {\"{$dosen->nama}\"},\n  year = {\"{$year}\"},\n";
                if ($p->jenis_paten) $out .= "  keywords = {\"{$p->jenis_paten}\"},\n";
                if ($p->link)       $out .= "  url = {\"{$p->link}\"}\n";
                $out .= "}\n\n";
            }
        }

        return $out;
    }

    private function buildCsv($dosens): string
    {
        $out = "type,dosen_nama,judul,tahun,keywords,skema,posisi,sumber_dana,status,link,expired,jenis_paten\n";

        foreach ($dosens as $dosen) {
            foreach ($dosen->penelitians as $p) {
                $kw = $p->keywords ? (is_array($p->keywords) ? implode(',', $p->keywords) : $p->keywords) : '';
                $out .= "\"Penelitian\",\"{$dosen->nama}\",\"{$p->judul_penelitian}\",\"{$p->tahun}\",\"{$kw}\",\"{$p->skema}\",\"{$p->posisi}\",\"{$p->sumber_dana}\",\"{$p->status}\",\"{$p->link_luaran}\",\"\",\"\"\n";
            }
            foreach ($dosen->pengabdians as $p) {
                $out .= "\"Pengabdian\",\"{$dosen->nama}\",\"{$p->judul_pengabdian}\",\"{$p->tahun}\",\"\",\"{$p->skema}\",\"{$p->posisi}\",\"{$p->sumber_dana}\",\"{$p->status}\",\"{$p->link_luaran}\",\"\",\"\"\n";
            }
            foreach ($dosen->hakis as $h) {
                $out .= "\"Haki\",\"{$dosen->nama}\",\"{$h->judul_haki}\",\"\",\"\",\"\",\"\",\"\",\"\",\"{$h->link}\",\"{$h->expired}\",\"\"\n";
            }
            foreach ($dosen->patens as $p) {
                $out .= "\"Paten\",\"{$dosen->nama}\",\"{$p->judul_paten}\",\"\",\"\",\"\",\"\",\"\",\"\",\"{$p->link}\",\"{$p->expired}\",\"{$p->jenis_paten}\"\n";
            }
        }

        return $out;
    }

    private function checkAdmin(): void
    {
        if (!Auth::guard('web')->check() || Auth::guard('web')->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya admin yang diizinkan.');
        }
    }
}