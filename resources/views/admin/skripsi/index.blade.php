{{-- resources/views/admin/skripsi/index.blade.php --}}

@extends('layouts.admin.app')

@section('title', 'Data Skripsi')

@section('content')
<div class="container-fluid px-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-graduation-cap me-2 text-primary"></i>Data Skripsi
            </h4>
            <p class="text-muted mb-0">Data skripsi yang diterima dari API sync e-Service Presma</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm hover-shadow transition">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-graduation-cap text-primary fs-4"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-primary">{{ number_format($stats['total']) }}</div>
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
                        <div class="flex-shrink-0 bg-success bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-cloud-upload-alt text-success fs-4"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-success">{{ number_format($stats['from_presma']) }}</div>
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
                        <div class="flex-shrink-0 bg-info bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-file-pdf text-info fs-4"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-info">{{ number_format($stats['with_files']) }}</div>
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
                        <div class="flex-shrink-0 bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-sync-alt text-warning fs-4"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-warning">{{ number_format($stats['synced_today']) }}</div>
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
            <form method="GET" action="{{ route('admin.skripsi.index') }}" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-semibold text-muted mb-1">
                        <i class="fas fa-search me-1"></i> Pencarian
                    </label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari nama mahasiswa, NIM, atau judul skripsi..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">
                        <i class="fas fa-filter me-1"></i> Sumber Data
                    </label>
                    <select name="source" class="form-select">
                        <option value="">Semua Source</option>
                        <option value="presma" {{ request('source') == 'presma' ? 'selected' : '' }}>📡 Presma</option>
                        <option value="manual" {{ request('source') == 'manual' ? 'selected' : '' }}>✍️ Manual</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.skripsi.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-undo-alt me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-semibold">
                    <i class="fas fa-list me-2 text-primary"></i>Daftar Skripsi
                </span>
                <span class="badge bg-secondary">{{ $skripsiList->total() }} data</span>
            </div>
        </div>
        <div class="card-body p-0">
            @if($skripsiList->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Belum ada data skripsi yang tersedia.</p>
                    <small class="text-muted">Data akan muncul setelah sync dari e-Service Presma</small>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" width="50">#</th>
                                <th>Mahasiswa</th>
                                <th>Judul Skripsi</th>
                                <th>Pembimbing 1</th>
                                <th>Pembimbing 2</th>
                                <th class="text-center">Files</th>
                                <th>Sumber</th>
                                <th>Sync Date</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($skripsiList as $index => $skripsi)
                            <tr>
                                <td class="ps-4 text-muted small fw-semibold">{{ $skripsiList->firstItem() + $index }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $skripsi->nama_mahasiswa }}</div>
                                    <small class="text-muted">
                                        <i class="fas fa-id-card me-1"></i>NIM: {{ $skripsi->nim ?: '-' }}
                                    </small>
                                    @if($skripsi->angkatan)
                                        <br><small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>Angkatan: {{ $skripsi->angkatan }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-wrap" style="max-width: 320px;">
                                        <span class="small">{{ Str::limit($skripsi->judul_skripsi, 70) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <small>{{ Str::limit($skripsi->raw_nama_pembimbing1 ?: ($skripsi->dosenPembimbing1->nama ?? '-'), 30) }}</small>
                                </td>
                                <td>
                                    <small>{{ Str::limit($skripsi->raw_nama_pembimbing2 ?: ($skripsi->dosenPembimbing2->nama ?? '-'), 30) }}</small>
                                </td>
                                <td class="text-center">
                                    @php
                                        $hasSkripsi = $skripsi->file_skripsi && Storage::disk('local')->exists($skripsi->file_skripsi);
                                        $hasSK = $skripsi->file_sk_pembimbing && Storage::disk('local')->exists($skripsi->file_sk_pembimbing);
                                        $hasProposal = $skripsi->file_proposal && Storage::disk('local')->exists($skripsi->file_proposal);
                                        $totalFiles = ($hasSkripsi ? 1 : 0) + ($hasSK ? 1 : 0) + ($hasProposal ? 1 : 0);
                                    @endphp
                                    <div class="d-flex justify-content-center gap-1">
                                        @if($hasSkripsi)
                                            <span class="badge bg-success" title="Skripsi"><i class="fas fa-file-pdf"></i> S</span>
                                        @endif
                                        @if($hasSK)
                                            <span class="badge bg-info" title="SK Pembimbing"><i class="fas fa-file-alt"></i> SK</span>
                                        @endif
                                        @if($hasProposal)
                                            <span class="badge bg-warning text-dark" title="Proposal"><i class="fas fa-file-word"></i> P</span>
                                        @endif
                                        @if($totalFiles == 0)
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </div>
                                    <div class="small text-muted mt-1">{{ $totalFiles }} file</div>
                                </td>
                                <td>
                                    <span class="badge {{ $skripsi->source == 'presma' ? 'bg-primary' : 'bg-secondary' }}">
                                        {{ $skripsi->source ?: 'manual' }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $skripsi->last_synced_at ? $skripsi->last_synced_at->format('d/m/Y H:i') : '-' }}
                                    </small>
                                </td>
                                <td class="text-center pe-4">
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

<style>
    .transition {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endsection