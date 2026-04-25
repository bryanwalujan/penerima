{{-- resources/views/dosen/proposal/index.blade.php --}}

@extends('layouts.dosen.app')

@section('title', 'Proposal Skripsi Bimbingan')

@section('header-title', 'Proposal Skripsi Bimbingan')
@section('header-subtitle', 'Daftar proposal skripsi mahasiswa bimbingan Anda')

@section('styles')
<style>
    .stats-card {
        transition: all 0.3s ease;
        border-radius: 16px;
    }
    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
    .table-row:hover {
        background-color: #f8fafc;
    }
    .btn-action {
        transition: all 0.2s ease;
    }
    .btn-action:hover {
        transform: scale(1.1);
    }
</style>
@endsection

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-file-word text-yellow-600 mr-3"></i>
                Proposal Skripsi Bimbingan
            </h1>
            <p class="text-gray-600 mt-1">
                Daftar proposal skripsi mahasiswa yang Anda bimbing
            </p>
        </div>
        <div class="bg-yellow-100 rounded-lg px-4 py-2">
            <span class="text-yellow-800 font-semibold">Total: {{ $stats['total_mahasiswa'] }} Mahasiswa</span>
        </div>
    </div>
</div>

{{-- Statistics Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-md p-5 stats-card border-l-4 border-blue-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Dosen Pembimbing</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $dosen->nama }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-user-tie text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-md p-5 stats-card border-l-4 border-yellow-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Mahasiswa Bimbingan</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_mahasiswa'] }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-md p-5 stats-card border-l-4 border-yellow-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Proposal</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_proposal'] }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-file-word text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

{{-- Search --}}
<div class="bg-white rounded-lg shadow mb-6 p-4">
    <div class="relative">
        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        <input type="text" id="searchInput" placeholder="Cari mahasiswa (Nama / NIM)..." 
               class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-300 focus:border-yellow-500">
    </div>
</div>

{{-- Tabel Proposal --}}
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 px-6 py-4">
        <h3 class="text-lg font-semibold text-white flex items-center">
            <i class="fas fa-file-word mr-2"></i> Daftar Proposal Skripsi Mahasiswa Bimbingan
        </h3>
    </div>
    <div class="p-4 overflow-x-auto">
        @if($skripsiList->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-folder-open fa-5x text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada proposal yang tersedia.</p>
                <p class="text-gray-400 text-sm mt-2">Proposal akan muncul setelah diupload oleh mahasiswa atau admin.</p>
            </div>
        @else
            <table class="w-full min-w-max" id="proposalTable">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="py-3 px-4 text-left rounded-tl-lg">No</th>
                        <th class="py-3 px-4 text-left">Nama Mahasiswa</th>
                        <th class="py-3 px-4 text-left">NIM</th>
                        <th class="py-3 px-4 text-left">Judul Skripsi</th>
                        <th class="py-3 px-4 text-left">Peran</th>
                        <th class="py-3 px-4 text-center rounded-tr-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody id="proposalTableBody">
                    @php $no = 1; @endphp
                    @foreach($skripsiList as $skripsi)
                        @php
                            $role = '';
                            if($dosen->id == $skripsi->dosen_pembimbing1_id) $role = 'Pembimbing 1';
                            if($dosen->id == $skripsi->dosen_pembimbing2_id) $role = 'Pembimbing 2';
                        @endphp
                        <tr class="border-b hover:bg-gray-50 table-row">
                            <td class="py-3 px-4">{{ $no++ }}</td>
                            <td class="py-3 px-4 font-semibold text-gray-800">{{ $skripsi->nama_mahasiswa }}</td>
                            <td class="py-3 px-4"><code class="bg-gray-100 px-2 py-1 rounded">{{ $skripsi->nim ?: '-' }}</code></td>
                            <td class="py-3 px-4">
                                <div class="max-w-md truncate" title="{{ $skripsi->judul_skripsi }}">
                                    {{ Str::limit($skripsi->judul_skripsi, 60) }}
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $role == 'Pembimbing 1' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                    <i class="fas fa-chalkboard-user mr-1"></i> {{ $role }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex justify-center space-x-3">
                                    <button onclick="previewFile('{{ route('dosen.proposal.preview', $skripsi) }}', '{{ $skripsi->nama_mahasiswa }}', 'proposal')" 
                                            class="text-yellow-600 hover:text-yellow-800 btn-action" title="Preview">
                                        <i class="fas fa-eye text-lg"></i>
                                    </button>
                                    <a href="{{ route('dosen.proposal.download', $skripsi) }}" 
                                       class="text-green-600 hover:text-green-800 btn-action" title="Download">
                                        <i class="fas fa-download text-lg"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

{{-- Modal Preview PDF --}}
<div id="pdfModal" class="hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50">
    <div class="relative w-full max-w-6xl mx-auto mt-10 mb-10">
        <div class="bg-white rounded-xl shadow-2xl">
            <div class="flex justify-between items-center p-4 border-b bg-gray-50 rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-800" id="modalTitle">
                    <i class="fas fa-file-word text-yellow-600 mr-2"></i>Preview Proposal
                </h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-4">
                <iframe id="pdfIframe" src="" style="width: 100%; height: 70vh; border: none;" class="rounded-lg"></iframe>
            </div>
            <div class="flex justify-end p-4 border-t bg-gray-50 rounded-b-xl">
                <button onclick="closeModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition mr-2">
                    <i class="fas fa-times mr-1"></i> Tutup
                </button>
                <a href="#" id="downloadLink" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                    <i class="fas fa-download mr-1"></i> Download File
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function previewFile(previewUrl, namaMahasiswa, type) {
        let icon = 'fa-file-word';
        let color = 'text-yellow-600';
        let title = 'Preview Proposal';
        
        if (type === 'skripsi') {
            icon = 'fa-file-pdf';
            color = 'text-red-600';
            title = 'Preview File Skripsi - ' + namaMahasiswa;
        } else if (type === 'sk_pembimbing') {
            icon = 'fa-file-alt';
            color = 'text-blue-600';
            title = 'Preview SK Pembimbing - ' + namaMahasiswa;
        } else if (type === 'proposal') {
            icon = 'fa-file-word';
            color = 'text-yellow-600';
            title = 'Preview Proposal - ' + namaMahasiswa;
        }
        
        document.getElementById('pdfIframe').src = previewUrl;
        document.getElementById('downloadLink').href = previewUrl.replace('/preview/', '/download/');
        document.getElementById('modalTitle').innerHTML = '<i class="fas ' + icon + ' ' + color + ' mr-2"></i>' + title;
        document.getElementById('pdfModal').classList.remove('hidden');
    }
    
    function closeModal() {
        document.getElementById('pdfModal').classList.add('hidden');
        document.getElementById('pdfIframe').src = '';
    }
    
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let searchText = this.value.toLowerCase();
        let rows = document.querySelectorAll('#proposalTableBody tr');
        
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            if (text.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    
    // Close modal with ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
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