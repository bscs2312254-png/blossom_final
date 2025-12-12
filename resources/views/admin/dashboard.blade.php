@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Admin Dashboard</h2>
</div>

<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5>Total Products</h5>
                <h3>{{ App\Models\Product::count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5>Featured Products</h5>
                <h3>{{ App\Models\Product::where('featured', true)->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5>Categories</h5>
                <h3>{{ App\Models\Category::count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5>Active Categories</h5>
                <h3>{{ App\Models\Category::where('is_active', true)->count() }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <h4>Quick Actions</h4>
        <div class="d-flex gap-3 mt-3 flex-wrap">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Product
            </a>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-info text-white">
                <i class="bi bi-plus-circle"></i> Add New Category
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="bi bi-list"></i> View All Products
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="bi bi-tags"></i> View All Categories
            </a>
        </div>
    </div>
</div>
@endsection