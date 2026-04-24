{{-- resources/views/admin/dosen/create.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Tambah Dosen - Repositori Dosen')

@section('header-title', 'Tambah Data Dosen Baru')

@section('styles')
<style>
    .form-card {
        transition: all 0.3s ease;
    }
    .form-card:hover {
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }
    .section-header {
        transition: all 0.2s ease;
    }
    .section-header:hover {
        background-color: #f8fafc;
    }
    .input-focus:focus {
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
    .dynamic-group {
        animation: slideIn 0.3s ease;
    }
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .remove-btn {
        transition: all 0.2s ease;
    }
    .remove-btn:hover {
        transform: scale(1.1);
    }
</style>
@endsection

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-user-plus text-blue-600 mr-3"></i>
                Tambah Data Dosen Baru
            </h1>
            <p class="text-gray-600 mt-1">Lengkapi form di bawah untuk menambahkan data dosen baru ke sistem</p>
        </div>
        <a href="{{ route('admin.dosen.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all duration-200 text-gray-700">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Dosen
        </a>
    </div>
</div>

@if ($errors->any())
    <div class="bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 p-4 mb-6 rounded-lg shadow-sm animate-pulse">
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

<form method="POST" action="{{ route('admin.dosen.store') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf

    {{-- Data Utama Section --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden form-card">
        <div class="section-header px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-user-graduate mr-3 text-blue-600 text-xl"></i>
                Data Utama
                <span class="ml-3 text-xs text-gray-500 font-normal">Informasi dasar dosen</span>
            </h3>
        </div>
        <div class="px-6 py-6 space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-3 top-3 text-gray-400"></i>
                        <input type="text" name="nama" value="{{ old('nama') }}"
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all input-focus" 
                               placeholder="Masukkan nama lengkap dosen" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3 top-3 text-gray-400"></i>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all input-focus" 
                               placeholder="dosen@example.com" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        NIDN <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-id-card absolute left-3 top-3 text-gray-400"></i>
                        <input type="text" name="nidn" value="{{ old('nidn') }}"
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all input-focus" 
                               placeholder="Nomor Induk Dosen Nasional" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        NIP
                    </label>
                    <div class="relative">
                        <i class="fas fa-address-card absolute left-3 top-3 text-gray-400"></i>
                        <input type="text" name="nip" value="{{ old('nip') }}"
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all input-focus" 
                               placeholder="Nomor Induk Pegawai">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        NUPTK
                    </label>
                    <div class="relative">
                        <i class="fas fa-certificate absolute left-3 top-3 text-gray-400"></i>
                        <input type="text" name="nuptk" value="{{ old('nuptk') }}"
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all input-focus" 
                               placeholder="Nomor Unik Pendidik dan Tenaga Kependidikan">
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Profil</label>
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <div class="relative">
                            <input type="file" name="foto" accept="image/jpeg,image/png,image/jpg" 
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer">
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i> Format: JPEG, PNG, JPG. Maksimal 2MB.
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-gray-400 text-2xl"></i>
                    </div>
                </div>
                @error('foto')
                    <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Penelitian Section --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden form-card">
        <div class="section-header px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-white flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-flask mr-3 text-green-600 text-xl"></i>
                Penelitian
                <span class="ml-3 text-xs text-gray-500 font-normal">Data penelitian dosen</span>
            </h3>
            <button type="button" id="add-penelitian" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all">
                <i class="fas fa-plus mr-2"></i> Tambah Penelitian
            </button>
        </div>
        <div id="penelitian-fields" class="px-6 py-6 space-y-4">
            <div class="penelitian-group bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-200 p-5 dynamic-group">
                <div class="flex justify-end mb-3">
                    <button type="button" class="remove-penelitian text-red-500 hover:text-red-700 text-sm remove-btn">
                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Skema</label>
                        <select name="penelitians[0][skema]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-green-500">
                            <option value="" selected>Pilih Skema</option>
                            <option value="-">-</option>
                            <option value="drtpm">DRTPM</option>
                            <option value="internal">Pendanaan Internal</option>
                            <option value="hibah">Pendanaan Hibah</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Posisi</label>
                        <input type="text" name="penelitians[0][posisi]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-green-500"
                               placeholder="Ketua / Anggota">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Penelitian</label>
                        <input type="text" name="penelitians[0][judul_penelitian]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-green-500"
                               placeholder="Masukkan judul penelitian">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sumber Dana</label>
                        <input type="text" name="penelitians[0][sumber_dana]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-green-500"
                               placeholder="Sumber pendanaan">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <input type="text" name="penelitians[0][status]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-green-500"
                               placeholder="Selesai / Berjalan">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                        <input type="number" name="penelitians[0][tahun]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-green-500"
                               placeholder="2024">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Link Luaran</label>
                        <input type="url" name="penelitians[0][link_luaran]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-green-500"
                               placeholder="https://...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pengabdian Section --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden form-card">
        <div class="section-header px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-white flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-hands-helping mr-3 text-purple-600 text-xl"></i>
                Pengabdian
                <span class="ml-3 text-xs text-gray-500 font-normal">Data pengabdian masyarakat</span>
            </h3>
            <button type="button" id="add-pengabdian" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all">
                <i class="fas fa-plus mr-2"></i> Tambah Pengabdian
            </button>
        </div>
        <div id="pengabdian-fields" class="px-6 py-6 space-y-4">
            <div class="pengabdian-group bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-200 p-5 dynamic-group">
                <div class="flex justify-end mb-3">
                    <button type="button" class="remove-pengabdian text-red-500 hover:text-red-700 text-sm remove-btn">
                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Skema</label>
                        <select name="pengabdians[0][skema]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-400 focus:border-purple-500">
                            <option value="" selected>Pilih Skema</option>
                            <option value="-">-</option>
                            <option value="drtpm">DRTPM</option>
                            <option value="internal">Pendanaan Internal</option>
                            <option value="hibah">Pendanaan Hibah</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Posisi</label>
                        <input type="text" name="pengabdians[0][posisi]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-400 focus:border-purple-500"
                               placeholder="Ketua / Anggota">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Pengabdian</label>
                        <input type="text" name="pengabdians[0][judul_pengabdian]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-400 focus:border-purple-500"
                               placeholder="Masukkan judul pengabdian">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sumber Dana</label>
                        <input type="text" name="pengabdians[0][sumber_dana]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-400 focus:border-purple-500"
                               placeholder="Sumber pendanaan">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <input type="text" name="pengabdians[0][status]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-400 focus:border-purple-500"
                               placeholder="Selesai / Berjalan">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                        <input type="number" name="pengabdians[0][tahun]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-400 focus:border-purple-500"
                               placeholder="2024">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Link Luaran</label>
                        <input type="url" name="pengabdians[0][link_luaran]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-400 focus:border-purple-500"
                               placeholder="https://...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- HAKI Section --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden form-card">
        <div class="section-header px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-white flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-copyright mr-3 text-yellow-600 text-xl"></i>
                HAKI
                <span class="ml-3 text-xs text-gray-500 font-normal">Hak atas kekayaan intelektual</span>
            </h3>
            <button type="button" id="add-haki" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all">
                <i class="fas fa-plus mr-2"></i> Tambah HAKI
            </button>
        </div>
        <div id="haki-fields" class="px-6 py-6 space-y-4">
            <div class="haki-group bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-200 p-5 dynamic-group">
                <div class="flex justify-end mb-3">
                    <button type="button" class="remove-haki text-red-500 hover:text-red-700 text-sm remove-btn">
                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul HAKI</label>
                        <input type="text" name="hakis[0][judul_haki]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                               placeholder="Masukkan judul HAKI">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Expired</label>
                        <input type="date" name="hakis[0][expired]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Link</label>
                        <input type="url" name="hakis[0][link]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                               placeholder="https://...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Paten Section --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden form-card">
        <div class="section-header px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-white flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-lightbulb mr-3 text-red-600 text-xl"></i>
                Paten
                <span class="ml-3 text-xs text-gray-500 font-normal">Hak paten dan inovasi</span>
            </h3>
            <button type="button" id="add-paten" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all">
                <i class="fas fa-plus mr-2"></i> Tambah Paten
            </button>
        </div>
        <div id="paten-fields" class="px-6 py-6 space-y-4">
            <div class="paten-group bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-200 p-5 dynamic-group">
                <div class="flex justify-end mb-3">
                    <button type="button" class="remove-paten text-red-500 hover:text-red-700 text-sm remove-btn">
                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Paten</label>
                        <input type="text" name="patens[0][judul_paten]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-400 focus:border-red-500"
                               placeholder="Masukkan judul paten">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Paten</label>
                        <input type="text" name="patens[0][jenis_paten]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-400 focus:border-red-500"
                               placeholder="Paten sederhana / Paten biasa">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Expired</label>
                        <input type="date" name="patens[0][expired]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-400 focus:border-red-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Link</label>
                        <input type="url" name="patens[0][link]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-400 focus:border-red-500"
                               placeholder="https://...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Actions --}}
    <div class="flex justify-end gap-4 mt-8 pb-8">
        <a href="{{ route('admin.dosen.index') }}" 
           class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-all">
            <i class="fas fa-times mr-2"></i> Batal
        </a>
        <button type="submit" 
                class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-105">
            <i class="fas fa-save mr-2"></i> Simpan Data Dosen
        </button>
    </div>
</form>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    // Penelitian
    let penelitianCount = 1;
    $('#add-penelitian').click(function () {
        let newPenelitian = `
            <div class="penelitian-group bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-200 p-5 dynamic-group">
                <div class="flex justify-end mb-3">
                    <button type="button" class="remove-penelitian text-red-500 hover:text-red-700 text-sm remove-btn">
                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Skema</label>
                        <select name="penelitians[${penelitianCount}][skema]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-green-500">
                            <option value="" selected>Pilih Skema</option>
                            <option value="-">-</option>
                            <option value="drtpm">DRTPM</option>
                            <option value="internal">Pendanaan Internal</option>
                            <option value="hibah">Pendanaan Hibah</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Posisi</label>
                        <input type="text" name="penelitians[${penelitianCount}][posisi]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-green-500"
                               placeholder="Ketua / Anggota">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Penelitian</label>
                        <input type="text" name="penelitians[${penelitianCount}][judul_penelitian]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-green-500"
                               placeholder="Masukkan judul penelitian">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sumber Dana</label>
                        <input type="text" name="penelitians[${penelitianCount}][sumber_dana]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-green-500"
                               placeholder="Sumber pendanaan">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <input type="text" name="penelitians[${penelitianCount}][status]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-green-500"
                               placeholder="Selesai / Berjalan">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                        <input type="number" name="penelitians[${penelitianCount}][tahun]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-green-500"
                               placeholder="2024">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Link Luaran</label>
                        <input type="url" name="penelitians[${penelitianCount}][link_luaran]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-green-500"
                               placeholder="https://...">
                    </div>
                </div>
            </div>
        `;
        $('#penelitian-fields').append(newPenelitian);
        penelitianCount++;
    });

    $(document).on('click', '.remove-penelitian', function() {
        $(this).closest('.penelitian-group').remove();
    });

    // Pengabdian
    let pengabdianCount = 1;
    $('#add-pengabdian').click(function () {
        let newPengabdian = `
            <div class="pengabdian-group bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-200 p-5 dynamic-group">
                <div class="flex justify-end mb-3">
                    <button type="button" class="remove-pengabdian text-red-500 hover:text-red-700 text-sm remove-btn">
                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Skema</label>
                        <select name="pengabdians[${pengabdianCount}][skema]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-400 focus:border-purple-500">
                            <option value="" selected>Pilih Skema</option>
                            <option value="-">-</option>
                            <option value="drtpm">DRTPM</option>
                            <option value="internal">Pendanaan Internal</option>
                            <option value="hibah">Pendanaan Hibah</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Posisi</label>
                        <input type="text" name="pengabdians[${pengabdianCount}][posisi]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-400 focus:border-purple-500"
                               placeholder="Ketua / Anggota">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Pengabdian</label>
                        <input type="text" name="pengabdians[${pengabdianCount}][judul_pengabdian]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-400 focus:border-purple-500"
                               placeholder="Masukkan judul pengabdian">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sumber Dana</label>
                        <input type="text" name="pengabdians[${pengabdianCount}][sumber_dana]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-400 focus:border-purple-500"
                               placeholder="Sumber pendanaan">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <input type="text" name="pengabdians[${pengabdianCount}][status]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-400 focus:border-purple-500"
                               placeholder="Selesai / Berjalan">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                        <input type="number" name="pengabdians[${pengabdianCount}][tahun]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-400 focus:border-purple-500"
                               placeholder="2024">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Link Luaran</label>
                        <input type="url" name="pengabdians[${pengabdianCount}][link_luaran]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-400 focus:border-purple-500"
                               placeholder="https://...">
                    </div>
                </div>
            </div>
        `;
        $('#pengabdian-fields').append(newPengabdian);
        pengabdianCount++;
    });

    $(document).on('click', '.remove-pengabdian', function() {
        $(this).closest('.pengabdian-group').remove();
    });

    // HAKI
    let hakiCount = 1;
    $('#add-haki').click(function () {
        let newHaki = `
            <div class="haki-group bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-200 p-5 dynamic-group">
                <div class="flex justify-end mb-3">
                    <button type="button" class="remove-haki text-red-500 hover:text-red-700 text-sm remove-btn">
                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul HAKI</label>
                        <input type="text" name="hakis[${hakiCount}][judul_haki]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                               placeholder="Masukkan judul HAKI">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Expired</label>
                        <input type="date" name="hakis[${hakiCount}][expired]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Link</label>
                        <input type="url" name="hakis[${hakiCount}][link]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                               placeholder="https://...">
                    </div>
                </div>
            </div>
        `;
        $('#haki-fields').append(newHaki);
        hakiCount++;
    });

    $(document).on('click', '.remove-haki', function() {
        $(this).closest('.haki-group').remove();
    });

    // Paten
    let patenCount = 1;
    $('#add-paten').click(function () {
        let newPaten = `
            <div class="paten-group bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-200 p-5 dynamic-group">
                <div class="flex justify-end mb-3">
                    <button type="button" class="remove-paten text-red-500 hover:text-red-700 text-sm remove-btn">
                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Paten</label>
                        <input type="text" name="patens[${patenCount}][judul_paten]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-400 focus:border-red-500"
                               placeholder="Masukkan judul paten">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Paten</label>
                        <input type="text" name="patens[${patenCount}][jenis_paten]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-400 focus:border-red-500"
                               placeholder="Paten sederhana / Paten biasa">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Expired</label>
                        <input type="date" name="patens[${patenCount}][expired]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-400 focus:border-red-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Link</label>
                        <input type="url" name="patens[${patenCount}][link]" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-400 focus:border-red-500"
                               placeholder="https://...">
                    </div>
                </div>
            </div>
        `;
        $('#paten-fields').append(newPaten);
        patenCount++;
    });

    $(document).on('click', '.remove-paten', function() {
        $(this).closest('.paten-group').remove();
    });
});
</script>
@endsection