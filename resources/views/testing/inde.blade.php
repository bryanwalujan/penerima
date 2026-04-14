<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Testing - Repodosen Files</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Daftar File yang Masuk dari cssr-dppkbd.id</h1>

    @if ($files->isEmpty())
        <div class="alert alert-info">Belum ada file yang di-upload.</div>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama File</th>
                    <th>Ukuran</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($files as $index => $file)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $file['name'] }}</td>
                    <td>{{ $file['size'] }}</td>
                    <td>{{ $file['modified'] }}</td>
                    <td>
                        <a href="{{ $file['url'] }}" target="_blank" class="btn btn-sm btn-primary">Lihat / Download</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <hr>
    <a href="https://cssr-dppkbd.id/testing" class="btn btn-secondary">← Kembali ke Form Upload</a>
</body>
</html>