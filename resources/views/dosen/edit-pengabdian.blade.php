{{-- resources/views/dosen/edit-pengabdian.blade.php --}}
@extends('layouts.dosen.app')

@section('title', 'Edit Pengabdian - Repositori Dosen')
@section('header-title', 'Kelola Pengabdian')

@section('styles')
<style>
    .form-card, .data-card {
        transition: all 0.3s ease;
        border-radius: 20px;
    }
    .form-card:hover, .data-card:hover {
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }
    .input-field, .select-field {
        transition: all 0.2s ease;
        border-radius: 12px;
    }
    .input-field:focus, .select-field:focus {
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
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
                <i class="fas fa-hands-helping text-yellow-600 mr-3"></i>
                Kelola Pengabdian
            </h1>
            <p class="text-gray-600 mt-2">
                <i class="fas fa-info-circle mr-2 text-yellow-500"></i>
                Tambah, edit, atau hapus data pengabdian kepada masyarakat Anda
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

{{-- Form Tambah Pengabdian --}}
<div class="bg-white rounded-xl shadow-lg overflow-hidden form-card mb-8">
    <div class="px-6 py-5 bg-gradient-to-r from-yellow-50 to-white border-b border-yellow-100">
        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="fas fa-plus-circle mr-3 text-yellow-600 text-xl"></i>
            Tambah Pengabdian Baru
        </h3>
    </div>
    <form method="POST" action="{{ route('dosen.pengabdian.store') }}" class="p-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-heading mr-2 text-yellow-500"></i>Judul Pengabdian <span class="text-red-500">*</span>
                </label>
                <input type="text" name="judul_pengabdian" value="{{ old('judul_pengabdian') }}" required
                       class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500 transition-all"
                       placeholder="Masukkan judul pengabdian">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-diagram-project mr-2 text-yellow-500"></i>Skema
                </label>
                <select name="skema" class="select-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500 transition-all bg-white">
                    <option value="">Pilih Skema</option>
                    <option value="-">-</option>
                    <option value="drtpm">DRTPM</option>
                    <option value="internal">Pendanaan Internal</option>
                    <option value="hibah">Pendanaan Hibah</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-user-tie mr-2 text-yellow-500"></i>Posisi
                </label>
                <input type="text" name="posisi" value="{{ old('posisi') }}"
                       class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500 transition-all"
                       placeholder="Ketua / Anggota">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-money-bill-wave mr-2 text-yellow-500"></i>Sumber Dana
                </label>
                <input type="text" name="sumber_dana" value="{{ old('sumber_dana') }}"
                       class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500 transition-all"
                       placeholder="Kemenristekdikti / LPPM / Mandiri">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-chart-line mr-2 text-yellow-500"></i>Status
                </label>
                <select name="status" class="select-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500 transition-all bg-white">
                    <option value="">Pilih Status</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Berjalan">Berjalan</option>
                    <option value="Diajukan">Diajukan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar-alt mr-2 text-yellow-500"></i>Tahun
                </label>
                <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}"
                       class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500 transition-all"
                       placeholder="2024" min="2000" max="{{ date('Y') + 5 }}">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-link mr-2 text-yellow-500"></i>Link Luaran
                </label>
                <input type="url" name="link_luaran" value="{{ old('link_luaran') }}"
                       class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500 transition-all"
                       placeholder="https://example.com/luaran">
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-600 to-yellow-700 text-white rounded-xl hover:from-yellow-700 hover:to-yellow-800 transition-all transform hover:scale-105 btn-action">
                <i class="fas fa-save mr-2"></i> Simpan Pengabdian
            </button>
        </div>
    </form>
</div>

{{-- Daftar Pengabdian --}}
<div class="bg-white rounded-xl shadow-lg overflow-hidden form-card">
    <div class="px-6 py-5 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="fas fa-list mr-3 text-yellow-600 text-xl"></i>
            Daftar Pengabdian
            <span class="ml-3 text-sm text-gray-500">({{ $pengabdians->count() }} data)</span>
        </h3>
    </div>
    <div class="p-6">
        @forelse ($pengabdians as $index => $pengabdian)
            <div class="community-item bg-gradient-to-r from-gray-50 to-white rounded-xl border border-yellow-100 p-5 mb-4">
                <form method="POST" action="{{ route('dosen.pengabdian.update', $pengabdian->id) }}" class="edit-form">
                    @csrf
                    @method('PUT')
                    
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
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="toggleEditMode(this)" class="text-blue-500 hover:text-blue-700 transition">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button type="button" onclick="confirmDelete('{{ route('dosen.pengabdian.destroy', $pengabdian->id) }}', '{{ $pengabdian->judul_pengabdian }}')" 
                                    class="text-red-500 hover:text-red-700 transition delete-btn">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Pengabdian</label>
                            <input type="text" name="judul_pengabdian" value="{{ $pengabdian->judul_pengabdian }}" 
                                   class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100" readonly
                                   data-editable="true">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Skema</label>
                            <select name="skema" class="select-field w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100" disabled data-editable="true">
                                <option value="">Pilih Skema</option>
                                <option value="-" {{ $pengabdian->skema == '-' ? 'selected' : '' }}>-</option>
                                <option value="drtpm" {{ $pengabdian->skema == 'drtpm' ? 'selected' : '' }}>DRTPM</option>
                                <option value="internal" {{ $pengabdian->skema == 'internal' ? 'selected' : '' }}>Pendanaan Internal</option>
                                <option value="hibah" {{ $pengabdian->skema == 'hibah' ? 'selected' : '' }}>Pendanaan Hibah</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Posisi</label>
                            <input type="text" name="posisi" value="{{ $pengabdian->posisi }}" 
                                   class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100" readonly data-editable="true">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Sumber Dana</label>
                            <input type="text" name="sumber_dana" value="{{ $pengabdian->sumber_dana }}" 
                                   class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100" readonly data-editable="true">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select name="status" class="select-field w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100" disabled data-editable="true">
                                <option value="">Pilih Status</option>
                                <option value="Selesai" {{ $pengabdian->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="Berjalan" {{ $pengabdian->status == 'Berjalan' ? 'selected' : '' }}>Berjalan</option>
                                <option value="Diajukan" {{ $pengabdian->status == 'Diajukan' ? 'selected' : '' }}>Diajukan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                            <input type="number" name="tahun" value="{{ $pengabdian->tahun }}" 
                                   class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100" readonly data-editable="true"
                                   min="2000" max="{{ date('Y') + 5 }}">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Link Luaran</label>
                            <input type="url" name="link_luaran" value="{{ $pengabdian->link_luaran }}" 
                                   class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100" readonly data-editable="true"
                                   placeholder="https://example.com/luaran">
                        </div>
                    </div>
                    
                    <div class="hidden mt-4 flex justify-end gap-3 edit-buttons">
                        <button type="button" onclick="cancelEdit(this)" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        @empty
            <div class="text-center py-12 bg-gray-50 rounded-xl">
                <i class="fas fa-hands-helping fa-4x text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada data Pengabdian yang terdaftar.</p>
                <p class="text-gray-400 text-sm mt-2">Silakan tambah data Pengabdian melalui form di atas.</p>
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
    function toggleEditMode(btn) {
        const form = btn.closest('form');
        const inputs = form.querySelectorAll('[data-editable="true"]');
        const editButtons = form.querySelector('.edit-buttons');
        const isReadOnly = inputs[0].hasAttribute('readonly');
        
        inputs.forEach(input => {
            if (input.tagName === 'SELECT') {
                input.disabled = !isReadOnly;
            } else {
                if (isReadOnly) {
                    input.removeAttribute('readonly');
                    input.classList.remove('bg-gray-100');
                    input.classList.add('bg-white');
                } else {
                    input.setAttribute('readonly', true);
                    input.classList.remove('bg-white');
                    input.classList.add('bg-gray-100');
                }
            }
        });
        
        if (editButtons) {
            editButtons.classList.toggle('hidden');
        }
        
        if (isReadOnly) {
            btn.innerHTML = '<i class="fas fa-save"></i> Simpan';
            btn.classList.remove('text-blue-500', 'hover:text-blue-700');
            btn.classList.add('text-green-600', 'hover:text-green-700');
        } else {
            btn.innerHTML = '<i class="fas fa-edit"></i> Edit';
            btn.classList.remove('text-green-600', 'hover:text-green-700');
            btn.classList.add('text-blue-500', 'hover:text-blue-700');
        }
    }
    
    function cancelEdit(btn) {
        const form = btn.closest('form');
        const inputs = form.querySelectorAll('[data-editable="true"]');
        const editButtons = form.querySelector('.edit-buttons');
        const editBtn = form.querySelector('.edit-form button[onclick*="toggleEditMode"]');
        
        inputs.forEach(input => {
            if (input.tagName === 'SELECT') {
                input.disabled = true;
            } else {
                input.setAttribute('readonly', true);
                input.classList.remove('bg-white');
                input.classList.add('bg-gray-100');
            }
        });
        
        if (editButtons) {
            editButtons.classList.add('hidden');
        }
        
        if (editBtn) {
            editBtn.innerHTML = '<i class="fas fa-edit"></i> Edit';
            editBtn.classList.remove('text-green-600', 'hover:text-green-700');
            editBtn.classList.add('text-blue-500', 'hover:text-blue-700');
        }
        
        form.reset();
    }
    
    let deleteUrl = '';
    let deleteTitle = '';
    
    function confirmDelete(url, title) {
        deleteUrl = url;
        deleteTitle = title;
        document.getElementById('deleteMessage').innerHTML = `Apakah Anda yakin ingin menghapus data pengabdian "<strong>${title}</strong>"?`;
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