<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Edit Item</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('fe.items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Item</label>
                        <input type="text" id="item_name" class="form-control @error('item_name') is-invalid @enderror" 
                            name="item_name" value="{{ old('item_name', $item->item_name) }}" required>
                        @error('item_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                            name="description" rows="3">{{ old('description', $item->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" id="slug" class="form-control @error('slug') is-invalid @enderror" 
                            name="slug" value="{{ old('slug', $item->slug) }}" placeholder="Akan dibuat otomatis" required>
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Slug akan dibuat otomatis dari nama item</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="category_item_id" id="category_item_id" 
                            class="form-select @error('category_item_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" 
                                    {{ old('category_item_id', $item->category_item_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->category_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_item_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- SUB CATEGORY DROPDOWN -->
                    <div class="mb-3">
                        <label class="form-label">Sub Kategori</label>
                        <select name="sub_category_item_id" id="sub_category_item_id" 
                            class="form-select @error('sub_category_item_id') is-invalid @enderror">
                            <option value="">-- Pilih Sub Kategori --</option>
                            @foreach ($subCategories as $sub)
                                <option value="{{ $sub->id }}" data-parent="{{ $sub->parent_category_id }}" 
                                    {{ old('sub_category_item_id', $item->sub_category_item_id) == $sub->id ? 'selected' : '' }}>
                                    {{ $sub->sub_category_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('sub_category_item_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" 
                            name="price" value="{{ old('price', $item->price) }}" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Preview Image</label>
                        
                        @if($item->preview_image)
                            <div class="mb-2">
                                <p class="text-muted mb-1">Gambar Saat Ini:</p>
                                <img src="{{ asset('storage/' . $item->preview_image) }}" 
                                    alt="{{ $item->item_name }}" 
                                    class="img-thumbnail" 
                                    style="max-height: 200px;">
                            </div>
                        @endif

                        <input type="file" class="form-control @error('preview_image') is-invalid @enderror" 
                            name="preview_image" accept="image/*" id="preview_image" onchange="previewImage(event)">
                        @error('preview_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>

                        <div id="imagePreview" class="mt-3 d-none">
                            <p class="text-muted mb-2">Preview Gambar Baru:</p>
                            <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('fe.items.index') }}" class="btn btn-secondary">Kembali</a>
                        <button class="btn btn-warning flex-grow-1" type="submit">Update</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto-generate slug from item name
        document.getElementById('item_name').addEventListener('input', function() {
            const itemName = this.value;
            const slug = itemName
                .toLowerCase()
                .replace(/[^\w\s-]/g, '') // Remove special characters
                .replace(/\s+/g, '-')      // Replace spaces with hyphens
                .replace(/-+/g, '-')       // Replace multiple hyphens with single hyphen
                .trim();
            document.getElementById('slug').value = slug;
        });

        // Filter sub-categories based on selected category
        document.getElementById('category_item_id').addEventListener('change', function() {
            const selectedCategoryId = this.value;
            const subCategorySelect = document.getElementById('sub_category_item_id');
            const subCategoryOptions = subCategorySelect.querySelectorAll('option');

            // Reset sub-category selection
            subCategorySelect.value = '';

            // Show/hide sub-category options based on parent category
            subCategoryOptions.forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block'; // Always show placeholder
                } else {
                    const parentId = option.getAttribute('data-parent');
                    if (parentId === selectedCategoryId) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                }
            });
        });

        // Trigger filter on page load if category is pre-selected
        window.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category_item_id');
            if (categorySelect.value) {
                categorySelect.dispatchEvent(new Event('change'));
            }
        });

        // Preview new image
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