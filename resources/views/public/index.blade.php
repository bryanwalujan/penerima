@extends('layouts.repodosen')

@section('title', 'Repositori Dosen | Teknik Informatika - Universitas Negeri Manado')

@section('head_scripts')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('content')

{{-- ── Hero Section ────────────────────────────────────────────────────────── --}}
<div class="hero-pattern py-12 relative overflow-hidden">
    <div class="absolute top-10 right-10 opacity-10">
        <i class="fas fa-atom text-[200px] text-blue-500 hover:rotate-180 transition-transform duration-1000"></i>
    </div>
    <div class="container-wide relative z-10">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-10 md:mb-0 text-center md:text-left">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4 leading-tight">
                    Temukan Dosen & Karya Inovasi
                    <span class="text-blue-600 hover:text-blue-800 transition-colors">Teknik Informatika</span>
                </h1>
                <p class="text-gray-600 mb-6 max-w-xl">
                    Jelajahi profil dosen, penelitian, pengabdian, dan karya inovatif
                    Program Studi Teknik Informatika Universitas Negeri Manado
                </p>
                <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                    <a href="{{ route('public.category', ['category' => 'dosens']) }}" class="stat-badge clickable">
                        <i class="fas fa-users mr-2"></i> {{ $totalDosens ?? 0 }} Dosen
                    </a>
                    <a href="{{ route('public.category', ['category' => 'penelitians']) }}" class="stat-badge clickable">
                        <i class="fas fa-flask mr-2"></i> {{ $totalPenelitians ?? 0 }} Penelitian
                    </a>
                    <a href="{{ route('public.category', ['category' => 'pengabdians']) }}" class="stat-badge clickable">
                        <i class="fas fa-hands-helping mr-2"></i> {{ $totalPengabdians ?? 0 }} Pengabdian
                    </a>
                    <a href="{{ route('public.category', ['category' => 'hakis']) }}" class="stat-badge clickable">
                        <i class="fas fa-copyright mr-2"></i> {{ $totalHakis ?? 0 }} HKI Penelitian
                    </a>
                    <a href="{{ route('public.category', ['category' => 'patens']) }}" class="stat-badge clickable">
                        <i class="fas fa-book mr-2"></i> {{ $totalPatens ?? 0 }} HKI Pengabdian
                    </a>
                </div>
            </div>
            <div class="md:w-1/2 flex justify-center">
                <div class="floating">
                    <img src="https://cdn.pixabay.com/photo/2018/03/10/12/00/teamwork-3213924_1280.jpg"
                         alt="Teamwork"
                         class="w-72 h-72 object-cover rounded-xl shadow-xl border-8 border-white hero-image transition-all duration-300">
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Search Box ──────────────────────────────────────────────────────────── --}}
<div class="py-8">
    <div class="search-container">
        <div class="bg-white search-card-wide">
            <div class="gradient-bg text-white p-6">
                <h2 class="text-xl md:text-2xl font-bold flex items-center">
                    <i class="fas fa-search mr-3 hover:rotate-12 transition-transform"></i> Pencarian Dosen
                </h2>
                <p class="opacity-90">Cari dosen berdasarkan nama, NIDN</p>
            </div>
            <div class="search-form">
                <form action="{{ route('public.search') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <input type="text" name="query" value="{{ old('query', $query ?? '') }}"
                               placeholder="Contoh: Dr. Audy Kenap, atau 1234567890"
                               class="search-input w-full p-4 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('query')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site') }}"></div>
                        @error('g-recaptcha-response')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                            class="w-full gradient-bg hover:opacity-90 text-white p-4 rounded-xl text-base font-bold transition-all duration-300 flex items-center justify-center glow-hover">
                        <i class="fas fa-search mr-3"></i> Cari Dosen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ── Hasil Pencarian / Kategori ──────────────────────────────────────────── --}}
<div class="container-wide py-8">
    <div class="card-container">

        {{-- ── Tampilan Kategori ──────────────────────────────────────── --}}
        @if(isset($categoryData))
            <div class="space-y-6">
                @if($category === 'dosens')
                    @foreach($categoryData as $dosen)
                        @include('public.components.dosen-card', ['dosen' => $dosen, 'withToggle' => true])
                    @endforeach

                @elseif($category === 'penelitians')
                    @include('public.components.category-table', [
                        'title'     => $categoryTitle,
                        'icon'      => 'fa-flask',
                        'tableId'   => 'penelitian-table',
                        'type'      => 'penelitian',
                        'data'      => $categoryData,
                        'skema'     => $skema,
                        'category'  => 'penelitians',
                        'color'     => 'text-blue-600',
                        'columns'   => ['Judul Penelitian', 'Dosen', 'Skema', 'Tahun', 'Status'],
                    ])

                @elseif($category === 'pengabdians')
                    @include('public.components.category-table', [
                        'title'    => $categoryTitle,
                        'icon'     => 'fa-hands-helping',
                        'tableId'  => 'pengabdian-table',
                        'type'     => 'pengabdian',
                        'data'     => $categoryData,
                        'skema'    => $skema,
                        'category' => 'pengabdians',
                        'color'    => 'text-green-600',
                        'columns'  => ['Judul Pengabdian', 'Dosen', 'Skema', 'Tahun', 'Status'],
                    ])

                @elseif($category === 'hakis')
                    @include('public.components.category-haki-paten', [
                        'title'   => $categoryTitle,
                        'icon'    => 'fa-copyright',
                        'tableId' => 'haki-table',
                        'type'    => 'haki',
                        'data'    => $categoryData,
                        'columns' => ['Judul HAKI', 'Dosen', 'Expired', 'Status'],
                    ])

                @elseif($category === 'patens')
                    @include('public.components.category-haki-paten', [
                        'title'   => $categoryTitle,
                        'icon'    => 'fa-book',
                        'tableId' => 'paten-table',
                        'type'    => 'paten',
                        'data'    => $categoryData,
                        'columns' => ['Judul Paten', 'Dosen', 'Jenis Paten', 'Expired', 'Status'],
                    ])
                @endif
            </div>

        {{-- ── Hasil Pencarian Dosen ──────────────────────────────────── --}}
        @elseif(isset($dosens) && $dosens->count() > 0)
            <div class="space-y-6">
                @foreach($dosens as $dosen)
                    @include('public.components.dosen-card', ['dosen' => $dosen, 'withToggle' => false])
                @endforeach
            </div>

        {{-- ── Tidak Ditemukan ────────────────────────────────────────── --}}
        @elseif(isset($dosens))
            <div class="bg-white rounded-2xl card-shadow p-12 text-center max-w-3xl mx-auto animate-fade-in">
                <div class="text-6xl text-blue-500 mb-6"><i class="fas fa-search"></i></div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">Dosen Tidak Ditemukan</h3>
                <p class="text-gray-600 mb-8 max-w-xl mx-auto">
                    Tidak ada hasil untuk pencarian "{{ $query }}". Silakan coba dengan kata kunci lain.
                </p>
                <div class="inline-block bg-blue-100 text-blue-800 px-6 py-3 rounded-full font-medium">
                    <i class="fas fa-lightbulb mr-2"></i>Tips: Gunakan hanya nama depan atau NIDN untuk hasil lebih akurat
                </div>
            </div>
        @endif

    </div>
</div>

@endsection