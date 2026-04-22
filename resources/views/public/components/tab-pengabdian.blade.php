{{-- Tab: Pengabdian. Props: $dosen --}}
<div id="pengabdian-{{ $dosen->id }}" class="tab-content hidden">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-hands-helping text-green-600 mr-2 hover:rotate-12 transition-transform"></i>
                Pengabdian Masyarakat
            </h3>
            <div>
                <button class="sort-button" data-sort-target="pengabdian-{{ $dosen->id }}" data-sort-order="desc">Terbaru <i class="fas fa-chevron-down"></i></button>
                <button class="sort-button" data-sort-target="pengabdian-{{ $dosen->id }}" data-sort-order="asc">Terlama <i class="fas fa-chevron-up"></i></button>
            </div>
        </div>
        <div class="flex flex-wrap gap-4 mb-4 skema-filter">
            @foreach([
                ['skema' => 'all',      'label' => 'Semua',            'count' => $dosen->pengabdians->count()],
                ['skema' => 'drtpm',    'label' => 'DRTPM',            'count' => $dosen->pengabdians->where('skema', 'drtpm')->count()],
                ['skema' => 'internal', 'label' => 'Pendanaan Internal','count' => $dosen->pengabdians->where('skema', 'internal')->count()],
                ['skema' => 'hibah',    'label' => 'Pendanaan Hibah',   'count' => $dosen->pengabdians->where('skema', 'hibah')->count()],
            ] as $f)
            <a href="#" class="portfolio-tab tab-link {{ $loop->first ? 'tab-active' : '' }}"
               data-skema="{{ $f['skema'] }}" data-tab="pengabdian-{{ $dosen->id }}">
                <div class="tab-title">{{ $f['label'] }}</div>
                <div class="tab-count">{{ $f['count'] }}</div>
            </a>
            @endforeach
        </div>
        <div class="table-responsive">
            <table class="portfolio-table w-full">
                <thead><tr><th class="w-4/12">Judul Pengabdian</th><th>Skema</th><th>Tahun</th><th>Status</th></tr></thead>
                <tbody class="dosen-data" data-type="pengabdian" data-id="{{ $dosen->id }}">
                    @forelse($dosen->pengabdians as $pengabdian)
                    <tr data-skema="{{ $pengabdian->skema }}" data-year="{{ $pengabdian->tahun }}">
                        <td class="font-medium hover:text-green-600 transition-colors cursor-pointer">{{ $pengabdian->judul_pengabdian }}</td>
                        <td>{{ ucfirst($pengabdian->skema) }}</td>
                        <td>{{ $pengabdian->tahun }}</td>
                        <td>
                            @if($pengabdian->status === 'Selesai')
                                <span class="status-badge status-active"><i class="fas fa-check-circle mr-1"></i>{{ $pengabdian->status }}</span>
                            @else
                                <span class="status-badge status-pending"><i class="fas fa-spinner mr-1"></i>{{ $pengabdian->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr data-skema="none"><td colspan="4" class="text-center py-4 text-gray-600">Tidak ada data pengabdian.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>