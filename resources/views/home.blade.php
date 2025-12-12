@extends('layouts.app')

@section('content')
<style>
.hero {
    background: linear-gradient(120deg, #ffe6f0, #fff8f9);
    border-radius: 12px;
    padding: 70px 20px;
    text-align: center;
    margin-bottom: 50px;
}
.hero h1 {
    font-weight: 700;
    color: #c2185b;
}
.hero p { color: #555; font-size: 1.1rem; }

.category-card {
    background: #fff;
    border: 1px solid #f3cadb;
    border-radius: 12px;
    text-align: center;
    padding: 25px 10px;
    transition: all 0.3s;
    height: 100%;
}
.category-card:hover {
    background: #ffe6f0;
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
</style>

<div class="container py-4">
    <div class="hero shadow-sm">
        <h1>Welcome to Blossom Haven</h1>
        <p>Fresh blooms, elegant arrangements, and heartfelt gifts for every occasion.</p>
        <a href="{{ route('products') }}" class="btn btn-lg btn-primary mt-3">Shop Now</a>
    </div>

    <h3 class="text-center text-secondary mb-4">Featured Products</h3>
    
    <div class="row">
        @foreach($products as $product)
        <div class="col-6 col-md-4 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm border-0">
            
                                @if(file_exists(public_path('uploads/products/' . $product->image)))
                                    <img src="{{ asset('uploads/products/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         style="width: 50px; height: 50px; object-fit: cover;" 
                                         class="rounded">
                                @else
                                    <img src="{{ asset('images/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         style="width: 50px; height: 50px; object-fit: cover;" 
                                         class="rounded">
                                @endif
            <div class="card-body text-center">
                    <h6 class="fw-semibold">{{ $product->name }}</h6>
                    <p class="text-muted mb-2">${{ number_format($product->price, 2) }}</p>
                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                        <a href="{{ route('product.detail', $product->id) }}" class="btn btn-outline-primary btn-sm">View</a>
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <input type="hidden" name="name" value="{{ $product->name }}">
                            <input type="hidden" name="price" value="{{ $product->price }}">
                            <input type="hidden" name="image" value="{{ asset('images/' . $product->image) }}">
                            <button class="btn btn-success btn-sm">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <h3 class="text-center text-secondary mt-5 mb-4">Shop by Category</h3>
    <div class="row">
        @foreach($categories as $category)
        <div class="col-4 col-md-2 mb-4">
            <a href="{{ route('products') }}?category={{ $category->slug }}" class="category-card text-decoration-none d-block">
                <div class="mb-2">
                    @if($category->image)
                        <img src="{{ asset('images/' . $category->image) }}" 
                             alt="{{ $category->name }}" 
                             class="img-fluid rounded-circle" 
                             style="width: 60px; height: 60px; object-fit: cover;">
                    @else
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px;">
                            <i class="bi bi-flower2 fs-4"></i>
                        </div>
                    @endif
                </div>
                <h6 class="text-dark mb-1">{{ $category->name }}</h6>
                <small class="text-muted">{{ $category->products()->count() }} items</small>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection