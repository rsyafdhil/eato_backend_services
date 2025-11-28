<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Items</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container mt-4">

        <div class="d-flex justify-content-between mb-3">
            <h3>Daftar Items</h3>
            <a href="/items/create" class="btn btn-primary">+ Tambah Item</a>
        </div>

        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Items</h5>
            </div>

            <div class="card-body p-0">

                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Preview</th>
                            <th>Nama Item</th>
                            <th>Deskripsi</th>
                            <th>Harga</th>
                            <th>Kategori</th>
                            <th>Sub Kategori</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>

                                <td>
                                    @if ($item->preview_image)
                                        <img src="{{ asset('storage/' . $item->preview_image) }}" width="60"
                                            class="rounded">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td>{{ $item->item_name }}</td>
                                <td>{{ $item->description }}</td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>{{ $item->category_item_id }}</td>
                                <td>{{ $item->sub_category_item_id }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
