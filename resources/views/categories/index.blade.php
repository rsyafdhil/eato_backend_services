<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-4">

        <div class="d-flex justify-content-between mb-3">
            <h2>Daftar Kategori</h2>
            <a href="{{ route('fe.category.create') }}" class="btn btn-primary">+ Tambah Kategori</a>
        </div>

        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Kategori</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama Kategori</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $cat)
                            <tr>
                                <td>{{ $cat->id }}</td>
                                <td>{{ $cat->category_name }}</td>
                                <td>{{ $cat->slug }}</td>
                                <td>
                                    @if ($cat->status)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('fe.category.edit', $cat->id) }}"
                                        class="btn btn-sm btn-warning">Edit</a>

                                    <form action="{{ route('fe.category.destroy', $cat->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Delete this subcategory?')"
                                            class="btn btn-sm btn-danger">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>

</html>
