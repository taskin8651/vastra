@extends('frontend.master')
@section('content')

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
      <section class="quick-categories">
        <div class="container-fluid page-container">
          <div class="quick-category-grid">
            <a href="men.html" class="quick-category-card">
              <img src="assets/images/category-men.png" alt="Men collection">
              <span><strong>Men</strong><small>Explore <i class="bi bi-arrow-right"></i></small></span>
            </a>
            <a href="women.html" class="quick-category-card">
              <img src="assets/images/category-women.png" alt="Women collection">
              <span><strong>Women</strong><small>Explore <i class="bi bi-arrow-right"></i></small></span>
            </a>
            <a href="kids.html" class="quick-category-card">
              <img src="assets/images/category-kids.png" alt="Kids collection">
              <span><strong>Kids</strong><small>Explore <i class="bi bi-arrow-right"></i></small></span>
            </a>
          </div>
        </div>
      </section>

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
      <section class="section-block" id="trending">
        <div class="container-fluid page-container">
          <div class="section-head">
            <h2>Trending Now</h2>
            <a href="#top-picks">View All <i class="bi bi-arrow-right"></i></a>
          </div>
          <div class="product-grid" id="trendingProducts" data-server-products="true">@foreach($products->take(2) as $product)<article class="product-card" data-product-card><a class="product-media" href="{{ route('storefront.product', $product) }}"><img src="{{ asset($product->image_path ?: 'assets/images/product-man.png') }}" alt="{{ $product->name }}"><button type="button" class="wishlist-btn"><i class="bi bi-heart"></i></button></a><div class="product-body"><h3 class="product-title">{{ $product->name }}</h3><span class="product-brand">{{ $product->brand->name }}</span><div class="price-row"><span class="current-price">₹{{ number_format($product->price, 0) }}</span></div></div></article>@endforeach</div>
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
      <section class="section-block shop-category" id="accessories">
        <div class="container-fluid page-container">
          <div class="section-head">
            <h2>Shop By Category</h2>
            <a href="accessories.html">View All <i class="bi bi-arrow-right"></i></a>
          </div>

          <div class="round-category-grid">
            <a href="men.html" class="round-category" id="men">
              <span class="round-image"><img src="assets/images/cat-men.png" alt="Men"></span>
              <strong>Men</strong>
            </a>
            <a href="women.html" class="round-category" id="women">
              <span class="round-image"><img src="assets/images/cat-women.png" alt="Women"></span>
              <strong>Women</strong>
            </a>
            <a href="kids.html" class="round-category" id="kids">
              <span class="round-image"><img src="assets/images/cat-kids.png" alt="Kids"></span>
              <strong>Kids</strong>
            </a>
            <a href="#" class="round-category">
              <span class="round-image"><img src="assets/images/cat-watch.png" alt="Watch"></span>
              <strong>Watch</strong>
            </a>
            <a href="#" class="round-category">
              <span class="round-image"><img src="assets/images/cat-shoe.png" alt="Shoe"></span>
              <strong>Shoe</strong>
            </a>
            <a href="#" class="round-category">
              <span class="round-image"><img src="assets/images/cat-bag.png" alt="Bag"></span>
              <strong>Bag</strong>
            </a>
          </div>
        </div>
      </section>

      <div class="container-fluid page-container"><hr class="section-divider"></div>

      <!-- ================= TOP PICKS ================= -->
      <section class="section-block" id="top-picks">
        <div class="container-fluid page-container">
          <div class="section-head">
            <h2>Top Picks For You</h2>
            <a href="#">View All <i class="bi bi-arrow-right"></i></a>
          </div>
          <div class="product-grid top-pick-grid" id="topPickProducts" data-server-products="true">@foreach($products as $product)<article class="product-card" data-product-card><a class="product-media" href="{{ route('storefront.product', $product) }}"><img src="{{ asset($product->image_path ?: 'assets/images/product-man.png') }}" alt="{{ $product->name }}"></a><div class="product-body"><h3 class="product-title">{{ $product->name }}</h3><span class="product-brand">{{ $product->brand->name }}</span><div class="price-row"><span class="current-price">₹{{ number_format($product->price, 0) }}</span></div></div></article>@endforeach</div>
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
            <a href="brands.html">View All <i class="bi bi-arrow-right"></i></a>
          </div>

          <div class="brand-grid">
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
@endsection
