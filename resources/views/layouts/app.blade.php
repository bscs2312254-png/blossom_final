<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blossom Haven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        html, body { height: 100%; }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #fffafc;
        }
        main { flex: 1; }
        .navbar-brand { color: #c2185b !important; }
        footer { background-color: #c2185b; }
        footer p { margin: 0; color: #fff; }
        .card img { object-fit: cover; height: 200px; }

        /* Search Bar CSS */
        .search-container { position: relative; margin: 0 15px; }
        .search-results-dropdown {
            position: absolute;
            top: 100%;
            left: 0; right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 0 0 5px 5px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-height: 400px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }
        .search-result-item {
            padding: 10px 15px;
            border-bottom: 1px solid #f0f0f0;
            display: block;
            color: #333;
            text-decoration: none;
        }
        .search-result-item:hover {
            background-color: #f8f9fa;
            text-decoration: none;
        }
        .search-result-item:last-child { border-bottom: none; }
        .search-result-image {
            width: 40px; height: 40px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 10px;
        }
        .search-result-info { flex: 1; }
        .search-result-name { font-weight: 500; margin-bottom: 2px; }
        .search-result-category {
            font-size: 12px;
            color: #6c757d;
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 3px;
        }
        .search-result-price { font-weight: 600; color: #c2185b; }
        .no-results {
            padding: 15px;
            text-align: center;
            color: #6c757d;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card img { height: 160px; }
            .hero { padding: 40px 15px !important; }
            .search-container { margin: 10px 0; order: 3; width: 100%; }
            .navbar-collapse { order: 2; }
        }
        @media (max-width: 992px) {
            .search-container { margin: 10px 0; width: 100%; }
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg bg-light shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/">🌸 Blossom Haven</a>

    <!-- Search Bar Desktop -->
    <div class="search-container position-relative d-none d-lg-flex flex-grow-1 mx-lg-3">
        <form action="{{ route('products') }}" method="GET" id="searchForm">
            <div class="input-group">
                <input type="text" class="form-control" id="searchInput" name="search" placeholder="Search flowers..." autocomplete="off">
                <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
            </div>
        </form>
        <div id="searchResults" class="search-results-dropdown"></div>
    </div>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="nav">
        <!-- Search Bar Mobile -->
        <div class="search-container position-relative d-block d-lg-none mb-3">
            <form action="{{ route('products') }}" method="GET" id="searchFormMobile">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchInputMobile" name="search" placeholder="Search flowers..." autocomplete="off">
                    <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
                </div>
            </form>
            <div id="searchResultsMobile" class="search-results-dropdown"></div>
        </div>

        <ul class="navbar-nav ms-auto text-center">
            <li class="nav-item"><a href="/" class="nav-link">Home</a></li>
            <li class="nav-item"><a href="/products" class="nav-link">Products</a></li>
            <li class="nav-item"><a href="/about" class="nav-link">About</a></li>
            <li class="nav-item"><a href="/contact" class="nav-link">Contact</a></li>
            <li class="nav-item"><a href="/cart" class="nav-link">Cart</a></li>

            @auth
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger nav-link" style="border:none; background:none;">
                    Logout
                </button>
            </form>
        </li>
        @endauth
    </ul>

    </div>

    <div class="d-flex align-items-center">
        <a href="/admin/dashboard" class="btn btn-primary">
            <i class="bi bi-person"></i> <span class="d-none d-md-inline">Admin</span>
        </a>
    </div>
  </div>
</nav>

<main class="container my-5">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @yield('content')
</main>

<footer class="py-3 mt-auto text-center">
  <p>© {{ date('Y') }} Blossom Haven – All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Your inline search script (unchanged)
document.addEventListener('DOMContentLoaded', function() {
    setupSearch('searchInput', 'searchResults');
    setupSearch('searchInputMobile', 'searchResultsMobile');

    function setupSearch(inputId, resultsId) {
        const searchInput = document.getElementById(inputId);
        const searchResults = document.getElementById(resultsId);
        if (!searchInput || !searchResults) return;

        let delay;
        searchInput.addEventListener('input', function () {
            clearTimeout(delay);
            const query = this.value.trim();
            if (query.length < 2) {
                searchResults.style.display = 'none';
                searchResults.innerHTML = '';
                return;
            }
            delay = setTimeout(() => performSearch(query, searchResults), 300);
        });

        searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                window.location.href = `/products?search=${encodeURIComponent(this.value.trim())}`;
            }
        });
    }

    function performSearch(query, container) {
        fetch(`/search/products?q=${encodeURIComponent(query)}`)
        .then(res => res.json())
        .then(data => showResults(data, query, container));
    }

    function showResults(results, query, container) {
        if (!results.length) {
            container.innerHTML = `<div class="no-results">No flowers found for "${query}"</div>`;
            container.style.display = 'block';
            return;
        }

        let html = "";
        results.forEach(p => {
            html += `
                <a href="${p.url}" class="search-result-item d-flex align-items-center">
                    <img src="${p.image}" class="search-result-image">
                    <div class="search-result-info">
                        <div class="search-result-name">${p.name}</div>
                        <div class="d-flex justify-content-between">
                            <span class="search-result-category">${p.category}</span>
                            <span class="search-result-price">$${p.price}</span>
                        </div>
                    </div>
                </a>`;
        });

        html += `
            <a href="/products?search=${encodeURIComponent(query)}" class="search-result-item text-center text-primary">
                View all results for "${query}"
            </a>`;

        container.innerHTML = html;
        container.style.display = 'block';
    }
});
</script>

<!-- ✅ Step 5 Added -->
<script src="{{ asset('js/search.js') }}"></script>

</body>
</html>