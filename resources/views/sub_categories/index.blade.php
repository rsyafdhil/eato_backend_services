<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sub Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
            <h3>Sub Category List</h3>
            <a href="{{ route('fe.subcat.create') }}" class="btn btn-primary">+ Add Sub Category</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Sub Category</th>
                            <th>Slug</th>
                            <th>Parent Category</th>
                            <th>Status</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($subCategories as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->sub_category_name }}</td>
                                <td>{{ $item->slug }}</td>
                                <td>{{ $item->category->category_name }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->status ? 'success' : 'secondary' }}">
                                        {{ $item->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('fe.subcat.edit', $item->id) }}"
                                        class="btn btn-sm btn-warning">Edit</a>

                                    <form action="{{ route('fe.subcat.destroy', $item->id) }}" method="POST"
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
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No sub categories found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</body>

</html>
