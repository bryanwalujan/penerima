{{-- resources/views/components/sidebar.blade.php --}}
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

    <div class="py-4 sidebar-menu">
        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center py-3 px-6 transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt text-blue-300 mr-3 w-5"></i>
            <span>Dashboard</span>
        </a>

        {{-- Data Dosen --}}
        <a href="{{ route('admin.dosen.index') }}" class="nav-link flex items-center py-3 px-6 transition-all duration-300 {{ request()->routeIs('admin.dosen.*') ? 'active' : '' }}">
            <i class="fas fa-user-tie text-blue-300 mr-3 w-5"></i>
            <span>Data Dosen</span>
        </a>

        {{-- Analytics --}}
        <a href="{{ route('admin.analytics.index') }}" class="nav-link flex items-center py-3 px-6 transition-all duration-300 {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar text-blue-300 mr-3 w-5"></i>
            <span>Analytics</span>
        </a>

        {{-- Separator --}}
        <div class="px-6 mt-4 mb-2">
            <span class="text-xs text-blue-300 uppercase tracking-wider">Manajemen Skripsi</span>
        </div>

        {{-- Data Skripsi (Main) --}}
        <a href="{{ route('admin.skripsi.index') }}" class="nav-link flex items-center py-3 px-6 transition-all duration-300 {{ request()->routeIs('admin.skripsi.index') ? 'active' : '' }}">
            <i class="fas fa-graduation-cap text-blue-300 mr-3 w-5"></i>
            <span>Semua Skripsi</span>
            @php
                $totalSkripsi = \App\Models\Skripsi::count();
            @endphp
            @if($totalSkripsi > 0)
                <span class="ml-auto bg-blue-600 text-xs px-2 py-0.5 rounded-full">{{ $totalSkripsi }}</span>
            @endif
        </a>

        {{-- File Skripsi --}}
        <a href="{{ route('admin.file.skripsi.index') }}" class="nav-link flex items-center py-3 pl-12 pr-6 transition-all duration-300 {{ request()->routeIs('admin.file.skripsi.*') ? 'active' : '' }}">
            <i class="fas fa-file-pdf text-red-400 mr-3 w-5"></i>
            <span>File Skripsi</span>
            @php
                $fileSkripsiCount = \App\Models\Skripsi::whereNotNull('file_skripsi')->count();
            @endphp
            @if($fileSkripsiCount > 0)
                <span class="ml-auto bg-red-600 text-xs px-2 py-0.5 rounded-full">{{ $fileSkripsiCount }}</span>
            @endif
        </a>

        {{-- File SK Pembimbing --}}
        <a href="{{ route('admin.file.sk-pembimbing.index') }}" class="nav-link flex items-center py-3 pl-12 pr-6 transition-all duration-300 {{ request()->routeIs('admin.file.sk-pembimbing.*') ? 'active' : '' }}">
            <i class="fas fa-file-alt text-blue-400 mr-3 w-5"></i>
            <span>SK Pembimbing</span>
            @php
                $fileSkPembimbingCount = \App\Models\Skripsi::whereNotNull('file_sk_pembimbing')->count();
            @endphp
            @if($fileSkPembimbingCount > 0)
                <span class="ml-auto bg-blue-600 text-xs px-2 py-0.5 rounded-full">{{ $fileSkPembimbingCount }}</span>
            @endif
        </a>

        {{-- File Proposal --}}
        <a href="{{ route('admin.file.proposal.index') }}" class="nav-link flex items-center py-3 pl-12 pr-6 transition-all duration-300 {{ request()->routeIs('admin.file.proposal.*') ? 'active' : '' }}">
            <i class="fas fa-file-word text-yellow-400 mr-3 w-5"></i>
            <span>Proposal</span>
            @php
                $fileProposalCount = \App\Models\Skripsi::whereNotNull('file_proposal')->count();
            @endphp
            @if($fileProposalCount > 0)
                <span class="ml-auto bg-yellow-600 text-xs px-2 py-0.5 rounded-full">{{ $fileProposalCount }}</span>
            @endif
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
        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="w-full text-left text-sm text-red-300 hover:text-red-200 transition">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </button>
        </form>
    </div>
</div>

<style>
    .sidebar-menu .nav-link {
        color: #e2e8f0;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    .sidebar-menu .nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        padding-left: 1.75rem;
    }
    .sidebar-menu .nav-link.active {
        background: rgba(59, 130, 246, 0.3);
        color: white;
        border-left: 3px solid #3b82f6;
    }
    .sidebar-menu .nav-link i {
        width: 1.25rem;
        text-align: center;
    }
</style>