<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class TestingViewController extends Controller
{
    public function index()
    {
        $uploadPath = public_path('testing/uploads');

        // Buat folder otomatis jika belum ada
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $files = collect(File::files($uploadPath))
            ->map(function ($file) {
                return [
                    'name'     => $file->getFilename(),
                    'size'     => round($file->getSize() / 1024, 2) . ' KB',
                    'modified' => date('d M Y H:i', $file->getMTime()),
                    'url'      => '/testing/uploads/' . $file->getFilename()
                ];
            })
            ->sortByDesc('modified');

        return view('testing.index', compact('files'));
    }
}