@php
    $bodyClass = trim($__env->yieldContent('body_class', 'brands-page'));
    $pageTitle = trim($__env->yieldContent('title', 'Brands - Vastra Express'));
@endphp

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#fff">

    <title>{{ $pageTitle }}</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body class="{{ $bodyClass }}">

    <div class="site-wrap">

        <header class="category-header @yield('header_class')">

            @hasSection('header_left')
                @yield('header_left')
            @else
                <a href="{{ url('/') }}" class="detail-icon" aria-label="Home">
                    <i class="bi bi-list"></i>
                </a>
            @endif

            <a href="{{ url('/') }}" class="brand">
                <span class="brand-mark">
                    <span>V</span>
                </span>
                <span class="brand-name">
                    VASTRA<span>EXPRESS</span>
                </span>
            </a>

            <div class="header-actions">
                <a class="icon-btn" href="{{ route('frontend.search') }}" aria-label="Search">
                    <i class="bi bi-search"></i>
                </a>

                <a class="icon-btn" href="#" aria-label="Account">
                    <i class="bi bi-person"></i>
                </a>

                <a class="icon-btn cart-button" href="{{ url('/cart') }}" aria-label="Cart">
                    <i class="bi bi-bag"></i>
                </a>
            </div>

        </header>

        @yield('content')

        <nav class="bottom-nav">
            <a href="{{ url('/') }}">
                <i class="bi bi-house-door-fill"></i>
                <span>Home</span>
            </a>

            <a href="{{ url('/shop/men') }}">
                <i class="bi bi-grid-3x3-gap-fill"></i>
                <span>Categories</span>
            </a>

            <a href="#">
                <i class="bi bi-heart"></i>
                <span>Wishlist</span>
            </a>

            <a href="#">
                <i class="bi bi-person"></i>
                <span>Account</span>
            </a>

            <a href="{{ url('/cart') }}">
                <i class="bi bi-bag"></i>
                <span>Orders</span>
            </a>
        </nav>

        @yield('filter_sheet')

    </div>

    <div class="toast-container position-fixed end-0 p-3">
        <div id="cartToast" class="toast align-items-center text-bg-dark border-0" role="status" aria-live="polite" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">Product added to cart.</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>
