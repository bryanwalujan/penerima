{{-- resources/views/dosen/edit-haki.blade.php --}}
@extends('layouts.dosen.app')

@section('title', 'Edit HAKI - Repositori Dosen')
@section('header-title', 'Kelola HAKI')

@section('styles')
<style>
    .form-card, .data-card {
        transition: all 0.3s ease;
        border-radius: 20px;
    }
    .form-card:hover, .data-card:hover {
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }
    .input-field {
        transition: all 0.2s ease;
        border-radius: 12px;
    }
    .input-field:focus {
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
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
    .delete-btn {
        transition: all 0.2s ease;
    }
    .delete-btn:hover {
        transform: scale(1.1);
        color: #dc2626 !important;
    }
    .modal-transition {
        transition: all 0.3s ease;
    }
</style>
@endsection

@section('content')
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-copyright text-purple-600 mr-3"></i>
                Kelola HAKI
            </h1>
            <p class="text-gray-600 mt-2">
                <i class="fas fa-info-circle mr-2 text-purple-500"></i>
                Tambah, edit, atau hapus data Hak atas Kekayaan Intelektual (HAKI)
            </p>
        </div>
        <a href="{{ route('dosen.dashboard') }}" 
           class="inline-flex items-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all duration-200 text-gray-700 font-medium">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

@if(session('success'))
    <div class="bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 p-5 mb-6 rounded-xl shadow-sm">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 p-5 mb-6 rounded-xl shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-semibold text-red-800">Terdapat {{ $errors->count() }} kesalahan</h3>
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

{{-- Form Tambah HAKI --}}
<div class="bg-white rounded-xl shadow-lg overflow-hidden form-card mb-8">
    <div class="px-6 py-5 bg-gradient-to-r from-purple-50 to-white border-b border-purple-100">
        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="fas fa-plus-circle mr-3 text-purple-600 text-xl"></i>
            Tambah HAKI Baru
        </h3>
    </div>
    <form method="POST" action="{{ route('dosen.haki.store') }}" class="p-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-heading mr-2 text-purple-500"></i>Judul HAKI <span class="text-red-500">*</span>
                </label>
                <input type="text" name="judul_haki" value="{{ old('judul_haki') }}" required
                       class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:border-purple-500 transition-all"
                       placeholder="Masukkan judul HAKI">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar-alt mr-2 text-purple-500"></i>Tanggal Expired
                </label>
                <input type="date" name="expired" value="{{ old('expired') }}"
                       class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:border-purple-500 transition-all">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-link mr-2 text-purple-500"></i>Link / URL
                </label>
                <input type="url" name="link" value="{{ old('link') }}"
                       class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:border-purple-500 transition-all"
                       placeholder="https://example.com/haki">
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all transform hover:scale-105 btn-action">
                <i class="fas fa-save mr-2"></i> Simpan HAKI
            </button>
        </div>
    </form>
</div>

{{-- Daftar HAKI --}}
<div class="bg-white rounded-xl shadow-lg overflow-hidden form-card">
    <div class="px-6 py-5 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="fas fa-list mr-3 text-purple-600 text-xl"></i>
            Daftar HAKI
            <span class="ml-3 text-sm text-gray-500">({{ $hakis->count() }} data)</span>
        </h3>
    </div>
    <div class="p-6">
        @forelse ($hakis as $index => $haki)
            <div class="haki-item bg-gradient-to-r from-gray-50 to-white rounded-xl border border-purple-100 p-5 mb-4" id="haki-{{ $haki->id }}">
                <form method="POST" action="{{ route('dosen.haki.update', $haki->id) }}" class="edit-form" id="form-{{ $haki->id }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-purple-100">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                <i class="fas fa-copyright text-purple-500 text-sm"></i>
                            </div>
                            <h4 class="font-semibold text-gray-700">HAKI #{{ $index + 1 }}</h4>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="toggleEditMode({{ $haki->id }})" class="text-blue-500 hover:text-blue-700 transition" id="edit-btn-{{ $haki->id }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button type="button" onclick="confirmDelete('{{ route('dosen.haki.destroy', $haki->id) }}', '{{ addslashes($haki->judul_haki) }}')" 
                                    class="text-red-500 hover:text-red-700 transition delete-btn">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Judul HAKI</label>
                            <input type="text" name="judul_haki" value="{{ $haki->judul_haki }}" 
                                   class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100" readonly
                                   data-editable="true" id="judul-{{ $haki->id }}">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Expired</label>
                            <input type="date" name="expired" value="{{ $haki->expired }}" 
                                   class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100" readonly data-editable="true" id="expired-{{ $haki->id }}">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Link / URL</label>
                            <input type="url" name="link" value="{{ $haki->link }}" 
                                   class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100" readonly data-editable="true"
                                   placeholder="https://example.com/haki" id="link-{{ $haki->id }}">
                        </div>
                    </div>
                    
                    <div class="hidden mt-4 flex justify-end gap-3 edit-buttons" id="edit-buttons-{{ $haki->id }}">
                        <button type="button" onclick="cancelEdit({{ $haki->id }})" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        @empty
            <div class="text-center py-12 bg-gray-50 rounded-xl">
                <i class="fas fa-copyright fa-4x text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada data HAKI yang terdaftar.</p>
                <p class="text-gray-400 text-sm mt-2">Silakan tambah data HAKI melalui form di atas.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-auto bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 modal-transition">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-trash-alt text-red-500 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-xl font-semibold text-center text-gray-800 mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-600 text-center mb-6" id="deleteMessage">Apakah Anda yakin ingin menghapus data ini?</p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </button>
                <form id="deleteForm" method="POST" action="" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let editStates = {};
    
    function toggleEditMode(id) {
        const form = document.getElementById(`form-${id}`);
        if (!form) return;
        
        const inputs = form.querySelectorAll('[data-editable="true"]');
        const editButtons = document.getElementById(`edit-buttons-${id}`);
        const editBtn = document.getElementById(`edit-btn-${id}`);
        
        const isEditMode = editStates[id] || false;
        
        inputs.forEach(input => {
            if (isEditMode) {
                // Exit edit mode (cancel)
                input.setAttribute('readonly', true);
                input.classList.remove('bg-white');
                input.classList.add('bg-gray-100');
            } else {
                // Enter edit mode
                input.removeAttribute('readonly');
                input.classList.remove('bg-gray-100');
                input.classList.add('bg-white');
            }
        });
        
        if (editButtons) {
            if (!isEditMode) {
                editButtons.classList.remove('hidden');
                if (editBtn) {
                    editBtn.innerHTML = '<i class="fas fa-times"></i> Batal Edit';
                    editBtn.classList.remove('text-blue-500', 'hover:text-blue-700');
                    editBtn.classList.add('text-orange-500', 'hover:text-orange-700');
                }
                editStates[id] = true;
            } else {
                editButtons.classList.add('hidden');
                if (editBtn) {
                    editBtn.innerHTML = '<i class="fas fa-edit"></i> Edit';
                    editBtn.classList.remove('text-orange-500', 'hover:text-orange-700');
                    editBtn.classList.add('text-blue-500', 'hover:text-blue-700');
                }
                editStates[id] = false;
            }
        }
    }
    
    function cancelEdit(id) {
        location.reload();
    }
    
    let deleteUrl = '';
    
    function confirmDelete(url, title) {
        deleteUrl = url;
        document.getElementById('deleteMessage').innerHTML = `Apakah Anda yakin ingin menghapus data HAKI "<strong>${title}</strong>"?`;
        document.getElementById('deleteForm').action = url;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
    
    window.onclick = function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target == modal) {
            closeDeleteModal();
        }
    }
</script>
@endsection