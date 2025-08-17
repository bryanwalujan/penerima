@extends('layouts.admin.app')

@section('title', 'Dashboard Admin')

@section('header-title', 'Dashboard Admin')

@section('content')
    <!-- Welcome Banner -->
    <div class="welcome-banner text-white p-6 mb-8">
        <div class="flex flex-col md:flex-row justify-between items-center relative z-10">
            <div class="md:w-3/4 mb-6 md:mb-0">
                <h1 class="text-2xl md:text-3xl font-bold mb-3">Selamat Datang, {{ Auth::guard('web')->user()->name }}</h1>
                <p class="text-blue-100 max-w-3xl">
                    Ini adalah dashboard admin untuk mengelola data dosen Program Studi Teknik Informatika 
                    Universitas Negeri Manado. Anda dapat menambahkan, mengedit, dan mengelola data dosen.
                </p>
            </div>
            <div class="md:w-1/4 flex justify-center">
                <div class="bg-blue-600/30 p-5 rounded-full">
                    <i class="fas fa-user-shield text-4xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Cards -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-tasks text-blue-600 mr-2"></i>
            Kelola Data Dosen
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Lihat Data Dosen -->
            <a href="{{ route('admin.dosen.index') }}" class="action-card bg-white">
                <div class="p-6 flex flex-col h-full">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-list text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Lihat Data Dosen</h3>
                    </div>
                    <p class="text-gray-600 mb-4 flex-grow">
                        Kelola semua data dosen termasuk profil, penelitian, pengabdian, dan karya akademik.
                    </p>
                    <div class="text-blue-600 font-medium flex items-center mt-auto">
                        <span>Akses Data</span>
                        <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </div>
                </div>
            </a>
            
            <!-- Tambah Dosen -->
            <a href="{{ route('admin.dosen.create') }}" class="action-card bg-white">
                <div class="p-6 flex flex-col h-full">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-user-plus text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Tambah Dosen</h3>
                    </div>
                    <p class="text-gray-600 mb-4 flex-grow">
                        Tambahkan dosen baru ke dalam sistem dengan mengisi data profil dan informasi akademik.
                    </p>
                    <div class="text-green-600 font-medium flex items-center mt-auto">
                        <span>Tambahkan Sekarang</span>
                        <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </div>
                </div>
            </a>
            
            <!-- Dashboard Analytics -->
            <a href="{{ route('admin.analytics.index') }}" class="action-card bg-white">
                <div class="p-6 flex flex-col h-full">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-chart-bar text-purple-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Dashboard Analytics</h3>
                    </div>
                    <p class="text-gray-600 mb-4 flex-grow">
                        Lihat analisis dan visualisasi data akademik seperti tren penelitian dan produktivitas dosen.
                    </p>
                    <div class="text-purple-600 font-medium flex items-center mt-auto">
                        <span>Lihat Analytics</span>
                        <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection