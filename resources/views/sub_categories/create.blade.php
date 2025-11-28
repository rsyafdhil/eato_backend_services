<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Sub Category</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Tambah Sub Category</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('fe.subcat.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Nama Sub Category -->
                    <div class="mb-3">
                        <label class="form-label">Nama Sub Category</label>
                        <input type="text"
                            class="form-control @error('sub_category_name') 
                            is-invalid
                        @enderror"
                            name="sub_category_name" value="{{ old('sub_category_name') }}" required>
                        @error('sub_category_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text"
                            class="form-control @error('slug')
                            is-invalid
                        @enderror"
                            name="slug" value="{{ old('slug') }}">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Pilih Parent Category -->
                    <div class="mb-3">
                        <label class="form-label">Parent Category</label>
                        <select class="form-select" name="parent_category_id" required>
                            <option value="">-- Pilih Category --</option>

                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                            @endforeach

                        </select>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>

                    <button class="btn btn-success w-100" type="submit">Simpan</button>

                </form>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
