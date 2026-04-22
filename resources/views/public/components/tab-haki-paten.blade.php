{{-- Tab: HAKI. Props: $dosen --}}
<div id="haki-{{ $dosen->id }}" class="tab-content hidden">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-copyright text-purple-600 mr-2 hover:rotate-12 transition-transform"></i>
                Hak Kekayaan Intelektual
            </h3>
            <div>
                <button class="sort-button" data-sort-target="haki-{{ $dosen->id }}" data-sort-order="desc">Terbaru <i class="fas fa-chevron-down"></i></button>
                <button class="sort-button" data-sort-target="haki-{{ $dosen->id }}" data-sort-order="asc">Terlama <i class="fas fa-chevron-up"></i></button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="portfolio-table w-full">
                <thead><tr><th class="w-4/12">Judul HAKI</th><th>Expired</th><th>Status</th></tr></thead>
                <tbody class="dosen-data" data-type="haki" data-id="{{ $dosen->id }}">
                    @forelse($dosen->hakis as $haki)
                    <tr data-year="{{ $haki->expired ? \Carbon\Carbon::parse($haki->expired)->format('Y') : '0' }}">
                        <td class="font-medium hover:text-purple-600 transition-colors cursor-pointer">{{ $haki->judul_haki }}</td>
                        <td>{{ $haki->expired ? \Carbon\Carbon::parse($haki->expired)->format('d M Y') : '-' }}</td>
                        <td><span class="status-badge status-active"><i class="fas fa-check-circle mr-1"></i>Aktif</span></td>
                    </tr>
                    @empty
                    <tr data-skema="none"><td colspan="3" class="text-center py-4 text-gray-600">Tidak ada data HAKI.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Tab: Paten. Props: $dosen --}}
<div id="paten-{{ $dosen->id }}" class="tab-content hidden">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-book text-yellow-600 mr-2 hover:rotate-12 transition-transform"></i>
                Paten dan Inovasi
            </h3>
            <div>
                <button class="sort-button" data-sort-target="paten-{{ $dosen->id }}" data-sort-order="desc">Terbaru <i class="fas fa-chevron-down"></i></button>
                <button class="sort-button" data-sort-target="paten-{{ $dosen->id }}" data-sort-order="asc">Terlama <i class="fas fa-chevron-up"></i></button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="portfolio-table w-full">
                <thead><tr><th class="w-4/12">Judul Paten</th><th>Jenis Paten</th><th>Expired</th><th>Status</th></tr></thead>
                <tbody class="dosen-data" data-type="paten" data-id="{{ $dosen->id }}">
                    @forelse($dosen->patens as $paten)
                    <tr data-year="{{ $paten->expired ? \Carbon\Carbon::parse($paten->expired)->format('Y') : '0' }}">
                        <td class="font-medium hover:text-yellow-600 transition-colors cursor-pointer">{{ $paten->judul_paten }}</td>
                        <td>{{ $paten->jenis_paten }}</td>
                        <td>{{ $paten->expired ? \Carbon\Carbon::parse($paten->expired)->format('d M Y') : '-' }}</td>
                        <td><span class="status-badge status-active"><i class="fas fa-check-circle mr-1"></i>Aktif</span></td>
                    </tr>
                    @empty
                    <tr data-skema="none"><td colspan="4" class="text-center py-4 text-gray-600">Tidak ada data paten.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>