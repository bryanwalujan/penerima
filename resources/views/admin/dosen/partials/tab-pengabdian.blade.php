<div id="pengabdian" class="tab-content hidden" role="tabpanel">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-3">
        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="fas fa-hands-helping text-blue-600 mr-2"></i> Data Pengabdian
        </h3>
        <div class="relative w-full md:w-64">
            <input type="text" placeholder="Cari data pengabdian..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500 search-pengabdian">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </div>
    </div>

    <table class="w-full min-w-max">
        <thead>
            <tr class="bg-gray-100 text-gray-700">
                <th class="py-3 px-4 text-left rounded-tl-lg">No</th>
                <th class="py-3 px-4 text-left">Dosen</th>
                <th class="py-3 px-4 text-left">Judul Pengabdian</th>
                <th class="py-3 px-4 text-left">Skema</th>
                <th class="py-3 px-4 text-left">Posisi</th>
                <th class="py-3 px-4 text-left">Sumber Dana</th>
                <th class="py-3 px-4 text-left">Status</th>
                <th class="py-3 px-4 text-left">Tahun</th>
                <th class="py-3 px-4 text-left">Luaran</th>
                <th class="py-3 px-4 text-center rounded-tr-lg">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @if (isset($dosens) && is_iterable($dosens))
                @foreach ($dosens as $dosen)
                    @if (is_iterable($dosen->pengabdians))
                        @foreach ($dosen->pengabdians as $pengabdian)
                            <tr class="table-row">
                                <td class="py-3 px-4 border-b">{{ $no++ }}</td>
                                <td class="py-3 px-4 border-b">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if ($dosen->foto)
                                                <img src="{{ Storage::url($dosen->foto) }}" alt="{{ $dosen->nama }}" class="h-10 w-10 rounded-full object-cover border">
                                            @else
                                                <div class="bg-gray-200 border-2 border-dashed rounded-full w-10 h-10 flex items-center justify-center text-gray-500">
                                                    <i class="fas fa-user text-sm"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <a href="{{ route('admin.dosen.show', $dosen->id) }}" class="font-medium text-blue-600 hover:underline">{{ $dosen->nama }}</a>
                                            <div class="text-gray-500 text-xs">{{ $dosen->nidn }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 border-b max-w-xs">
                                    <div class="font-medium">{{ $pengabdian->judul_pengabdian }}</div>
                                </td>
                                <td class="py-3 px-4 border-b">{{ $pengabdian->skema ?? '-' }}</td>
                                <td class="py-3 px-4 border-b">{{ $pengabdian->posisi ?? '-' }}</td>
                                <td class="py-3 px-4 border-b">{{ $pengabdian->sumber_dana ?? '-' }}</td>
                                <td class="py-3 px-4 border-b">
                                    <span class="status-badge {{ $pengabdian->status == 'Selesai' ? 'status-selesai' : ($pengabdian->status == 'Berjalan' ? 'status-berjalan' : 'status-diajukan') }}">
                                        {{ $pengabdian->status ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 border-b">{{ $pengabdian->tahun ?? '-' }}</td>
                                <td class="py-3 px-4 border-b">
                                    @if ($pengabdian->link_luaran)
                                        <a href="{{ $pengabdian->link_luaran }}" target="_blank" class="text-blue-600 hover:underline">Lihat Luaran</a>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 border-b">
                                    <div class="flex justify-center space-x-3">
                                        <a href="{{ route('admin.dosen.edit', $dosen->id) }}" class="text-blue-600 hover:text-blue-800 action-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.pengabdian.destroy', $pengabdian->id) }}" method="POST" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 action-btn" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            @else
                <tr>
                    <td colspan="10" class="py-3 px-4 text-center text-gray-500">Tidak ada data pengabdian tersedia.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>