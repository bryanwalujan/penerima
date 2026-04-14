@extends('layouts.admin.app')

@section('title', 'Manajemen Dosen - Repositori Dosen')

@section('header-title', 'Manajemen Data Dosen')

@section('styles')
    <style>
        /* Gaya spesifik untuk halaman data dosen */
        .statistics-card {
            transition: all 0.3s ease;
        }

        .statistics-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-user-tie text-blue-600 mr-2"></i>Manajemen Data Dosen
        </h1>
        <p class="text-gray-600 mt-1">Repositori Fakultas Teknik Informatika UNIMA</p>
    </div>

    @include('admin.dosen.partials.statistics')
    @include('admin.dosen.partials.actions')
    @include('admin.dosen.partials.tabs')
    @include('admin.dosen.partials.modal-recommendation')
@endsection

@section('scripts')
    <script src="{{ asset('js/dosen.js') }}"></script>
@endsection