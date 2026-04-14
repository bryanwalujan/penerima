<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumen dari Presma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
<div class="container">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4 py-3">
            <h5 class="mb-0">
                <i class="bi bi-folder2-open me-2"></i>Dokumen dari Presma
            </h5>
            <span class="badge bg-light text-dark">{{ $files->count() }} file</span>
        </div>
        <div class="card-body">
            @if ($files->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox display-1"></i>
                    <h5 class="mt-3">Belum ada dokumen</h5>
                    <p>File yang dikirim dari Presma akan muncul di sini</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama File</th>
                                <th>Ukuran</th>
                                <th>Tanggal Upload</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($files as $index => $file)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-medium">
                                    <i class="bi bi-file-earmark-pdf text-danger me-1"></i>
                                    {{ $file['name'] }}
                                </td>
                                <td>{{ $file['size'] }}</td>
                                <td>{{ $file['modified'] }}</td>
                                <td class="text-center">
                                    <a href="{{ $file['url'] }}" 
   class="btn btn-sm btn-success" 
   target="_blank">
    <i class="bi bi-download me-1"></i>Download
</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
</body>
</html>