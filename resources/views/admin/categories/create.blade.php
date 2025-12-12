@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Add New Category</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Category Name *</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    <div class="form-text">e.g., Roses, Lilies, Tulips</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="sort_order" class="form-label">Sort Order</label>
                    <input type="number" class="form-control" id="sort_order" name="sort_order" value="0">
                    <div class="form-text">Lower numbers appear first</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="image" class="form-label">Category Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <div class="form-text">Optional: Allowed: JPG, PNG, GIF, WEBP. Max: 2MB</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Image Preview</label>
                    <div id="imagePreview" class="border rounded p-3 text-center" style="min-height: 150px;">
                        <p class="text-muted mb-0">No image selected</p>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                <div class="col-12 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">
                            Active Category
                        </label>
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Save Category</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Image preview functionality
document.getElementById('image').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}" class="img-fluid rounded" style="max-height: 140px;" alt="Preview">
                <p class="mt-2 mb-0"><small>${file.name}</small></p>
                <p class="text-muted mb-0"><small>${(file.size / 1024).toFixed(2)} KB</small></p>
            `;
        }
        
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '<p class="text-muted mb-0">No image selected</p>';
    }
});
</script>
@endsection