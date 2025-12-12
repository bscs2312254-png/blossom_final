@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="text-secondary">
                @if(request()->has('search'))
                    Search Results: "{{ request('search') }}"
                @else
                    All Flowers
                @endif
            </h2>
            @if(request()->has('search'))
                <p class="text-muted">{{ $products->total() }} flowers found</p>
            @endif
        </div>
        <div class="col-md-4">
            <div class="d-flex justify-content-end">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Sort by
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'name']) }}">Name A-Z</a></li>
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}">Price: Low to High</a></li>
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}">Price: High to Low</a></li>
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'featured']) }}">Featured First</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('products') }}">Reset Sorting</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    @if($products->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-search display-1 text-muted mb-4"></i>
            <h4>No flowers found</h4>
            <p class="text-muted">Try adjusting your search or browse all flowers</p>
            <a href="{{ route('products') }}" class="btn btn-primary">View All Flowers</a>
        </div>
    @else
        <div class="row">
            @foreach($products as $product)
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    @if($product->image)
                        @if(file_exists(public_path('uploads/products/' . $product->image)))
                            <img src="{{ asset('uploads/products/' . $product->image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $product->name }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/' . $product->image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $product->name }}"
                                 style="height: 200px; object-fit: cover;">
                        @endif
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                             style="height: 200px;">
                            <i class="bi bi-flower2 text-muted" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    <div class="card-body text-center">
                        <h6 class="fw-semibold">{{ $product->name }}</h6>
                        <p class="text-muted mb-2">${{ number_format($product->price, 2) }}</p>
                        <div class="mb-2">
                            <span class="badge bg-info">{{ ucfirst($product->category) }}</span>
                            @if($product->featured)
                                <span class="badge bg-warning">Featured</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <a href="{{ route('product.detail', $product->id) }}" class="btn btn-outline-primary btn-sm">Details</a>
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <input type="hidden" name="name" value="{{ $product->name }}">
                                <input type="hidden" name="price" value="{{ $product->price }}">
                                @if($product->image && file_exists(public_path('uploads/products/' . $product->image)))
                                    <input type="hidden" name="image" value="{{ asset('uploads/products/' . $product->image) }}">
                                @else
                                    <input type="hidden" name="image" value="{{ asset('images/' . $product->image) }}">
                                @endif
                                <button class="btn btn-success btn-sm">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        @if($products->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $products->withQueryString()->links() }}
            </div>
        @endif
    @endif
    
    @if(request()->has('search') && !request()->has('page'))
        <div class="text-center mt-5">
            <h5>Not finding what you're looking for?</h5>
            <div class="mt-3">
                <a href="{{ route('products') }}" class="btn btn-outline-secondary me-2">View All Flowers</a>
                <a href="/contact" class="btn btn-outline-primary">Contact Us</a>
            </div>
        </div>
    @endif
</div>
@endsection