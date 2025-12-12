@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Edit Product: {{ $product->name }}</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Price ($)</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $product->price }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-control" id="category" name="category" required>
                        <option value="roses" {{ $product->category == 'roses' ? 'selected' : '' }}>Roses</option>
                        <option value="lilies" {{ $product->category == 'lilies' ? 'selected' : '' }}>Lilies</option>
                        <option value="tulips" {{ $product->category == 'tulips' ? 'selected' : '' }}>Tulips</option>
                        <option value="orchids" {{ $product->category == 'orchids' ? 'selected' : '' }}>Orchids</option>
                        <option value="sunflowers" {{ $product->category == 'sunflowers' ? 'selected' : '' }}>Sunflowers</option>
                        <option value="mixed" {{ $product->category == 'mixed' ? 'selected' : '' }}>Mixed</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="image" class="form-label">Image URL</label>
                    <input type="text" class="form-control" id="image" name="image" value="{{ $product->image }}" required>
                    <small class="text-muted">Current: <a href="{{ $product->image }}" target="_blank">View Image</a></small>
                </div>
                <div class="col-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required>{{ $product->description }}</textarea>
                </div>
                <div class="col-12 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="featured" name="featured" value="1" {{ $product->featured ? 'checked' : '' }}>
                        <label class="form-check-label" for="featured">
                            Mark as Featured Product
                        </label>
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Update Product</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection