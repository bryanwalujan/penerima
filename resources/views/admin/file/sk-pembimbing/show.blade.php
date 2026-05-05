{{-- resources/views/admin/file/sk-pembimbing/show.blade.php --}}

@extends('layouts.admin.app')

@section('title', 'SK Pembimbing - ' . $dosen->nama)

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.file.sk-pembimbing.index') }}" class="text-blue-600 hover:text-blue-800 mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-file-alt text-blue-600 mr-2"></i>SK Pembimbing - {{ $dosen->nama }}
            </h1>
            <p class="text-gray-600 mt-1">Daftar Surat Keputusan Pembimbing mahasiswa bimbingan</p>
        </div>
        <div class="bg-blue-100 rounded-lg px-4 py-2">
            <span class="text-blue-800 font-semibold">Total: {{ $skripsiList->count() }} SK</span>
        </div>
    </div>
</div>

{{-- Info Dosen --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-500 bg-opacity-75">
                <i class="fas fa-id-card text-white"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">NIDN</p>
                <p class="font-semibold">{{ $dosen->nidn ?: '-' }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-500 bg-opacity-75">
                <i class="fas fa-envelope text-white"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Email</p>
                <p class="font-semibold">{{ $dosen->email ?: '-' }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-500 bg-opacity-75">
                <i class="fas fa-chalkboard-user text-white"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Jabatan</p>
                <p class="font-semibold">{{ $dosen->jabatan ?: '-' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-4 border-b">
        <h3 class="text-lg font-semibold text-gray-800">
            <i class="fas fa-file-alt text-blue-600 mr-2"></i> Daftar SK Pembimbing
        </h3>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="w-full min-w-max">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="py-3 px-4 text-left rounded-tl-lg">No</th>
                    <th class="py-3 px-4 text-left">Nama Mahasiswa</th>
                    <th class="py-3 px-4 text-left">NIM</th>
                    <th class="py-3 px-4 text-left">Peran</th>
                    <th class="py-3 px-4 text-center rounded-tr-lg">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($skripsiList as $skripsi)
                    @php
                        $role = '';
                        if($dosen->id == $skripsi->dosen_pembimbing1_id) $role = 'Pembimbing 1';
                        if($dosen->id == $skripsi->dosen_pembimbing2_id) $role = 'Pembimbing 2';
                    @endphp
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $no++ }}</td>
                        <td class="py-3 px-4 font-medium">{{ $skripsi->nama_mahasiswa }}</td>
                        <td class="py-3 px-4"><code>{{ $skripsi->nim ?: '-' }}</code></td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $role == 'Pembimbing 1' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                <i class="fas fa-chalkboard-user mr-1"></i> {{ $role }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <a href="{{ route('admin.file.sk-pembimbing.download', $skripsi) }}" 
                               class="text-green-600 hover:text-green-800" title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($skripsiList->isEmpty())
            <div class="text-center py-8">
                <i class="fas fa-folder-open fa-4x text-gray-300 mb-3"></i>
                <p class="text-gray-500">Belum ada SK Pembimbing untuk dosen ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection