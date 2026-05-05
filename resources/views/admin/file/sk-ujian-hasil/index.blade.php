{{-- resources/views/admin/file/sk-ujian-hasil/index.blade.php --}}

@extends('layouts.admin.app')

@section('title', 'SK Ujian Hasil - Repositori Dosen')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
        <i class="fas fa-file-pdf text-red-600 mr-2"></i>SK Ujian Hasil Mahasiswa
    </h1>
    <p class="text-gray-600 mt-1">Daftar Surat Keputusan (SK) Ujian Hasil skripsi mahasiswa</p>
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
            <div class="p-3 rounded-full bg-red-500 bg-opacity-75">
                <i class="fas fa-file-pdf text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total SK Ujian Hasil</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_sk_ujian_hasil'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-500 bg-opacity-75">
                <i class="fas fa-cloud-upload-alt text-white text-xl"></i>
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
               class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-300 focus:border-red-500">
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-4 border-b">
        <h3 class="text-lg font-semibold text-gray-800">
            <i class="fas fa-list text-red-600 mr-2"></i> Daftar SK Ujian Hasil
        </h3>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="w-full min-w-max" id="skUjianHasilTable">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="py-3 px-4 text-left rounded-tl-lg">No</th>
                    <th class="py-3 px-4 text-left">Dosen Pembimbing</th>
                    <th class="py-3 px-4 text-left">Mahasiswa</th>
                    <th class="py-3 px-4 text-left">NIM</th>
                    <th class="py-3 px-4 text-left">Nomor SK</th>
                    <th class="py-3 px-4 text-left">Peran</th>
                    <th class="py-3 px-4 text-center rounded-tr-lg">Aksi</th>
                </tr>
            </thead>
            <tbody id="skUjianHasilTableBody">
                @php $no = 1; @endphp
                @foreach($dosens as $dosen)
                    @foreach($dosen->skripsiSebagaiPembimbing1 as $skripsi)
                        @if($skripsi->file_skripsi && $skripsi->raw_nama_pembimbing1)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $no++ }}</td>
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.file.sk-ujian-hasil.dosen', $dosen) }}" class="text-blue-600 hover:underline">
                                    {{ $dosen->nama }}
                                </a>
                            </td>
                            <td class="py-3 px-4 font-medium">{{ $skripsi->nama_mahasiswa }}</td>
                            <td class="py-3 px-4"><code>{{ $skripsi->nim ?: '-' }}</code></td>
                            <td class="py-3 px-4">
                                @php
                                    $nomorSk = explode(' | ', $skripsi->raw_nama_pembimbing1 ?? '')[0];
                                @endphp
                                <span class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">{{ $nomorSk ?: '-' }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-chalkboard-user mr-1"></i> Pembimbing 1
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex justify-center space-x-3">
                                    <button onclick="previewFile('{{ route('admin.file.sk-ujian-hasil.preview', $skripsi) }}', '{{ $skripsi->nama_mahasiswa }}')" 
                                            class="text-red-600 hover:text-red-800" title="Preview SK">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('admin.file.sk-ujian-hasil.download', $skripsi) }}" 
                                       class="text-green-600 hover:text-green-800" title="Download SK">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                    @foreach($dosen->skripsiSebagaiPembimbing2 as $skripsi)
                        @if($skripsi->file_skripsi && $skripsi->raw_nama_pembimbing1)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $no++ }}</td>
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.file.sk-ujian-hasil.dosen', $dosen) }}" class="text-blue-600 hover:underline">
                                    {{ $dosen->nama }}
                                </a>
                            </td>
                            <td class="py-3 px-4 font-medium">{{ $skripsi->nama_mahasiswa }}</td>
                            <td class="py-3 px-4"><code>{{ $skripsi->nim ?: '-' }}</code></td>
                            <td class="py-3 px-4">
                                @php
                                    $nomorSk = explode(' | ', $skripsi->raw_nama_pembimbing1 ?? '')[0];
                                @endphp
                                <span class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">{{ $nomorSk ?: '-' }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-chalkboard-user mr-1"></i> Pembimbing 2
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex justify-center space-x-3">
                                    <button onclick="previewFile('{{ route('admin.file.sk-ujian-hasil.preview', $skripsi) }}', '{{ $skripsi->nama_mahasiswa }}')" 
                                            class="text-red-600 hover:text-red-800" title="Preview SK">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('admin.file.sk-ujian-hasil.download', $skripsi) }}" 
                                       class="text-green-600 hover:text-green-800" title="Download SK">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
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
                <p class="text-gray-500">Belum ada SK Ujian Hasil yang tersedia.</p>
                <p class="text-sm text-gray-400 mt-1">SK Ujian Hasil akan muncul setelah disinkronkan dari e-Service</p>
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
    
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let searchText = this.value.toLowerCase();
        let rows = document.querySelectorAll('#skUjianHasilTableBody tr');
        
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            if (text.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    
    window.onclick = function(event) {
        let modal = document.getElementById('pdfModal');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>
@endsection