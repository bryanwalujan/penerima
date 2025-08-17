<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Repositori Dosen</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.0/dist/chart.umd.min.js"></script>
    
    <!-- Custom CSS -->
    @vite(['resources/views/css/admin.css'])
    
    <!-- Additional Styles -->
    @yield('styles')
</head>
<body class="bg-gray-50">
    <!-- Mobile Menu Burger -->
    <div class="hamburger md:hidden">
        <i class="fas fa-bars text-blue-800 text-xl"></i>
    </div>

    <!-- Sidebar -->
    @include('layouts.admin.partials.sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="bg-white rounded-lg shadow-sm mb-6 p-6">
            <h1 class="text-2xl font-bold text-gray-800">@yield('header-title', 'Dashboard')</h1>
            <p class="text-gray-600 text-sm">Welcome back, {{ Auth::guard('web')->user()->name }}</p>
        </header>

        <!-- Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="mt-auto py-4 text-center text-gray-500 text-sm">
            <p>&copy; 2024 Repositori Dosen - Fakultas Teknik Informatika UNIMA</p>
        </footer>
    </div>

    <!-- Scripts -->
    @vite(['resources/views/js/admin.js'])
    @yield('scripts')
</body>
</html>