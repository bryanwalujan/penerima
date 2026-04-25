{{-- resources/views/dosen/haki/edit.blade.php --}}
@extends('layouts.dosen.app')

@section('title', 'Edit HAKI - Repositori Dosen')

@section('header-title', 'Edit HAKI')

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
    .input-field {
        transition: all 0.2s ease;
        border-radius: 12px;
    }
    .input-field:focus {
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }
    .haki-item {
        transition: all 0.3s ease;
        border-radius: 16px;
    }
    .haki-item:hover {
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
                <i class="fas fa-copyright text-purple-600 mr-3"></i>
                Edit HAKI
            </h1>
            <p class="text-gray-600 mt-2">
                <i class="fas fa-info-circle mr-2 text-purple-500"></i>
                Kelola data Hak atas Kekayaan Intelektual (HAKI)
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

<form method="POST" action="{{ route('dosen.haki.update') }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-xl shadow-lg overflow-hidden form-card">
        <div class="section-header px-6 py-5 bg-gradient-to-r from-purple-50 to-white border-b border-purple-100">
            <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                <i class="fas fa-copyright mr-3 text-purple-600 text-xl"></i>
                Data HAKI
                <span class="ml-3 text-xs text-gray-500 font-normal">Hak atas Kekayaan Intelektual</span>
            </h3>
        </div>
        <div class="px-6 py-6 space-y-5">
            @forelse ($hakis as $index => $haki)
                <div class="haki-item bg-gradient-to-r from-gray-50 to-white rounded-xl border border-purple-100 p-5">
                    <div class="flex items-center mb-4 pb-3 border-b border-purple-100">
                        <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                            <span class="text-purple-600 font-semibold text-sm">{{ $index + 1 }}</span>
                        </div>
                        <h4 class="font-semibold text-gray-700">Data HAKI #{{ $index + 1 }}</h4>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2 input-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-heading mr-2 text-purple-500"></i>Judul HAKI
                            </label>
                            <input type="text" name="hakis[{{$index}}][judul_haki]" value="{{ old('hakis.' . $index . '.judul_haki', $haki->judul_haki) }}" 
                                   class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:border-purple-500 transition-all"
                                   placeholder="Masukkan judul HAKI">
                        </div>
                        <div class="input-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt mr-2 text-purple-500"></i>Tanggal Expired
                            </label>
                            <input type="date" name="hakis[{{$index}}][expired]" value="{{ old('hakis.' . $index . '.expired', $haki->expired) }}" 
                                   class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:border-purple-500 transition-all">
                        </div>
                        <div class="input-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-link mr-2 text-purple-500"></i>Link / URL
                            </label>
                            <input type="url" name="hakis[{{$index}}][link]" value="{{ old('hakis.' . $index . '.link', $haki->link) }}" 
                                   class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:border-purple-500 transition-all"
                                   placeholder="https://example.com/haki">
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-gray-50 rounded-xl">
                    <i class="fas fa-copyright fa-4x text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada data HAKI yang terdaftar.</p>
                    <p class="text-gray-400 text-sm mt-2">Silakan tambah data HAKI melalui menu yang tersedia.</p>
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
                class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all transform hover:scale-105 btn-action">
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