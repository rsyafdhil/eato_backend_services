<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tenant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body>
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Edit Tenant</span>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('fe.tenants.index') }}" class="btn btn-outline-secondary me-3">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <h1 class="h2 mb-0">Edit Toko</h1>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('fe.tenants.update', $tenant->id) }}" method="POST"
                            enctype="multipart/form-data">

                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">
                                    Nama Tenant <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $tenant->name) }}" required>

                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-semibold">
                                    Deskripsi <span class="text-danger">*</span>
                                </label>
                                <textarea name="description" id="description" rows="5"
                                    class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $tenant->description) }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="owner_id" class="form-label fw-semibold">
                                    Pemilik (Owner) <span class="text-danger">*</span>
                                </label>
                                <select name="owner_id" id="owner_id" class="form-select" required>
                                    <option value="">-- Pilih Owner --</option>
                                    @foreach ($owners as $owner)
                                        <option value="{{ $owner->id }}"
                                            {{ $owner->id == old('owner_id', $tenant->owner_id) ? 'selected' : '' }}>
                                            {{ $owner->name }} ({{ $owner->email }})
                                        </option>
                                    @endforeach
                                </select>

                                @error('owner_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="preview_image" class="form-label fw-semibold">
                                    Gambar Tenant
                                </label>
                                <input type="file" name="preview_image" id="preview_image"
                                    class="form-control @error('preview_image') is-invalid @enderror" accept="image/*"
                                    onchange="previewImage(event)">

                                @error('preview_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="form-text">Biarkan kosong jika tidak ingin mengubah gambar</div>

                                <div id="imagePreview" class="mt-3 {{ $tenant->preview_image ? '' : 'd-none' }}">
                                    <p class="text-muted mb-2">Preview:</p>
                                    <img id="preview"
                                        src="{{ $tenant->preview_image ? asset('storage/' . $tenant->preview_image) : '' }}"
                                        alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('fe.tenants.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Update
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <footer class="bg-light text-center py-3 mt-5">
        <p class="mb-0 text-muted">&copy; 2024 Sistem Manajemen Toko</p>
    </footer>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('preview');
                const previewDiv = document.getElementById('imagePreview');
                preview.src = reader.result;
                previewDiv.classList.remove('d-none');
            }
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
</body>

</html>
