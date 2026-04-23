{{-- resources/views/admin/dosen-sync/show.blade.php --}}
{{-- Di REPODOSEN --}}

@extends('layouts.admin')

@section('title', 'Detail Dosen - ' . $dosen->nama)

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <a href="{{ route('admin.dosen-sync.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
        <h4 class="fw-bold mb-1">Detail Dosen</h4>
        <p class="text-muted mb-0">Data yang diterima via sync dari e-Service</p>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <span class="fw-semibold"><i class="fas fa-user-tie me-2 text-primary"></i>Informasi Dosen</span>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted fw-semibold" style="width: 130px">Nama</td>
                            <td>{{ $dosen->nama }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">NIP</td>
                            <td>
                                @if($dosen->nip)
                                    <code>{{ $dosen->nip }}</code>
                                @else
                                    <span class="text-muted fst-italic">Tidak ada</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">NIDN</td>
                            <td>
                                @if($dosen->nidn)
                                    <code>{{ $dosen->nidn }}</code>
                                @else
                                    <span class="text-muted fst-italic">Tidak ada</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Dibuat</td>
                            <td>{{ $dosen->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Diperbarui</td>
                            <td>{{ $dosen->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection