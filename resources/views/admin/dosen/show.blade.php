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
        
        .card-shadow {
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
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
        
        .action-btn { 
            @apply transition-all duration-200 hover:scale-105 hover:shadow-md; 
        }
        
        .table-row:hover { 
            @apply bg-gradient-to-r from-gray-50 to-blue-50 transition-all duration-150; 
        }
        
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
        
        .stat-card {
            @apply bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-200;
        }
        
        .btn-primary {
            @apply bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-5 py-2.5 rounded-lg flex items-center gap-2 shadow-sm hover:shadow-md transition-all duration-200;
        }
        
        .btn-secondary {
            @apply bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-5 py-2.5 rounded-lg flex items-center gap-2 shadow-sm hover:shadow-md transition-all duration-200;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .tab-content {
            animation: fadeIn 0.3s ease-out;
        }
        
        .glow {
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.3);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                    <i class="fas fa-home"></i>
                    <span>/</span>
                    <span>Dashboard</span>
                    <span>/</span>
                    <span>Dosen</span>
                    <span>/</span>
                    <span class="text-gray-700">{{ $dosen->nama }}</span>
                </div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent flex items-center gap-2">
                    <i class="fas fa-user-tie text-blue-600"></i> Detail Dosen
                </h1>
                <p class="text-gray-500 mt-1 flex items-center gap-2">
                    <i class="fas fa-university text-sm"></i> Fakultas Teknik Informatika UNIMA
                </p>
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

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Penelitian</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $dosen->penelitians->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-flask text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Pengabdian</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $dosen->pengabdians->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-hands-helping text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">HAKI</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $dosen->hakis->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-copyright text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Paten</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $dosen->patens->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-certificate text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl card-shadow overflow-hidden">
            <!-- Dosen Info Banner -->
            <div class="gradient-bg px-6 py-8">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="flex-shrink-0 relative">
                        @if ($dosen->foto)
                            <img src="{{ Storage::url($dosen->foto) }}" alt="{{ $dosen->nama }}" class="h-28 w-28 rounded-full object-cover border-4 border-white shadow-lg">
                        @else
                            <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-full w-28 h-28 flex items-center justify-center border-4 border-white shadow-lg">
                                <i class="fas fa-user-graduate text-white text-4xl"></i>
                            </div>
                        @endif
                        <div class="absolute bottom-0 right-0 bg-green-500 rounded-full w-5 h-5 border-2 border-white"></div>
                    </div>
                    <div class="text-center md:text-left text-white">
                        <h2 class="text-2xl font-bold mb-2">{{ $dosen->nama }}</h2>
                        <div class="flex flex-wrap gap-4 justify-center md:justify-start text-white text-opacity-90">
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

            <!-- Portfolio Tabs -->
            <div class="border-b border-gray-200 px-6">
                <ul class="flex flex-wrap gap-1">
                    <li>
                        <a class="tab-link inline-flex items-center gap-2 py-4 px-6 rounded-t-lg" data-tab="penelitian">
                            <i class="fas fa-flask text-blue-600"></i>
                            <span class="font-medium">Penelitian</span>
                            <span class="ml-1 px-2 py-0.5 bg-gray-100 rounded-full text-xs">{{ $dosen->penelitians->count() }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="tab-link inline-flex items-center gap-2 py-4 px-6 rounded-t-lg" data-tab="pengabdian">
                            <i class="fas fa-hands-helping text-green-600"></i>
                            <span class="font-medium">Pengabdian</span>
                            <span class="ml-1 px-2 py-0.5 bg-gray-100 rounded-full text-xs">{{ $dosen->pengabdians->count() }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="tab-link inline-flex items-center gap-2 py-4 px-6 rounded-t-lg" data-tab="haki">
                            <i class="fas fa-copyright text-purple-600"></i>
                            <span class="font-medium">HAKI</span>
                            <span class="ml-1 px-2 py-0.5 bg-gray-100 rounded-full text-xs">{{ $dosen->hakis->count() }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="tab-link inline-flex items-center gap-2 py-4 px-6 rounded-t-lg" data-tab="paten">
                            <i class="fas fa-certificate text-orange-600"></i>
                            <span class="font-medium">Paten</span>
                            <span class="ml-1 px-2 py-0.5 bg-gray-100 rounded-full text-xs">{{ $dosen->patens->count() }}</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Penelitian Tab -->
                <div id="penelitian" class="tab-content">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-flask text-blue-600"></i> Data Penelitian
                        </h3>
                        <div class="flex gap-2">
                            <input type="text" id="searchPenelitian" placeholder="Cari penelitian..." class="px-3 py-1 border rounded-lg text-sm">
                        </div>
                    </div>
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
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
                                @foreach ($dosen->penelitians as $penelitian)
                                    <tr class="table-row border-b border-gray-100">
                                        <td class="py-3 px-4 font-medium text-gray-600">{{ $no++ }}</td>
                                        <td class="py-3 px-4 max-w-xs">
                                            <div class="font-medium text-gray-800">{{ $penelitian->judul_penelitian }}</div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs">{{ $penelitian->skema }}</span>
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
                                                <a href="{{ $penelitian->link_luaran }}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                                    <i class="fas fa-external-link-alt text-xs"></i> Lihat
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pengabdian Tab -->
                <div id="pengabdian" class="tab-content hidden">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-hands-helping text-green-600"></i> Data Pengabdian
                        </h3>
                    </div>
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
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
                                @foreach ($dosen->pengabdians as $pengabdian)
                                    <tr class="table-row border-b border-gray-100">
                                        <td class="py-3 px-4 font-medium text-gray-600">{{ $no++ }}</td>
                                        <td class="py-3 px-4 max-w-xs">
                                            <div class="font-medium text-gray-800">{{ $pengabdian->judul_pengabdian }}</div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-1 bg-green-50 text-green-700 rounded-lg text-xs">{{ $pengabdian->skema }}</span>
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
                                                <a href="{{ $pengabdian->link_luaran }}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                                    <i class="fas fa-external-link-alt text-xs"></i> Lihat
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- HAKI Tab -->
                <div id="haki" class="tab-content hidden">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-copyright text-purple-600"></i> Data HAKI
                    </h3>
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
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
                                @foreach ($dosen->hakis as $haki)
                                    <tr class="table-row border-b border-gray-100">
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
                                                <a href="{{ $haki->link }}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                                    <i class="fas fa-external-link-alt text-xs"></i> Lihat
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paten Tab -->
                <div id="paten" class="tab-content hidden">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-certificate text-orange-600"></i> Data Paten
                    </h3>
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
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
                                @foreach ($dosen->patens as $paten)
                                    <tr class="table-row border-b border-gray-100">
                                        <td class="py-3 px-4 font-medium text-gray-600">{{ $no++ }}</td>
                                        <td class="py-3 px-4 max-w-xs">
                                            <div class="font-medium text-gray-800">{{ $paten->judul_paten }}</div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-1 bg-orange-50 text-orange-700 rounded-lg text-xs">{{ $paten->jenis_paten }}</span>
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
                                                <a href="{{ $paten->link }}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                                    <i class="fas fa-external-link-alt text-xs"></i> Lihat
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
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
            // Tab switching with animation
            $('.tab-link').click(function(e) {
                e.preventDefault();
                
                // Get tab id
                const tabId = $(this).data('tab');
                
                // Hide all tabs with fadeout effect
                $('.tab-content').fadeOut(200, function() {
                    $('.tab-content').addClass('hidden');
                    $('#' + tabId).removeClass('hidden');
                    $('#' + tabId).fadeIn(200);
                });
                
                // Update active tab styling
                $('.tab-link').removeClass('active bg-blue-50 text-blue-600');
                $('.tab-link').removeClass('active');
                $(this).addClass('active');
            });
            
            // Search functionality for penelitian
            $('#searchPenelitian').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                $('#penelitian tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
            
            // Activate first tab by default
            $('.tab-link:first').addClass('active');
            $('#penelitian').show();
        });
    </script>
</body>
</html>