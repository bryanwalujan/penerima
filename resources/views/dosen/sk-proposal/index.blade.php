{{-- resources/views/dosen/sk-proposal/index.blade.php --}}

@extends('layouts.dosen.app')

@section('title', 'SK Proposal - ' . $dosen->nama)

@section('header-title', 'SK Proposal Mahasiswa')
@section('header-subtitle', 'Daftar Surat Keputusan Proposal mahasiswa bimbingan Anda')

@section('content')
<div class="container-fluid px-4">
    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-users text-primary fs-4"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-primary">{{ $stats['total_mahasiswa'] }}</div>
                            <div class="text-muted small">Total Mahasiswa Bimbingan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-file-alt text-info fs-4"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-info">{{ $stats['total_file'] }}</div>
                            <div class="text-muted small">Total SK Proposal</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-chalkboard-user text-success fs-4"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-success">{{ $stats['as_pembimbing_1'] }}</div>
                            <div class="text-muted small">Sebagai Pembimbing 1</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-chalkboard-user text-warning fs-4"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-warning">{{ $stats['as_pembimbing_2'] }}</div>
                            <div class="text-muted small">Sebagai Pembimbing 2</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control border-start-0" 
                               placeholder="Cari mahasiswa (Nama atau NIM)...">
                    </div>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary w-100" onclick="searchMahasiswa()">
                        <i class="fas fa-search me-1"></i> Cari
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-semibold">
                    <i class="fas fa-list me-2 text-primary"></i>Daftar SK Proposal
                </span>
                <span class="badge bg-secondary">{{ $skripsiList->count() }} data</span>
            </div>
        </div>
        <div class="card-body p-0">
            @if($skripsiList->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Belum ada SK Proposal yang tersedia.</p>
                    <p class="text-muted small mt-1">SK Proposal akan muncul setelah disinkronkan dari e-Service</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="skProposalTable">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" width="50">No</th>
                                <th>NIM</th>
                                <th>Nama Mahasiswa</th>
                                <th>Judul Skripsi</th>
                                <th>Nomor SK</th>
                                <th>Peran Anda</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($skripsiList as $index => $skripsi)
                                @php
                                    $peran = '';
                                    $badgeColor = '';
                                    if($dosen->id == $skripsi->dosen_pembimbing1_id) {
                                        $peran = 'Pembimbing 1';
                                        $badgeColor = 'bg-primary';
                                    } elseif($dosen->id == $skripsi->dosen_pembimbing2_id) {
                                        $peran = 'Pembimbing 2';
                                        $badgeColor = 'bg-success';
                                    }
                                    $nomorSk = explode(' | ', $skripsi->raw_nama_pembimbing1 ?? '')[0];
                                @endphp
                                <tr>
                                    <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                                    <td><code>{{ $skripsi->nim ?: '-' }}</code></td>
                                    <td class="fw-semibold">{{ $skripsi->nama_mahasiswa }}</td>
                                    <td>
                                        <div class="text-wrap" style="max-width: 300px;">
                                            {{ Str::limit($skripsi->judul_skripsi, 50) }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $nomorSk ?: '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $badgeColor }}">{{ $peran }}</span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('dosen.sk-proposal.download', $skripsi) }}" 
                                               class="btn btn-sm btn-outline-success" title="Download SK">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function searchMahasiswa() {
        let input = document.getElementById('searchInput');
        let filter = input.value.toLowerCase();
        let table = document.getElementById('skProposalTable');
        let rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            let cells = rows[i].getElementsByTagName('td');
            if (cells.length > 0) {
                let nim = cells[1]?.innerText.toLowerCase() || '';
                let nama = cells[2]?.innerText.toLowerCase() || '';
                let judul = cells[3]?.innerText.toLowerCase() || '';
                
                if (nama.includes(filter) || nim.includes(filter) || judul.includes(filter)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }
    }
    
    document.getElementById('searchInput').addEventListener('keyup', searchMahasiswa);
</script>
@endsection