{{-- resources/views/dosen/pengabdian/edit.blade.php --}}
@extends('layouts.dosen.app')

@section('title', 'Edit Pengabdian - Repositori Dosen')

@section('header-title', 'Edit Pengabdian')

@section('styles')
<style>
    .form-card {
        transition: all 0.3s ease;
        border-radius: 20px;
    }
    .form-card:hover {
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
    }
    .section-header {
        transition: all 0.2s ease;
        border-radius: 20px 20px 0 0;
    }
    .input-group {
        transition: all 0.2s ease;
    }
    .input-group:focus-within {
        transform: translateY(-1px);
    }
    .input-field, .select-field {
        transition: all 0.2s ease;
        border-radius: 12px;
    }
    .input-field:focus, .select-field:focus {
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }
    .community-item {
        transition: all 0.3s ease;
        border-radius: 16px;
    }
    .community-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }
    .btn-action {
        transition: all 0.3s ease;
        border-radius: 12px;
        font-weight: 600;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    .btn-cancel {
        transition: all 0.3s ease;
    }
    .btn-cancel:hover {
        transform: translateY(-2px);
        background-color: #f3f4f6;
    }
</style>
@endsection

@section('content')
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-hands-helping text-yellow-600 mr-3"></i>
                Edit Pengabdian
            </h1>
            <p class="text-gray-600 mt-2">
                <i class="fas fa-info-circle mr-2 text-yellow-500"></i>
                Kelola data pengabdian kepada masyarakat
            </p>
        </div>
        <a href="{{ route('dosen.dashboard') }}" 
           class="inline-flex items-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all duration-200 text-gray-700 font-medium btn-cancel">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

@if ($errors->any())
    <div class="bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 p-5 mb-6 rounded-xl shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-semibold text-red-800">Terdapat {{ $errors->count() }} kesalahan dalam pengisian form</h3>
                <div class="mt-2 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif

<form method="POST" action="{{ route('dosen.pengabdian.update') }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-xl shadow-lg overflow-hidden form-card">
        <div class="section-header px-6 py-5 bg-gradient-to-r from-yellow-50 to-white border-b border-yellow-100">
            <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                <i class="fas fa-hand-holding-heart mr-3 text-yellow-600 text-xl"></i>
                Data Pengabdian
                <span class="ml-3 text-xs text-gray-500 font-normal">Pengabdian kepada Masyarakat</span>
            </h3>
        </div>
        <div class="px-6 py-6 space-y-5">
            @forelse ($pengabdians as $index => $pengabdian)
                <div class="community-item bg-gradient-to-r from-gray-50 to-white rounded-xl border border-yellow-100 p-5">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-yellow-100">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center mr-3">
                                <i class="fas fa-users text-yellow-500 text-sm"></i>
                            </div>
                            <h4 class="font-semibold text-gray-700">Pengabdian #{{ $index + 1 }}</h4>
                            @if($pengabdian->skema && $pengabdian->skema != '-')
                                <span class="ml-3 text-xs px-2 py-1 rounded-full bg-yellow-100 text-yellow-600">
                                    {{ $pengabdian->skema == 'drtpm' ? 'DRTPM' : ($pengabdian->skema == 'internal' ? 'Internal' : ucfirst($pengabdian->skema)) }}
                                </span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-400">
                            Tahun: {{ $pengabdian->tahun ?? '-' }}
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="input-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-diagram-project mr-2 text-yellow-500"></i>Skema Pengabdian
                            </label>
                            <select name="pengabdians[{{$index}}][skema]" class="select-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500 transition-all bg-white">
                                <option value="" disabled {{ old('pengabdians.' . $index . '.skema', $pengabdian->skema) ? '' : 'selected' }}>Pilih Skema</option>
                                <option value="-" {{ old('pengabdians.' . $index . '.skema', $pengabdian->skema) == '-' ? 'selected' : '' }}>-</option>
                                <option value="drtpm" {{ old('pengabdians.' . $index . '.skema', $pengabdian->skema) == 'drtpm' ? 'selected' : '' }}>DRTPM</option>
                                <option value="internal" {{ old('pengabdians.' . $index . '.skema', $pengabdian->skema) == 'internal' ? 'selected' : '' }}>Pendanaan Internal</option>
                                <option value="hibah" {{ old('pengabdians.' . $index . '.skema', $pengabdian->skema) == 'hibah' ? 'selected' : '' }}>Pendanaan Hibah</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user-tie mr-2 text-yellow-500"></i>Posisi
                            </label>
                            <input type="text" name="pengabdians[{{$index}}][posisi]" value="{{ old('pengabdians.' . $index . '.posisi', $pengabdian->posisi) }}" 
                                   class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500 transition-all"
                                   placeholder="Ketua / Anggota">
                        </div>
                        <div class="md:col-span-2 input-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-heading mr-2 text-yellow-500"></i>Judul Pengabdian
                            </label>
                            <input type="text" name="pengabdians[{{$index}}][judul_pengabdian]" value="{{ old('pengabdians.' . $index . '.judul_pengabdian', $pengabdian->judul_pengabdian) }}" 
                                   class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500 transition-all"
                                   placeholder="Masukkan judul pengabdian">
                        </div>
                        <div class="input-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-money-bill-wave mr-2 text-yellow-500"></i>Sumber Dana
                            </label>
                            <input type="text" name="pengabdians[{{$index}}][sumber_dana]" value="{{ old('pengabdians.' . $index . '.sumber_dana', $pengabdian->sumber_dana) }}" 
                                   class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500 transition-all"
                                   placeholder="Kemenristekdikti / LPPM / Mandiri">
                        </div>
                        <div class="input-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-chart-line mr-2 text-yellow-500"></i>Status
                            </label>
                            <input type="text" name="pengabdians[{{$index}}][status]" value="{{ old('pengabdians.' . $index . '.status', $pengabdian->status) }}" 
                                   class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500 transition-all"
                                   placeholder="Selesai / Berjalan / Diterima">
                        </div>
                        <div class="input-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt mr-2 text-yellow-500"></i>Tahun Pelaksanaan
                            </label>
                            <input type="number" name="pengabdians[{{$index}}][tahun]" value="{{ old('pengabdians.' . $index . '.tahun', $pengabdian->tahun) }}" 
                                   class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500 transition-all"
                                   placeholder="2024" min="2000" max="{{ date('Y') + 5 }}">
                        </div>
                        <div class="input-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-link mr-2 text-yellow-500"></i>Link Luaran
                            </label>
                            <input type="url" name="pengabdians[{{$index}}][link_luaran]" value="{{ old('pengabdians.' . $index . '.link_luaran', $pengabdian->link_luaran) }}" 
                                   class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500 transition-all"
                                   placeholder="https://example.com/luaran">
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-gray-50 rounded-xl">
                    <i class="fas fa-hands-helping fa-4x text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada data Pengabdian yang terdaftar.</p>
                    <p class="text-gray-400 text-sm mt-2">Silakan tambah data Pengabdian melalui menu yang tersedia.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="flex justify-end gap-4 mt-8 pb-8">
        <a href="{{ route('dosen.dashboard') }}" 
           class="inline-flex items-center px-6 py-3 border-2 border-gray-300 shadow-sm text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-all btn-cancel">
            <i class="fas fa-times mr-2"></i> Batal
        </a>
        <button type="submit" 
                class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all transform hover:scale-105 btn-action">
            <i class="fas fa-save mr-2"></i> Simpan Semua Perubahan
        </button>
    </div>
</form>
@endsection

@section('scripts')
<script>
    @if(session('success'))
        console.log('Success: {{ session('success') }}');
    @endif
    
    @if(session('error'))
        console.log('Error: {{ session('error') }}');
    @endif
</script>
@endsection