{{-- resources/views/admin/file/skripsi/index.blade.php --}}

@extends('layouts.admin.app')

@section('title', 'File Skripsi - Repositori Dosen')

@section('header-title', 'File Skripsi Mahasiswa')

@section('styles')
<style>
    .statistics-card {
        transition: all 0.3s ease;
    }
    .statistics-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    .table-row:hover {
        background-color: #f8fafc;
    }
</style>
@endsection

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
        <i class="fas fa-file-pdf text-red-600 mr-2"></i>File Skripsi Mahasiswa
    </h1>
    <p class="text-gray-600 mt-1">Daftar file skripsi yang dibimbing oleh dosen</p>
</div>

{{-- Statistics Cards --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6 statistics-card">
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
    <div class="bg-white rounded-lg shadow p-6 statistics-card">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-500 bg-opacity-75">
                <i class="fas fa-file-pdf text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total File Skripsi</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_skripsi'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6 statistics-card">
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
    <div class="bg-white rounded-lg shadow p-6 statistics-card">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-500 bg-opacity-75">
                <i class="fas fa-check-circle text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Tersedia</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_skripsi'] }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Search --}}
<div class="bg-white rounded-lg shadow mb-6 p-4">
    <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                <input type="text" id="search-input" placeholder="Cari dosen atau mahasiswa..." 
                       class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-300 focus:border-red-500">
            </div>
        </div>
    </div>
</div>

{{-- Daftar Dosen dengan File Skripsi --}}
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="border-b">
        <ul class="flex flex-wrap" role="tablist">
            <li class="flex-1 min-w-[200px]">
                <a class="tab-link py-4 px-6 block text-center hover:bg-gray-50 active" data-tab="skripsi" role="tab">
                    <i class="fas fa-file-pdf text-red-600 mr-2"></i>File Skripsi
                    <span class="count-badge">{{ $stats['total_skripsi'] }}</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="p-4 overflow-x-auto">
        <div id="skripsi" class="tab-content" role="tabpanel">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-3">
                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-file-pdf text-red-600 mr-2"></i> Data File Skripsi ({{ $stats['total_skripsi'] }} file)
                </h3>
            </div>

            <table class="w-full min-w-max" id="skripsi-table">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="py-3 px-4 text-left rounded-tl-lg">No</th>
                        <th class="py-3 px-4 text-left">Dosen Pembimbing</th>
                        <th class="py-3 px-4 text-left">Mahasiswa</th>
                        <th class="py-3 px-4 text-left">NIM</th>
                        <th class="py-3 px-4 text-left">Judul Skripsi</th>
                        <th class="py-3 px-4 text-left">Peran</th>
                        <th class="py-3 px-4 text-center rounded-tr-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody id="skripsi-table-body">
                    @php $no = 1; @endphp
                    @foreach($dosens as $dosen)
                        @php
                            $skripsiList = [];
                            foreach($dosen->skripsiSebagaiPembimbing1 as $s) {
                                $skripsiList[] = ['skripsi' => $s, 'role' => 'Pembimbing 1'];
                            }
                            foreach($dosen->skripsiSebagaiPembimbing2 as $s) {
                                $skripsiList[] = ['skripsi' => $s, 'role' => 'Pembimbing 2'];
                            }
                        @endphp
                        @foreach($skripsiList as $item)
                            <tr class="table-row hover:bg-gray-50">
                                <td class="py-3 px-4 border-b">{{ $no++ }}</td>
                                <td class="py-3 px-4 border-b">
                                    <a href="{{ route('admin.file.skripsi.dosen', $dosen) }}" class="font-medium text-blue-600 hover:underline">
                                        {{ $dosen->nama }}
                                    </a>
                                </td>
                                <td class="py-3 px-4 border-b font-medium">{{ $item['skripsi']->nama_mahasiswa }}</td>
                                <td class="py-3 px-4 border-b"><code>{{ $item['skripsi']->nim ?: '-' }}</code></td>
                                <td class="py-3 px-4 border-b">
                                    <div class="max-w-xs truncate" title="{{ $item['skripsi']->judul_skripsi }}">
                                        {{ Str::limit($item['skripsi']->judul_skripsi, 50) }}
                                    </div>
                                </td>
                                <td class="py-3 px-4 border-b">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-chalkboard-user mr-1"></i> {{ $item['role'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 border-b text-center">
                                    <div class="flex justify-center space-x-3">
                                        <button onclick="previewFile('{{ route('admin.file.skripsi.preview', $item['skripsi']) }}', '{{ $item['skripsi']->nama_mahasiswa }}')" 
                                                class="text-red-600 hover:text-red-800 action-btn" title="Preview">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('admin.file.skripsi.download', $item['skripsi']) }}" 
                                           class="text-green-600 hover:text-green-800 action-btn" title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
            
            @if($no == 1)
                <div class="text-center py-8">
                    <i class="fas fa-folder-open fa-4x text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Belum ada file skripsi yang tersedia.</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Preview PDF --}}
<div id="pdfModal" class="modal hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50">
    <div class="relative w-full max-w-6xl mx-auto mt-10 mb-10">
        <div class="bg-white rounded-lg shadow-lg">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-semibold" id="modalTitle">
                    <i class="fas fa-file-pdf text-red-600 mr-2"></i>Preview File Skripsi
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
@endsection

@section('scripts')
<script>
    function previewFile(previewUrl, namaMahasiswa) {
        document.getElementById('pdfIframe').src = previewUrl;
        document.getElementById('downloadLink').href = previewUrl.replace('/preview/', '/download/');
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-file-pdf text-red-600 mr-2"></i>Preview Skripsi - ' + namaMahasiswa;
        document.getElementById('pdfModal').classList.remove('hidden');
    }
    
    function closeModal() {
        document.getElementById('pdfModal').classList.add('hidden');
        document.getElementById('pdfIframe').src = '';
    }
    
    // Search functionality
    document.getElementById('search-input').addEventListener('keyup', function() {
        let searchText = this.value.toLowerCase();
        let rows = document.querySelectorAll('#skripsi-table-body tr');
        
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            if (text.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        let modal = document.getElementById('pdfModal');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>
@endsection