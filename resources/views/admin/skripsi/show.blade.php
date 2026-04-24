{{-- resources/views/admin/skripsi/show.blade.php --}}

@extends('layouts.admin.app')

@section('title', 'Detail Skripsi - ' . $skripsi->nama_mahasiswa)

@section('head')
<style>
    .file-card {
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
    }
    .file-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .file-icon {
        font-size: 3rem;
        color: #dc3545;
    }
    .preview-modal {
        z-index: 1050;
    }
    .pdf-preview {
        height: 80vh;
        width: 100%;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <a href="{{ route('admin.skripsi.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
        </a>
        <h4 class="fw-bold mb-1">Detail Skripsi</h4>
        <p class="text-muted mb-0">Informasi lengkap dan file skripsi</p>
    </div>

    <div class="row">
        {{-- Informasi Skripsi --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <span class="fw-semibold"><i class="fas fa-info-circle me-2 text-primary"></i>Informasi Skripsi</span>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted fw-semibold" style="width: 160px">Nama Mahasiswa</td>
                            <td class="fw-semibold">{{ $skripsi->nama_mahasiswa }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">NIM</td>
                            <td>{{ $skripsi->nim ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Angkatan</td>
                            <td>{{ $skripsi->angkatan ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Judul Skripsi</td>
                            <td class="text-wrap">{{ $skripsi->judul_skripsi }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Pembimbing 1</td>
                            <td>
                                {{ $skripsi->raw_nama_pembimbing1 ?: ($skripsi->dosenPembimbing1->nama ?? '-') }}
                                @if($skripsi->raw_nip_pembimbing1)
                                    <br><small class="text-muted">NIP: {{ $skripsi->raw_nip_pembimbing1 }}</small>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Pembimbing 2</td>
                            <td>
                                {{ $skripsi->raw_nama_pembimbing2 ?: ($skripsi->dosenPembimbing2->nama ?? '-') }}
                                @if($skripsi->raw_nip_pembimbing2)
                                    <br><small class="text-muted">NIP: {{ $skripsi->raw_nip_pembimbing2 }}</small>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Source</td>
                            <td>
                                <span class="badge bg-info">{{ $skripsi->source ?: 'manual' }}</span>
                                @if($skripsi->pendaftaran_id)
                                    <br><small class="text-muted">Pendaftaran ID: {{ $skripsi->pendaftaran_id }}</small>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- File Files --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <span class="fw-semibold"><i class="fas fa-file-alt me-2 text-primary"></i>File Dokumen</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        {{-- PERBAIKAN: Hanya menampilkan tombol preview jika file tersedia --}}
                        
                        {{-- File Skripsi --}}
                        <div class="col-md-4">
                            <div class="card file-card border-0 shadow-sm text-center p-3">
                                <div class="file-icon">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="mt-2">
                                    <strong>File Skripsi</strong>
                                </div>
                                @if($files['skripsi'])
                                    <span class="badge bg-success mt-2">Tersedia</span>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="previewFile({{ $skripsi->id }}, 'skripsi')">
                                            <i class="fas fa-eye me-1"></i> Preview
                                        </button>
                                        <a href="{{ route('admin.skripsi.download', ['skripsi' => $skripsi->id, 'fileType' => 'skripsi']) }}" 
                                           class="btn btn-sm btn-outline-primary w-100">
                                            <i class="fas fa-download me-1"></i> Download
                                        </a>
                                    </div>
                                @else
                                    <span class="badge bg-secondary mt-2">Tidak tersedia</span>
                                @endif
                            </div>
                        </div>

                        {{-- File SK Pembimbing --}}
                        <div class="col-md-4">
                            <div class="card file-card border-0 shadow-sm text-center p-3">
                                <div class="file-icon">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="mt-2">
                                    <strong>SK Pembimbing</strong>
                                </div>
                                @if($files['sk_pembimbing'])
                                    <span class="badge bg-success mt-2">Tersedia</span>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="previewFile({{ $skripsi->id }}, 'sk_pembimbing')">
                                            <i class="fas fa-eye me-1"></i> Preview
                                        </button>
                                        <a href="{{ route('admin.skripsi.download', ['skripsi' => $skripsi->id, 'fileType' => 'sk_pembimbing']) }}" 
                                           class="btn btn-sm btn-outline-primary w-100">
                                            <i class="fas fa-download me-1"></i> Download
                                        </a>
                                    </div>
                                @else
                                    <span class="badge bg-secondary mt-2">Tidak tersedia</span>
                                @endif
                            </div>
                        </div>

                        {{-- File Proposal --}}
                        <div class="col-md-4">
                            <div class="card file-card border-0 shadow-sm text-center p-3">
                                <div class="file-icon">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="mt-2">
                                    <strong>Proposal</strong>
                                </div>
                                @if($files['proposal'])
                                    <span class="badge bg-success mt-2">Tersedia</span>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="previewFile({{ $skripsi->id }}, 'proposal')">
                                            <i class="fas fa-eye me-1"></i> Preview
                                        </button>
                                        <a href="{{ route('admin.skripsi.download', ['skripsi' => $skripsi->id, 'fileType' => 'proposal']) }}" 
                                           class="btn btn-sm btn-outline-primary w-100">
                                            <i class="fas fa-download me-1"></i> Download
                                        </a>
                                    </div>
                                @else
                                    <span class="badge bg-secondary mt-2">Tidak tersedia</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if(!$files['skripsi'] && !$files['sk_pembimbing'] && !$files['proposal'])
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Tidak ada file yang tersedia</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Modal Preview PDF --}}
<div class="modal fade preview-modal" id="pdfPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="pdfPreviewFrame" class="pdf-preview" src=""></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="#" id="downloadLink" class="btn btn-primary">
                    <i class="fas fa-download me-1"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // PERBAIKAN: Fungsi preview dengan parameter yang benar
    function previewFile(skripsiId, fileType) {
        // Buat URL secara manual menggunakan base URL
        const previewUrl = `/admin/skripsi/${skripsiId}/preview/${fileType}`;
        const downloadUrl = `/admin/skripsi/${skripsiId}/download/${fileType}`;
        
        console.log('Preview URL:', previewUrl);
        
        // Set iframe source
        document.getElementById('pdfPreviewFrame').src = previewUrl;
        document.getElementById('downloadLink').href = downloadUrl;
        
        // Tampilkan modal
        const modal = new bootstrap.Modal(document.getElementById('pdfPreviewModal'));
        modal.show();
    }
</script>
@endsection