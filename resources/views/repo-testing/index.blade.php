<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Repo Testing - File dari cssr</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>File yang Diterima dari cssr-dppkbd.id</h1>

    @if ($files->isEmpty())
        <div class="alert alert-info">Belum ada file yang di-upload dari e-service.</div>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama File</th>
                    <th>Ukuran</th>
                    <th>Tanggal Upload</th>
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
                        <a href="{{ $file['download_url'] }}" class="btn btn-sm btn-success">Download</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <hr>
    <a href="https://cssr-dppkbd.id/testing" class="btn btn-secondary">← Kembali ke Form Upload cssr</a>
</body>
</html>