{{-- resources/views/admin/file/sk-ujian-hasil/show.blade.php --}}

@extends('layouts.admin.app')

@section('title', 'SK Ujian Hasil - ' . $dosen->nama)

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.file.sk-ujian-hasil.index') }}" class="text-blue-600 hover:text-blue-800 mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-file-pdf text-red-600 mr-2"></i>SK Ujian Hasil - {{ $dosen->nama }}
            </h1>
            <p class="text-gray-600 mt-1">Daftar Surat Keputusan Ujian Hasil mahasiswa bimbingan</p>
        </div>
        <div class="bg-red-100 rounded-lg px-4 py-2">
            <span class="text-red-800 font-semibold">Total: {{ $skripsiList->count() }} SK</span>
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
            <i class="fas fa-file-pdf text-red-600 mr-2"></i> Daftar SK Ujian Hasil
        </h3>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="w-full min-w-max">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="py-3 px-4 text-left rounded-tl-lg">No</th>
                    <th class="py-3 px-4 text-left">Nama Mahasiswa</th>
                    <th class="py-3 px-4 text-left">NIM</th>
                    <th class="py-3 px-4 text-left">Nomor SK</th>
                    <th class="py-3 px-4 text-left">Judul Skripsi</th>
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
                        $nomorSk = explode(' | ', $skripsi->raw_nama_pembimbing1 ?? '')[0];
                    @endphp
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $no++ }}</td>
                        <td class="py-3 px-4 font-medium">{{ $skripsi->nama_mahasiswa }}</td>
                        <td class="py-3 px-4"><code>{{ $skripsi->nim ?: '-' }}</code></td>
                        <td class="py-3 px-4">
                            <span class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">{{ $nomorSk ?: '-' }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="max-w-xs truncate" title="{{ $skripsi->judul_skripsi }}">
                                {{ Str::limit($skripsi->judul_skripsi, 50) }}
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $role == 'Pembimbing 1' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                <i class="fas fa-chalkboard-user mr-1"></i> {{ $role }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center space-x-3">
                                <button onclick="previewFile('{{ route('admin.file.sk-ujian-hasil.preview', $skripsi) }}', '{{ $skripsi->nama_mahasiswa }}')" 
                                        class="text-red-600 hover:text-red-800" title="Preview">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('admin.file.sk-ujian-hasil.download', $skripsi) }}" 
                                   class="text-green-600 hover:text-green-800" title="Download">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($skripsiList->isEmpty())
            <div class="text-center py-8">
                <i class="fas fa-folder-open fa-4x text-gray-300 mb-3"></i>
                <p class="text-gray-500">Belum ada SK Ujian Hasil untuk dosen ini.</p>
            </div>
        @endif
    </div>
</div>

{{-- Modal Preview PDF --}}
<div id="pdfModal" class="modal hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50">
    <div class="relative w-full max-w-6xl mx-auto mt-10 mb-10">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-semibold" id="modalTitle">
                    <i class="fas fa-file-pdf text-red-600 mr-2"></i>Preview SK Ujian Hasil
                </h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-4">
                <iframe id="pdfIframe" src="" style="width: 100%; height: 70vh; border: none;"></iframe>
            </div>
            <div class="flex justify-end p-4 border-t">
                <button onclick="closeModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 mr-2">
                    Tutup
                </button>
                <a href="#" id="downloadLink" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    <i class="fas fa-download mr-1"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function previewFile(previewUrl, namaMahasiswa) {
        document.getElementById('pdfIframe').src = previewUrl;
        document.getElementById('downloadLink').href = previewUrl.replace('/preview/', '/download/');
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-file-pdf text-red-600 mr-2"></i>Preview SK Ujian Hasil - ' + namaMahasiswa;
        document.getElementById('pdfModal').classList.remove('hidden');
    }
    
    function closeModal() {
        document.getElementById('pdfModal').classList.add('hidden');
        document.getElementById('pdfIframe').src = '';
    }
    
    window.onclick = function(event) {
        let modal = document.getElementById('pdfModal');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>
@endsection