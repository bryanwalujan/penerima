<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar SK Pembimbing Mahasiswa</h2>
        <a href="{{ route('sk-pembimbing.index') }}" class="btn btn-primary">Refresh</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Mahasiswa</th>
                    <th>Judul Skripsi</th>
                    <th>Pembimbing 1</th>
                    <th>Pembimbing 2</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($skPembimbings as $sk)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sk->mahasiswa->nama ?? 'N/A' }}</td>
                    <td class="text-truncate" style="max-width: 250px;">
                        {{ Str::limit($sk->judul_skripsi, 70) }}
                    </td>
                    <td>{{ $sk->dosenPembimbing1->nama ?? '-' }}</td>
                    <td>{{ $sk->dosenPembimbing2->nama ?? '-' }}</td>
                    <td>
                        <span class="badge bg-{{ $sk->status === 'selesai' ? 'success' : 'warning' }}">
                            {{ ucfirst($sk->status) }}
                        </span>
                    </td>
                    <td>{{ $sk->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('sk-pembimbing.show', $sk) }}" 
                           class="btn btn-sm btn-info">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">Belum ada data SK Pembimbing</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $skPembimbings->links() }}
    </div>
</div>
@endsection

</body>
</html> 
