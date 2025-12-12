@extends('layouts.app')
@section('content')
<div class="text-center mb-5">
    <h1 class="fw-bold text-secondary mb-3">About Blossom Haven</h1>
    <p class="text-muted fs-5 w-100 w-md-75 mx-auto">
        Welcome to <strong>Blossom Haven</strong>, your one-stop destination for
        beautiful, fresh flowers delivered straight to your heart.
    </p>
</div>
<div class="row g-4 align-items-center">
    <div class="col-md-6">
        <img src="/images/rose.jpg" class="img-fluid rounded shadow-sm" alt="Flowers">
    </div>
    <div class="col-md-6">
        <h3 class="text-secondary mb-3">Our Story</h3>
        <p>
            Founded in 2020, Blossom Haven began as a small local shop celebrating life’s
            moments with nature’s most elegant gift — flowers.
        </p>
        <h4 class="text-secondary mt-4 mb-2">What We Offer</h4>
        <ul>
            <li>Fresh seasonal blooms</li>
            <li>Custom floral arrangements</li>
            <li>Gift sets for every occasion</li>
            <li>Same-day delivery in select areas</li>
        </ul>
    </div>
</div>
@endsection
