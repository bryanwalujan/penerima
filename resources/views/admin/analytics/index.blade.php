@extends('layouts.admin.app')

@section('title', 'Dashboard Analytics')

@section('header-title', 'Dashboard Analytics')

@section('styles')
    <style>
        /* Ensure canvas elements are responsive */
        canvas {
            max-width: 100%;
            height: auto;
        }
    </style>
@endsection

@section('content')
    <!-- Summary Cards -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-chart-pie text-blue-600 mr-2"></i>
            Ringkasan Statistik
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="summary-card bg-white border-l-blue-500">
                <div class="p-4">
                    <div class="flex items-center mb-2">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-user-tie text-blue-600"></i>
                        </div>
                        <h3 class="text-md font-semibold">Total Dosen</h3>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalDosens }}</p>
                </div>
            </div>
            
            <div class="summary-card bg-white border-l-green-500">
                <div class="p-4">
                    <div class="flex items-center mb-2">
                        <div class="bg-green-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-flask text-green-600"></i>
                        </div>
                        <h3 class="text-md font-semibold">Total Penelitian</h3>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalPenelitians }}</p>
                </div>
            </div>
            
            <div class="summary-card bg-white border-l-yellow-500">
                <div class="p-4">
                    <div class="flex items-center mb-2">
                        <div class="bg-yellow-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-hands-helping text-yellow-600"></i>
                        </div>
                        <h3 class="text-md font-semibold">Total Pengabdian</h3>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalPengabdians }}</p>
                </div>
            </div>
            
            <div class="summary-card bg-white border-l-purple-500">
                <div class="p-4">
                    <div class="flex items-center mb-2">
                        <div class="bg-purple-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-copyright text-purple-600"></i>
                        </div>
                        <h3 class="text-md font-semibold">Total HAKI</h3>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalHakis }}</p>
                </div>
            </div>
            
            <div class="summary-card bg-white border-l-red-500">
                <div class="p-4">
                    <div class="flex items-center mb-2">
                        <div class="bg-red-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-file-alt text-red-600"></i>
                        </div>
                        <h3 class="text-md font-semibold">Total Paten</h3>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalPatens }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-chart-line text-blue-600 mr-2"></i>
            Visualisasi Data
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Penelitian per Tahun -->
            <div class="chart-container">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                    Penelitian per Tahun
                </h3>
                <canvas id="penelitianPerTahunChart"></canvas>
            </div>

            <!-- Distribusi Skema Penelitian -->
            <div class="chart-container">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-chart-pie text-green-500 mr-2"></i>
                    Distribusi Skema Penelitian
                </h3>
                <canvas id="skemaPenelitianChart"></canvas>
            </div>

            <!-- Top 5 Dosen Penelitian -->
            <div class="chart-container md:col-span-2">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-trophy text-yellow-500 mr-2"></i>
                    Top 5 Dosen Produktif (Penelitian)
                </h3>
                <canvas id="topDosenChart"></canvas>
            </div>

            <!-- Pengabdian per Tahun -->
            <div class="chart-container">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                    Pengabdian per Tahun
                </h3>
                <canvas id="pengabdianPerTahunChart"></canvas>
            </div>

            <!-- Distribusi Skema Pengabdian -->
            <div class="chart-container">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-chart-pie text-green-500 mr-2"></i>
                    Distribusi Skema Pengabdian
                </h3>
                <canvas id="skemaPengabdianChart"></canvas>
            </div>

            <!-- Top 5 Dosen Pengabdian -->
            <div class="chart-container md:col-span-2">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-trophy text-yellow-500 mr-2"></i>
                    Top 5 Dosen Produktif (Pengabdian)
                </h3>
                <canvas id="topDosenPengabdianChart"></canvas>
            </div>

            <!-- HAKI per Tahun -->
            <div class="chart-container">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                    HAKI per Tahun
                </h3>
                <canvas id="hakiPerTahunChart"></canvas>
            </div>

            <!-- Distribusi Status HAKI -->
            <div class="chart-container">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-chart-pie text-green-500 mr-2"></i>
                    Distribusi Status HAKI
                </h3>
                <canvas id="statusHakiChart"></canvas>
            </div>

            <!-- Top 5 Dosen HAKI -->
            <div class="chart-container md:col-span-2">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-trophy text-yellow-500 mr-2"></i>
                    Top 5 Dosen Produktif (HAKI)
                </h3>
                <canvas id="topDosenHakiChart"></canvas>
            </div>

            <!-- Paten per Tahun -->
            <div class="chart-container">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                    Paten per Tahun
                </h3>
                <canvas id="patenPerTahunChart"></canvas>
            </div>

            <!-- Distribusi Jenis Paten -->
            <div class="chart-container">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-chart-pie text-green-500 mr-2"></i>
                    Distribusi Jenis Paten
                </h3>
                <canvas id="jenisPatenChart"></canvas>
            </div>

            <!-- Top 5 Dosen Paten -->
            <div class="chart-container md:col-span-2">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <i class="fas fa-trophy text-yellow-500 mr-2"></i>
                    Top 5 Dosen Produktif (Paten)
                </h3>
                <canvas id="topDosenPatenChart"></canvas>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/analytics.js') }}"></script>
    <script>
        // Pass PHP data to JavaScript
        window.analyticsData = {
            penelitianPerTahun: {
                labels: @json(array_keys($penelitianPerTahun)),
                data: @json(array_values($penelitianPerTahun))
            },
            skemaPenelitian: {
                labels: @json(array_keys($skemaPenelitian)),
                data: @json(array_values($skemaPenelitian))
            },
            topDosen: {
                labels: @json(array_column($topDosen, 'nama')),
                data: @json(array_column($topDosen, 'total_penelitian'))
            },
            pengabdianPerTahun: {
                labels: @json(array_keys($pengabdianPerTahun)),
                data: @json(array_values($pengabdianPerTahun))
            },
            skemaPengabdian: {
                labels: @json(array_keys($skemaPengabdian)),
                data: @json(array_values($skemaPengabdian))
            },
            topDosenPengabdian: {
                labels: @json(array_column($topDosenPengabdian, 'nama')),
                data: @json(array_column($topDosenPengabdian, 'total_pengabdian'))
            },
            hakiPerTahun: {
                labels: @json(array_keys($hakiPerTahun)),
                data: @json(array_values($hakiPerTahun))
            },
            statusHaki: {
                labels: @json(array_keys($statusHaki)),
                data: @json(array_values($statusHaki))
            },
            topDosenHaki: {
                labels: @json(array_column($topDosenHaki, 'nama')),
                data: @json(array_column($topDosenHaki, 'total_haki'))
            },
            patenPerTahun: {
                labels: @json(array_keys($patenPerTahun)),
                data: @json(array_values($patenPerTahun))
            },
            jenisPaten: {
                labels: @json(array_keys($jenisPaten)),
                data: @json(array_values($jenisPaten))
            },
            topDosenPaten: {
                labels: @json(array_column($topDosenPaten, 'nama')),
                data: @json(array_column($topDosenPaten, 'total_paten'))
            }
        };
    </script>
@endsection