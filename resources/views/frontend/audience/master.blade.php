<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#fff">
    <title>Women - Vastra Express</title>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="category-page reference-women-page">
    <div class="site-wrap">
        <div class="phone-status"><span>8:00</span><span class="phone-status-icons"><i class="bi bi-reception-4"></i><i
                    class="bi bi-wifi"></i><i class="bi bi-battery-full"></i></span></div>
        <header class="category-header"><a href="index.html" class="detail-icon" aria-label="Home"><i
                    class="bi bi-list"></i></a><a href="index.html" class="brand"><span
                    class="brand-mark"><span>V</span></span><span
                    class="brand-name">VASTRA<span>EXPRESS</span></span></a>
            <div class="header-actions"><a class="icon-btn" href="index.html" aria-label="Search"><i
                        class="bi bi-search"></i></a><a class="icon-btn" href="#" aria-label="Account"><i
                        class="bi bi-person"></i></a><a class="icon-btn cart-button" href="cart.html"
                    aria-label="Cart"><i class="bi bi-bag"></i></a></div>
        </header>

        @yield('content')

         <nav class="bottom-nav"><a href="index.html"><i class="bi bi-house-door-fill"></i><span>Home</span></a><a
                class="active" href="women.html"><i class="bi bi-grid-3x3-gap-fill"></i><span>Categories</span></a><a
                href="#"><i class="bi bi-heart"></i><span>Wishlist</span></a><a href="#"><i
                    class="bi bi-person"></i><span>Account</span></a><a href="cart.html"><i
                    class="bi bi-bag"></i><span>Orders</span></a></nav>
    </div>
</body>

</html>