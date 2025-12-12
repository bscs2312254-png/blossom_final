@extends('layouts.app')
@section('content')
<h2 class="text-center text-secondary mb-4">All Flowers</h2>
@foreach($products as $p)
<div class="col-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm border-0">
        <img src="{{ $p->image }}" class="card-img-top" alt="{{ $p->name }}">
        <div class="card-body text-center">
            <h6 class="fw-semibold">{{ $p->name }}</h6>
            <p class="text-muted mb-2">${{ number_format($p->price, 2) }}</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <a href="/product/{{ $p->id }}" class="btn btn-outline-primary btn-sm">Details</a>
                <form action="/cart/add" method="POST">@csrf
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
@endforeach
</div>
@endsection
