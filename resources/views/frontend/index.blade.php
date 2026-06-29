<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="theme-color" content="#ffffff">

    <title>Vastra Express — Fashion Store</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>

    <div class="site-wrap">

        <!-- ================= HEADER ================= -->
        <header class="site-header sticky-top">
            <div class="container-fluid page-container">

                <div class="header-row">

                    <button class="icon-btn menu-btn"
                            type="button"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#mobileMenu"
                            aria-label="Open menu">
                        <i class="bi bi-list"></i>
                    </button>

                    <a href="{{ route('frontend.home') }}" class="brand" aria-label="Vastra Express home">
                        <span class="brand-mark">
                            <span>V</span>
                        </span>

                        <span class="brand-name">
                            VASTRA<span>EXPRESS</span>
                        </span>
                    </a>

                    <div class="header-actions">

                        <button class="icon-btn"
                                type="button"
                                data-bs-toggle="modal"
                                data-bs-target="#searchModal"
                                aria-label="Search">
                            <i class="bi bi-search"></i>
                        </button>

                        @auth
                            <a class="icon-btn" href="{{ route('frontend.orders.index') }}" aria-label="Account">
                                <i class="bi bi-person"></i>
                            </a>
                        @else
                            <a class="icon-btn" href="{{ url('/login') }}" aria-label="Account">
                                <i class="bi bi-person"></i>
                            </a>
                        @endauth

                        <a class="icon-btn cart-button" href="{{ route('frontend.cart.index') }}" aria-label="Cart">
                            <i class="bi bi-bag"></i>

                            @php
                                $cartCount = collect(session('cart', []))->sum('qty');
                            @endphp

                            <span class="cart-count {{ $cartCount > 0 ? 'show' : '' }}">
                                {{ $cartCount }}
                            </span>
                        </a>

                    </div>
                </div>

                <nav class="category-nav" aria-label="Primary category navigation">

                    <a class="{{ request()->routeIs('frontend.home') ? 'active' : '' }}"
                       href="{{ route('frontend.home') }}">
                        Home
                    </a>

                    @foreach($audiences ?? [] as $audience)
                        <a class="{{ request()->is('shop/' . $audience->slug . '*') ? 'active' : '' }}"
                           href="{{ route('frontend.audience.show', $audience) }}">
                            {{ $audience->name }}
                        </a>
                    @endforeach

                </nav>

            </div>
        </header>
    <main id="home">

      <!-- ================= HERO ================= -->
      <section class="hero-section">
        <div class="container-fluid page-container">
          <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
              <div class="carousel-item active">
                <div class="hero-card">
                  <div class="hero-copy">
                    <span class="hero-kicker">Summer Collection 2025</span>
                    <h1>Stay cool.<br>Look <span>hot.</span></h1>
                    <p>Light. Breezy. Effortless.<br>Summer fashion for you.</p>
                    <a href="#trending" class="hero-cta">Shop summer collection <i class="bi bi-arrow-right"></i></a>
                  </div>
                  <img src="assets/images/hero-models.png" alt="Summer fashion models" class="hero-models">
                </div>
              </div>
              
            </div>
          </div>
        </div>
      </section>

      <!-- ================= QUICK CATEGORY CARDS ================= -->
    @php
    $audienceImage = function ($audience) {
        if ($audience->image_url) {
            return $audience->image_url;
        }

        $path = 'assets/images/category-' . $audience->slug . '.png';

        if (file_exists(public_path($path))) {
            return asset($path);
        }

        return asset('assets/images/category-men.png');
    };
@endphp

<section class="quick-categories">
    <div class="container-fluid page-container">
        <div class="quick-category-grid" id="quickCategorySlider">

            @forelse($audiences as $audience)

                <a href="{{ route('frontend.audience.show', $audience) }}" class="quick-category-card">
                    <img src="{{ $audienceImage($audience) }}" alt="{{ $audience->name }} collection">

                    <span>
                        <strong>{{ $audience->name }}</strong>

                        <small>
                            Explore
                            <i class="bi bi-arrow-right"></i>
                        </small>
                    </span>
                </a>

            @empty

                <a href="{{ url('/shop/men') }}" class="quick-category-card">
                    <img src="{{ asset('assets/images/category-men.png') }}" alt="Men collection">
                    <span><strong>Men</strong><small>Explore <i class="bi bi-arrow-right"></i></small></span>
                </a>

                <a href="{{ url('/shop/women') }}" class="quick-category-card">
                    <img src="{{ asset('assets/images/category-women.png') }}" alt="Women collection">
                    <span><strong>Women</strong><small>Explore <i class="bi bi-arrow-right"></i></small></span>
                </a>

                <a href="{{ url('/shop/kids') }}" class="quick-category-card">
                    <img src="{{ asset('assets/images/category-kids.png') }}" alt="Kids collection">
                    <span><strong>Kids</strong><small>Explore <i class="bi bi-arrow-right"></i></small></span>
                </a>

            @endforelse

        </div>
    </div>
</section>

<style>
  .quick-category-grid {
    display: flex;
    gap: 16px;
    overflow-x: auto;
    overflow-y: hidden;
    scroll-snap-type: x mandatory;
    scroll-behavior: smooth;
    padding-bottom: 12px;
    scrollbar-width: none;
}

.quick-category-grid::-webkit-scrollbar {
    display: none;
}

.quick-category-grid .quick-category-card {
    flex: 0 0 auto;
    scroll-snap-align: start;
}

@media (min-width: 992px) {
    .quick-category-grid .quick-category-card {
        width: calc((100% - 32px) / 3);
    }
}

@media (max-width: 991px) {
    .quick-category-grid .quick-category-card {
        width: 280px;
    }
}

@media (max-width: 575px) {
    .quick-category-grid {
        gap: 12px;
    }

    .quick-category-grid .quick-category-card {
        width: 235px;
    }
}
</style>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const slider = document.getElementById("quickCategorySlider");
    const prevBtn = document.getElementById("quickCatPrev");
    const nextBtn = document.getElementById("quickCatNext");

    if (!slider) return;

    const slideAmount = 300;

    if (nextBtn) {
        nextBtn.addEventListener("click", function () {
            slider.scrollBy({
                left: slideAmount,
                behavior: "smooth"
            });
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener("click", function () {
            slider.scrollBy({
                left: -slideAmount,
                behavior: "smooth"
            });
        });
    }
});
</script>
      <!-- ================= TRUST STRIP ================= -->
      <section class="trust-strip-section">
        <div class="container-fluid page-container">
          <div class="trust-strip">
            <div class="trust-item">
              <i class="bi bi-truck"></i>
              <span><strong>60 min</strong><small>Delivery</small></span>
            </div>
            <div class="trust-item">
              <i class="bi bi-arrow-counterclockwise"></i>
              <span><strong>30 min</strong><small>Returns Policy</small></span>
            </div>
            <div class="trust-item">
              <i class="bi bi-house-heart"></i>
              <span><strong>Home Trial</strong><small>Try before you buy</small></span>
            </div>
            <div class="trust-item">
              <i class="bi bi-shield-check"></i>
              <span><strong>Secure</strong><small>Payment</small></span>
            </div>
          </div>
        </div>
      </section>

      <!-- ================= TRENDING ================= -->
   @php
    $productImage = function ($product) {
        return $product->image_url ?: asset('assets/images/product-man.png');
    };

    $discountPercent = function ($product) {
        if (! $product->compare_at_price || $product->compare_at_price <= $product->price) {
            return null;
        }

        return round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100);
    };
@endphp

<section class="section-block" id="trending">
    <div class="container-fluid page-container">
        <div class="section-head">
            <h2>Trending Now</h2>
            <a href="#top-picks">View All <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="product-grid" id="trendingProducts" data-server-products="1">

            @forelse($trendingProducts as $product)

                @php
                    $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);

                    $sizes = is_array($product->available_sizes) ? $product->available_sizes : [];
                    $defaultSize = $sizes[0] ?? null;

                    $discount = $discountPercent($product);
                @endphp

                <article class="product-card" data-product-card>
                    <div class="product-media">
                        <a href="{{ route('frontend.products.show', $product) }}">
                            <img src="{{ $productImage($product) }}"
                                 alt="{{ $product->name }}"
                                 loading="lazy">
                        </a>

                        <form action="{{ route('frontend.wishlist.toggle', $product) }}"
                              method="POST"
                              data-wishlist-product="{{ $product->id }}"
                              data-server-wishlist
                              data-wishlist-toggle>
                            @csrf

                            <button type="submit"
                                    class="wishlist-btn {{ $isWishlisted ? 'active' : '' }}"
                                    aria-label="Add {{ $product->name }} to wishlist">
                                <i class="bi {{ $isWishlisted ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                            </button>
                        </form>
                    </div>

                    <div class="product-body">
                        <a href="{{ route('frontend.products.show', $product) }}"
                           style="color: inherit; text-decoration: none;">
                            <h3 class="product-title">{{ $product->name }}</h3>
                        </a>

                        <span class="product-brand">
                            {{ optional($product->brand)->name ?? 'VASTRA' }}
                        </span>

                        <div class="product-meta">
                            <span class="rating-badge">
                                <i class="bi bi-star-fill"></i>4.3
                            </span>

                            <span class="rating-count">
                                120 Rating
                            </span>
                        </div>

                        <div class="price-row">
                            <span class="current-price">
                                ₹{{ number_format($product->price, 0) }}
                            </span>

                            @if($product->compare_at_price && $product->compare_at_price > $product->price)
                                <span class="old-price">
                                    ₹{{ number_format($product->compare_at_price, 0) }}
                                </span>
                            @endif

                            @if($discount)
                                <span class="discount">
                                    {{ $discount }}% OFF
                                </span>
                            @endif
                        </div>

                        <div class="swatches" aria-label="Available colors">
                            <span class="swatch pink"></span>
                            <span class="swatch black"></span>
                            <span class="swatch white"></span>
                        </div>

                        <form action="{{ route('frontend.cart.add', $product) }}"
                              method="POST"
                              data-server-cart>
                            @csrf

                            <input type="hidden" name="qty" value="1">

                            @if($defaultSize)
                                <input type="hidden" name="size" value="{{ $defaultSize }}">
                            @endif

                            @if($product->colour)
                                <input type="hidden" name="colour" value="{{ $product->colour }}">
                            @endif

                            <button type="submit" class="add-cart-btn">
                                <i class="bi bi-cart3"></i>
                                Add to cart
                            </button>
                        </form>
                    </div>
                </article>

            @empty

                <p>No trending products found.</p>

            @endforelse

        </div>
    </div>
</section>

      <!-- ================= SMALL PROMOS ================= -->
      <section class="small-promo-section">
        <div class="container-fluid page-container">
          <div class="small-promo-grid">
            <a href="men.html"><img src="assets/images/promo-small-men.png" alt="Men summer sale"></a>
            <a href="women.html"><img src="assets/images/promo-small-women.png" alt="Women summer sale"></a>
          </div>
        </div>
      </section>

      <!-- ================= SHOP BY CATEGORY ================= -->
    @php

    $categoryImage = function ($category) {
        if ($category->image_url) {
            return $category->image_url;
        }

        $path = 'assets/images/cat-' . $category->slug . '.png';

        if (file_exists(public_path($path))) {
            return asset($path);
        }

        return asset('assets/images/cat-men.png');
    };
@endphp

<section class="section-block shop-category" id="accessories">
    <div class="container-fluid page-container">
        <div class="section-head">
            <h2>Shop By Category</h2>

            <a href="{{ route('frontend.categories.index') }}">
    View All <i class="bi bi-arrow-right"></i>
</a>

           
        </div>

        <div class="round-category-grid">

            @forelse($shopCategories as $category)

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

                <a href="#" class="round-category">
                    <span class="round-image">
                        <img src="{{ asset('assets/images/cat-watch.png') }}" alt="Watch">
                    </span>
                    <strong>Watch</strong>
                </a>

                <a href="#" class="round-category">
                    <span class="round-image">
                        <img src="{{ asset('assets/images/cat-shoe.png') }}" alt="Shoe">
                    </span>
                    <strong>Shoe</strong>
                </a>

                <a href="#" class="round-category">
                    <span class="round-image">
                        <img src="{{ asset('assets/images/cat-bag.png') }}" alt="Bag">
                    </span>
                    <strong>Bag</strong>
                </a>

            @endforelse

        </div>
    </div>
</section>

      <div class="container-fluid page-container"><hr class="section-divider"></div>

      <!-- ================= TOP PICKS ================= -->
    @php

    $productImage = function ($product) {
        return $product->image_url ?: asset('assets/images/product-man.png');
    };

    $discountPercent = function ($product) {
        if (! $product->compare_at_price || $product->compare_at_price <= $product->price) {
            return null;
        }

        return round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100);
    };
@endphp

<section class="section-block" id="top-picks">
    <div class="container-fluid page-container">

        <div class="section-head">
            <h2>Top Picks For You</h2>

            <a href="{{ url('/shop/men') }}">
                View All
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="product-grid top-pick-grid" id="topPickProducts" data-server-products="1">

            @forelse($topPickProducts as $product)

                @php
                    $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);

                    $sizes = is_array($product->available_sizes) ? $product->available_sizes : [];
                    $defaultSize = $sizes[0] ?? null;

                    $discount = $discountPercent($product);
                @endphp

                <article class="product-card" data-product-card>

                    <div class="product-media">

                        <a href="{{ route('frontend.products.show', $product) }}">
                            <img src="{{ $productImage($product) }}"
                                 alt="{{ $product->name }}"
                                 loading="lazy">
                        </a>

                        <form action="{{ route('frontend.wishlist.toggle', $product) }}"
                              method="POST"
                              data-wishlist-product="{{ $product->id }}"
                              data-server-wishlist
                              data-wishlist-toggle>
                            @csrf

                            <button type="submit"
                                    class="wishlist-btn {{ $isWishlisted ? 'active' : '' }}"
                                    aria-label="Add {{ $product->name }} to wishlist">
                                <i class="bi {{ $isWishlisted ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                            </button>
                        </form>

                    </div>

                    <div class="product-body">

                        <a href="{{ route('frontend.products.show', $product) }}"
                           style="color:inherit;text-decoration:none;">
                            <h3 class="product-title">{{ $product->name }}</h3>
                        </a>

                        <span class="product-brand">
                            {{ optional($product->brand)->name ?? 'VASTRA' }}
                        </span>

                        <div class="product-meta">
                            <span class="rating-badge">
                                <i class="bi bi-star-fill"></i>4.3
                            </span>

                            <span class="rating-count">
                                120 Rating
                            </span>
                        </div>

                        <div class="price-row">
                            <span class="current-price">
                                ₹{{ number_format($product->price, 0) }}
                            </span>

                            @if($product->compare_at_price && $product->compare_at_price > $product->price)
                                <span class="old-price">
                                    ₹{{ number_format($product->compare_at_price, 0) }}
                                </span>
                            @endif

                            @if($discount)
                                <span class="discount">
                                    {{ $discount }}% OFF
                                </span>
                            @endif
                        </div>

                        <div class="swatches" aria-label="Available colors">
                            <span class="swatch pink"></span>
                            <span class="swatch black"></span>
                            <span class="swatch white"></span>
                        </div>

                        <form action="{{ route('frontend.cart.add', $product) }}"
                              method="POST"
                              data-server-cart>
                            @csrf

                            <input type="hidden" name="qty" value="1">

                            @if($defaultSize)
                                <input type="hidden" name="size" value="{{ $defaultSize }}">
                            @endif

                            @if($product->colour)
                                <input type="hidden" name="colour" value="{{ $product->colour }}">
                            @endif

                            <button type="submit" class="add-cart-btn">
                                <i class="bi bi-cart3"></i>
                                Add to cart
                            </button>
                        </form>

                    </div>

                </article>

            @empty

                <p>No top pick products found.</p>

            @endforelse

        </div>

    </div>
</section>

      <!-- ================= LARGE PROMOS ================= -->
      <section class="large-promo-section">
        <div class="container-fluid page-container">
          <div class="large-promo-grid">
            <a href="men.html" class="large-promo-card">
              <img src="assets/images/promo-large-men.png" alt="Men summer sale">
            </a>
            <a href="women.html" class="large-promo-card">
              <img src="assets/images/promo-large-women.png" alt="Women summer sale">
            </a>
          </div>
        </div>
      </section>

      <!-- ================= TRUST STRIP ================= -->
      <section class="trust-strip-section trust-middle">
        <div class="container-fluid page-container">
          <div class="trust-strip">
            <div class="trust-item">
              <i class="bi bi-truck"></i>
              <span><strong>60 min</strong><small>Delivery</small></span>
            </div>
            <div class="trust-item">
              <i class="bi bi-arrow-counterclockwise"></i>
              <span><strong>30 min</strong><small>Returns Policy</small></span>
            </div>
            <div class="trust-item">
              <i class="bi bi-house-heart"></i>
              <span><strong>Home Trial</strong><small>Try before you buy</small></span>
            </div>
            <div class="trust-item">
              <i class="bi bi-shield-check"></i>
              <span><strong>Secure</strong><small>Payment</small></span>
            </div>
          </div>
        </div>
      </section>

      <!-- ================= BEST BRANDS ================= -->
   <section class="section-block brands-section">
    <div class="container-fluid page-container">
        <div class="section-head">
            <h2>Best Of Brands</h2>
            <a href="{{ route('frontend.brands.index') }}">View All <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="brand-grid">

            @forelse($brands as $brand)

                @php
                    $brandClass = strtolower($brand->slug ?? $brand->name);
                    $brandClass = preg_replace('/[^a-z0-9]/', '', $brandClass);
                @endphp

                <a href="{{ route('frontend.brands.show', $brand) }}" class="brand-chip {{ $brandClass }}">
                    {{ $brand->name }}
                </a>

            @empty

                <a href="#" class="brand-chip hm">H&amp;M</a>
                <a href="#" class="brand-chip zara">ZARA</a>
                <a href="#" class="brand-chip puma">PUMA</a>
                <a href="#" class="brand-chip hm">H&amp;M</a>
                <a href="#" class="brand-chip hm">H&amp;M</a>
                <a href="#" class="brand-chip zara">ZARA</a>
                <a href="#" class="brand-chip puma">PUMA</a>
                <a href="#" class="brand-chip hm">H&amp;M</a>
                <a href="#" class="brand-chip hm">H&amp;M</a>
                <a href="#" class="brand-chip zara">ZARA</a>
                <a href="#" class="brand-chip puma">PUMA</a>
                <a href="#" class="brand-chip hm">H&amp;M</a>

            @endforelse

        </div>
    </div>
</section>

      <!-- ================= TRUST STRIP ================= -->
      <section class="trust-strip-section final-trust">
        <div class="container-fluid page-container">
          <div class="trust-strip">
            <div class="trust-item">
              <i class="bi bi-truck"></i>
              <span><strong>60 min</strong><small>Delivery</small></span>
            </div>
            <div class="trust-item">
              <i class="bi bi-arrow-counterclockwise"></i>
              <span><strong>30 min</strong><small>Returns Policy</small></span>
            </div>
            <div class="trust-item">
              <i class="bi bi-house-heart"></i>
              <span><strong>Home Trial</strong><small>Try before you buy</small></span>
            </div>
            <div class="trust-item">
              <i class="bi bi-shield-check"></i>
              <span><strong>Secure</strong><small>Payment</small></span>
            </div>
          </div>
        </div>
      </section>

    </main>

    <!-- ================= MOBILE BOTTOM NAV ================= -->
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
        <a class="{{ request()->routeIs('frontend.orders.index') || request()->routeIs('frontend.trials.index') ? 'active' : '' }}"
           href="{{ route('frontend.orders.index') }}">
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

  <!-- ================= OFFCANVAS MENU ================= -->
  <div class="offcanvas offcanvas-start app-menu" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
    <div class="app-menu-top">
      <button type="button" class="app-menu-close" data-bs-dismiss="offcanvas" aria-label="Close menu"><i class="bi bi-x-lg"></i></button>
      <a href="{{ route('frontend.home') }}" class="app-menu-brand" data-bs-dismiss="offcanvas"><span>VE</span><b>VASTRA<span>EXPRESS</span></b></a>
    </div>
    <div class="offcanvas-body app-menu-body">
      <a class="app-profile" href="{{ auth()->check() ? route('frontend.address.index') : url('/login') }}"><span><i class="bi bi-person"></i></span><div><strong>Hello, {{ auth()->user()->name ?? 'Guest' }} 👋</strong><small>View Profile <i class="bi bi-chevron-right"></i></small></div></a>
      <nav class="app-menu-links" aria-label="Account navigation">
        <a href="{{ route('frontend.orders.index') }}"><i class="bi bi-box-seam"></i><span>My Orders</span><i class="bi bi-chevron-right"></i></a>
        <a href="{{ route('frontend.wishlist.index') }}"><i class="bi bi-heart"></i><span>My Wishlist</span><i class="bi bi-chevron-right"></i></a>
        <a href="{{ route('frontend.trials.index') }}"><i class="bi bi-box2-heart"></i><span>My Trials <b>NEW</b></span><i class="bi bi-chevron-right"></i></a>
        <a href="{{ route('frontend.address.index') }}"><i class="bi bi-geo-alt"></i><span>My Addresses</span><i class="bi bi-chevron-right"></i></a>
        <a href="{{ route('frontend.checkout.payment') }}"><i class="bi bi-credit-card"></i><span>Payment Methods</span><i class="bi bi-chevron-right"></i></a>
        <a href="{{ route('frontend.checkout.payment') }}"><i class="bi bi-wallet2"></i><span>VE Wallet <b>₹250</b></span><i class="bi bi-chevron-right"></i></a>
        <a href="{{ route('frontend.home') }}#top-picks"><i class="bi bi-people"></i><span>Refer &amp; Earn</span><i class="bi bi-chevron-right"></i></a>
        <a href="{{ route('frontend.size-guide') }}"><i class="bi bi-rulers"></i><span>Size Guide</span><i class="bi bi-chevron-right"></i></a>
        <a href="{{ route('frontend.support') }}"><i class="bi bi-headset"></i><span>Customer Support</span><i class="bi bi-chevron-right"></i></a>
        <a href="{{ route('frontend.home') }}#home"><i class="bi bi-info-circle"></i><span>About Us</span><i class="bi bi-chevron-right"></i></a>
        <a href="{{ route('frontend.support') }}"><i class="bi bi-file-earmark-text"></i><span>Terms &amp; Conditions</span><i class="bi bi-chevron-right"></i></a>
        <a href="{{ route('frontend.support') }}"><i class="bi bi-shield-check"></i><span>Privacy Policy</span><i class="bi bi-chevron-right"></i></a>
      </nav>
      <div class="app-menu-social"><strong>Follow Us</strong><div><a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a><a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a><a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a><a href="#" aria-label="Pinterest"><i class="bi bi-pinterest"></i></a></div></div>
      <a class="app-menu-logout" href="{{ auth()->check() ? route('logout') : url('/login') }}" @auth onclick="event.preventDefault(); document.getElementById('app-menu-logout-form').submit();" @endauth><i class="bi bi-box-arrow-right"></i>Logout</a>
      @auth
        <form id="app-menu-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
      @endauth
      <small class="app-menu-version">Version 2.3.0</small>
    </div>
  </div>

  <!-- ================= SEARCH MODAL ================= -->
  <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content search-modal">
        <div class="modal-header border-0">
          <h2 class="modal-title fs-5" id="searchModalLabel">Search products</h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body pt-0">
          <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
            <input type="search" class="form-control" id="productSearch" data-search-url="{{ route('frontend.search') }}" value="{{ request('q') }}" placeholder="Search shirts, bags, shoes...">
          </div>
          <div class="search-suggestions">
            <span>Popular:</span>
            <button type="button">Summer shirt</button>
            <button type="button">Women dress</button>
            <button type="button">Sneakers</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ================= TOAST ================= -->
  <div class="toast-container position-fixed end-0 p-3">
    <div id="cartToast" class="toast align-items-center text-bg-dark border-0" role="status" aria-live="polite" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">Product added to cart.</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>
