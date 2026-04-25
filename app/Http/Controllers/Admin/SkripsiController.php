<?php
// app/Http/Controllers/Admin/SkripsiController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SkripsiController extends Controller
{
    /**
     * Menampilkan daftar semua skripsi
     */
    public function index(Request $request)
    {
        $query = Skripsi::with(['dosenPembimbing1', 'dosenPembimbing2'])
            ->latest('created_at');

        // Filter pencarian
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_mahasiswa', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('judul_skripsi', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan source
        if ($source = $request->query('source')) {
            $query->where('source', $source);
        }

        $skripsiList = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Skripsi::count(),
            'from_presma' => Skripsi::where('source', 'presma')->count(),
            'with_files' => Skripsi::whereNotNull('file_skripsi')->count(),
            'synced_today' => Skripsi::whereDate('last_synced_at', today())->count(),
        ];

        return view('admin.skripsi.index', compact('skripsiList', 'stats'));
    }

    /**
     * Menampilkan detail skripsi beserta file-file
     */
    public function show(Skripsi $skripsi)
    {
        $skripsi->load(['dosenPembimbing1', 'dosenPembimbing2']);
        
        // Cek keberadaan file di kedua disk
        $files = [
            'skripsi' => $this->checkFileExists($skripsi->file_skripsi),
            'sk_pembimbing' => $this->checkFileExists($skripsi->file_sk_pembimbing),
            'proposal' => $this->checkFileExists($skripsi->file_proposal),
        ];

        return view('admin.skripsi.show', compact('skripsi', 'files'));
    }

    /**
     * Download file skripsi
     */
    public function downloadFile(Skripsi $skripsi, $fileType)
    {
        $fileField = null;
        $fileName = null;
        
        switch ($fileType) {
            case 'skripsi':
                $fileField = $skripsi->file_skripsi;
                $fileName = "Skripsi_{$skripsi->nama_mahasiswa}.pdf";
                break;
            case 'sk_pembimbing':
                $fileField = $skripsi->file_sk_pembimbing;
                $fileName = "SK_Pembimbing_{$skripsi->nama_mahasiswa}.pdf";
                break;
            case 'proposal':
                $fileField = $skripsi->file_proposal;
                $fileName = "Proposal_{$skripsi->nama_mahasiswa}.pdf";
                break;
            default:
                abort(404, 'Tipe file tidak ditemukan');
        }

        if (!$fileField) {
            abort(404, 'File tidak ditemukan di database');
        }

        // Cari file di disk yang tersedia
        $disk = $this->getDiskWhereFileExists($fileField);
        
        if ($disk) {
            return Storage::disk($disk)->download($fileField, $fileName);
        }
        
        abort(404, 'File tidak ditemukan di storage');
    }

    /**
     * Preview file (untuk menampilkan PDF di browser)
     */
    public function previewFile(Skripsi $skripsi, $fileType)
    {
        $fileField = null;
        
        switch ($fileType) {
            case 'skripsi':
                $fileField = $skripsi->file_skripsi;
                break;
            case 'sk_pembimbing':
                $fileField = $skripsi->file_sk_pembimbing;
                break;
            case 'proposal':
                $fileField = $skripsi->file_proposal;
                break;
            default:
                abort(404, 'Tipe file tidak valid');
        }

        if (!$fileField) {
            abort(404, 'File tidak ditemukan di database');
        }

        // Log untuk debugging
        \Log::info('Mencoba mengakses file', [
            'file_field' => $fileField,
            'file_type' => $fileType,
            'skripsi_id' => $skripsi->id
        ]);

        // Cari file di disk yang tersedia
        $disk = $this->getDiskWhereFileExists($fileField);
        
        if ($disk) {
            $file = Storage::disk($disk)->get($fileField);
            $mimeType = Storage::disk($disk)->mimeType($fileField);
            
            \Log::info('File ditemukan', [
                'disk' => $disk,
                'path' => $fileField,
                'size' => strlen($file)
            ]);
            
            return response($file, 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline; filename="' . basename($fileField) . '"');
        }
        
        // Log error jika file tidak ditemukan
        \Log::error('File tidak ditemukan', [
            'file_field' => $fileField,
            'checked_disks' => ['local', 'public']
        ]);
        
        abort(404, 'File tidak ditemukan di storage: ' . $fileField);
    }

    /**
     * Serve file dari private storage
     */
    public function serveFile($filename)
    {
        // Cari file di folder skripsi di private storage
        $paths = [
            'skripsi/' . $filename,
            'private/skripsi/' . $filename,
            $filename
        ];
        
        foreach ($paths as $path) {
            if (Storage::disk('local')->exists($path)) {
                $file = Storage::disk('local')->get($path);
                $mimeType = Storage::disk('local')->mimeType($path);
                
                return response($file, 200)
                    ->header('Content-Type', $mimeType)
                    ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
            }
        }
        
        abort(404, 'File tidak ditemukan');
    }

    /**
     * Hapus file tertentu
     */
    public function deleteFile(Skripsi $skripsi, $fileType)
    {
        $fileField = null;
        
        switch ($fileType) {
            case 'skripsi':
                $fileField = 'file_skripsi';
                break;
            case 'sk_pembimbing':
                $fileField = 'file_sk_pembimbing';
                break;
            case 'proposal':
                $fileField = 'file_proposal';
                break;
            default:
                return response()->json(['error' => 'Tipe file tidak valid'], 400);
        }

        if ($skripsi->$fileField) {
            // Hapus dari semua disk
            $disks = ['public', 'local'];
            foreach ($disks as $disk) {
                if (Storage::disk($disk)->exists($skripsi->$fileField)) {
                    Storage::disk($disk)->delete($skripsi->$fileField);
                    \Log::info('File dihapus dari disk: ' . $disk, [
                        'path' => $skripsi->$fileField
                    ]);
                }
            }
            
            $skripsi->update([$fileField => null]);
            
            return response()->json(['message' => 'File berhasil dihapus']);
        }

        return response()->json(['error' => 'File tidak ditemukan'], 404);
    }

    /**
     * Helper: Cek apakah file exists di salah satu disk
     */
    private function checkFileExists($filePath)
    {
        if (!$filePath) {
            return false;
        }
        
        return Storage::disk('local')->exists($filePath) || 
               Storage::disk('public')->exists($filePath);
    }

    /**
     * Helper: Dapatkan disk dimana file exists
     */
    private function getDiskWhereFileExists($filePath)
    {
        if (!$filePath) {
            return null;
        }
        
        if (Storage::disk('local')->exists($filePath)) {
            return 'local';
        }
        
        if (Storage::disk('public')->exists($filePath)) {
            return 'public';
        }
        
        return null;
    }
    
    /**
 * Helper: Get full storage path for a file
 */
private function getFileStoragePath($filePath)
{
    if (!$filePath) {
        return null;
    }
    
    // Cek di berbagai kemungkinan folder
    $possiblePaths = [
        $filePath, // full path
        'skripsi/' . $filePath,
        'sk_pembimbing/' . $filePath,
        'proposal/' . $filePath,
    ];
    
    foreach ($possiblePaths as $path) {
        if (Storage::disk('local')->exists($path)) {
            return ['disk' => 'local', 'path' => $path];
        }
        if (Storage::disk('public')->exists($path)) {
            return ['disk' => 'public', 'path' => $path];
        }
    }
    
    return null;
}

// Tambahkan method untuk preview proposal
public function previewProposal(Skripsi $skripsi)
{
    if (!$skripsi->file_proposal) {
        abort(404, 'File proposal tidak ditemukan');
    }

    $disk = $this->getDiskWhereFileExists($skripsi->file_proposal);
    
    if ($disk) {
        $file = Storage::disk($disk)->get($skripsi->file_proposal);
        $mimeType = Storage::disk($disk)->mimeType($skripsi->file_proposal);
        
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="Proposal_' . $skripsi->nama_mahasiswa . '.pdf"');
    }
    
    abort(404, 'File proposal tidak ditemukan di storage');
}

// Tambahkan method untuk download proposal
public function downloadProposal(Skripsi $skripsi)
{
    if (!$skripsi->file_proposal) {
        abort(404, 'File proposal tidak ditemukan');
    }

    $disk = $this->getDiskWhereFileExists($skripsi->file_proposal);
    
    if ($disk) {
        $fileName = "Proposal_{$skripsi->nama_mahasiswa}_{$skripsi->nim}.pdf";
        return Storage::disk($disk)->download($skripsi->file_proposal, $fileName);
    }
    
    abort(404, 'File proposal tidak ditemukan di storage');
}
} 