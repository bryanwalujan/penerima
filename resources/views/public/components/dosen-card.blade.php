{{--
    Component: public/components/dosen-card.blade.php
    Props: $dosen (Dosen model with relations)
    Usage: @include('public.components.dosen-card', ['dosen' => $dosen])
--}}
<div class="bg-white rounded-2xl card-shadow overflow-hidden animate-fade-in dosen-card"
     data-dosen-id="{{ $dosen->id }}">

    {{-- ── Header Profil ────────────────────────────────────────────────── --}}
    <div class="gradient-bg text-white p-6 md:p-8 relative overflow-hidden dosen-header">
        <div class="absolute top-0 right-0 opacity-20">
            <i class="fas fa-atom text-[180px] text-blue-500 hover:rotate-180 transition-transform duration-1000"></i>
        </div>
        <div class="flex flex-col md:flex-row items-center relative z-10">
            {{-- Foto --}}
            <div class="mb-6 md:mb-0 md:mr-8">
                <div class="w-24 h-24 md:w-32 md:h-32 bg-gray-200 rounded-full overflow-hidden profile-border">
                    <img src="{{ $dosen->foto ? Storage::url($dosen->foto) : 'https://static.vecteezy.com/system/resources/previews/005/544/718/non_2x/profile-icon-design-free-vector.jpg' }}"
                         alt="Foto Dosen {{ $dosen->nama }}"
                         class="w-full h-full object-cover">
                </div>
            </div>
            {{-- Info --}}
            <div class="text-center md:text-left">
                <h2 class="text-2xl md:text-3xl font-bold hover:text-blue-200 transition-colors">
                    {{ $dosen->nama }}
                </h2>
                <p class="text-blue-200 mt-2">
                    <i class="fas fa-user-tie mr-2"></i>
                    {{ $dosen->jabatan_akademik ?? 'Dosen Teknik Informatika' }}
                </p>
                <div class="flex flex-wrap justify-center md:justify-start gap-3 mt-4">
                    <div class="bg-blue-500/30 backdrop-blur px-3 py-1.5 rounded-full text-sm hover:bg-blue-600 transition-colors">
                        <i class="fas fa-id-card mr-2"></i> NIDN: {{ $dosen->nidn ?? '-' }}
                    </div>
                    <div class="bg-blue-500/30 backdrop-blur px-3 py-1.5 rounded-full text-sm hover:bg-blue-600 transition-colors">
                        <i class="fas fa-fingerprint mr-2"></i> NIP: {{ $dosen->nip ?? '-' }}
                    </div>
                    <div class="bg-blue-500/30 backdrop-blur px-3 py-1.5 rounded-full text-sm hover:bg-blue-600 transition-colors">
                        <i class="fas fa-id-badge mr-2"></i> NUPTK: {{ $dosen->nuptk ?? '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Toggle Button (hanya jika dari category dosens) ────────────────── --}}
    @if(isset($withToggle) && $withToggle)
    <div class="p-4 bg-white flex justify-center">
        <button class="toggle-button" data-toggle-target="content-{{ $dosen->id }}">
            Lihat Penelitian <i class="fas fa-chevron-down"></i>
        </button>
    </div>
    <div id="content-{{ $dosen->id }}" class="tab-content-container">
    @endif

    {{-- ── Tab Navigation ───────────────────────────────────────────────── --}}
    <div class="border-b border-gray-200 bg-white">
        <ul class="flex flex-wrap tab-group">
            @foreach([
                ['id' => 'penelitian', 'icon' => 'fa-flask', 'color' => 'text-blue-600', 'label' => 'Penelitian', 'count' => $dosen->penelitians->count()],
                ['id' => 'pengabdian', 'icon' => 'fa-hands-helping', 'color' => 'text-green-600', 'label' => 'Pengabdian', 'count' => $dosen->pengabdians->count()],
                ['id' => 'haki', 'icon' => 'fa-copyright', 'color' => 'text-purple-600', 'label' => 'HAKI', 'count' => $dosen->hakis->count()],
                ['id' => 'paten', 'icon' => 'fa-book', 'color' => 'text-yellow-600', 'label' => 'Paten', 'count' => $dosen->patens->count()],
            ] as $tab)
            <li class="tab-item">
                <a class="portfolio-tab tab-link {{ $loop->first ? 'tab-active' : '' }}"
                   data-tab="{{ $tab['id'] }}-{{ $dosen->id }}">
                    <div class="tab-icon {{ $tab['color'] }}"><i class="fas {{ $tab['icon'] }}"></i></div>
                    <div class="tab-title">{{ $tab['label'] }}</div>
                    <div class="tab-count">{{ $tab['count'] }}</div>
                </a>
            </li>
            @endforeach
        </ul>
    </div>

    {{-- ── Tab Content ──────────────────────────────────────────────────── --}}
    <div class="portfolio-section dosen-content">

        {{-- Penelitian --}}
        @include('public.components.tab-penelitian', ['dosen' => $dosen])

        {{-- Pengabdian --}}
        @include('public.components.tab-pengabdian', ['dosen' => $dosen])

        {{-- HAKI --}}
        @include('public.components.tab-haki', ['dosen' => $dosen])

        {{-- Paten --}}
        @include('public.components.tab-paten', ['dosen' => $dosen])

    </div>

    @if(isset($withToggle) && $withToggle)
    </div>{{-- end tab-content-container --}}
    @endif

</div>