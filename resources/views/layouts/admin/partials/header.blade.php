<header class="bg-white shadow-sm">
    <div class="flex justify-between items-center p-4">
        <div class="flex items-center">
            <button id="menu-toggle" class="hamburger mr-4 text-gray-600">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h1 class="text-xl font-bold text-gray-800">@yield('header-title', 'Manajemen Data')</h1>
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