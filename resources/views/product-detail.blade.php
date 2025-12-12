@extends('layouts.app')
@section('content')
<div class="row g-4">
<div class="col-md-6">
    @if(file_exists(public_path('uploads/products/' . $product->image)))
                                    <img src="{{ asset('uploads/products/' . $product['image']) }}" 
                                          class="img-fluid rounded shadow-sm w-100" alt="{{ $product['name'] }}">
                                @else
                                    <img src="{{ asset('images/' . $product['image']) }}" 
                                          class="img-fluid rounded shadow-sm w-100" alt="{{ $product['name'] }}">
                                @endif
<img src="/images/{{ $product['image'] }}" class="img-fluid rounded shadow-sm w-100" alt="{{ $product['name'] }}">
</div>
<div class="col-md-6">
<h2>{{ $product['name'] }}</h2>
<p class="text-muted fs-5">${{ $product['price'] }}</p>
<p>{{ $product['desc'] }}</p>
<form action="/cart/add" method="POST" class="mb-3">@csrf
<input type="hidden" name="id" value="{{ $product['id'] }}">
<input type="hidden" name="name" value="{{ $product['name'] }}">
<input type="hidden" name="price" value="{{ $product['price'] }}">
<input type="hidden" name="image" value="{{ $product['image'] }}">
<button class="btn btn-success">Add to Cart</button>
</form>
<hr>
<h5>Customer Reviews</h5>
<div class="border p-2 mb-2 rounded"><strong>Sarah</strong><br>Beautiful bouquet, fresh and fragrant!</div>
<div class="border p-2 rounded"><strong>Ali</strong><br>Perfect for gifting. Loved it!</div>
</div>
</div>
@endsection
