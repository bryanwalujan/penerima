{{-- resources/views/layouts/dosen/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Dosen') - Repositori Dosen UNIMA</title>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Custom Styles --}}
    @stack('styles')
    
    <style>
        :root {
            --primary: #1e3a8a;
            --primary-dark: #0f2c6e;
            --primary-light: #3b82f6;
            --secondary: #d4af37;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #8b5cf6;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f7ff 0%, #e6f2ff 100%);
            background-attachment: fixed;
        }
        
        .sidebar {
            transition: all 0.3s ease;
        }
        
        .sidebar-item {
            transition: all 0.2s ease;
            border-radius: 12px;
        }
        
        .sidebar-item:hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(30, 58, 138, 0.05) 100%);
            transform: translateX(5px);
        }
        
        .sidebar-item.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        }
        
        .sidebar-item.active i {
            color: white;
        }
        
        .main-content {
            transition: all 0.3s ease;
        }
        
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: var(--primary-light);
            border-radius: 10px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.4s ease-out;
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(30, 58, 138, 0.4);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        <aside class="sidebar w-72 bg-white shadow-2xl flex flex-col fixed h-full z-30 overflow-y-auto scrollbar-thin">
            {{-- Logo & Brand --}}
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-graduation-cap text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">Repositori Dosen</h1>
                        <p class="text-xs text-gray-500">UNIVERSITAS NEGERI MANADO</p>
                    </div>
                </div>
            </div>
            
            {{-- User Info --}}
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    @php
                        $authDosen = Auth::guard('dosen')->user();
                    @endphp
                    @if($authDosen && $authDosen->foto)
                        <img src="{{ Storage::url($authDosen->foto) }}" alt="{{ $authDosen->nama }}" 
                             class="w-12 h-12 rounded-full object-cover border-2 border-blue-200">
                    @else
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                            <i class="fas fa-user-graduate text-blue-600 text-xl"></i>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 truncate">{{ $authDosen->nama ?? 'Dosen' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $authDosen->nidn ?? 'NIDN tidak tersedia' }}</p>
                    </div>
                </div>
            </div>
            
            {{-- Navigation Menu --}}
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('dosen.dashboard') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 text-gray-700 {{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 text-blue-500"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <a href="{{ route('dosen.edit') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 text-gray-700 {{ request()->routeIs('dosen.edit') ? 'active' : '' }}">
                    <i class="fas fa-user-edit w-5 text-green-500"></i>
                    <span class="font-medium">Edit Profil</span>
                </a>
                
                <div class="pt-4 mt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-400 px-4 mb-2 uppercase font-semibold">Data Akademik</p>
                </div>
                
                <a href="{{ route('dosen.penelitian.edit') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 text-gray-700 {{ request()->routeIs('dosen.penelitian.edit') ? 'active' : '' }}">
                    <i class="fas fa-flask w-5 text-blue-500"></i>
                    <span class="font-medium">Penelitian</span>
                    @if($authDosen && $authDosen->penelitians->count() > 0)
                        <span class="ml-auto text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">{{ $authDosen->penelitians->count() }}</span>
                    @endif
                </a>
                
                <a href="{{ route('dosen.pengabdian.edit') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 text-gray-700 {{ request()->routeIs('dosen.pengabdian.edit') ? 'active' : '' }}">
                    <i class="fas fa-hands-helping w-5 text-yellow-500"></i>
                    <span class="font-medium">Pengabdian</span>
                    @if($authDosen && $authDosen->pengabdians->count() > 0)
                        <span class="ml-auto text-xs bg-yellow-100 text-yellow-600 px-2 py-0.5 rounded-full">{{ $authDosen->pengabdians->count() }}</span>
                    @endif
                </a>
                
                <a href="{{ route('dosen.haki.edit') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 text-gray-700 {{ request()->routeIs('dosen.haki.edit') ? 'active' : '' }}">
                    <i class="fas fa-copyright w-5 text-purple-500"></i>
                    <span class="font-medium">HAKI</span>
                    @if($authDosen && $authDosen->hakis->count() > 0)
                        <span class="ml-auto text-xs bg-purple-100 text-purple-600 px-2 py-0.5 rounded-full">{{ $authDosen->hakis->count() }}</span>
                    @endif
                </a>
                
                <a href="{{ route('dosen.paten.edit') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 text-gray-700 {{ request()->routeIs('dosen.paten.edit') ? 'active' : '' }}">
                    <i class="fas fa-certificate w-5 text-red-500"></i>
                    <span class="font-medium">Paten</span>
                    @if($authDosen && $authDosen->patens->count() > 0)
                        <span class="ml-auto text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full">{{ $authDosen->patens->count() }}</span>
                    @endif
                </a>

                <div class="pt-4 mt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-400 px-4 mb-2 uppercase font-semibold">Bimbingan Mahasiswa</p>
                </div>

                <a href="{{ route('dosen.sk-proposal.index') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 text-gray-700 {{ request()->routeIs('dosen.sk-proposal.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt w-5 text-yellow-500"></i>
                    <span class="font-medium">SK Proposal</span>
                    @php
                        $skProposalCount = 0;
                        if ($authDosen) {
                            $skProposalCount = \App\Models\Skripsi::whereNotNull('file_proposal')
                                ->where('raw_nama_pembimbing1', 'like', 'SK_%')
                                ->where(function($q) use ($authDosen) {
                                    $q->where('dosen_pembimbing1_id', $authDosen->id)
                                      ->orWhere('dosen_pembimbing2_id', $authDosen->id);
                                })
                                ->count();
                        }
                    @endphp
                    @if($skProposalCount > 0)
                        <span class="ml-auto text-xs bg-yellow-100 text-yellow-600 px-2 py-0.5 rounded-full">{{ $skProposalCount }}</span>
                    @endif
                </a>
                
                <a href="{{ route('dosen.sk-ujian-hasil.index') }}" class="sidebar-item flex items-center space-x-3 px-4 py-3 text-gray-700 {{ request()->routeIs('dosen.sk-ujian-hasil.*') ? 'active' : '' }}">
                    <i class="fas fa-file-pdf w-5 text-red-500"></i>
                    <span class="font-medium">SK Ujian Hasil</span>
                    @php
                        $skUjianHasilCount = 0;
                        if ($authDosen) {
                            $skUjianHasilCount = \App\Models\Skripsi::whereNotNull('file_skripsi')
                                ->where(function($q) use ($authDosen) {
                                    $q->where('dosen_pembimbing1_id', $authDosen->id)
                                      ->orWhere('dosen_pembimbing2_id', $authDosen->id);
                                })
                                ->count();
                        }
                    @endphp
                    @if($skUjianHasilCount > 0)
                        <span class="ml-auto text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full">{{ $skUjianHasilCount }}</span>
                    @endif
                </a>
            </nav>
            
            {{-- Footer Menu --}}
            <div class="p-4 border-t border-gray-100">
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="sidebar-item flex items-center space-x-3 px-4 py-3 text-gray-700 w-full hover:bg-red-50 transition-all">
                        <i class="fas fa-sign-out-alt w-5 text-red-500"></i>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </aside>
        
        {{-- Main Content --}}
        <main class="flex-1 ml-72 overflow-y-auto scrollbar-thin">
            {{-- Top Header --}}
            <div class="bg-white shadow-sm sticky top-0 z-20">
                <div class="px-8 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">@yield('header-title', 'Dashboard')</h2>
                        <p class="text-sm text-gray-500 mt-0.5">@yield('header-subtitle', 'Selamat datang di panel dosen')</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        
                        <div class="flex items-center space-x-2">
                            @if($authDosen && $authDosen->foto)
                                <img src="{{ Storage::url($authDosen->foto) }}" alt="{{ $authDosen->nama }}" 
                                     class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600 text-sm"></i>
                                </div>
                            @endif
                            <span class="text-sm font-medium text-gray-700">{{ $authDosen->nama ?? 'Dosen' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Page Content --}}
            <div class="p-8 fade-in">
                @yield('content')
            </div>
        </main>
    </div>
    
    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
    
    <script>
        // Toggle sidebar untuk mobile (optional)
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('-translate-x-full');
        }
    </script>
</body>
</html>