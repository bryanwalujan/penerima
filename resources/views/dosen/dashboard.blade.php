{{-- resources/views/dosen/dashboard.blade.php --}}
@extends('layouts.dosen.app')

@section('title', 'Dashboard Dosen - Repositori Dosen')

@section('header-title', 'Dashboard')

@section('styles')
<style>
    :root {
        --unima-blue: #1e3a8a;
        --unima-gold: #d4af37;
        --unima-light-blue: #3b82f6;
    }

    .dashboard-stats-card {
        transition: all 0.3s ease;
        border-radius: 20px;
    }
    .dashboard-stats-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.15);
    }
    
    .info-card {
        transition: all 0.3s ease;
        border-radius: 16px;
    }
    .info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .btn-dashboard {
        transition: all 0.3s ease;
        border-radius: 12px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    .btn-dashboard:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .profile-image {
        transition: all 0.3s ease;
    }
    .profile-image:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 25px rgba(30, 58, 138, 0.2);
    }
    
    .welcome-badge {
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.1) 0%, rgba(59, 130, 246, 0.05) 100%);
        border-radius: 12px;
    }
    
    /* Animasi untuk icon background */
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }
    .floating-icon {
        animation: float 8s ease-in-out infinite;
    }
    .floating-icon-delayed {
        animation: float 10s ease-in-out infinite 2s;
    }
</style>
@endsection

@section('content')
{{-- Welcome Section --}}
<div class="mb-8">
    <div class="welcome-badge p-6 flex flex-col md:flex-row justify-between items-start md:items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-chalkboard-user text-blue-600 mr-3"></i>
                Selamat Datang, {{ $dosen->nama ?? 'Dosen' }}!
            </h1>
            <p class="text-gray-600 mt-2">
                <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>
        <div class="mt-4 md:mt-0">
            <span class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                <i class="fas fa-graduation-cap mr-2"></i> Dashboard Dosen
            </span>
        </div>
    </div>
</div>

{{-- Statistics Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-md p-5 dashboard-stats-card border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Penelitian</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $dosen->penelitians->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-flask text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-md p-5 dashboard-stats-card border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Pengabdian</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $dosen->pengabdians->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-hands-helping text-green-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-md p-5 dashboard-stats-card border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total HAKI</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $dosen->hakis->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-copyright text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-md p-5 dashboard-stats-card border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Paten</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $dosen->patens->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-certificate text-red-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

{{-- Main Content Grid --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    {{-- Informasi Pribadi --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden info-card">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-id-card mr-2"></i> Informasi Pribadi
                </h3>
            </div>
            <div class="p-6">
                {{-- Foto Profil --}}
                <div class="flex justify-center mb-6">
                    @if ($dosen && $dosen->foto)
                        <img src="{{ Storage::url($dosen->foto) }}"
                             alt="Foto {{ $dosen->nama }}"
                             class="profile-image w-32 h-32 object-cover rounded-full border-4 border-blue-200 shadow-lg">
                    @else
                        <div class="profile-image w-32 h-32 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center border-4 border-blue-200 shadow-lg">
                            <i class="fas fa-user-graduate text-blue-500 text-5xl"></i>
                        </div>
                    @endif
                </div>
                
                <div class="space-y-4">
                    <div class="border-b border-gray-100 pb-3">
                        <label class="text-xs text-gray-500 uppercase font-semibold">Nama Lengkap</label>
                        <p class="text-gray-800 font-medium mt-1">{{ $dosen->nama ?? 'Tidak tersedia' }}</p>
                    </div>
                    <div class="border-b border-gray-100 pb-3">
                        <label class="text-xs text-gray-500 uppercase font-semibold">Email</label>
                        <p class="text-gray-800 font-medium mt-1">{{ $dosen->email ?? 'Tidak tersedia' }}</p>
                    </div>
                    <div class="border-b border-gray-100 pb-3">
                        <label class="text-xs text-gray-500 uppercase font-semibold">NIDN</label>
                        <p class="text-gray-800 font-medium mt-1">{{ $dosen->nidn ?? 'Tidak tersedia' }}</p>
                    </div>
                    <div class="border-b border-gray-100 pb-3">
                        <label class="text-xs text-gray-500 uppercase font-semibold">NIP</label>
                        <p class="text-gray-800 font-medium mt-1">{{ $dosen->nip ?? 'Tidak tersedia' }}</p>
                    </div>
                    <div class="pb-2">
                        <label class="text-xs text-gray-500 uppercase font-semibold">NUPTK</label>
                        <p class="text-gray-800 font-medium mt-1">{{ $dosen->nuptk ?? 'Tidak tersedia' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Aksi Cepat & Aktivitas --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden info-card">
            <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-bolt mr-2 text-yellow-400"></i> Menu Aksi Cepat
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('dosen.edit') }}"
                       class="btn-dashboard flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-white rounded-lg border border-green-200 hover:border-green-400 hover:bg-green-50 transition-all group">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user-edit text-green-600 text-lg"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Edit Profil</p>
                                <p class="text-xs text-gray-500">Perbarui informasi pribadi</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-green-600 transition-colors"></i>
                    </a>
                    
                    <a href="{{ route('dosen.penelitian.edit') }}"
                       class="btn-dashboard flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-white rounded-lg border border-blue-200 hover:border-blue-400 hover:bg-blue-50 transition-all group">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-flask text-blue-600 text-lg"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Penelitian</p>
                                <p class="text-xs text-gray-500">Kelola data penelitian</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full">{{ $dosen->penelitians->count() }}</span>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-600 transition-colors"></i>
                        </div>
                    </a>
                    
                    <a href="{{ route('dosen.pengabdian.edit') }}"
                       class="btn-dashboard flex items-center justify-between p-4 bg-gradient-to-r from-yellow-50 to-white rounded-lg border border-yellow-200 hover:border-yellow-400 hover:bg-yellow-50 transition-all group">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-hands-helping text-yellow-600 text-lg"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Pengabdian</p>
                                <p class="text-xs text-gray-500">Kelola data pengabdian</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="bg-yellow-600 text-white text-xs px-2 py-1 rounded-full">{{ $dosen->pengabdians->count() }}</span>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-yellow-600 transition-colors"></i>
                        </div>
                    </a>
                    
                    <a href="{{ route('dosen.haki.edit') }}"
                       class="btn-dashboard flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-white rounded-lg border border-purple-200 hover:border-purple-400 hover:bg-purple-50 transition-all group">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-copyright text-purple-600 text-lg"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">HAKI</p>
                                <p class="text-xs text-gray-500">Kelola data HAKI</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="bg-purple-600 text-white text-xs px-2 py-1 rounded-full">{{ $dosen->hakis->count() }}</span>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-purple-600 transition-colors"></i>
                        </div>
                    </a>
                    
                    <a href="{{ route('dosen.paten.edit') }}"
                       class="btn-dashboard flex items-center justify-between p-4 bg-gradient-to-r from-red-50 to-white rounded-lg border border-red-200 hover:border-red-400 hover:bg-red-50 transition-all group">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-certificate text-red-600 text-lg"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Paten</p>
                                <p class="text-xs text-gray-500">Kelola data paten</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="bg-red-600 text-white text-xs px-2 py-1 rounded-full">{{ $dosen->patens->count() }}</span>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-red-600 transition-colors"></i>
                        </div>
                    </a>
                    
                    <form action="{{ route('logout') }}" method="POST" class="contents">
                        @csrf
                        <button type="submit"
                                class="btn-dashboard flex items-center justify-between p-4 bg-gradient-to-r from-gray-100 to-gray-200 rounded-lg border border-gray-300 hover:border-gray-500 hover:bg-gray-200 transition-all group w-full">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-sign-out-alt text-gray-700 text-lg"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">Logout</p>
                                    <p class="text-xs text-gray-500">Keluar dari sistem</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-700 transition-colors"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        {{-- Statistik Singkat --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden info-card mt-6">
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-chart-line mr-2"></i> Ringkasan Aktivitas
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                        <p class="text-2xl font-bold text-blue-600">{{ $dosen->penelitians->count() + $dosen->pengabdians->count() }}</p>
                        <p class="text-xs text-gray-600 mt-1">Total Karya</p>
                    </div>
                    <div class="text-center p-3 bg-purple-50 rounded-lg">
                        <p class="text-2xl font-bold text-purple-600">{{ $dosen->hakis->count() + $dosen->patens->count() }}</p>
                        <p class="text-xs text-gray-600 mt-1">Total Kekayaan Intelektual</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Notifikasi jika ada session success atau error
    @if(session('success'))
        // Toast notifikasi bisa ditambahkan di sini jika diperlukan
        console.log('Success: {{ session('success') }}');
    @endif
    
    @if(session('error'))
        console.log('Error: {{ session('error') }}');
    @endif
</script>
@endsection