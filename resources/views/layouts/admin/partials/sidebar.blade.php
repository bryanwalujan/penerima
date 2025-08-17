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

    <div class="py-4">
        <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center py-3 px-6 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt text-blue-300 mr-3"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.dosen.index') }}" class="nav-link flex items-center py-3 px-6 {{ request()->routeIs('admin.dosen.*') ? 'active' : '' }}">
            <i class="fas fa-user-tie text-blue-300 mr-3"></i>
            <span>Data Dosen</span>
        </a>
        <a href="{{ route('admin.analytics.index') }}" class="nav-link flex items-center py-3 px-6 {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
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