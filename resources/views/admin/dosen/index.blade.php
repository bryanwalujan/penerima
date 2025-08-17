<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Dosen - Repositori Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        :root {
            --unima-blue: #1e3a8a;
            --unima-gold: #d4af37;
            --unima-light-blue: #3b82f6;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
        }

        .sidebar {
            width: var(--sidebar-width);
            transition: all 0.3s ease;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, var(--unima-blue) 0%, #0f2c6e 100%);
            color: white;
        }

        .main-content {
            width: calc(100% - var(--sidebar-width));
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 1000;
                height: 100vh;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                width: 100%;
                margin-left: 0;
            }
        }

        .nav-link {
            transition: all 0.2s;
            position: relative;
            color: rgba(255, 255, 255, 0.8);
        }

        .nav-link:hover, .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-link.active {
            color: white;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: var(--unima-gold);
        }

        .tab-link {
            transition: all 0.2s;
            position: relative;
        }

        .tab-link.active {
            background-color: white;
            color: var(--unima-blue);
            border-top: 3px solid var(--unima-blue);
            font-weight: 500;
        }

        .status-badge {
            @apply px-2 py-1 rounded-full text-xs font-medium;
        }

        .status-selesai { background-color: #e6f7ee; color: #10b981; }
        .status-berjalan { background-color: #fef3c7; color: #d97706; }
        .status-diajukan { background-color: #e0f2fe; color: #0284c7; }
        .status-expired { background-color: #fee2e2; color: #ef4444; }
        .status-aktif { background-color: #e6f7ee; color: #10b981; }

        .card-shadow {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
        }

        .action-btn {
            transition: all 0.2s;
        }

        .action-btn:hover {
            transform: scale(1.1);
            opacity: 0.8;
        }

        .table-row:hover {
            background-color: #f8fafc;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            border-radius: 8px;
        }

        .file-input-wrapper input[type=file] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
            height: 100%;
            width: 100%;
        }

        .hamburger {
            display: none;
        }

        @media (max-width: 768px) {
            .hamburger {
                display: block;
            }
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-close {
            cursor: pointer;
            float: right;
            font-size: 1.5rem;
            color: #4b5563;
        }

        .count-badge {
            background-color: #3b82f6;
            color: white;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            margin-left: 0.5rem;
        }
    </style>
</head>
<body class="flex">
    <!-- Sidebar -->
    <div class="sidebar fixed h-full">
        <div class="p-5 flex items-center border-b border-blue-700">
            <div class="bg-white p-3 rounded-lg mr-3">
                <i class="fas fa-graduation-cap text-blue-800 text-xl"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold">Repositori Dosen</h1>
                <p class="text-xs text-blue-200">Admin Dashboard</p>
            </div>
        </div>

        <div class="py-4">
            <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center py-3 px-6">
                <i class="fas fa-tachometer-alt text-blue-300 mr-3"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.dosen.index') }}" class="nav-link active flex items-center py-3 px-6">
                <i class="fas fa-user-tie text-blue-300 mr-3"></i>
                <span>Data Dosen</span>
            </a>
            <a href="{{ route('admin.analytics.index') }}" class="nav-link flex items-center py-3 px-6">
                <i class="fas fa-chart-bar text-blue-300 mr-3"></i>
                <span>Analytics</span>
            </a>
        </div>

        <div class="absolute bottom-0 w-full p-4 border-t border-blue-700">
            <div class="flex items-center">
                <div class="user-avatar rounded-full flex items-center justify-center text-white font-bold mr-3 bg-gradient-to-br from-[var(--unima-gold)] to-[#b8860b] w-10 h-10">
                    {{ substr(Auth::guard('web')->user()->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-sm font-medium">{{ Auth::guard('web')->user()->name }}</p>
                    <p class="text-xs text-blue-300">Administrator</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content min-h-screen flex flex-col">
        <!-- Topbar -->
        <header class="bg-white shadow-sm">
            <div class="flex justify-between items-center p-4">
                <div class="flex items-center">
                    <button id="menu-toggle" class="hamburger mr-4 text-gray-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-xl font-bold text-gray-800">Manajemen Data Dosen</h1>
                </div>

                <div class="flex items-center space-x-6">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center text-red-600 hover:text-red-800 transition-colors font-medium">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-grow p-6">
            <!-- Header Section -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-user-tie text-blue-600 mr-2"></i>Manajemen Data Dosen
                </h1>
                <p class="text-gray-600 mt-1">Repositori Fakultas Teknik Informatika UNIMA</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-500 bg-opacity-75">
                            <i class="fas fa-user-tie text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Dosen</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ isset($dosens) ? $dosens->count() : 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-500 bg-opacity-75">
                            <i class="fas fa-flask text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Penelitian</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ isset($dosens) ? $dosens->sum(function($d) { return $d->penelitians->count(); }) : 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-500 bg-opacity-75">
                            <i class="fas fa-hands-helping text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Pengabdian</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ isset($dosens) ? $dosens->sum(function($d) { return $d->pengabdians->count(); }) : 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-500 bg-opacity-75">
                            <i class="fas fa-certificate text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total HAKI & Paten</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ isset($dosens) ? $dosens->sum(function($d) { return $d->hakis->count() + $d->patens->count(); }) : 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 mb-6">
                <a href="{{ route('admin.dosen.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors">
                    <i class="fas fa-plus-circle"></i> Tambah Dosen
                </a>
                <!-- Tombol Download Template -->
<a href="{{ route('admin.dosen.exportTemplate') }}" 
   style="
      background-color: #2463ea;
      color: white;
      padding: 8px 16px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      font-family: 'Poppins', sans-serif;
      font-weight: 500;
      transition: all 0.2s;
   "
   onmouseover="this.style.backgroundColor='#1157ee'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(36, 99, 234, 0.4)'"
   onmouseout="this.style.backgroundColor='#2463ea'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
>
    <i class="fas fa-file-download"></i> Download Template
</a>
                <div class="flex flex-col sm:flex-row gap-3 w-full">
                    <form id="import-dosen-form" action="{{ route('admin.dosen.import') }}" method="POST" enctype="multipart/form-data" class="flex w-full">
                        @csrf
                        <div class="file-input-wrapper flex-1">
                            <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-l-lg flex items-center justify-center gap-2 w-full h-full">
                                <i class="fas fa-file-excel"></i> Pilih File
                            </button>
                            <input type="file" name="file" accept=".xlsx,.xls" id="fileInput">
                        </div>
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-r-lg flex items-center gap-2">
                            <i class="fas fa-upload"></i> Impor
                        </button>
                    </form>
                    <!-- Tombol Ekspor -->
                    <div class="relative">
                        <button id="export-btn" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                            <i class="fas fa-download"></i> Ekspor
                        </button>
                        <div id="export-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10">
                            <a href="{{ route('admin.dosen.export', ['format' => 'excel']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Excel</a>
                            <a href="{{ route('admin.dosen.export', ['format' => 'ris']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">RIS</a>
                            <a href="{{ route('admin.dosen.export', ['format' => 'bib']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">BibTeX</a>
                            <a href="{{ route('admin.dosen.export', ['format' => 'csv']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">CSV</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-xl card-shadow overflow-hidden">
                <!-- Tab Navigation -->
                <div class="border-b">
                    <ul class="flex flex-wrap" role="tablist">
                        <li class="flex-1 min-w-[150px]">
                            <a class="tab-link py-4 px-6 block text-center hover:bg-gray-50 active" data-tab="dosen" role="tab" aria-selected="true" aria-controls="dosen">
                                <i class="fas fa-user-tie mr-2"></i>Data Dosen
                                <span class="count-badge">{{ isset($dosens) ? $dosens->count() : 0 }}</span>
                            </a>
                        </li>
                        <li class="flex-1 min-w-[150px]">
                            <a class="tab-link py-4 px-6 block text-center hover:bg-gray-50" data-tab="penelitian" role="tab" aria-selected="false" aria-controls="penelitian">
                                <i class="fas fa-flask mr-2"></i>Penelitian
                                <span class="count-badge">{{ isset($dosens) ? $dosens->sum(function($d) { return $d->penelitians->count(); }) : 0 }}</span>
                            </a>
                        </li>
                        <li class="flex-1 min-w-[150px]">
                            <a class="tab-link py-4 px-6 block text-center hover:bg-gray-50" data-tab="pengabdian" role="tab" aria-selected="false" aria-controls="pengabdian">
                                <i class="fas fa-hands-helping mr-2"></i>Pengabdian
                                <span class="count-badge">{{ isset($dosens) ? $dosens->sum(function($d) { return $d->pengabdians->count(); }) : 0 }}</span>
                            </a>
                        </li>
                        <li class="flex-1 min-w-[150px]">
                            <a class="tab-link py-4 px-6 block text-center hover:bg-gray-50" data-tab="haki" role="tab" aria-selected="false" aria-controls="haki">
                                <i class="fas fa-copyright mr-2"></i>HAKI
                                <span class="count-badge">{{ isset($dosens) ? $dosens->sum(function($d) { return $d->hakis->count(); }) : 0 }}</span>
                            </a>
                        </li>
                        <li class="flex-1 min-w-[150px]">
                            <a class="tab-link py-4 px-6 block text-center hover:bg-gray-50" data-tab="paten" role="tab" aria-selected="false" aria-controls="paten">
                                <i class="fas fa-certificate mr-2"></i>Paten
                                <span class="count-badge">{{ isset($dosens) ? $dosens->sum(function($d) { return $d->patens->count(); }) : 0 }}</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content -->
                <div class="p-4 overflow-x-auto">
                    <!-- Data Dosen Tab -->
                    <div id="dosen" class="tab-content" role="tabpanel">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-3">
                            <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-user-tie text-blue-600 mr-2"></i> Data Dosen ({{ isset($dosens) ? $dosens->count() : 0 }} dosen)
                            </h3>
                            <div class="relative w-full md:w-64">
                                <input type="text" id="search-dosen" placeholder="Cari data dosen..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>

                        <table class="w-full min-w-max">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700">
                                    <th class="py-3 px-4 text-left rounded-tl-lg">No</th>
                                    <th class="py-3 px-4 text-left">Foto</th>
                                    <th class="py-3 px-4 text-left">Nama</th>
                                    <th class="py-3 px-4 text-left">NIDN</th>
                                    <th class="py-3 px-4 text-left">NIP</th>
                                    <th class="py-3 px-4 text-left">NUPTK</th>
                                    <th class="py-3 px-4 text-left">Jumlah Data</th>
                                    <th class="py-3 px-4 text-center rounded-tr-lg">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="dosen-table">
                                @php $no = 1; @endphp
                                @if (isset($dosens) && is_iterable($dosens))
                                    @foreach ($dosens as $dosen)
                                        <tr class="table-row">
                                            <td class="py-3 px-4 border-b">{{ $no++ }}</td>
                                            <td class="py-3 px-4 border-b">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if ($dosen->foto)
                                                        <img src="{{ Storage::url($dosen->foto) }}" alt="{{ $dosen->nama }}" class="h-10 w-10 rounded-full object-cover border">
                                                    @else
                                                        <div class="bg-gray-200 border-2 border-dashed rounded-full w-10 h-10 flex items-center justify-center text-gray-500">
                                                            <i class="fas fa-user text-sm"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="py-3 px-4 border-b">
                                                <a href="{{ route('admin.dosen.show', $dosen->id) }}" class="font-medium text-blue-600 hover:underline">{{ $dosen->nama }}</a>
                                            </td>
                                            <td class="py-3 px-4 border-b">{{ $dosen->nidn }}</td>
                                            <td class="py-3 px-4 border-b">{{ $dosen->nip ?? '-' }}</td>
                                            <td class="py-3 px-4 border-b">{{ $dosen->nuptk ?? '-' }}</td>
                                            <td>
                                                    <div class="flex flex-wrap gap-1">
                                                        @if ($dosen->penelitians->count() > 0)
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                <i class="fas fa-flask mr-1"></i>
                                                                {{ $dosen->penelitians->count() }}
                                                            </span>
                                                        @endif
                                                        @if ($dosen->pengabdians->count() > 0)
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                <i class="fas fa-hands-helping mr-1"></i>
                                                                {{ $dosen->pengabdians->count() }}
                                                            </span>
                                                        @endif
                                                        @if ($dosen->hakis->count() > 0)
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                <i class="fas fa-copyright mr-1"></i>
                                                                {{ $dosen->hakis->count() }}
                                                            </span>
                                                        @endif
                                                        @if ($dosen->patens->count() > 0)
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                                <i class="fas fa-certificate mr-1"></i>
                                                                {{ $dosen->patens->count() }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>
                                            <td class="py-3 px-4 border-b">
                                                <div class="flex justify-center space-x-3">
                                                    <a href="{{ route('admin.dosen.show', $dosen->id) }}" class="text-blue-600 hover:text-blue-800 action-btn" title="Lihat">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.dosen.edit', $dosen->id) }}" class="text-blue-600 hover:text-blue-800 action-btn" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button data-dosen-id="{{ $dosen->id }}" class="text-green-600 hover:text-green-800 action-btn recommend-btn" title="Rekomendasi Kolaborasi">
                                                        <i class="fas fa-users"></i>
                                                    </button>
                                                    <form action="{{ route('admin.dosen.destroy', $dosen->id) }}" method="POST" class="inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 action-btn" title="Hapus">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="py-3 px-4 text-center text-gray-500">Tidak ada data dosen tersedia.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Penelitian Tab -->
                    <div id="penelitian" class="tab-content hidden" role="tabpanel">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-3">
                            <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-flask text-blue-600 mr-2"></i> Data Penelitian
                            </h3>
                            <div class="relative w-full md:w-64">
                                <input type="text" placeholder="Cari data penelitian..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500 search-penelitian">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>

                        <table class="w-full min-w-max">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700">
                                    <th class="py-3 px-4 text-left rounded-tl-lg">No</th>
                                    <th class="py-3 px-4 text-left">Dosen</th>
                                    <th class="py-3 px-4 text-left">Judul Penelitian</th>
                                    <th class="py-3 px-4 text-left">Keywords</th>
                                    <th class="py-3 px-4 text-left">Skema</th>
                                    <th class="py-3 px-4 text-left">Posisi</th>
                                    <th class="py-3 px-4 text-left">Sumber Dana</th>
                                    <th class="py-3 px-4 text-left">Status</th>
                                    <th class="py-3 px-4 text-left">Tahun</th>
                                    <th class="py-3 px-4 text-left">Luaran</th>
                                    <th class="py-3 px-4 text-center rounded-tr-lg">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @if (isset($dosens) && is_iterable($dosens))
                                    @foreach ($dosens as $dosen)
                                        @if (is_iterable($dosen->penelitians))
                                            @foreach ($dosen->penelitians as $penelitian)
                                                <tr class="table-row">
                                                    <td class="py-3 px-4 border-b">{{ $no++ }}</td>
                                                    <td class="py-3 px-4 border-b">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0 h-10 w-10">
                                                                @if ($dosen->foto)
                                                                    <img src="{{ Storage::url($dosen->foto) }}" alt="{{ $dosen->nama }}" class="h-10 w-10 rounded-full object-cover border">
                                                                @else
                                                                    <div class="bg-gray-200 border-2 border-dashed rounded-full w-10 h-10 flex items-center justify-center text-gray-500">
                                                                        <i class="fas fa-user text-sm"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="ml-3">
                                                                <a href="{{ route('admin.dosen.show', $dosen->id) }}" class="font-medium text-blue-600 hover:underline">{{ $dosen->nama }}</a>
                                                                <div class="text-gray-500 text-xs">{{ $dosen->nidn }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="py-3 px-4 border-b max-w-xs">
                                                        <div class="font-medium">{{ $penelitian->judul_penelitian }}</div>
                                                    </td>
                                                    <td class="py-3 px-4 border-b">{{ $penelitian->keywords ?? '-' }}</td>
                                                    <td class="py-3 px-4 border-b">{{ $penelitian->skema ?? '-' }}</td>
                                                    <td class="py-3 px-4 border-b">{{ $penelitian->posisi ?? '-' }}</td>
                                                    <td class="py-3 px-4 border-b">{{ $penelitian->sumber_dana ?? '-' }}</td>
                                                    <td class="py-3 px-4 border-b">
                                                        <span class="status-badge {{ $penelitian->status == 'Selesai' ? 'status-selesai' : ($penelitian->status == 'Berjalan' ? 'status-berjalan' : 'status-diajukan') }}">
                                                            {{ $penelitian->status ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td class="py-3 px-4 border-b">{{ $penelitian->tahun ?? '-' }}</td>
                                                    <td class="py-3 px-4 border-b">
                                                        @if ($penelitian->link_luaran)
                                                            <a href="{{ $penelitian->link_luaran }}" target="_blank" class="text-blue-600 hover:underline">Lihat Luaran</a>
                                                        @else
                                                            <span class="text-gray-500">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="py-3 px-4 border-b">
                                                        <div class="flex justify-center space-x-3">
                                                            <a href="{{ route('admin.dosen.edit', $dosen->id) }}" class="text-blue-600 hover:text-blue-800 action-btn" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form action="{{ route('admin.penelitian.destroy', $penelitian->id) }}" method="POST" class="inline delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:text-red-800 action-btn" title="Hapus">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="11" class="py-3 px-4 text-center text-gray-500">Tidak ada data penelitian tersedia.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Pengabdian Tab -->
                    <div id="pengabdian" class="tab-content hidden" role="tabpanel">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-3">
                            <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-hands-helping text-blue-600 mr-2"></i> Data Pengabdian
                            </h3>
                            <div class="relative w-full md:w-64">
                                <input type="text" placeholder="Cari data pengabdian..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500 search-pengabdian">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>

                        <table class="w-full min-w-max">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700">
                                    <th class="py-3 px-4 text-left rounded-tl-lg">No</th>
                                    <th class="py-3 px-4 text-left">Dosen</th>
                                    <th class="py-3 px-4 text-left">Judul Pengabdian</th>
                                    <th class="py-3 px-4 text-left">Skema</th>
                                    <th class="py-3 px-4 text-left">Posisi</th>
                                    <th class="py-3 px-4 text-left">Sumber Dana</th>
                                    <th class="py-3 px-4 text-left">Status</th>
                                    <th class="py-3 px-4 text-left">Tahun</th>
                                    <th class="py-3 px-4 text-left">Luaran</th>
                                    <th class="py-3 px-4 text-center rounded-tr-lg">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @if (isset($dosens) && is_iterable($dosens))
                                    @foreach ($dosens as $dosen)
                                        @if (is_iterable($dosen->pengabdians))
                                            @foreach ($dosen->pengabdians as $pengabdian)
                                                <tr class="table-row">
                                                    <td class="py-3 px-4 border-b">{{ $no++ }}</td>
                                                    <td class="py-3 px-4 border-b">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0 h-10 w-10">
                                                                @if ($dosen->foto)
                                                                    <img src="{{ Storage::url($dosen->foto) }}" alt="{{ $dosen->nama }}" class="h-10 w-10 rounded-full object-cover border">
                                                                @else
                                                                    <div class="bg-gray-200 border-2 border-dashed rounded-full w-10 h-10 flex items-center justify-center text-gray-500">
                                                                        <i class="fas fa-user text-sm"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="ml-3">
                                                                <a href="{{ route('admin.dosen.show', $dosen->id) }}" class="font-medium text-blue-600 hover:underline">{{ $dosen->nama }}</a>
                                                                <div class="text-gray-500 text-xs">{{ $dosen->nidn }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="py-3 px-4 border-b max-w-xs">
                                                        <div class="font-medium">{{ $pengabdian->judul_pengabdian }}</div>
                                                    </td>
                                                    <td class="py-3 px-4 border-b">{{ $pengabdian->skema ?? '-' }}</td>
                                                    <td class="py-3 px-4 border-b">{{ $pengabdian->posisi ?? '-' }}</td>
                                                    <td class="py-3 px-4 border-b">{{ $pengabdian->sumber_dana ?? '-' }}</td>
                                                    <td class="py-3 px-4 border-b">
                                                        <span class="status-badge {{ $pengabdian->status == 'Selesai' ? 'status-selesai' : ($pengabdian->status == 'Berjalan' ? 'status-berjalan' : 'status-diajukan') }}">
                                                            {{ $pengabdian->status ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td class="py-3 px-4 border-b">{{ $pengabdian->tahun ?? '-' }}</td>
                                                    <td class="py-3 px-4 border-b">
                                                        @if ($pengabdian->link_luaran)
                                                            <a href="{{ $pengabdian->link_luaran }}" target="_blank" class="text-blue-600 hover:underline">Lihat Luaran</a>
                                                        @else
                                                            <span class="text-gray-500">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="py-3 px-4 border-b">
                                                        <div class="flex justify-center space-x-3">
                                                            <a href="{{ route('admin.dosen.edit', $dosen->id) }}" class="text-blue-600 hover:text-blue-800 action-btn" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form action="{{ route('admin.pengabdian.destroy', $pengabdian->id) }}" method="POST" class="inline delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:text-red-800 action-btn" title="Hapus">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="10" class="py-3 px-4 text-center text-gray-500">Tidak ada data pengabdian tersedia.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- HAKI Tab -->
                    <div id="haki" class="tab-content hidden" role="tabpanel">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-3">
                            <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-copyright text-blue-600 mr-2"></i> Data HAKI
                            </h3>
                            <div class="relative w-full md:w-64">
                                <input type="text" placeholder="Cari data HAKI..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500 search-haki">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>

                        <table class="w-full min-w-max">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700">
                                    <th class="py-3 px-4 text-left rounded-tl-lg">No</th>
                                    <th class="py-3 px-4 text-left">Dosen</th>
                                    <th class="py-3 px-4 text-left">Judul HAKI</th>
                                    <th class="py-3 px-4 text-left">Expired</th>
                                    <th class="py-3 px-4 text-left">Link</th>
                                    <th class="py-3 px-4 text-center rounded-tr-lg">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @if (isset($dosens) && is_iterable($dosens))
                                    @foreach ($dosens as $dosen)
                                        @if (is_iterable($dosen->hakis))
                                            @foreach ($dosen->hakis as $haki)
                                                <tr class="table-row">
                                                    <td class="py-3 px-4 border-b">{{ $no++ }}</td>
                                                    <td class="py-3 px-4 border-b">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0 h-10 w-10">
                                                                @if ($dosen->foto)
                                                                    <img src="{{ Storage::url($dosen->foto) }}" alt="{{ $dosen->nama }}" class="h-10 w-10 rounded-full object-cover border">
                                                                @else
                                                                    <div class="bg-gray-200 border-2 border-dashed rounded-full w-10 h-10 flex items-center justify-center text-gray-500">
                                                                        <i class="fas fa-user text-sm"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="ml-3">
                                                                <a href="{{ route('admin.dosen.show', $dosen->id) }}" class="font-medium text-blue-600 hover:underline">{{ $dosen->nama }}</a>
                                                                <div class="text-gray-500 text-xs">{{ $dosen->nidn }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="py-3 px-4 border-b max-w-xs">
                                                        <div class="font-medium">{{ $haki->judul_haki }}</div>
                                                    </td>
                                                    <td class="py-3 px-4 border-b">{{ $haki->expired ?? '-' }}</td>
                                                    <td class="py-3 px-4 border-b">
                                                        @if ($haki->link)
                                                            <a href="{{ $haki->link }}" target="_blank" class="text-blue-600 hover:underline">Lihat Link</a>
                                                        @else
                                                            <span class="text-gray-500">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="py-3 px-4 border-b">
                                                        <div class="flex justify-center space-x-3">
                                                            <a href="{{ route('admin.dosen.edit', $dosen->id) }}" class="text-blue-600 hover:text-blue-800 action-btn" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form action="{{ route('admin.haki.destroy', $haki->id) }}" method="POST" class="inline delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:text-red-800 action-btn" title="Hapus">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="py-3 px-4 text-center text-gray-500">Tidak ada data HAKI tersedia.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Paten Tab -->
                    <div id="paten" class="tab-content hidden" role="tabpanel">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-3">
                            <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-certificate text-blue-600 mr-2"></i> Data Paten
                            </h3>
                            <div class="relative w-full md:w-64">
                                <input type="text" placeholder="Cari data paten..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500 search-paten">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>

                        <table class="w-full min-w-max">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700">
                                    <th class="py-3 px-4 text-left rounded-tl-lg">No</th>
                                    <th class="py-3 px-4 text-left">Dosen</th>
                                    <th class="py-3 px-4 text-left">Judul Paten</th>
                                    <th class="py-3 px-4 text-left">Jenis Paten</th>
                                    <th class="py-3 px-4 text-left">Expired</th>
                                    <th class="py-3 px-4 text-left">Link</th>
                                    <th class="py-3 px-4 text-center rounded-tr-lg">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @if (isset($dosens) && is_iterable($dosens))
                                    @foreach ($dosens as $dosen)
                                        @if (is_iterable($dosen->patens))
                                            @foreach ($dosen->patens as $paten)
                                                <tr class="table-row">
                                                    <td class="py-3 px-4 border-b">{{ $no++ }}</td>
                                                    <td class="py-3 px-4 border-b">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0 h-10 w-10">
                                                                @if ($dosen->foto)
                                                                    <img src="{{ Storage::url($dosen->foto) }}" alt="{{ $dosen->nama }}" class="h-10 w-10 rounded-full object-cover border">
                                                                @else
                                                                    <div class="bg-gray-200 border-2 border-dashed rounded-full w-10 h-10 flex items-center justify-center text-gray-500">
                                                                        <i class="fas fa-user text-sm"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="ml-3">
                                                                <a href="{{ route('admin.dosen.show', $dosen->id) }}" class="font-medium text-blue-600 hover:underline">{{ $dosen->nama }}</a>
                                                                <div class="text-gray-500 text-xs">{{ $dosen->nidn }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="py-3 px-4 border-b max-w-xs">
                                                        <div class="font-medium">{{ $paten->judul_paten }}</div>
                                                    </td>
                                                    <td class="py-3 px-4 border-b">{{ $paten->jenis_paten ?? '-' }}</td>
                                                    <td class="py-3 px-4 border-b">{{ $paten->expired ?? '-' }}</td>
                                                    <td class="py-3 px-4 border-b">
                                                        @if ($paten->link)
                                                            <a href="{{ $paten->link }}" target="_blank" class="text-blue-600 hover:underline">Lihat Link</a>
                                                        @else
                                                            <span class="text-gray-500">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="py-3 px-4 border-b">
                                                        <div class="flex justify-center space-x-3">
                                                            <a href="{{ route('admin.dosen.edit', $dosen->id) }}" class="text-blue-600 hover:text-blue-800 action-btn" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form action="{{ route('admin.paten.destroy', $paten->id) }}" method="POST" class="inline delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:text-red-800 action-btn" title="Hapus">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="py-3 px-4 text-center text-gray-500">Tidak ada data paten tersedia.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal untuk Rekomendasi -->
            <div id="recommendation-modal" class="modal hidden">
                <div class="modal-content">
                    <span class="modal-close">&times;</span>
                    <h2 class="text-xl font-bold mb-4 text-gray-800 flex items-center">
                        <i class="fas fa-users text-blue-600 mr-2"></i> Rekomendasi Kolaborasi
                    </h2>
                    <div id="recommendation-content" class="text-gray-700"></div>
                </div>
            </div>
        </main>
    </div>

    <script>
        $(document).ready(function () {
            // Tab navigation
            $('.tab-link').click(function () {
                $('.tab-link').removeClass('active');
                $(this).addClass('active');
                $('.tab-content').addClass('hidden');
                $('#' + $(this).data('tab')).removeClass('hidden');
            });

            // Search functionality for dosen
            $('#search-dosen').on('input', function () {
                let value = $(this).val().toLowerCase();
                $('#dosen-table tr').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Search functionality for penelitian
            $('.search-penelitian').on('input', function () {
                let value = $(this).val().toLowerCase();
                $('#penelitian tbody tr').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Search functionality for pengabdian
            $('.search-pengabdian').on('input', function () {
                let value = $(this).val().toLowerCase();
                $('#pengabdian tbody tr').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Search functionality for haki
            $('.search-haki').on('input', function () {
                let value = $(this).val().toLowerCase();
                $('#haki tbody tr').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Search functionality for paten
            $('.search-paten').on('input', function () {
                let value = $(this).val().toLowerCase();
                $('#paten tbody tr').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Delete confirmation
            $('.delete-form').on('submit', function (e) {
                e.preventDefault();
                let form = $(this);
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form[0].submit();
                    }
                });
            });

            // Sidebar toggle for mobile
            $('#menu-toggle').click(function () {
                $('.sidebar').toggleClass('active');
            });

            // Export button dropdown
            $('#export-btn').click(function () {
                $('#export-dropdown').toggleClass('hidden');
            });

            // Close export dropdown when clicking outside
            $(document).click(function (e) {
                if (!$(e.target).closest('#export-btn, #export-dropdown').length) {
                    $('#export-dropdown').addClass('hidden');
                }
            });

            // Recommendation button
            $('.recommend-btn').click(function () {
                let dosenId = $(this).data('dosen-id');
                $.ajax({
                    url: '/admin/dosen/' + dosenId + '/recommend',
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        let modalContent = '';
                        if (response.recommendations.length === 0) {
                            modalContent = `<p class="text-red-600">${response.message}</p>`;
                        } else {
                            modalContent = '<ul class="list-disc pl-5 space-y-4">';
                            response.recommendations.forEach(function (rec) {
                                let keywords = Array.isArray(rec.matched_keywords) 
                                    ? rec.matched_keywords.slice(0, 3).join(', ') 
                                    : (rec.matched_keywords || 'Tidak ada');
                                let penelitians = Array.isArray(rec.penelitians) 
                                    ? rec.penelitians.map(function (judul) {
                                        return judul.split(/\s+/).slice(0, 3).join(' ');
                                    }).join(', ') 
                                    : 'Tidak ada';
                                modalContent += `
                                    <li class="border-b pb-2">
                                        <div class="flex justify-between">
                                            <span class="font-semibold">${rec.nama} (NIDN: ${rec.nidn})</span>
                                            <span class="text-blue-600">Skor: ${rec.score}</span>
                                        </div>
                                        <p class="text-sm text-gray-600">Kata Kunci: ${keywords}</p>
                                        <p class="text-sm text-gray-600">Penelitian: ${penelitians}</p>
                                        <p class="text-sm">Pengabdian: ${rec.pengabdians.length ? rec.pengabdians.join(', ') : 'Tidak ada'}</p>
                                    </li>`;
                            });
                            modalContent += '</ul>';
                        }
                        $('#recommendation-content').html(modalContent);
                        $('#recommendation-modal').removeClass('hidden').fadeIn();
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Gagal mengambil rekomendasi.',
                            icon: 'error',
                            confirmButtonColor: '#d33'
                        });
                    }
                });
            });

            // Close modal
            $(document).on('click', '.modal-close', function () {
                $('#recommendation-modal').fadeOut(function () {
                    $(this).addClass('hidden');
                });
            });

            // Close modal when clicking outside
            $(document).on('click', '.modal', function (e) {
                if (e.target.classList.contains('modal')) {
                    $('#recommendation-modal').fadeOut(function () {
                        $(this).addClass('hidden');
                    });
                }
            });
        });
    </script>
</body>
</html>