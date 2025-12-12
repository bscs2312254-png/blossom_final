<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blossom Haven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #fffafc;
        }
        main {
            flex: 1;
        }
        .navbar-brand { color: #c2185b !important; }
        footer { background-color: #c2185b; }
        footer p { margin: 0; color: #fff; }
        .card img {
            object-fit: cover;
            height: 200px;
        }
        @media (max-width: 768px) {
            .card img { height: 160px; }
            .hero { padding: 40px 15px !important; }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-light shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/">🌸 Blossom Haven</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto text-center">
        <li class="nav-item"><a href="/" class="nav-link">Home</a></li>
        <li class="nav-item"><a href="/products" class="nav-link">Products</a></li>
        <li class="nav-item"><a href="/about" class="nav-link">About</a></li>
        <li class="nav-item"><a href="/contact" class="nav-link">Contact</a></li>
        <li class="nav-item"><a href="/cart" class="nav-link">Cart</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container my-5">
  @yield('content')
</main>

<footer class="py-3 mt-auto text-center">
  <p>© {{ date('Y') }} Blossom Haven – All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
