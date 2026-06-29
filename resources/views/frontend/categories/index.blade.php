@php
    $categoryImage = function ($category) {
        if ($category->image_url) {
            return $category->image_url;
        }

        $fallbackPath = 'assets/images/cat-' . $category->slug . '.png';

        if (file_exists(public_path($fallbackPath))) {
            return asset($fallbackPath);
        }

        return asset('assets/images/cat-men.png');
    };
@endphp

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="theme-color" content="#fff">

    <title>Categories - Vastra Express</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>

    <div class="site-wrap">

        <header class="wishlist-header">
            <a href="{{ route('frontend.home') }}">
                <i class="bi bi-chevron-left"></i>
            </a>

            <h1>Categories</h1>

            <a href="{{ route('frontend.cart.index') }}">
                <i class="bi bi-bag"></i>
            </a>
        </header>

        <main>

            <section class="section-block shop-category" id="accessories">
                <div class="container-fluid page-container">

                    <div class="section-head">
                        <h2>Shop By Category</h2>

                        <a href="{{ route('frontend.home') }}">
                            Home <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <div class="round-category-grid">

                        @forelse($categories as $category)

                            <a href="{{ $category->audience ? route('frontend.category.products', [$category->audience, $category]) : '#' }}"
                               class="round-category"
                               id="{{ $category->slug }}">

                                <span class="round-image">
                                    <img src="{{ $categoryImage($category) }}" alt="{{ $category->name }}">
                                </span>

                                <strong>{{ $category->name }}</strong>
                            </a>

                        @empty

                            <a href="{{ url('/shop/men') }}" class="round-category" id="men">
                                <span class="round-image">
                                    <img src="{{ asset('assets/images/cat-men.png') }}" alt="Men">
                                </span>
                                <strong>Men</strong>
                            </a>

                            <a href="{{ url('/shop/women') }}" class="round-category" id="women">
                                <span class="round-image">
                                    <img src="{{ asset('assets/images/cat-women.png') }}" alt="Women">
                                </span>
                                <strong>Women</strong>
                            </a>

                            <a href="{{ url('/shop/kids') }}" class="round-category" id="kids">
                                <span class="round-image">
                                    <img src="{{ asset('assets/images/cat-kids.png') }}" alt="Kids">
                                </span>
                                <strong>Kids</strong>
                            </a>

                        @endforelse

                    </div>
                </div>
            </section>

        </main>

        <nav class="bottom-nav" aria-label="Mobile bottom navigation">

            <a class="{{ request()->routeIs('frontend.home') ? 'active' : '' }}"
               href="{{ route('frontend.home') }}">
                <i class="bi bi-house-door-fill"></i>
                <span>Home</span>
            </a>

            <a class="{{ request()->routeIs('frontend.categories.index') ? 'active' : '' }}"
               href="{{ route('frontend.categories.index') }}">
                <i class="bi bi-grid-3x3-gap-fill"></i>
                <span>Categories</span>
            </a>

            <a class="{{ request()->routeIs('frontend.wishlist.index') ? 'active' : '' }}"
               href="{{ route('frontend.wishlist.index') }}">
                <i class="bi bi-heart"></i>
                <span>Wishlist</span>
            </a>

            @auth
                <a href="{{ route('frontend.orders.index') }}">
                    <i class="bi bi-person"></i>
                    <span>Account</span>
                </a>
            @else
                <a href="{{ url('/login') }}">
                    <i class="bi bi-person"></i>
                    <span>Account</span>
                </a>
            @endauth

            <a class="{{ request()->routeIs('frontend.orders.*') ? 'active' : '' }}"
               href="{{ route('frontend.orders.index') }}">
                <i class="bi bi-bag"></i>
                <span>Orders</span>
            </a>

        </nav>

    </div>

</body>

</html>
