@extends('layouts.app')
@section('content')
<h2 class="text-center text-secondary mb-4">Checkout</h2>

<form action="/checkout" method="POST" class="mx-auto shadow-sm p-4 bg-white rounded" style="max-width:600px;">
    @csrf
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" placeholder="Enter your name" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" class="form-control" placeholder="Enter your phone number" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Address</label>
            <input type="text" class="form-control" placeholder="Enter your address" required>
        </div>

        <div class="col-12 mt-3">
            <label class="form-label fw-bold">Payment Method</label>
            <div class="d-flex flex-column flex-md-row gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" id="cash" checked>
                    <label class="form-check-label" for="cash">Cash on Delivery</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" id="card">
                    <label class="form-check-label" for="card">Card Payment</label>
                </div>
            </div>
        </div>

        <div id="card-fields" class="col-12 mt-3" style="display:none;">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Card Number</label>
                    <input type="text" class="form-control" placeholder="1234 5678 9012 3456">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Expiry</label>
                    <input type="text" class="form-control" placeholder="MM/YY">
                </div> 
                <div class="col-md-3">
                    <label class="form-label">CVV</label>
                    <input type="text" class="form-control" placeholder="123">
                </div>
            </div>
        </div>
    </div>

    <button class="btn btn-success w-100 mt-4">Place Order</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cash = document.getElementById('cash');
    const card = document.getElementById('card');
    const cardFields = document.getElementById('card-fields');
    cash.addEventListener('change', () => cardFields.style.display = 'none');
    card.addEventListener('change', () => cardFields.style.display = 'block');
});
</script>
@endsection