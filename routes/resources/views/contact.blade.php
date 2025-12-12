@extends('layouts.app')
@section('content')
<div class="text-center mb-5">
    <h1 class="fw-bold text-secondary mb-3">Contact Us</h1>
    <p class="text-muted fs-5 w-100 w-md-75 mx-auto">
        We'd love to hear from you! Reach out for questions, feedback, or special orders.
    </p>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" placeholder="Enter your name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" rows="4" placeholder="Your message..." required></textarea>
                    </div>
                    <button class="btn btn-primary w-100">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="text-center mt-5">
    <h5 class="text-secondary">Visit Our Store</h5>
    <p class="text-muted mb-0">Blossom Haven Florist</p>
    <p class="text-muted mb-0">123 Bloom Street, Garden City</p>
    <p class="text-muted mb-0">Email: hello@blossomhaven.com</p>
    <p class="text-muted">Phone: +1 (555) 234-9876</p>
</div>
@endsection
