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
        
        /* Enhanced Status Badges - Sama seperti halaman index */
        .status-badge {
            @apply px-3 py-1.5 rounded-full text-xs font-semibold inline-flex items-center gap-1;
        }
        
        .status-selesai { 
            @apply bg-gradient-to-r from-green-50 to-green-100 text-green-700 border border-green-200;
        }
        
        .status-berjalan { 
            @apply bg-gradient-to-r from-yellow-50 to-yellow-100 text-yellow-700 border border-yellow-200;
        }
        
        .status-diajukan { 
            @apply bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 border border-blue-200;
        }
        
        .status-aktif {
            @apply bg-gradient-to-r from-emerald-50 to-emerald-100 text-emerald-700 border border-emerald-200;
        }
        
        .status-expired {
            @apply bg-gradient-to-r from-red-50 to-red-100 text-red-700 border border-red-200;
        }
        
        /* Enhanced Buttons */
        .action-btn { 
            @apply transition-all duration-200 hover:scale-105 hover:shadow-md; 
        }
        
        .table-row:hover { 
            @apply bg-gradient-to-r from-gray-50 to-blue-50 transition-all duration-150; 
        }
        
        /* Enhanced Tabs - Sama seperti halaman index */
        .tab-link {
            @apply relative font-medium transition-all duration-200;
        }
        
        .tab-link.active {
            @apply text-blue-600 bg-blue-50;
        }
        
        .tab-link.active::after {
            content: '';
            @apply absolute bottom-0 left-0 right-0 h-0.5 bg-blue-600;
        }
        
        .tab-link:not(.active):hover {
            @apply bg-gray-50 text-blue-600;
        }
        
        /* Enhanced Stat Cards */
        .stat-card {
            @apply bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 hover:-translate-y-1;
        }
        
        /* Modern Buttons - Sama seperti halaman index */
        .btn-primary {
            @apply bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-5 py-2.5 rounded-lg flex items-center gap-2 shadow-md hover:shadow-lg transition-all duration-200;
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
        
        .tab-content {
            animation: fadeInUp 0.3s ease-out;
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
        
        /* Table Enhancements */
        .data-table {
            @apply rounded-lg overflow-hidden;
        }
        
        .data-table thead tr {
            @apply bg-gray-100 text-gray-700;
        }
        
        /* Search Input */
        .search-input {
            @apply pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all duration-200;
        }
        
        /* Badge计数器样式 - Sama seperti halaman index */
        .count-badge {
            @apply ml-1 px-2 py-0.5 bg-gray-100 rounded-full text-xs;
        }
        
        .badge-blue {
            @apply inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800;
        }
        
        .badge-green {
            @apply inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800;
        }
        
        .badge-yellow {
            @apply inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800;
        }
        
        .badge-purple {
            @apply inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section dengan Icon yang konsisten -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                    <i class="fas fa-home text-blue-600"></i>
                    <span>/</span>
                    <span>Dashboard</span>
                    <span>/</span>
                    <a href="{{ route('admin.dosen.index') }}" class="hover:text-blue-600">Dosen</a>
                    <span>/</span>
                    <span class="text-gray-700">{{ $dosen->nama }}</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-user-tie text-blue-600 mr-2"></i> Detail Dosen
                </h1>
                <p class="text-gray-600 mt-1">Informasi lengkap dosen dan portofolio karya</p>
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

        <!-- Stats Cards dengan Icon yang konsisten -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="stat-card">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500 bg-opacity-75">
                        <i class="fas fa-flask text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Penelitian</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $dosen->penelitians->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-500 bg-opacity-75">
                        <i class="fas fa-hands-helping text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pengabdian</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $dosen->pengabdians->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-500 bg-opacity-75">
                        <i class="fas fa-copyright text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">HAKI</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $dosen->hakis->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-500 bg-opacity-75">
                        <i class="fas fa-certificate text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Paten</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $dosen->patens->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Dosen Info Banner -->
            <div class="gradient-bg px-6 py-6">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="flex-shrink-0">
                        @if ($dosen->foto)
                            <img src="{{ Storage::url($dosen->foto) }}" alt="{{ $dosen->nama }}" class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-lg">
                        @else
                            <div class="bg-gray-200 border-2 border-dashed rounded-full w-24 h-24 flex items-center justify-center text-gray-500">
                                <i class="fas fa-user text-3xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="text-center md:text-left text-white">
                        <h2 class="text-2xl font-bold mb-2">{{ $dosen->nama }}</h2>
                        <div class="flex flex-wrap gap-4 justify-center md:justify-start text-white text-opacity-90 text-sm">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-id-card"></i>
                                <span>NIDN: {{ $dosen->nidn }}</span>
                            </div>
                            @if ($dosen->nip)
                            <div class="flex items-center gap-2">
                                <i class="fas fa-id-badge"></i>
                                <span>NIP: {{ $dosen->nip }}</span>
                            </div>
                            @endif
                            @if ($dosen->nuptk)
                            <div class="flex items-center gap-2">
                                <i class="fas fa-qrcode"></i>
                                <span>NUPTK: {{ $dosen->nuptk }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Portfolio Tabs dengan Icon konsisten -->
            <div class="border-b">
                <ul class="flex flex-wrap" role="tablist">
                    <li class="flex-1 min-w-[150px]">
                        <a class="tab-link py-4 px-6 block text-center hover:bg-gray-50" data-tab="penelitian" role="tab">
                            <i class="fas fa-flask text-blue-600 mr-2"></i>Penelitian
                            <span class="count-badge">{{ $dosen->penelitians->count() }}</span>
                        </a>
                    </li>
                    <li class="flex-1 min-w-[150px]">
                        <a class="tab-link py-4 px-6 block text-center hover:bg-gray-50" data-tab="pengabdian" role="tab">
                            <i class="fas fa-hands-helping text-green-600 mr-2"></i>Pengabdian
                            <span class="count-badge">{{ $dosen->pengabdians->count() }}</span>
                        </a>
                    </li>
                    <li class="flex-1 min-w-[150px]">
                        <a class="tab-link py-4 px-6 block text-center hover:bg-gray-50" data-tab="haki" role="tab">
                            <i class="fas fa-copyright text-yellow-600 mr-2"></i>HAKI
                            <span class="count-badge">{{ $dosen->hakis->count() }}</span>
                        </a>
                    </li>
                    <li class="flex-1 min-w-[150px]">
                        <a class="tab-link py-4 px-6 block text-center hover:bg-gray-50" data-tab="paten" role="tab">
                            <i class="fas fa-certificate text-purple-600 mr-2"></i>Paten
                            <span class="count-badge">{{ $dosen->patens->count() }}</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Tab Content -->
            <div class="p-4 overflow-x-auto">
                <!-- Penelitian Tab -->
                <div id="penelitian" class="tab-content" role="tabpanel">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-3">
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-flask text-blue-600 mr-2"></i> Data Penelitian ({{ $dosen->penelitians->count() }} penelitian)
                        </h3>
                        <div class="relative w-full md:w-64">
                            <input type="text" id="searchPenelitian" placeholder="Cari penelitian..." class="search-input w-full">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-max">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700">
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
                                    <tr class="table-row hover:bg-gray-50">
                                        <td class="py-3 px-4 border-b">{{ $no++ }}</td>
                                        <td class="py-3 px-4 border-b font-medium">{{ $penelitian->judul_penelitian }}</td>
                                        <td class="py-3 px-4 border-b">
                                            <span class="badge-blue">{{ $penelitian->skema }}</span>
                                        </td>
                                        <td class="py-3 px-4 border-b">{{ $penelitian->posisi }}</td>
                                        <td class="py-3 px-4 border-b">{{ $penelitian->sumber_dana }}</td>
                                        <td class="py-3 px-4 border-b">
                                            <span class="status-badge {{ $penelitian->status == 'Selesai' ? 'status-selesai' : ($penelitian->status == 'Berjalan' ? 'status-berjalan' : 'status-diajukan') }}">
                                                <i class="fas {{ $penelitian->status == 'Selesai' ? 'fa-check-circle' : ($penelitian->status == 'Berjalan' ? 'fa-hourglass-half' : 'fa-clock') }} text-xs"></i>
                                                {{ $penelitian->status }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 border-b">{{ $penelitian->tahun }}</td>
                                        <td class="py-3 px-4 border-b">
                                            @if ($penelitian->link_luaran)
                                                <a href="{{ $penelitian->link_luaran }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-external-link-alt"></i> Lihat
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
                <div id="pengabdian" class="tab-content hidden" role="tabpanel">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-3">
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-hands-helping text-green-600 mr-2"></i> Data Pengabdian ({{ $dosen->pengabdians->count() }} pengabdian)
                        </h3>
                        <div class="relative w-full md:w-64">
                            <input type="text" id="searchPengabdian" placeholder="Cari pengabdian..." class="search-input w-full">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-max">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700">
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
                                    <tr class="table-row hover:bg-gray-50">
                                        <td class="py-3 px-4 border-b">{{ $no++ }}</td>
                                        <td class="py-3 px-4 border-b font-medium">{{ $pengabdian->judul_pengabdian }}</td>
                                        <td class="py-3 px-4 border-b">
                                            <span class="badge-green">{{ $pengabdian->skema }}</span>
                                        </td>
                                        <td class="py-3 px-4 border-b">{{ $pengabdian->posisi }}</td>
                                        <td class="py-3 px-4 border-b">{{ $pengabdian->sumber_dana }}</td>
                                        <td class="py-3 px-4 border-b">
                                            <span class="status-badge {{ $pengabdian->status == 'Selesai' ? 'status-selesai' : ($pengabdian->status == 'Berjalan' ? 'status-berjalan' : 'status-diajukan') }}">
                                                <i class="fas {{ $pengabdian->status == 'Selesai' ? 'fa-check-circle' : ($pengabdian->status == 'Berjalan' ? 'fa-hourglass-half' : 'fa-clock') }} text-xs"></i>
                                                {{ $pengabdian->status }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 border-b">{{ $pengabdian->tahun }}</td>
                                        <td class="py-3 px-4 border-b">
                                            @if ($pengabdian->link_luaran)
                                                <a href="{{ $pengabdian->link_luaran }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-external-link-alt"></i> Lihat
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
                <div id="haki" class="tab-content hidden" role="tabpanel">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-3">
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-copyright text-yellow-600 mr-2"></i> Data HAKI ({{ $dosen->hakis->count() }} HAKI)
                        </h3>
                        <div class="relative w-full md:w-64">
                            <input type="text" id="searchHaki" placeholder="Cari HAKI..." class="search-input w-full">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-max">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700">
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
                                    <tr class="table-row hover:bg-gray-50">
                                        <td class="py-3 px-4 border-b">{{ $no++ }}</td>
                                        <td class="py-3 px-4 border-b font-medium">{{ $haki->judul_haki }}</td>
                                        <td class="py-3 px-4 border-b">
                                            @if ($haki->expired)
                                                {{ \Carbon\Carbon::parse($haki->expired)->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 border-b">
                                            @php
                                                $expiredDate = $haki->expired ? \Carbon\Carbon::parse($haki->expired) : null;
                                                $isExpired = $expiredDate && $expiredDate->isPast();
                                            @endphp
                                            <span class="status-badge {{ $isExpired ? 'status-expired' : 'status-aktif' }}">
                                                <i class="fas {{ $isExpired ? 'fa-times-circle' : 'fa-check-circle' }} text-xs"></i>
                                                {{ $isExpired ? 'Expired' : 'Aktif' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 border-b">
                                            @if ($haki->link)
                                                <a href="{{ $haki->link }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-external-link-alt"></i> Lihat
                                                </a>
                                            @else
                                                -
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
                <div id="paten" class="tab-content hidden" role="tabpanel">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-3">
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-certificate text-purple-600 mr-2"></i> Data Paten ({{ $dosen->patens->count() }} paten)
                        </h3>
                        <div class="relative w-full md:w-64">
                            <input type="text" id="searchPaten" placeholder="Cari paten..." class="search-input w-full">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-max">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700">
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
                                    <tr class="table-row hover:bg-gray-50">
                                        <td class="py-3 px-4 border-b">{{ $no++ }}</td>
                                        <td class="py-3 px-4 border-b font-medium">{{ $paten->judul_paten }}</td>
                                        <td class="py-3 px-4 border-b">
                                            <span class="badge-purple">{{ $paten->jenis_paten }}</span>
                                        </td>
                                        <td class="py-3 px-4 border-b">
                                            @if ($paten->expired)
                                                {{ \Carbon\Carbon::parse($paten->expired)->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 border-b">
                                            @php
                                                $expiredDate = $paten->expired ? \Carbon\Carbon::parse($paten->expired) : null;
                                                $isExpired = $expiredDate && $expiredDate->isPast();
                                            @endphp
                                            <span class="status-badge {{ $isExpired ? 'status-expired' : 'status-aktif' }}">
                                                <i class="fas {{ $isExpired ? 'fa-times-circle' : 'fa-check-circle' }} text-xs"></i>
                                                {{ $isExpired ? 'Expired' : 'Aktif' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 border-b">
                                            @if ($paten->link)
                                                <a href="{{ $paten->link }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-external-link-alt"></i> Lihat
                                                </a>
                                            @else
                                                -
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
            // Tab switching
            $('.tab-link').click(function(e) {
                e.preventDefault();
                
                // Get tab id
                const tabId = $(this).data('tab');
                
                // Hide all tabs
                $('.tab-content').addClass('hidden');
                $('#' + tabId).removeClass('hidden');
                
                // Update active tab styling
                $('.tab-link').removeClass('active');
                $(this).addClass('active');
            });
            
            // Search functionality for each tab
            $('#searchPenelitian').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                $('#penelitian tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
            
            $('#searchPengabdian').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                $('#pengabdian tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
            
            $('#searchHaki').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                $('#haki tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
            
            $('#searchPaten').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                $('#paten tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
            
            // Activate first tab by default
            $('.tab-link:first').addClass('active');
            $('#penelitian').removeClass('hidden');
        });
    </script>
</body>
</html>