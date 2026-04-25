{{-- resources/views/dosen/edit.blade.php --}}
@extends('layouts.dosen.app')

@section('title', 'Edit Profil Dosen - Repositori Dosen')

@section('header-title', 'Edit Profil Dosen')
@section('header-subtitle', 'Perbarui informasi pribadi dan foto profil Anda')

@section('styles')
<style>
    .profile-card {
        transition: all 0.3s ease;
        border-radius: 20px;
    }
    .profile-card:hover {
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
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
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .photo-preview {
        transition: all 0.3s ease;
    }
    .photo-preview:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 25px rgba(30, 58, 138, 0.2);
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
    .info-badge {
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
    }
</style>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-user-edit text-blue-600 mr-3"></i>
                    Edit Profil Dosen
                </h1>
                <p class="text-gray-600 mt-1">
                    <i class="fas fa-info-circle mr-2 text-blue-500 text-sm"></i>
                    Perbarui informasi pribadi Anda
                </p>
            </div>
            <a href="{{ route('dosen.dashboard') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all text-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    {{-- Error Alert --}}
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

    {{-- Success Alert --}}
    @if(session('success'))
        <div class="bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 p-5 mb-6 rounded-xl shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('dosen.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Profile Form Card --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden profile-card">
            <div class="px-6 py-5 bg-gradient-to-r from-blue-50 to-white border-b border-blue-100">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-id-card mr-2 text-blue-600"></i>
                    Informasi Pribadi
                </h3>
            </div>
            
            <div class="p-6 space-y-6">
                {{-- Foto Profil --}}
                <div class="flex flex-col md:flex-row items-center gap-6 pb-6 border-b border-gray-100">
                    <div class="text-center">
                        @if ($dosen->foto)
                            <img src="{{ Storage::url($dosen->foto) }}" alt="Foto {{ $dosen->nama }}" 
                                 class="photo-preview w-32 h-32 object-cover rounded-full border-4 border-blue-200 shadow-lg">
                        @else
                            <div class="photo-preview w-32 h-32 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center border-4 border-blue-200 shadow-lg">
                                <i class="fas fa-user-graduate text-blue-500 text-5xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 w-full">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-camera mr-2 text-blue-500"></i>Foto Profil
                        </label>
                        <input type="file" name="foto" accept="image/jpeg,image/png,image/jpg" 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer">
                        <div class="mt-2 flex items-center gap-2">
                            <i class="fas fa-info-circle text-gray-400 text-xs"></i>
                            <p class="text-xs text-gray-500">Format: JPEG, PNG, JPG. Maksimal 2MB. Biarkan kosong jika tidak ingin mengganti foto.</p>
                        </div>
                        @error('foto')
                            <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Data Diri --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="input-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-blue-500"></i>Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" value="{{ old('nama', $dosen->nama) }}" 
                               class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all"
                               placeholder="Masukkan nama lengkap" required>
                    </div>
                    
                    <div class="input-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-id-card mr-2 text-blue-500"></i>Email
                        </label>
                        <input type="email" value="{{ $dosen->email ?? 'Tidak tersedia' }}" 
                               class="input-field w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-500"
                               disabled readonly>
                        <p class="text-xs text-gray-400 mt-1">Email tidak dapat diubah</p>
                    </div>
                    
                    <div class="input-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-qrcode mr-2 text-blue-500"></i>NIDN <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nidn" value="{{ old('nidn', $dosen->nidn) }}" 
                               class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all"
                               placeholder="Nomor Induk Dosen Nasional" required>
                    </div>
                    
                    <div class="input-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-address-card mr-2 text-blue-500"></i>NIP
                        </label>
                        <input type="text" name="nip" value="{{ old('nip', $dosen->nip) }}" 
                               class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all"
                               placeholder="Nomor Induk Pegawai">
                    </div>
                    
                    <div class="input-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-certificate mr-2 text-blue-500"></i>NUPTK
                        </label>
                        <input type="text" name="nuptk" value="{{ old('nuptk', $dosen->nuptk) }}" 
                               class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all"
                               placeholder="Nomor Unik Pendidik dan Tenaga Kependidikan">
                    </div>
                </div>

                {{-- Informasi Tambahan --}}
                <div class="mt-4 p-4 bg-blue-50 rounded-xl">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-shield-alt text-blue-500 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-medium text-blue-800">Informasi Keamanan</p>
                            <p class="text-xs text-blue-600 mt-1">Data Anda aman dan hanya dapat diakses oleh Anda sebagai dosen yang bersangkutan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex justify-end gap-4 mt-8 pb-8">
            <a href="{{ route('dosen.dashboard') }}" 
               class="inline-flex items-center px-6 py-3 border-2 border-gray-300 shadow-sm text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-all">
                <i class="fas fa-times mr-2"></i> Batal
            </a>
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-xl shadow-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-105 btn-action">
                <i class="fas fa-save mr-2"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // Preview foto sebelum upload
    document.querySelector('input[name="foto"]').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.querySelector('.photo-preview');
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = preview.className;
                    img.alt = 'Preview Foto';
                    preview.parentNode.replaceChild(img, preview);
                }
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
@endsection