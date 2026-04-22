{{--
    Component: public/components/category-table.blade.php
    Dipakai untuk kategori penelitians dan pengabdians.
    Props: $title, $icon, $tableId, $type, $data, $skema, $category, $color, $columns
--}}
<div class="bg-white rounded-2xl card-shadow overflow-hidden animate-fade-in">
    <div class="gradient-bg text-white p-6">
        <h2 class="text-xl md:text-2xl font-bold flex items-center">
            <i class="fas {{ $icon }} mr-3 hover:rotate-12 transition-transform"></i> {{ $title }}
        </h2>
    </div>
    <div class="p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas {{ $icon }} {{ $color }} mr-2"></i> {{ $title }}
            </h3>
            <div>
                <button class="sort-button" data-sort-target="{{ $tableId }}" data-sort-order="desc">Terbaru <i class="fas fa-chevron-down"></i></button>
                <button class="sort-button" data-sort-target="{{ $tableId }}" data-sort-order="asc">Terlama <i class="fas fa-chevron-up"></i></button>
            </div>
        </div>

        {{-- Filter Skema --}}
        <div class="skema-filter">
            @foreach([
                ['skema' => 'all',      'label' => 'Semua'],
                ['skema' => 'drtpm',    'label' => 'DRTPM'],
                ['skema' => 'internal', 'label' => 'Pendanaan Internal'],
                ['skema' => 'hibah',    'label' => 'Pendanaan Hibah'],
            ] as $f)
            <a href="{{ route('public.category', ['category' => $category, 'skema' => $f['skema']]) }}"
               class="{{ ($skema ?? 'all') === $f['skema'] ? 'tab-active '.$color : '' }}"
               data-skema="{{ $f['skema'] }}">
                <div class="tab-title">{{ $f['label'] }}</div>
                <div class="tab-count">
                    {{ $f['skema'] === 'all' ? $data->count() : $data->where('skema', $f['skema'])->count() }}
                </div>
            </a>
            @endforeach
        </div>

        {{-- Tabel --}}
        <div class="table-responsive">
            <table class="portfolio-table w-full" id="{{ $tableId }}">
                <thead>
                    <tr>
                        @foreach($columns as $col)
                            <th @if($loop->first) class="w-4/12" @endif>{{ $col }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="category-data" data-type="{{ $type }}">
                    @forelse($data as $item)
                    <tr data-skema="{{ $item->skema }}"
                        data-year="{{ $type === 'penelitian' ? $item->tahun : $item->tahun }}">
                        <td class="font-medium hover:{{ $color }} transition-colors cursor-pointer">
                            {{ $type === 'penelitian' ? $item->judul_penelitian : $item->judul_pengabdian }}
                        </td>
                        <td class="hover:font-medium transition-all">{{ $item->dosen->nama }}</td>
                        <td>{{ ucfirst($item->skema) }}</td>
                        <td>{{ $item->tahun }}</td>
                        <td>
                            @if($item->status === 'Selesai')
                                <span class="status-badge status-active"><i class="fas fa-check-circle mr-1"></i>{{ $item->status }}</span>
                            @else
                                <span class="status-badge status-pending"><i class="fas fa-spinner mr-1"></i>{{ $item->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr data-skema="none">
                        <td colspan="{{ count($columns) }}" class="text-center py-4 text-gray-600">Tidak ada data.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>