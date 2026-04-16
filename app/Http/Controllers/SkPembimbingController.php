<?php

namespace App\Http\Controllers;

use App\Models\SkPembimbing;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SkPembimbingController extends Controller
{
    /**
     * Daftar semua SK Pembimbing
     */
    public function index()
    {
        $skPembimbings = SkPembimbing::with(['mahasiswa', 'dosenPembimbing1', 'dosenPembimbing2'])
            ->latest()
            ->paginate(20);

        return view('sk-pembimbing.index', compact('skPembimbings'));
    }

    /**
     * Detail satu SK Pembimbing
     */
    public function show(SkPembimbing $skPembimbing)
    {
        $skPembimbing->load(['mahasiswa', 'dosenPembimbing1', 'dosenPembimbing2']);

        return view('sk-pembimbing.show', compact('skPembimbing'));
    }
}