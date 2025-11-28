<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Item</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tambah Item</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('fe.items.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Item</label>
                        <input type="text" class="form-control" name="item_name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" class="form-control" name="slug">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <input type="number" class="form-control" name="category_item_id">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sub Kategori</label>
                        <input type="number" class="form-control" name="sub_category_item_id">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" class="form-control" name="price" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Preview Image</label>
                        <input type="file" class="form-control" name="preview_image">
                    </div>

                    <button class="btn btn-primary w-100">Simpan</button>

                </form>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
