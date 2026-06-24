@extends('frontend.audience.master')
@section('content')

        <main>
            <section class="category-trust">
                <div class="trust-strip">
                    <div class="trust-item"><i class="bi bi-truck"></i><span><strong>60
                                min</strong><small>Delivery</small></span></div>
                    <div class="trust-item"><i class="bi bi-box-seam"></i><span><strong>30 min</strong><small>Returns
                                Policy</small></span></div>
                    <div class="trust-item"><i class="bi bi-house-heart"></i><span><strong>Home Trial</strong><small>Try
                                before you buy</small></span></div>
                    <div class="trust-item"><i
                            class="bi bi-shield-check"></i><span><strong>Secure</strong><small>Payment</small></span>
                    </div>
                </div>
            </section>
            <section class="category-hero">
                <div class="category-hero-copy">
                    <p>CATEGORY</p>
                    <h1>ACCESSORIES</h1><span>The details that complete your look.</span>
                </div><img src="assets/images/cat-bag.png" alt="Accessories">
            </section>
            <section class="category-grid-wrap">
                <div class="category-tile-grid"><a href="product.html" class="category-tile"><img
                            src="assets/images/cat-watch.png" alt="Watches"><strong>Watches</strong></a><a
                        href="product.html" class="category-tile"><img src="assets/images/cat-bag.png"
                            alt="Bags"><strong>Bags</strong></a><a href="product.html" class="category-tile"><img
                            src="assets/images/cat-men.png" alt="Sunglasses"><strong>Sunglasses</strong></a><a
                        href="product.html" class="category-tile"><img src="assets/images/cat-shoe.png"
                            alt="Footwear"><strong>Footwear</strong></a><a href="product.html"
                        class="category-tile"><img src="assets/images/cat-bag.png"
                            alt="Wallets"><strong>Wallets</strong></a><a href="product.html" class="category-tile"><img
                            src="assets/images/cat-men.png" alt="Belts"><strong>Belts</strong></a><a href="product.html"
                        class="category-tile"><img src="assets/images/cat-women.png"
                            alt="Jewellery"><strong>Jewellery</strong></a><a href="product.html"
                        class="category-tile"><img src="assets/images/cat-kids.png"
                            alt="Caps"><strong>Caps</strong></a><a href="product.html" class="category-tile"><img
                            src="assets/images/cat-bag.png" alt="Travel"><strong>Travel</strong></a><a
                        href="product.html" class="category-tile"><img src="assets/images/category-women.png"
                            alt="Fragrance"><strong>Fragrance</strong></a><a href="product.html"
                        class="category-tile"><img src="assets/images/cat-watch.png"
                            alt="Tech"><strong>Tech</strong></a><a href="product.html" class="category-tile"><img
                            src="assets/images/promo-small-men.png" alt="Gift cards"><strong>Gift Cards</strong></a>
                </div>
            </section>
            <section class="home-trial-banner">
                <div>
                    <p>TRY AT HOME, SHOP WITH CONFIDENCE</p>
                    <h2>Try at Home in <span>15-20 Minutes</span></h2><a href="product.html">SHOP NOW <i
                            class="bi bi-arrow-right"></i></a>
                </div><img src="assets/images/promo-large-women.png" alt="Vastra Express home trial">
            </section><a class="all-categories-link" href="index.html"><i class="bi bi-grid-3x3-gap-fill"></i> View All
                Categories</a>
        </main>
       @endsection