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
.hero h1 { font-weight: 700; color: #c2185b; }
.hero p { color: #555; font-size: 1.1rem; }

.category-card {
    background: #fff;
    border: 1px solid #f3cadb;
    border-radius: 12px;
    text-align: center;
    padding: 25px 10px;
    transition: all 0.3s;
}
.category-card:hover {
    background: #ffe6f0;
    transform: translateY(-5px);
}
.category-icon { font-size: 2rem; color: #c2185b; }
</style>

<div class="hero shadow-sm">
    <h1>Welcome to Blossom Haven</h1>
    <p>Fresh blooms, elegant arrangements, and heartfelt gifts for every occasion.</p>
    <a href="/products" class="btn btn-lg btn-primary mt-3">Shop Now</a>
</div>

<h3 class="text-center text-secondary mb-4">Explore Our Categories</h3>

<div class="row justify-content-center mb-5">
@foreach($products as $p)
<div class="col-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm border-0">
        <img src="{{ $p->image }}" class="card-img-top" alt="{{ $p->name }}">

        <div class="card-body text-center">
            <h6 class="fw-semibold">{{ $p->name }}</h6>
            <p class="text-muted mb-2">${{ number_format($p->price, 2) }}</p>

            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <a href="/product/{{ $p->id }}" class="btn btn-outline-primary btn-sm">View</a>

                <form action="/cart/add" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $p->id }}">
                    <input type="hidden" name="name" value="{{ $p->name }}">
                    <input type="hidden" name="price" value="{{ $p->price }}">
                    <input type="hidden" name="image" value="{{ $p->image }}">
                    <button class="btn btn-success btn-sm">Add</button>
                </form>
            </div>
        </div>

    </div>
</div>
@endforeach
</div>

@endsection
