{{-- resources/views/admin/file/sk-pembimbing/index.blade.php --}}

@extends('layouts.admin.app')

@section('title', 'SK Pembimbing - Repositori Dosen')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
        <i class="fas fa-file-alt text-blue-600 mr-2"></i>SK Pembimbing Mahasiswa
    </h1>
    <p class="text-gray-600 mt-1">Daftar Surat Keputusan (SK) Pembimbing skripsi mahasiswa</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-500 bg-opacity-75">
                <i class="fas fa-user-tie text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Dosen Pembimbing</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_dosen'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-500 bg-opacity-75">
                <i class="fas fa-file-alt text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total SK Pembimbing</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_sk_pembimbing'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-500 bg-opacity-75">
                <i class="fas fa-file-alt text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Dari Presma</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['from_presma'] }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Search --}}
<div class="bg-white rounded-lg shadow mb-6 p-4">
    <div class="relative">
        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        <input type="text" id="searchInput" placeholder="Cari dosen atau mahasiswa..." 
               class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500">
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-4 border-b">
        <h3 class="text-lg font-semibold text-gray-800">
            <i class="fas fa-list text-blue-600 mr-2"></i> Daftar SK Pembimbing
        </h3>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="w-full min-w-max">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="py-3 px-4 text-left rounded-tl-lg">No</th>
                    <th class="py-3 px-4 text-left">Dosen Pembimbing</th>
                    <th class="py-3 px-4 text-left">Mahasiswa</th>
                    <th class="py-3 px-4 text-left">NIM</th>
                    <th class="py-3 px-4 text-left">Peran</th>
                    <th class="py-3 px-4 text-center rounded-tr-lg">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($dosens as $dosen)
                    @foreach($dosen->skripsiSebagaiPembimbing1 as $skripsi)
                        @if($skripsi->file_sk_pembimbing)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $no++ }}</td>
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.file.sk-pembimbing.dosen', $dosen) }}" class="text-blue-600 hover:underline">
                                    {{ $dosen->nama }}
                                </a>
                            </td>
                            <td class="py-3 px-4 font-medium">{{ $skripsi->nama_mahasiswa }}</td>
                            <td class="py-3 px-4"><code>{{ $skripsi->nim ?: '-' }}</code></td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-chalkboard-user mr-1"></i> Pembimbing 1
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('admin.file.sk-pembimbing.download', $skripsi) }}" 
                                   class="text-green-600 hover:text-green-800" title="Download">
                                    <i class="fas fa-download"></i>
                                </a>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                    @foreach($dosen->skripsiSebagaiPembimbing2 as $skripsi)
                        @if($skripsi->file_sk_pembimbing)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $no++ }}</td>
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.file.sk-pembimbing.dosen', $dosen) }}" class="text-blue-600 hover:underline">
                                    {{ $dosen->nama }}
                                </a>
                            </td>
                            <td class="py-3 px-4 font-medium">{{ $skripsi->nama_mahasiswa }}</td>
                            <td class="py-3 px-4"><code>{{ $skripsi->nim ?: '-' }}</code></td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-chalkboard-user mr-1"></i> Pembimbing 2
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('admin.file.sk-pembimbing.download', $skripsi) }}" 
                                   class="text-green-600 hover:text-green-800" title="Download">
                                    <i class="fas fa-download"></i>
                                </a>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                @endforeach
            </tbody>
        </table>
        
        @if($no == 1)
            <div class="text-center py-8">
                <i class="fas fa-folder-open fa-4x text-gray-300 mb-3"></i>
                <p class="text-gray-500">Belum ada SK Pembimbing yang tersedia.</p>
                <p class="text-sm text-gray-400 mt-1">SK Pembimbing akan muncul setelah disinkronkan dari e-Service</p>
            </div>
        @endif
    </div>
</div>
@endsection