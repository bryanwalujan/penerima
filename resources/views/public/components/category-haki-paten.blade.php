{{--
    Component: public/components/category-haki-paten.blade.php
    Dipakai untuk kategori hakis dan patens (tanpa filter skema).
    Props: $title, $icon, $tableId, $type, $data, $columns
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
                <i class="fas {{ $icon }} {{ $type === 'haki' ? 'text-purple-600' : 'text-yellow-600' }} mr-2"></i>
                {{ $title }}
            </h3>
            <div>
                <button class="sort-button" data-sort-target="{{ $tableId }}" data-sort-order="desc">Terbaru <i class="fas fa-chevron-down"></i></button>
                <button class="sort-button" data-sort-target="{{ $tableId }}" data-sort-order="asc">Terlama <i class="fas fa-chevron-up"></i></button>
            </div>
        </div>
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
                    <tr data-year="{{ $item->expired ? \Carbon\Carbon::parse($item->expired)->format('Y') : '0' }}">
                        <td class="font-medium hover:{{ $type === 'haki' ? 'text-purple-600' : 'text-yellow-600' }} transition-colors cursor-pointer">
                            {{ $type === 'haki' ? $item->judul_haki : $item->judul_paten }}
                        </td>
                        <td class="hover:font-medium transition-all">{{ $item->dosen->nama }}</td>
                        @if($type === 'paten')
                        <td>{{ $item->jenis_paten }}</td>
                        @endif
                        <td>{{ $item->expired ? \Carbon\Carbon::parse($item->expired)->format('d M Y') : '-' }}</td>
                        <td><span class="status-badge status-active"><i class="fas fa-check-circle mr-1"></i>Aktif</span></td>
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