{{-- resources/views/admin/file/skripsi/show.blade.php --}}

@extends('layouts.admin.app')

@section('title', 'Detail File Skripsi - ' . $skripsi->nama_mahasiswa)

@section('content')
<div class="container-fluid px-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.file.skripsi.index') }}">File Skripsi</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.file.skripsi.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <h4 class="fw-bold mb-1">
                <i class="fas fa-file-pdf text-danger me-2"></i>Detail File Skripsi
            </h4>
            <p class="text-muted mb-0">Informasi lengkap dan file skripsi mahasiswa</p>
        </div>
    </div>

    <div class="row">
        {{-- Informasi Mahasiswa --}}
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <span class="fw-semibold">
                        <i class="fas fa-user-graduate me-2 text-primary"></i>Informasi Mahasiswa
                    </span>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted fw-semibold" style="width: 140px">Nama Lengkap</td>
                            <td class="fw-semibold">{{ $skripsi->nama_mahasiswa }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">NIM</td>
                            <td><code>{{ $skripsi->nim ?: '-' }}</code></td>
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
                            <td>{{ $skripsi->raw_nama_pembimbing1 ?: ($skripsi->dosenPembimbing1->nama ?? '-') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Pembimbing 2</td>
                            <td>{{ $skripsi->raw_nama_pembimbing2 ?: ($skripsi->dosenPembimbing2->nama ?? '-') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Sumber Data</td>
                            <td>
                                <span class="badge {{ $skripsi->source == 'presma' ? 'bg-primary' : 'bg-secondary' }}">
                                    {{ $skripsi->source ?: 'manual' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Sync Terakhir</td>
                            <td>{{ $skripsi->last_synced_at ? $skripsi->last_synced_at->format('d F Y H:i:s') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- File Preview --}}
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <span class="fw-semibold">
                        <i class="fas fa-file-pdf text-danger me-2"></i>Preview File Skripsi
                    </span>
                </div>
                <div class="card-body">
                    @if($fileExists)
                        <div class="text-center mb-4">
                            <div class="file-icon mb-3">
                                <i class="fas fa-file-pdf text-danger" style="font-size: 5rem;"></i>
                            </div>
                            <h5>{{ $skripsi->nama_mahasiswa }} - Skripsi</h5>
                            <p class="text-muted small">
                                File skripsi lengkap mahasiswa a.n. <strong>{{ $skripsi->nama_mahasiswa }}</strong>
                            </p>
                        </div>

                        <div class="d-flex justify-content-center gap-3 mb-4">
                            <button type="button" class="btn btn-primary px-4" onclick="previewPDF()">
                                <i class="fas fa-eye me-2"></i> Preview Fullscreen
                            </button>
                            <a href="{{ route('admin.file.skripsi.download', $skripsi) }}" class="btn btn-outline-primary px-4">
                                <i class="fas fa-download me-2"></i> Download File
                            </a>
                        </div>

                        {{-- Embedded PDF Preview --}}
                        <div class="border rounded-3 overflow-hidden" style="background: #f8f9fa;">
                            <div class="ratio ratio-16x9">
                                <iframe src="{{ route('admin.file.skripsi.preview', $skripsi) }}" 
                                        style="border: none; width: 100%; height: 500px;"
                                        class="rounded-3">
                                </iframe>
                            </div>
                        </div>
                        
                        <div class="file-info mt-3 p-3 bg-light rounded-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i> 
                                File ditampilkan dalam mode preview. Klik tombol Preview Fullscreen untuk melihat dalam ukuran penuh, 
                                atau Download untuk menyimpan file ke komputer.
                            </small>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-pdf fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">File Tidak Ditemukan</h5>
                            <p class="text-muted small mb-0">
                                File skripsi tidak tersedia atau telah dihapus dari storage.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Fullscreen PDF --}}
<div class="modal fade" id="pdfModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="fas fa-file-pdf text-danger me-2"></i>
                    {{ $skripsi->nama_mahasiswa }} - Skripsi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="fullscreenPdf" src="" style="width: 100%; height: calc(100vh - 60px); border: none;"></iframe>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Tutup
                </button>
                <a href="{{ route('admin.file.skripsi.download', $skripsi) }}" class="btn btn-primary">
                    <i class="fas fa-download me-1"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function previewPDF() {
        const previewUrl = "{{ route('admin.file.skripsi.preview', $skripsi) }}";
        document.getElementById('fullscreenPdf').src = previewUrl;
        new bootstrap.Modal(document.getElementById('pdfModal')).show();
    }
</script>
@endsection