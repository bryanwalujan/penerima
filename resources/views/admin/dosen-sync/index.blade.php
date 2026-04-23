{{-- resources/views/admin/dosen-sync/index.blade.php --}}
{{-- Di REPODOSEN --}}

@extends('layouts.admin')

@section('title', 'Data Dosen Sync')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Data Dosen Pembimbing</h4>
            <p class="text-muted mb-0">Data yang masuk melalui sync dari e-Service Presma</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded p-3 me-3">
                            <i class="fas fa-users text-primary fs-5"></i>
                        </div>
                        <div>
                            <div class="fs-4 fw-bold">{{ $stats['total'] }}</div>
                            <div class="text-muted small">Total Dosen</div>
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
                            <i class="fas fa-id-card text-success fs-5"></i>
                        </div>
                        <div>
                            <div class="fs-4 fw-bold">{{ $stats['dengan_nip'] }}</div>
                            <div class="text-muted small">Dengan NIP</div>
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
                            <i class="fas fa-fingerprint text-info fs-5"></i>
                        </div>
                        <div>
                            <div class="fs-4 fw-bold">{{ $stats['dengan_nidn'] }}</div>
                            <div class="text-muted small">Dengan NIDN</div>
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
                            <i class="fas fa-exclamation-triangle text-warning fs-5"></i>
                        </div>
                        <div>
                            <div class="fs-4 fw-bold">{{ $stats['tanpa_nip'] }}</div>
                            <div class="text-muted small">Tanpa NIP</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.dosen-sync.index') }}" class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label class="form-label small text-muted mb-1">Cari Dosen</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input
                            type="text"
                            name="search"
                            class="form-control border-start-0"
                            placeholder="Nama, NIP, atau NIDN..."
                            value="{{ request('search') }}"
                        >
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Cari
                    </button>
                </div>
                @if(request('search'))
                <div class="col-md-2">
                    <a href="{{ route('admin.dosen-sync.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times me-1"></i> Reset
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
            <span class="fw-semibold">
                Daftar Dosen
                @if(request('search'))
                    <span class="badge bg-secondary ms-2">Filter aktif</span>
                @endif
            </span>
            <span class="text-muted small">{{ $dosens->total() }} data ditemukan</span>
        </div>
        <div class="card-body p-0">
            @if($dosens->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">
                        @if(request('search'))
                            Tidak ada dosen yang cocok dengan pencarian.
                        @else
                            Belum ada data dosen yang masuk via sync.
                        @endif
                    </p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 50px">#</th>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>NIDN</th>
                                <th>Terakhir Diperbarui</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dosens as $index => $dosen)
                            <tr>
                                <td class="ps-4 text-muted small">
                                    {{ $dosens->firstItem() + $index }}
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $dosen->nama }}</div>
                                </td>
                                <td>
                                    @if($dosen->nip)
                                        <code class="small">{{ $dosen->nip }}</code>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($dosen->nidn)
                                        <code class="small">{{ $dosen->nidn }}</code>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    {{ $dosen->updated_at->format('d M Y, H:i') }}
                                    <br>
                                    <span class="text-muted" style="font-size: 0.75rem">
                                        {{ $dosen->updated_at->diffForHumans() }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.dosen-sync.show', $dosen) }}"
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
                @if($dosens->hasPages())
                <div class="px-4 py-3 border-top">
                    {{ $dosens->links() }}
                </div>
                @endif
            @endif
        </div>
    </div>

</div>
@endsection