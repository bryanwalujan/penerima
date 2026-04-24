{{-- resources/views/admin/skripsi/show.blade.php --}}

@extends('layouts.admin.app')

@section('title', 'Detail Skripsi - ' . $skripsi->nama_mahasiswa)

@section('head')
<style>
    .file-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border-radius: 12px;
        overflow: hidden;
    }
    .file-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
    .file-icon {
        font-size: 3rem;
        transition: transform 0.3s ease;
    }
    .file-card:hover .file-icon {
        transform: scale(1.05);
    }
    .file-card.skripsi { border-top: 4px solid #dc3545; }
    .file-card.sk-pembimbing { border-top: 4px solid #0d6efd; }
    .file-card.proposal { border-top: 4px solid #ffc107; }
    
    .info-card {
        border-radius: 12px;
        overflow: hidden;
    }
    
    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    .nav-tabs .nav-link:hover {
        color: #0d6efd;
        background: transparent;
        border: none;
    }
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        background: transparent;
        border-bottom: 3px solid #0d6efd;
    }
    
    .preview-modal {
        z-index: 1050;
    }
    .pdf-preview {
        height: 80vh;
        width: 100%;
        border: none;
    }
    
    .badge-file {
        font-size: 0.7rem;
        padding: 0.3rem 0.6rem;
    }
    
    .file-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 0.75rem;
        margin-top: 0.5rem;
    }
    
    .detail-label {
        background: #f8f9fa;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-size: 0.85rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.skripsi.index') }}" class="text-decoration-none">Data Skripsi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Skripsi</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.skripsi.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>
            <h4 class="fw-bold mb-1">
                <i class="fas fa-file-alt me-2 text-primary"></i>Detail Skripsi
            </h4>
            <p class="text-muted mb-0">Informasi lengkap dan dokumen skripsi mahasiswa</p>
        </div>
        <div>
            <span class="badge bg-primary fs-6 px-3 py-2">
                <i class="fas fa-calendar-alt me-1"></i> 
                Sync: {{ $skripsi->last_synced_at ? $skripsi->last_synced_at->format('d M Y H:i') : '-' }}
            </span>
        </div>
    </div>

    <div class="row">
        {{-- Informasi Skripsi --}}
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm info-card h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <span class="fw-semibold">
                        <i class="fas fa-info-circle me-2 text-primary"></i>Informasi Skripsi
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-5 detail-label">
                            <i class="fas fa-user-graduate me-2 text-primary"></i>Nama Mahasiswa
                        </div>
                        <div class="col-md-7">
                            <span class="fw-semibold">{{ $skripsi->nama_mahasiswa }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-5 detail-label">
                            <i class="fas fa-id-card me-2 text-primary"></i>NIM
                        </div>
                        <div class="col-md-7">
                            <code>{{ $skripsi->nim ?: '-' }}</code>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-5 detail-label">
                            <i class="fas fa-calendar-alt me-2 text-primary"></i>Angkatan
                        </div>
                        <div class="col-md-7">
                            {{ $skripsi->angkatan ?: '-' }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-5 detail-label">
                            <i class="fas fa-book me-2 text-primary"></i>Judul Skripsi
                        </div>
                        <div class="col-md-7">
                            <span class="text-wrap">{{ $skripsi->judul_skripsi }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-5 detail-label">
                            <i class="fas fa-chalkboard-user me-2 text-primary"></i>Pembimbing 1
                        </div>
                        <div class="col-md-7">
                            <div>{{ $skripsi->raw_nama_pembimbing1 ?: ($skripsi->dosenPembimbing1->nama ?? '-') }}</div>
                            @if($skripsi->raw_nip_pembimbing1)
                                <small class="text-muted">NIP: {{ $skripsi->raw_nip_pembimbing1 }}</small>
                            @endif
                            @if($skripsi->match_status_pb1)
                                <br><span class="badge bg-success mt-1">✓ Match</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-5 detail-label">
                            <i class="fas fa-chalkboard-user me-2 text-primary"></i>Pembimbing 2
                        </div>
                        <div class="col-md-7">
                            <div>{{ $skripsi->raw_nama_pembimbing2 ?: ($skripsi->dosenPembimbing2->nama ?? '-') }}</div>
                            @if($skripsi->raw_nip_pembimbing2)
                                <small class="text-muted">NIP: {{ $skripsi->raw_nip_pembimbing2 }}</small>
                            @endif
                            @if($skripsi->match_status_pb2)
                                <br><span class="badge bg-success mt-1">✓ Match</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-5 detail-label">
                            <i class="fas fa-database me-2 text-primary"></i>Sumber Data
                        </div>
                        <div class="col-md-7">
                            <span class="badge {{ $skripsi->source == 'presma' ? 'bg-primary' : 'bg-secondary' }}">
                                {{ $skripsi->source ?: 'manual' }}
                            </span>
                            @if($skripsi->pendaftaran_id)
                                <br><small class="text-muted">Pendaftaran ID: {{ $skripsi->pendaftaran_id }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- File Dokumen dengan Tabs --}}
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm info-card h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <span class="fw-semibold">
                        <i class="fas fa-folder-open me-2 text-primary"></i>Dokumen Skripsi
                    </span>
                </div>
                <div class="card-body">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs mb-4" id="fileTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="skripsi-tab" data-bs-toggle="tab" data-bs-target="#skripsi" type="button" role="tab">
                                <i class="fas fa-file-pdf text-danger me-2"></i>File Skripsi
                                @if($files['skripsi'])
                                    <span class="badge bg-success ms-2">✓</span>
                                @else
                                    <span class="badge bg-secondary ms-2">✗</span>
                                @endif
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="sk-pembimbing-tab" data-bs-toggle="tab" data-bs-target="#sk-pembimbing" type="button" role="tab">
                                <i class="fas fa-file-alt text-primary me-2"></i>SK Pembimbing
                                @if($files['sk_pembimbing'])
                                    <span class="badge bg-success ms-2">✓</span>
                                @else
                                    <span class="badge bg-secondary ms-2">✗</span>
                                @endif
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="proposal-tab" data-bs-toggle="tab" data-bs-target="#proposal" type="button" role="tab">
                                <i class="fas fa-file-word text-warning me-2"></i>Proposal
                                @if($files['proposal'])
                                    <span class="badge bg-success ms-2">✓</span>
                                @else
                                    <span class="badge bg-secondary ms-2">✗</span>
                                @endif
                            </button>
                        </li>
                    </ul>

                    <!-- Tabs Content -->
                    <div class="tab-content" id="fileTabContent">
                        <!-- Tab Skripsi -->
                        <div class="tab-pane fade show active" id="skripsi" role="tabpanel">
                            @if($files['skripsi'])
                                <div class="text-center py-4">
                                    <div class="file-icon mb-3">
                                        <i class="fas fa-file-pdf text-danger" style="font-size: 5rem;"></i>
                                    </div>
                                    <h5 class="mb-2">File Skripsi</h5>
                                    <p class="text-muted small mb-3">
                                        File skripsi lengkap mahasiswa atas nama <strong>{{ $skripsi->nama_mahasiswa }}</strong>
                                    </p>
                                    <div class="d-flex justify-content-center gap-3">
                                        <button type="button" class="btn btn-primary px-4" onclick="previewFile({{ $skripsi->id }}, 'skripsi')">
                                            <i class="fas fa-eye me-2"></i> Preview
                                        </button>
                                        <a href="{{ route('admin.skripsi.download', ['skripsi' => $skripsi->id, 'fileType' => 'skripsi']) }}" 
                                           class="btn btn-outline-primary px-4">
                                            <i class="fas fa-download me-2"></i> Download
                                        </a>
                                    </div>
                                    <div class="file-info mt-4">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i> 
                                            Klik Preview untuk melihat file langsung di browser, atau Download untuk menyimpan file.
                                        </small>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-file-pdf fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">File Skripsi Tidak Tersedia</h5>
                                    <p class="text-muted small mb-0">
                                        Belum ada file skripsi yang diupload untuk mahasiswa ini.
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Tab SK Pembimbing -->
                        <div class="tab-pane fade" id="sk-pembimbing" role="tabpanel">
                            @if($files['sk_pembimbing'])
                                <div class="text-center py-4">
                                    <div class="file-icon mb-3">
                                        <i class="fas fa-file-alt text-primary" style="font-size: 5rem;"></i>
                                    </div>
                                    <h5 class="mb-2">SK Pembimbing</h5>
                                    <p class="text-muted small mb-3">
                                        Surat Keputusan pembimbing skripsi untuk mahasiswa <strong>{{ $skripsi->nama_mahasiswa }}</strong>
                                    </p>
                                    <div class="d-flex justify-content-center gap-3">
                                        <button type="button" class="btn btn-primary px-4" onclick="previewFile({{ $skripsi->id }}, 'sk_pembimbing')">
                                            <i class="fas fa-eye me-2"></i> Preview
                                        </button>
                                        <a href="{{ route('admin.skripsi.download', ['skripsi' => $skripsi->id, 'fileType' => 'sk_pembimbing']) }}" 
                                           class="btn btn-outline-primary px-4">
                                            <i class="fas fa-download me-2"></i> Download
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">SK Pembimbing Tidak Tersedia</h5>
                                    <p class="text-muted small mb-0">
                                        Belum ada SK pembimbing yang diupload untuk mahasiswa ini.
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Tab Proposal -->
                        <div class="tab-pane fade" id="proposal" role="tabpanel">
                            @if($files['proposal'])
                                <div class="text-center py-4">
                                    <div class="file-icon mb-3">
                                        <i class="fas fa-file-word text-warning" style="font-size: 5rem;"></i>
                                    </div>
                                    <h5 class="mb-2">Proposal Skripsi</h5>
                                    <p class="text-muted small mb-3">
                                        Proposal skripsi mahasiswa <strong>{{ $skripsi->nama_mahasiswa }}</strong>
                                    </p>
                                    <div class="d-flex justify-content-center gap-3">
                                        <button type="button" class="btn btn-primary px-4" onclick="previewFile({{ $skripsi->id }}, 'proposal')">
                                            <i class="fas fa-eye me-2"></i> Preview
                                        </button>
                                        <a href="{{ route('admin.skripsi.download', ['skripsi' => $skripsi->id, 'fileType' => 'proposal']) }}" 
                                           class="btn btn-outline-primary px-4">
                                            <i class="fas fa-download me-2"></i> Download
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-file-word fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">Proposal Tidak Tersedia</h5>
                                    <p class="text-muted small mb-0">
                                        Belum ada proposal yang diupload untuk mahasiswa ini.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Modal Preview PDF --}}
<div class="modal fade preview-modal" id="pdfPreviewModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="fas fa-file-pdf text-danger me-2"></i>Preview File
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe id="pdfPreviewFrame" class="pdf-preview" src="" style="min-height: 500px;"></iframe>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Tutup
                </button>
                <a href="#" id="downloadLink" class="btn btn-primary">
                    <i class="fas fa-download me-1"></i> Download File
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function previewFile(skripsiId, fileType) {
        const previewUrl = `/admin/skripsi/${skripsiId}/preview/${fileType}`;
        const downloadUrl = `/admin/skripsi/${skripsiId}/download/${fileType}`;
        
        console.log('Preview URL:', previewUrl);
        
        const iframe = document.getElementById('pdfPreviewFrame');
        iframe.src = previewUrl;
        
        document.getElementById('downloadLink').href = downloadUrl;
        
        const modal = new bootstrap.Modal(document.getElementById('pdfPreviewModal'));
        modal.show();
    }
</script>
@endsection