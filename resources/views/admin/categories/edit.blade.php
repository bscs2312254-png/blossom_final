@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Edit Category: {{ $category->name }}</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Category Name *</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="sort_order" class="form-label">Sort Order</label>
                    <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ $category->sort_order }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="image" class="form-label">Category Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <div class="form-text">Leave empty to keep current image. Allowed: JPG, PNG, GIF, WEBP. Max: 2MB</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Current Image</label>
                    <div id="imagePreview" class="border rounded p-3 text-center">
                        @if($category->image && file_exists(public_path('uploads/categories/' . $category->image)))
                            <img src="{{ asset('uploads/categories/' . $category->image) }}" class="img-fluid rounded" style="max-height: 140px;" alt="{{ $category->name }}">
                            <p class="mt-2 mb-0"><small>Current: {{ $category->image }}</small></p>
                        @elseif($category->image)
                            <img src="{{ asset('images/' . $category->image) }}" class="img-fluid rounded" style="max-height: 140px;" alt="{{ $category->name }}">
                            <p class="mt-2 mb-0"><small>Legacy: {{ $category->image }}</small></p>
                        @else
                            <p class="text-muted mb-0">No image</p>
                        @endif
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ $category->description }}</textarea>
                </div>
                <div class="col-12 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                               {{ $category->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active Category
                        </label>
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Update Category</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Image preview functionality for edit form
document.getElementById('image').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}" class="img-fluid rounded" style="max-height: 140px;" alt="Preview">
                <p class="mt-2 mb-0"><small>${file.name} (New upload)</small></p>
                <p class="text-muted mb-0"><small>${(file.size / 1024).toFixed(2)} KB</small></p>
            `;
        }
        
        reader.readAsDataURL(file);
    }
});
</script>
@endsection