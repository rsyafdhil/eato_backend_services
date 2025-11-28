<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tambah Kategori</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('fe.category.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" name="category_name"
                            class="form-control @error('category_name')
                            is-invalid
                        @enderror"
                            value="{{ old('category_nama') }}" required>
                        @error('category_nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug"
                            class="form-control @error('slug')
                        is-invalid
                        @enderror"
                            value="{{ old('slug') }}" placeholder="otomatis atau manual" required>
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>

                    <button class="btn btn-primary w-100" type="submit">Simpan</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
