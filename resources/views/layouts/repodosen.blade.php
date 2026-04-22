<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Repositori Dosen | Teknik Informatika - Universitas Negeri Manado')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @yield('head_scripts')
    @include('public.partials.styles')
</head>
<body class="min-h-screen flex flex-col">
    <div class="content-wrapper">

        {{-- Header --}}
        @include('public.partials.header')

        {{-- Main Content --}}
        <main class="main-content">
            @yield('content')
        </main>

        {{-- Footer --}}
        @include('public.partials.footer')

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @include('public.partials.scripts')
    @stack('scripts')
</body>
</html>