@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h3>Edit Sub Category</h3>

        <form action="{{ route('fe.subcat.update', $subCategory->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Parent Category -->
            <div class="mb-3">
                <label class="form-label">Parent Category</label>
                <select name="parent_category_id" class="form-control" required>
                    <option value="">-- Select Category --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ $cat->id == $subCategory->parent_category_id ? 'selected' : '' }}>
                            {{ $cat->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sub Category Name -->
            <div class="mb-3">
                <label class="form-label">Sub Category Name</label>
                <input type="text" class="form-control" name="sub_category_name"
                    value="{{ $subCategory->sub_category_name }}" required>
            </div>

            <!-- Slug -->
            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" class="form-control" name="slug" value="{{ $subCategory->slug }}" required>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="1" {{ $subCategory->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $subCategory->status == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button class="btn btn-success" type="submit">Update</button>
            <a href="{{ route('fe.subcat.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
@endsection
