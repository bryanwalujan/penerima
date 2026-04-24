{{-- resources/views/admin/file/sk-pembimbing/index.blade.php --}}

@extends('layouts.admin.app')

@section('title', 'SK Pembimbing')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-file-alt text-primary me-2"></i>SK Pembimbing
            </h4>
            <p class="text-muted mb-0">Daftar Surat Keputusan pembimbing skripsi mahasiswa</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-file-alt text-primary fs-4"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-primary">{{ number_format($stats['total']) }}</div>
                            <div class="text-muted small">Total SK Pembimbing</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-cloud-upload-alt text-info fs-4"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-info">{{ number_format($stats['from_presma']) }}</div>
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
                        <div class="bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-gavel text-warning fs-4"></i>
                        </div>
                        <div>
                            <div class="fs-2 fw-bold text-warning">{{ number_format($stats['total']) }}</div>
                            <div class="text-muted small">Surat Aktif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.file.sk-pembimbing.index') }}" class="row g-3">
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

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-semibold">
                    <i class="fas fa-list me-2 text-primary"></i>Daftar SK Pembimbing
                </span>
                <span class="badge bg-secondary">{{ $skripsiList->total() }} data</span>
            </div>
        </div>
        <div class="card-body p-0">
            @if($skripsiList->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Belum ada SK Pembimbing yang tersedia.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Mahasiswa</th>
                                <th>NIM</th>
                                <th>Pembimbing 1</th>
                                <th>Pembimbing 2</th>
                                <th>Sumber</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($skripsiList as $index => $skripsi)
                            <tr>
                                <td class="ps-4 text-muted small">{{ $skripsiList->firstItem() + $index }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $skripsi->nama_mahasiswa }}</div>
                                    <small class="text-muted">{{ $skripsi->angkatan ?: '-' }}</small>
                                </td>
                                <td><code>{{ $skripsi->nim ?: '-' }}</code></td>
                                <td>{{ Str::limit($skripsi->raw_nama_pembimbing1 ?: ($skripsi->dosenPembimbing1->nama ?? '-'), 30) }}</td>
                                <td>{{ Str::limit($skripsi->raw_nama_pembimbing2 ?: ($skripsi->dosenPembimbing2->nama ?? '-'), 30) }}</td>
                                <td>
                                    <span class="badge {{ $skripsi->source == 'presma' ? 'bg-primary' : 'bg-secondary' }}">
                                        {{ $skripsi->source ?: 'manual' }}
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <a href="{{ route('admin.file.sk-pembimbing.show', $skripsi) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.file.sk-pembimbing.download', $skripsi) }}" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($skripsiList->hasPages())
                    <div class="px-4 py-3 border-top">{{ $skripsiList->links() }}</div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection