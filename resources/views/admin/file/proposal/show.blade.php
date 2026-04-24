{{-- resources/views/admin/file/proposal/show.blade.php --}}

@extends('layouts.admin.app')

@section('title', 'Detail Proposal - ' . $skripsi->nama_mahasiswa)

@section('content')
<div class="container-fluid px-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.file.proposal.index') }}">Proposal</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.file.proposal.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <h4 class="fw-bold mb-1">
                <i class="fas fa-file-word text-warning me-2"></i>Detail Proposal
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <span class="fw-semibold"><i class="fas fa-info-circle me-2 text-primary"></i>Informasi Proposal</span>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr><th class="text-muted" width="140">Mahasiswa</th><td>{{ $skripsi->nama_mahasiswa }}</td></tr>
                        <tr><th class="text-muted">NIM</th><td><code>{{ $skripsi->nim ?: '-' }}</code></td></tr>
                        <tr><th class="text-muted">Judul Skripsi</th><td>{{ Str::limit($skripsi->judul_skripsi, 60) }}</td></tr>
                        <tr><th class="text-muted">Sumber</th><td><span class="badge bg-primary">{{ $skripsi->source ?: 'manual' }}</span></td></tr>
                        <tr><th class="text-muted">Sync Terakhir</th><td>{{ $skripsi->last_synced_at ? $skripsi->last_synced_at->format('d F Y') : '-' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <span class="fw-semibold"><i class="fas fa-file-pdf text-danger me-2"></i>Preview Proposal</span>
                </div>
                <div class="card-body">
                    @if($fileExists)
                        <div class="text-center mb-4">
                            <i class="fas fa-file-word text-warning" style="font-size: 4rem;"></i>
                            <h5 class="mt-2">Proposal Skripsi</h5>
                        </div>
                        <div class="d-flex justify-content-center gap-3 mb-4">
                            <button type="button" class="btn btn-warning" onclick="previewPDF()">
                                <i class="fas fa-eye me-2"></i> Preview
                            </button>
                            <a href="{{ route('admin.file.proposal.download', $skripsi) }}" class="btn btn-outline-warning">
                                <i class="fas fa-download me-2"></i> Download
                            </a>
                        </div>
                        <div class="border rounded-3 overflow-hidden">
                            <iframe src="{{ route('admin.file.proposal.preview', $skripsi) }}" 
                                    style="width: 100%; height: 400px; border: none;"></iframe>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-word fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">File Tidak Ditemukan</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pdfModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Proposal - {{ $skripsi->nama_mahasiswa }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="fullscreenPdf" src="" style="width: 100%; height: 70vh; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    function previewPDF() {
        document.getElementById('fullscreenPdf').src = "{{ route('admin.file.proposal.preview', $skripsi) }}";
        new bootstrap.Modal(document.getElementById('pdfModal')).show();
    }
</script>
@endsection