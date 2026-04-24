{{-- resources/views/admin/dosen/show.blade.php --}}

<!DOCTYPE html>
<html>
<head>
    <title>Detail Dosen - {{ $dosen->nama }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            transition: all 0.2s ease;
        }
        
        /* Modern Card Shadow */
        .card-shadow {
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        /* Enhanced Status Badges */
        .status-badge {
            @apply px-3 py-1.5 rounded-full text-xs font-semibold inline-flex items-center gap-1;
        }
        
        .status-selesai { 
            @apply bg-gradient-to-r from-emerald-50 to-emerald-100 text-emerald-700 border border-emerald-200;
        }
        
        .status-berjalan { 
            @apply bg-gradient-to-r from-amber-50 to-amber-100 text-amber-700 border border-amber-200;
        }
        
        .status-diajukan { 
            @apply bg-gradient-to-r from-sky-50 to-sky-100 text-sky-700 border border-sky-200;
        }
        
        .status-aktif {
            @apply bg-gradient-to-r from-teal-50 to-teal-100 text-teal-700 border border-teal-200;
        }
        
        .status-expired {
            @apply bg-gradient-to-r from-rose-50 to-rose-100 text-rose-700 border border-rose-200;
        }
        
        /* Enhanced Buttons */
        .action-btn { 
            @apply transition-all duration-200 hover:scale-105 hover:shadow-md; 
        }
        
        .table-row:hover { 
            @apply bg-gradient-to-r from-gray-50 to-indigo-50 transition-all duration-150 transform scale-[1.01]; 
        }
        
        /* Enhanced Tabs */
        .tab-link {
            @apply relative font-medium transition-all duration-200;
        }
        
        .tab-link.active {
            @apply text-indigo-600 bg-indigo-50 shadow-sm;
        }
        
        .tab-link.active::after {
            content: '';
            @apply absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-indigo-500 to-indigo-600;
        }
        
        .tab-link:not(.active):hover {
            @apply bg-gray-50 text-indigo-600 transform -translate-y-0.5;
        }
        
        /* Enhanced Stat Cards */
        .stat-card {
            @apply bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1;
        }
        
        /* Modern Buttons */
        .btn-primary {
            @apply bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white px-5 py-2.5 rounded-lg flex items-center gap-2 shadow-md hover:shadow-lg transition-all duration-200;
        }
        
        .btn-secondary {
            @apply bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-5 py-2.5 rounded-lg flex items-center gap-2 shadow-md hover:shadow-lg transition-all duration-200;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        .tab-content {
            animation: fadeInUp 0.4s ease-out;
        }
        
        .stat-card {
            animation: slideInLeft 0.5s ease-out;
        }
        
        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }
        
        /* Gradient Background */
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }
        
        .gradient-bg::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 10s ease-in-out infinite;
        }
        
        /* Table Enhancements */
        .data-table {
            @apply rounded-lg overflow-hidden;
        }
        
        .data-table thead tr {
            @apply bg-gradient-to-r from-gray-100 to-gray-50;
        }
        
        .data-table tbody tr {
            @apply transition-all duration-200;
        }
        
        /* Search Input */
        .search-input {
            @apply px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-500 transition-all duration-200;
        }
        
        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            @apply bg-gray-100 rounded;
        }
        
        ::-webkit-scrollbar-thumb {
            @apply bg-gray-400 rounded hover:bg-gray-500;
        }
        
        /* Loading Animation */
        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }
        
        .loading {
            animation: shimmer 2s infinite linear;
            background: linear-gradient(to right, #f6f7f8 0%, #edeef1 20%, #f6f7f8 40%, #f6f7f8 100%);
            background-size: 1000px 100%;
        }
        
        /* Tooltip */
        [data-tooltip] {
            position: relative;
            cursor: pointer;
        }
        
        [data-tooltip]:before {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            padding: 4px 8px;
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            font-size: 12px;
            border-radius: 4px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
            z-index: 10;
        }
        
        [data-tooltip]:hover:before {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-gray-50 to-indigo-50">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Breadcrumb & Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate-fadeIn">
            <div>
                <nav class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                    <i class="fas fa-home text-indigo-500"></i>
                    <span>/</span>
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('admin.dosen.index') }}" class="hover:text-indigo-600 transition-colors">Dosen</a>
                    <span>/</span>
                    <span class="text-gray-700 font-medium">{{ $dosen->nama }}</span>
                </nav>
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg">
                        <i class="fas fa-user-tie text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                            Detail Dosen
                        </h1>
                        <p class="text-gray-500 mt-1 flex items-center gap-2">
                            <i class="fas fa-university text-sm text-indigo-500"></i>
                            Fakultas Teknik Informatika UNIMA
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.dosen.edit', $dosen->id) }}" class="btn-primary">
                    <i class="fas fa-edit"></i> Edit Dosen
                </a>
                <a href="{{ route('admin.dosen.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Enhanced Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Total Penelitian</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $dosen->penelitians->count() }}</p>
                        <p class="text-xs text-gray-400 mt-1">Sepanjang karir</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-flask text-white text-2xl"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Aktif</span>
                        <span class="font-semibold text-blue-600">{{ $dosen->penelitians->where('status', 'Berjalan')->count() }} Berjalan</span>
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Total Pengabdian</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $dosen->pengabdians->count() }}</p>
                        <p class="text-xs text-gray-400 mt-1">Kontribusi masyarakat</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-hands-helping text-white text-2xl"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Selesai</span>
                        <span class="font-semibold text-green-600">{{ $dosen->pengabdians->where('status', 'Selesai')->count() }} Program</span>
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Total HAKI</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $dosen->hakis->count() }}</p>
                        <p class="text-xs text-gray-400 mt-1">Kekayaan intelektual</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-copyright text-white text-2xl"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Aktif</span>
                        <span class="font-semibold text-purple-600">{{ $dosen->hakis->filter(function($haki) { return !$haki->expired || !\Carbon\Carbon::parse($haki->expired)->isPast(); })->count() }} Lisensi</span>
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Total Paten</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $dosen->patens->count() }}</p>
                        <p class="text-xs text-gray-400 mt-1">Inovasi teknologi</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-400 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-certificate text-white text-2xl"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Terdaftar</span>
                        <span class="font-semibold text-orange-600">{{ $dosen->patens->count() }} Paten</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl card-shadow overflow-hidden">
            <!-- Enhanced Dosen Info Banner -->
            <div class="gradient-bg px-8 py-8">
                <div class="flex flex-col md:flex-row items-center gap-6 relative z-10">
                    <div class="flex-shrink-0 relative group">
                        @if ($dosen->foto)
                            <img src="{{ Storage::url($dosen->foto) }}" alt="{{ $dosen->nama }}" class="h-32 w-32 rounded-full object-cover border-4 border-white shadow-xl group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-full w-32 h-32 flex items-center justify-center border-4 border-white shadow-xl group-hover:scale-105 transition-transform duration-300">
                                <i class="fas fa-user-graduate text-white text-5xl"></i>
                            </div>
                        @endif
                        <div class="absolute bottom-2 right-2 bg-gradient-to-r from-green-400 to-green-500 rounded-full w-5 h-5 border-2 border-white shadow-md">
                            <div class="absolute inset-0 bg-green-400 rounded-full animate-ping opacity-75"></div>
                        </div>
                    </div>
                    <div class="text-center md:text-left text-white flex-1">
                        <h2 class="text-3xl font-bold mb-3 flex items-center gap-2 justify-center md:justify-start">
                            {{ $dosen->nama }}
                            <span class="text-sm bg-white bg-opacity-20 px-3 py-1 rounded-full">Dosen Aktif</span>
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-white text-opacity-90">
                            <div class="flex items-center gap-2 justify-center md:justify-start">
                                <i class="fas fa-id-card"></i>
                                <span>NIDN: <span class="font-mono">{{ $dosen->nidn }}</span></span>
                            </div>
                            @if ($dosen->nip)
                            <div class="flex items-center gap-2 justify-center md:justify-start">
                                <i class="fas fa-id-badge"></i>
                                <span>NIP: <span class="font-mono">{{ $dosen->nip }}</span></span>
                            </div>
                            @endif
                            @if ($dosen->nuptk)
                            <div class="flex items-center gap-2 justify-center md:justify-start">
                                <i class="fas fa-qrcode"></i>
                                <span>NUPTK: <span class="font-mono">{{ $dosen->nuptk }}</span></span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Portfolio Tabs -->
            <div class="border-b border-gray-200 px-6 bg-gray-50">
                <ul class="flex flex-wrap gap-2">
                    <li>
                        <a class="tab-link inline-flex items-center gap-2 py-4 px-6 rounded-t-lg transition-all" data-tab="penelitian">
                            <i class="fas fa-flask text-blue-600"></i>
                            <span class="font-medium">Penelitian</span>
                            <span class="ml-1 px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">{{ $dosen->penelitians->count() }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="tab-link inline-flex items-center gap-2 py-4 px-6 rounded-t-lg transition-all" data-tab="pengabdian">
                            <i class="fas fa-hands-helping text-green-600"></i>
                            <span class="font-medium">Pengabdian</span>
                            <span class="ml-1 px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-semibold">{{ $dosen->pengabdians->count() }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="tab-link inline-flex items-center gap-2 py-4 px-6 rounded-t-lg transition-all" data-tab="haki">
                            <i class="fas fa-copyright text-purple-600"></i>
                            <span class="font-medium">HAKI</span>
                            <span class="ml-1 px-2 py-0.5 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">{{ $dosen->hakis->count() }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="tab-link inline-flex items-center gap-2 py-4 px-6 rounded-t-lg transition-all" data-tab="paten">
                            <i class="fas fa-certificate text-orange-600"></i>
                            <span class="font-medium">Paten</span>
                            <span class="ml-1 px-2 py-0.5 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold">{{ $dosen->patens->count() }}</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Penelitian Tab -->
                <div id="penelitian" class="tab-content">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-flask text-blue-600"></i> 
                            Data Penelitian
                        </h3>
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" id="searchPenelitian" placeholder="Cari judul penelitian..." class="search-input pl-9 w-64">
                        </div>
                    </div>
                    <div class="overflow-x-auto rounded-lg border border-gray-200 data-table">
                        <table class="w-full min-w-max">
                            <thead>
                                <tr class="bg-gradient-to-r from-gray-100 to-gray-50 text-gray-700">
                                    <th class="py-3 px-4 text-left rounded-tl-lg">No</th>
                                    <th class="py-3 px-4 text-left">Judul Penelitian</th>
                                    <th class="py-3 px-4 text-left">Skema</th>
                                    <th class="py-3 px-4 text-left">Posisi</th>
                                    <th class="py-3 px-4 text-left">Sumber Dana</th>
                                    <th class="py-3 px-4 text-left">Status</th>
                                    <th class="py-3 px-4 text-left">Tahun</th>
                                    <th class="py-3 px-4 text-left rounded-tr-lg">Luaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @forelse ($dosen->penelitians as $penelitian)
                                    <tr class="table-row border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-3 px-4 font-medium text-gray-600">{{ $no++ }}</td>
                                        <td class="py-3 px-4 max-w-xs">
                                            <div class="font-medium text-gray-800">{{ $penelitian->judul_penelitian }}</div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium">{{ $penelitian->skema }}</span>
                                        </td>
                                        <td class="py-3 px-4">{{ $penelitian->posisi }}</td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-1">
                                                <i class="fas fa-money-bill-wave text-gray-400 text-xs"></i>
                                                {{ $penelitian->sumber_dana }}
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="status-badge {{ $penelitian->status == 'Selesai' ? 'status-selesai' : ($penelitian->status == 'Berjalan' ? 'status-berjalan' : 'status-diajukan') }}">
                                                <i class="fas {{ $penelitian->status == 'Selesai' ? 'fa-check-circle' : ($penelitian->status == 'Berjalan' ? 'fa-hourglass-half' : 'fa-clock') }} text-xs"></i>
                                                {{ $penelitian->status }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-1">
                                                <i class="far fa-calendar-alt text-gray-400 text-xs"></i>
                                                {{ $penelitian->tahun }}
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            @if ($penelitian->link_luaran)
                                                <a href="{{ $penelitian->link_luaran }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 flex items-center gap-1 transition-colors" data-tooltip="Lihat luaran penelitian">
                                                    <i class="fas fa-external-link-alt text-xs"></i> Lihat
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-8 text-center text-gray-500">
                                            <i class="fas fa-inbox fa-3x mb-2 text-gray-300"></i>
                                            <p>Belum ada data penelitian</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pengabdian Tab -->
                <div id="pengabdian" class="tab-content hidden">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-hands-helping text-green-600"></i> 
                            Data Pengabdian
                        </h3>
                    </div>
                    <div class="overflow-x-auto rounded-lg border border-gray-200 data-table">
                        <table class="w-full min-w-max">
                            <thead>
                                <tr class="bg-gradient-to-r from-gray-100 to-gray-50 text-gray-700">
                                    <th class="py-3 px-4 text-left rounded-tl-lg">No</th>
                                    <th class="py-3 px-4 text-left">Judul Pengabdian</th>
                                    <th class="py-3 px-4 text-left">Skema</th>
                                    <th class="py-3 px-4 text-left">Posisi</th>
                                    <th class="py-3 px-4 text-left">Sumber Dana</th>
                                    <th class="py-3 px-4 text-left">Status</th>
                                    <th class="py-3 px-4 text-left">Tahun</th>
                                    <th class="py-3 px-4 text-left rounded-tr-lg">Luaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @forelse ($dosen->pengabdians as $pengabdian)
                                    <tr class="table-row border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-3 px-4 font-medium text-gray-600">{{ $no++ }}</td>
                                        <td class="py-3 px-4 max-w-xs">
                                            <div class="font-medium text-gray-800">{{ $pengabdian->judul_pengabdian }}</div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-1 bg-green-50 text-green-700 rounded-lg text-xs font-medium">{{ $pengabdian->skema }}</span>
                                        </td>
                                        <td class="py-3 px-4">{{ $pengabdian->posisi }}</td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-1">
                                                <i class="fas fa-money-bill-wave text-gray-400 text-xs"></i>
                                                {{ $pengabdian->sumber_dana }}
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="status-badge {{ $pengabdian->status == 'Selesai' ? 'status-selesai' : ($pengabdian->status == 'Berjalan' ? 'status-berjalan' : 'status-diajukan') }}">
                                                <i class="fas {{ $pengabdian->status == 'Selesai' ? 'fa-check-circle' : ($pengabdian->status == 'Berjalan' ? 'fa-hourglass-half' : 'fa-clock') }} text-xs"></i>
                                                {{ $pengabdian->status }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-1">
                                                <i class="far fa-calendar-alt text-gray-400 text-xs"></i>
                                                {{ $pengabdian->tahun }}
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            @if ($pengabdian->link_luaran)
                                                <a href="{{ $pengabdian->link_luaran }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 flex items-center gap-1 transition-colors" data-tooltip="Lihat luaran pengabdian">
                                                    <i class="fas fa-external-link-alt text-xs"></i> Lihat
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                     </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-8 text-center text-gray-500">
                                            <i class="fas fa-inbox fa-3x mb-2 text-gray-300"></i>
                                            <p>Belum ada data pengabdian</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- HAKI Tab -->
                <div id="haki" class="tab-content hidden">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-copyright text-purple-600"></i> 
                        Data HAKI
                    </h3>
                    <div class="overflow-x-auto rounded-lg border border-gray-200 data-table">
                        <table class="w-full min-w-max">
                            <thead>
                                <tr class="bg-gradient-to-r from-gray-100 to-gray-50 text-gray-700">
                                    <th class="py-3 px-4 text-left rounded-tl-lg">No</th>
                                    <th class="py-3 px-4 text-left">Judul HAKI</th>
                                    <th class="py-3 px-4 text-left">Expired</th>
                                    <th class="py-3 px-4 text-left">Status</th>
                                    <th class="py-3 px-4 text-left rounded-tr-lg">Link</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @forelse ($dosen->hakis as $haki)
                                    <tr class="table-row border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-3 px-4 font-medium text-gray-600">{{ $no++ }}</td>
                                        <td class="py-3 px-4 max-w-xs">
                                            <div class="font-medium text-gray-800">{{ $haki->judul_haki }}</div>
                                        </td>
                                        <td class="py-3 px-4">
                                            @if ($haki->expired)
                                                <div class="flex items-center gap-1">
                                                    <i class="far fa-calendar-alt text-gray-400 text-xs"></i>
                                                    {{ \Carbon\Carbon::parse($haki->expired)->format('d M Y') }}
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">
                                            @php
                                                $expiredDate = $haki->expired ? \Carbon\Carbon::parse($haki->expired) : null;
                                                $isExpired = $expiredDate && $expiredDate->isPast();
                                            @endphp
                                            <span class="status-badge {{ $isExpired ? 'status-expired' : 'status-aktif' }}">
                                                <i class="fas {{ $isExpired ? 'fa-times-circle' : 'fa-check-circle' }} text-xs"></i>
                                                {{ $isExpired ? 'Expired' : 'Aktif' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            @if ($haki->link)
                                                <a href="{{ $haki->link }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 flex items-center gap-1 transition-colors" data-tooltip="Lihat dokumen HAKI">
                                                    <i class="fas fa-external-link-alt text-xs"></i> Lihat
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                     </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-gray-500">
                                            <i class="fas fa-inbox fa-3x mb-2 text-gray-300"></i>
                                            <p>Belum ada data HAKI</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paten Tab -->
                <div id="paten" class="tab-content hidden">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-certificate text-orange-600"></i> 
                        Data Paten
                    </h3>
                    <div class="overflow-x-auto rounded-lg border border-gray-200 data-table">
                        <table class="w-full min-w-max">
                            <thead>
                                <tr class="bg-gradient-to-r from-gray-100 to-gray-50 text-gray-700">
                                    <th class="py-3 px-4 text-left rounded-tl-lg">No</th>
                                    <th class="py-3 px-4 text-left">Judul Paten</th>
                                    <th class="py-3 px-4 text-left">Jenis</th>
                                    <th class="py-3 px-4 text-left">Expired</th>
                                    <th class="py-3 px-4 text-left">Status</th>
                                    <th class="py-3 px-4 text-left rounded-tr-lg">Link</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @forelse ($dosen->patens as $paten)
                                    <tr class="table-row border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-3 px-4 font-medium text-gray-600">{{ $no++ }}</td>
                                        <td class="py-3 px-4 max-w-xs">
                                            <div class="font-medium text-gray-800">{{ $paten->judul_paten }}</div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-1 bg-orange-50 text-orange-700 rounded-lg text-xs font-medium">{{ $paten->jenis_paten }}</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            @if ($paten->expired)
                                                <div class="flex items-center gap-1">
                                                    <i class="far fa-calendar-alt text-gray-400 text-xs"></i>
                                                    {{ \Carbon\Carbon::parse($paten->expired)->format('d M Y') }}
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">
                                            @php
                                                $expiredDate = $paten->expired ? \Carbon\Carbon::parse($paten->expired) : null;
                                                $isExpired = $expiredDate && $expiredDate->isPast();
                                            @endphp
                                            <span class="status-badge {{ $isExpired ? 'status-expired' : 'status-aktif' }}">
                                                <i class="fas {{ $isExpired ? 'fa-times-circle' : 'fa-check-circle' }} text-xs"></i>
                                                {{ $isExpired ? 'Expired' : 'Aktif' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            @if ($paten->link)
                                                <a href="{{ $paten->link }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 flex items-center gap-1 transition-colors" data-tooltip="Lihat dokumen paten">
                                                    <i class="fas fa-external-link-alt text-xs"></i> Lihat
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-8 text-center text-gray-500">
                                            <i class="fas fa-inbox fa-3x mb-2 text-gray-300"></i>
                                            <p>Belum ada data paten</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Enhanced tab switching with fade animation
            $('.tab-link').click(function(e) {
                e.preventDefault();
                
                // Get tab id
                const tabId = $(this).data('tab');
                
                // Hide all tabs with smooth fadeout
                $('.tab-content').fadeOut(200, function() {
                    $('.tab-content').addClass('hidden');
                    $('#' + tabId).removeClass('hidden');
                    $('#' + tabId).fadeIn(250);
                });
                
                // Update active tab styling
                $('.tab-link').removeClass('active bg-indigo-50 text-indigo-600 shadow-sm');
                $(this).addClass('active bg-indigo-50 text-indigo-600 shadow-sm');
                
                // Smooth scroll to tab content
                $('html, body').animate({
                    scrollTop: $('.tab-link:first').offset().top - 100
                }, 300);
            });
            
            // Enhanced search functionality for penelitian
            let searchTimeout;
            $('#searchPenelitian').on('keyup', function() {
                clearTimeout(searchTimeout);
                const value = $(this).val().toLowerCase();
                
                searchTimeout = setTimeout(() => {
                    const rows = $('#penelitian tbody tr');
                    let visibleCount = 0;
                    
                    rows.each(function() {
                        const text = $(this).text().toLowerCase();
                        if (text.indexOf(value) > -1) {
                            $(this).show().css('animation', 'fadeInUp 0.3s ease-out');
                            visibleCount++;
                        } else {
                            $(this).hide();
                        }
                    });
                    
                    // Show/hide no results message
                    if (visibleCount === 0 && rows.length > 0) {
                        if ($('#noSearchResults').length === 0) {
                            $('#penelitian tbody').append(`
                                <tr id="noSearchResults">
                                    <td colspan="8" class="py-8 text-center text-gray-500">
                                        <i class="fas fa-search fa-3x mb-2 text-gray-300"></i>
                                        <p>Tidak ditemukan penelitian dengan kata kunci "${value}"</p>
                                    </td>
                                </tr>
                            `);
                        }
                    } else {
                        $('#noSearchResults').remove();
                    }
                }, 300);
            });
            
            // Activate first tab by default with animation
            $('.tab-link:first').addClass('active bg-indigo-50 text-indigo-600 shadow-sm');
            $('#penelitian').show();
            
            // Add hover effect to table rows
            $('.table-row').hover(
                function() {
                    $(this).css('transform', 'scale(1.01)');
                },
                function() {
                    $(this).css('transform', 'scale(1)');
                }
            );
        });
    </script>
</body>
</html>