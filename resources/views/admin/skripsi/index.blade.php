{{-- resources/views/admin/skripsi/index.blade.php --}}

@extends('layouts.admin.app')

@section('title', 'Data Skripsi')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Data Skripsi</h4>
            <p class="text-muted mb-0">Data skripsi yang diterima dari API sync</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded p-3 me-3">
                            <i class="fas fa-graduation-cap text-primary fs-5"></i>
                        </div>
                        <div>
                            <div class="fs-4 fw-bold">{{ $stats['total'] }}</div>
                            <div class="text-muted small">Total Skripsi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 rounded p-3 me-3">
                            <i class="fas fa-cloud-upload-alt text-success fs-5"></i>
                        </div>
                        <div>
                            <div class="fs-4 fw-bold">{{ $stats['from_presma'] }}</div>
                            <div class="text-muted small">Dari Presma</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info bg-opacity-10 rounded p-3 me-3">
                            <i class="fas fa-file-pdf text-info fs-5"></i>
                        </div>
                        <div>
                            <div class="fs-4 fw-bold">{{ $stats['with_files'] }}</div>
                            <div class="text-muted small">Dengan File</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-warning bg-opacity-10 rounded p-3 me-3">
                            <i class="fas fa-sync-alt text-warning fs-5"></i>
                        </div>
                        <div>
                            <div class="fs-4 fw-bold">{{ $stats['synced_today'] }}</div>
                            <div class="text-muted small">Sync Hari Ini</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.skripsi.index') }}" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small text-muted mb-1">Cari</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="Nama Mahasiswa, NIM, atau Judul..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Source</label>
                    <select name="source" class="form-select">
                        <option value="">Semua Source</option>
                        <option value="presma" {{ request('source') == 'presma' ? 'selected' : '' }}>Presma</option>
                        <option value="manual" {{ request('source') == 'manual' ? 'selected' : '' }}>Manual</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.skripsi.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-redo me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
            <span class="fw-semibold">Daftar Skripsi</span>
            <span class="text-muted small">{{ $skripsiList->total() }} data ditemukan</span>
        </div>
        <div class="card-body p-0">
            @if($skripsiList->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data skripsi yang tersedia.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Mahasiswa</th>
                                <th>Judul Skripsi</th>
                                <th>Pembimbing 1</th>
                                <th>Pembimbing 2</th>
                                <th>Files</th>
                                <th>Source</th>
                                <th>Sync Date</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($skripsiList as $index => $skripsi)
                            <tr>
                                <td class="ps-4 text-muted small">{{ $skripsiList->firstItem() + $index }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $skripsi->nama_mahasiswa }}</div>
                                    <small class="text-muted">NIM: {{ $skripsi->nim ?: '-' }}</small>
                                    @if($skripsi->angkatan)
                                        <br><small class="text-muted">Angkatan: {{ $skripsi->angkatan }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-wrap" style="max-width: 300px;">
                                        {{ Str::limit($skripsi->judul_skripsi, 60) }}
                                    </div>
                                </td>
                                <td>
                                    {{ $skripsi->raw_nama_pembimbing1 ?: ($skripsi->dosenPembimbing1->nama ?? '-') }}
                                </td>
                                <td>
                                    {{ $skripsi->raw_nama_pembimbing2 ?: ($skripsi->dosenPembimbing2->nama ?? '-') }}
                                </td>
                                <td>
                                    @php
                                        $hasSkripsi = $skripsi->file_skripsi && Storage::exists($skripsi->file_skripsi);
                                        $hasSK = $skripsi->file_sk_pembimbing && Storage::exists($skripsi->file_sk_pembimbing);
                                        $hasProposal = $skripsi->file_proposal && Storage::exists($skripsi->file_proposal);
                                        $totalFiles = ($hasSkripsi ? 1 : 0) + ($hasSK ? 1 : 0) + ($hasProposal ? 1 : 0);
                                    @endphp
                                    <span class="badge {{ $totalFiles > 0 ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $totalFiles }} file
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $skripsi->source ?: 'manual' }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $skripsi->last_synced_at ? $skripsi->last_synced_at->format('d/m/Y H:i') : '-' }}
                                    </small>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.skripsi.show', $skripsi) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($skripsiList->hasPages())
                    <div class="px-4 py-3 border-top">
                        {{ $skripsiList->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

</div>
@endsection