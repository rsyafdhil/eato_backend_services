<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Toko</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body>
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Daftar Tenants</span>
        </div>
    </nav>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">Daftar tenants</h1>
            <a href="{{ route('fe.tenants.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Tenant
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 100px;">Gambar</th>
                                <th>Nama Toko</th>
                                <th>Deskripsi</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tenants as $toko)
                                <tr>
                                    <td>
                                        <img src="{{ asset('storage/' . $toko->preview_image) }}"
                                            alt="{{ $toko->name }}" class="img-thumbnail"
                                            style="width: 80px; height: 80px; object-fit: cover;">
                                    </td>
                                    <td class="align-middle">
                                        <strong>{{ $toko->name }}</strong>
                                    </td>
                                    <td class="align-middle">
                                        <p class="mb-0 text-muted">{{ Str::limit($toko->description, 100) }}</p>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('fe.tenants.edit', $toko->id) }}"
                                            class="btn btn-sm btn-warning me-1">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('fe.tenants.destroy', $toko->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Yakin ingin menghapus toko ini?')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Belum ada data toko
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{-- {{ $tokos->links() }} --}}
        </div>
    </div>

    <footer class="bg-light text-center py-3 mt-5">
        <p class="mb-0 text-muted">&copy; 2024 Sistem Manajemen Toko</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
