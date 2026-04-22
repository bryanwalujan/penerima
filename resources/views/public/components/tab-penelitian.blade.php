{{-- Tab: Penelitian. Props: $dosen --}}
<div id="penelitian-{{ $dosen->id }}" class="tab-content">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-flask text-blue-600 mr-2 hover:rotate-12 transition-transform"></i>
                Penelitian Dosen
            </h3>
            <div>
                <button class="sort-button" data-sort-target="penelitian-{{ $dosen->id }}" data-sort-order="desc">
                    Terbaru <i class="fas fa-chevron-down"></i>
                </button>
                <button class="sort-button" data-sort-target="penelitian-{{ $dosen->id }}" data-sort-order="asc">
                    Terlama <i class="fas fa-chevron-up"></i>
                </button>
            </div>
        </div>

        {{-- Filter Skema --}}
        <div class="flex flex-wrap gap-4 mb-4 skema-filter">
            @foreach([
                ['skema' => 'all',      'label' => 'Semua',            'count' => $dosen->penelitians->count()],
                ['skema' => 'drtpm',    'label' => 'DRTPM',            'count' => $dosen->penelitians->where('skema', 'drtpm')->count()],
                ['skema' => 'internal', 'label' => 'Pendanaan Internal','count' => $dosen->penelitians->where('skema', 'internal')->count()],
                ['skema' => 'hibah',    'label' => 'Pendanaan Hibah',   'count' => $dosen->penelitians->where('skema', 'hibah')->count()],
            ] as $f)
            <a href="#"
               class="portfolio-tab tab-link {{ $loop->first ? 'tab-active' : '' }}"
               data-skema="{{ $f['skema'] }}"
               data-tab="penelitian-{{ $dosen->id }}">
                <div class="tab-title">{{ $f['label'] }}</div>
                <div class="tab-count">{{ $f['count'] }}</div>
            </a>
            @endforeach
        </div>

        {{-- Tabel --}}
        <div class="table-responsive">
            <table class="portfolio-table w-full">
                <thead>
                    <tr>
                        <th class="w-4/12">Judul Penelitian</th>
                        <th>Skema</th>
                        <th>Tahun</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="dosen-data" data-type="penelitian" data-id="{{ $dosen->id }}">
                    @forelse($dosen->penelitians as $penelitian)
                    <tr data-skema="{{ $penelitian->skema }}" data-year="{{ $penelitian->tahun }}">
                        <td class="font-medium hover:text-blue-600 transition-colors cursor-pointer">{{ $penelitian->judul_penelitian }}</td>
                        <td>{{ ucfirst($penelitian->skema) }}</td>
                        <td>{{ $penelitian->tahun }}</td>
                        <td>
                            @if($penelitian->status === 'Selesai')
                                <span class="status-badge status-active"><i class="fas fa-check-circle mr-1"></i>{{ $penelitian->status }}</span>
                            @else
                                <span class="status-badge status-pending"><i class="fas fa-spinner mr-1"></i>{{ $penelitian->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr data-skema="none">
                        <td colspan="4" class="text-center py-4 text-gray-600">Tidak ada data penelitian.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>