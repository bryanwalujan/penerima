<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar SK Pembimbing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Daftar SK Pembimbing Mahasiswa</h2>

        @if (empty($skPembimbings) || $skPembimbings->isEmpty())
            <div class="alert alert-info text-center py-5">
                <h5>Belum ada data SK Pembimbing</h5>
                <p>Data akan muncul setelah e-service mengirimkan data.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
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
                        @foreach ($skPembimbings as $sk)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $sk->mahasiswa->nama ?? 'N/A' }}</td>
                            <td>{{ Str::limit($sk->judul_skripsi ?? '', 80) }}</td>
                            <td>{{ $sk->dosenPembimbing1->nama ?? '-' }}</td>
                            <td>{{ $sk->dosenPembimbing2->nama ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ ($sk->status ?? 'draft') === 'selesai' ? 'success' : 'warning' }}">
                                    {{ ucfirst($sk->status ?? 'draft') }}
                                </span>
                            </td>
                            <td>{{ $sk->created_at?->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('sk-pembimbing.show', $sk) }}" class="btn btn-sm btn-primary">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</body>
</html>