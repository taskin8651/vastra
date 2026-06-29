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

    <div class="phone-status" aria-hidden="true">
      <span>8:00</span>
      <span class="phone-status-icons">
        <i class="bi bi-reception-4"></i>
        <i class="bi bi-wifi"></i>
        <i class="bi bi-battery-full"></i>
      </span>
    </div>

    <!-- ================= HEADER ================= -->
    <header class="site-header sticky-top">
      <div class="container-fluid page-container">
        <div class="header-row">
          <button class="icon-btn menu-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-label="Open menu">
            <i class="bi bi-list"></i>
          </button>

          <a href="{{ route('frontend.home') }}" class="brand" aria-label="Vastra Express home">
            <span class="brand-mark">
              <span>V</span>
            </span>
            <span class="brand-name">VASTRA<span>EXPRESS</span></span>
          </a>

          <div class="header-actions">
            <button class="icon-btn" type="button" data-bs-toggle="modal" data-bs-target="#searchModal" aria-label="Search">
              <i class="bi bi-search"></i>
            </button>
            <button class="icon-btn" type="button" aria-label="Account">
              <i class="bi bi-person"></i>
            </button>
            <a class="icon-btn cart-button" href="{{ url('/cart') }}" aria-label="Cart">
              <i class="bi bi-bag"></i>
              <span class="cart-count">0</span>
            </a>
          </div>
        </div>

        <nav class="category-nav" aria-label="Primary category navigation">
          <a class="{{ request()->routeIs('frontend.home') ? 'active' : '' }}" href="{{ route('frontend.home') }}">Home</a>
          <a class="{{ request()->is('shop/women*') ? 'active' : '' }}" href="{{ url('/shop/women') }}">Women</a>
          <a class="{{ request()->is('shop/men*') ? 'active' : '' }}" href="{{ url('/shop/men') }}">Men</a>
          <a class="{{ request()->is('shop/kids*') ? 'active' : '' }}" href="{{ url('/shop/kids') }}">Kids</a>
          <a class="{{ request()->is('shop/accessories*') ? 'active' : '' }}" href="{{ url('/shop/accessories') }}">Accessories</a>
        </nav>
      </div>
    </header>

    @yield('content')

    
    <!-- ================= MOBILE BOTTOM NAV ================= -->
    <nav class="bottom-nav" aria-label="Mobile bottom navigation">
      <a class="active" href="{{ route('frontend.home') }}"><i class="bi bi-house-door-fill"></i><span>Home</span></a>
      <a href="{{ url('/shop/men') }}"><i class="bi bi-grid-3x3-gap-fill"></i><span>Categories</span></a>
      <a href="{{ url('/wishlist') }}"><i class="bi bi-heart"></i><span>Wishlist</span></a>
      <a href="#"><i class="bi bi-person"></i><span>Account</span></a>
      <a href="{{ route('frontend.orders.index') }}"><i class="bi bi-bag"></i><span>Orders</span></a>
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
        <a href="{{ route('frontend.categories.index') }}"><i class="bi bi-rulers"></i><span>Size Guide</span><i class="bi bi-chevron-right"></i></a>
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
            <input type="search" class="form-control" id="productSearch" placeholder="Search shirts, bags, shoes...">
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
  <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>
