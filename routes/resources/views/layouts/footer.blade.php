<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>Blossom Haven</h5>
                <p>Your trusted source for beautiful flowers for all occasions. We deliver happiness in every bouquet.</p>
            </div>
            <div class="col-md-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home') }}" class="text-white">Home</a></li>
                    <li><a href="{{ route('products') }}" class="text-white">Products</a></li>
                    <li><a href="{{ route('contact') }}" class="text-white">Contact Us</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Contact Info</h5>
                <ul class="list-unstyled">
                    <li><i class="fas fa-map-marker-alt me-2"></i> 123 Flower Street, City</li>
                    <li><i class="fas fa-phone me-2"></i> (555) 123-4567</li>
                    <li><i class="fas fa-envelope me-2"></i> info@blossomhaven.com</li>
                </ul>
            </div>
        </div>
        <hr class="bg-white">
        <div class="text-center">
            <p>&copy; {{ date('Y') }} Blossom Haven. All rights reserved.</p>
        </div>
    </div>
</footer>