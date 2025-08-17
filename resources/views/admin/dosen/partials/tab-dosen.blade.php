<div id="dosen" class="tab-content" role="tabpanel">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-3">
        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="fas fa-user-tie text-blue-600 mr-2"></i> Data Dosen ({{ isset($dosens) ? $dosens->count() : 0 }} dosen)
        </h3>
        <div class="relative w-full md:w-64">
            <input type="text" id="search-dosen" placeholder="Cari data dosen..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </div>
    </div>

    <table class="w-full min-w-max">
        <thead>
            <tr class="bg-gray-100 text-gray-700">
                <th class="py-3 px-4 text-left rounded-tl-lg">No</th>
                <th class="py-3 px-4 text-left">Foto</th>
                <th class="py-3 px-4 text-left">Nama</th>
                <th class="py-3 px-4 text-left">NIDN</th>
                <th class="py-3 px-4 text-left">NIP</th>
                <th class="py-3 px-4 text-left">NUPTK</th>
                <th class="py-3 px-4 text-left">Jumlah Data</th>
                <th class="py-3 px-4 text-center rounded-tr-lg">Aksi</th>
            </tr>
        </thead>
        <tbody id="dosen-table">
            @php $no = 1; @endphp
            @if (isset($dosens) && is_iterable($dosens))
                @foreach ($dosens as $dosen)
                    <tr class="table-row">
                        <td class="py-3 px-4 border-b">{{ $no++ }}</td>
                        <td class="py-3 px-4 border-b">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if ($dosen->foto)
                                    <img src="{{ Storage::url($dosen->foto) }}" alt="{{ $dosen->nama }}" class="h-10 w-10 rounded-full object-cover border">
                                @else
                                    <div class="bg-gray-200 border-2 border-dashed rounded-full w-10 h-10 flex items-center justify-center text-gray-500">
                                        <i class="fas fa-user text-sm"></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4 border-b">
                            <a href="{{ route('admin.dosen.show', $dosen->id) }}" class="font-medium text-blue-600 hover:underline">{{ $dosen->nama }}</a>
                        </td>
                        <td class="py-3 px-4 border-b">{{ $dosen->nidn }}</td>
                        <td class="py-3 px-4 border-b">{{ $dosen->nip ?? '-' }}</td>
                        <td class="py-3 px-4 border-b">{{ $dosen->nuptk ?? '-' }}</td>
                        <td>
                            <div class="flex flex-wrap gap-1">
                                @if ($dosen->penelitians->count() > 0)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-flask mr-1"></i>
                                        {{ $dosen->penelitians->count() }}
                                    </span>
                                @endif
                                @if ($dosen->pengabdians->count() > 0)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-hands-helping mr-1"></i>
                                        {{ $dosen->pengabdians->count() }}
                                    </span>
                                @endif
                                @if ($dosen->hakis->count() > 0)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-copyright mr-1"></i>
                                        {{ $dosen->hakis->count() }}
                                    </span>
                                @endif
                                @if ($dosen->patens->count() > 0)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-certificate mr-1"></i>
                                        {{ $dosen->patens->count() }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4 border-b">
                            <div class="flex justify-center space-x-3">
                                <a href="{{ route('admin.dosen.show', $dosen->id) }}" class="text-blue-600 hover:text-blue-800 action-btn" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.dosen.edit', $dosen->id) }}" class="text-blue-600 hover:text-blue-800 action-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button data-dosen-id="{{ $dosen->id }}" class="text-green-600 hover:text-green-800 action-btn recommend-btn" title="Rekomendasi Kolaborasi">
                                    <i class="fas fa-users"></i>
                                </button>
                                <form action="{{ route('admin.dosen.destroy', $dosen->id) }}" method="POST" class="inline delete-form">
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
            @else
                <tr>
                    <td colspan="8" class="py-3 px-4 text-center text-gray-500">Tidak ada data dosen tersedia.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>