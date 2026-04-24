{{-- resources/views/admin/file/skripsi/index.blade.php --}}

@extends('layouts.admin.app')

@section('title', 'File Skripsi')

@section('content')
<div class="container-fluid px-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-file-pdf text-danger me-2"></i>File Skripsi
            </h4>
            <p class="text-muted mb-0">Daftar file skripsi mahasiswa yang tersedia di repository</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-danger bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-file-pdf text-danger fs-4"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-danger">{{ number_format($stats['total']) }}</div>
                            <div class="text-muted small">Total File Skripsi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-cloud-upload-alt text-primary fs-4"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-primary">{{ number_format($stats['from_presma']) }}</div>
                            <div class="text-muted small">Dari Presma</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-check-circle text-success fs-4"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-success">{{ number_format($stats['total']) }}</div>
                            <div class="text-muted small">Aktif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Search --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.file.skripsi.index') }}" class="row g-3">
                <div class="col-md-10">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="Cari nama mahasiswa atau NIM..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-semibold">
                    <i class="fas fa-list me-2 text-primary"></i>Daftar File Skripsi
                </span>
                <span class="badge bg-secondary">{{ $skripsiList->total() }} data</span>
            </div>
        </div>
        <div class="card-body p-0">
            @if($skripsiList->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Belum ada file skripsi yang tersedia.</p>
                    <small class="text-muted">File skripsi akan muncul setelah sync dari e-Service</small>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            </tr>
                                <th class="ps-4" width="50">#</th>
                                <th>Mahasiswa</th>
                                <th>NIM</th>
                                <th>Judul Skripsi</th>
                                <th>Sumber</th>
                                <th>Sync Date</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($skripsiList as $index => $skripsi)
                            <tr>
                                <td class="ps-4 text-muted small fw-semibold">
                                    {{ $skripsiList->firstItem() + $index }}
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $skripsi->nama_mahasiswa }}</div>
                                    <small class="text-muted">{{ $skripsi->angkatan ?: '-' }}</small>
                                </td>
                                <td><code>{{ $skripsi->nim ?: '-' }}</code></td>
                                <td>
                                    <div class="text-wrap" style="max-width: 300px;">
                                        <small>{{ Str::limit($skripsi->judul_skripsi, 60) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $skripsi->source == 'presma' ? 'bg-primary' : 'bg-secondary' }}">
                                        {{ $skripsi->source ?: 'manual' }}
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    {{ $skripsi->last_synced_at ? $skripsi->last_synced_at->format('d/m/Y') : '-' }}
                                </td>
                                <td class="text-center pe-4">
                                    <a href="{{ route('admin.file.skripsi.show', $skripsi) }}" 
                                       class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.file.skripsi.download', $skripsi) }}" 
                                       class="btn btn-sm btn-outline-success" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

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