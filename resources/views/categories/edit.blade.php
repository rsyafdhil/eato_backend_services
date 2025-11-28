@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h3>Edit Category</h3>

        <form action="{{ route('fe.category.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Category Name</label>
                <input type="text" class="form-control" name="category_name" value="{{ $category->category_name }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" class="form-control" name="slug" value="{{ $category->slug }}" required>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="1" {{ $category->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $category->status == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button class="btn btn-primary" type="submit">Update</button>
            <a href="{{ route('fe.category.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
@endsection
