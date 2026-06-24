@extends('frontend.master')
@section('content')

        <main>
            <div class="shirt-search"><i class="bi bi-search"></i><span>Search for clothes...</span><a
                    href="#shirtFilter" aria-label="Filter"><i class="bi bi-sliders"></i></a></div>
            <section class="shirts-hero"><img src="assets/images/shirts-hero.png" alt="Summer collection"></section>
            <div class="hero-dots"><span class="active"></span><span></span><span></span><span></span><span></span>
            </div>
            <section class="shirt-benefits">
                <div><i class="bi bi-award"></i><span>Premium<br>Quality</span></div>
                <div><i class="bi bi-patch-check"></i><span>100% Original<br>Product</span></div>
                <div><i class="bi bi-box-seam"></i><span>Easy<br>Returns</span></div>
                <div><i class="bi bi-shield-check"></i><span>Secure<br>Payment</span></div>
            </section>
            <section class="shirts-brand-row">
                <h1>BEST OF BRANDS</h1><a href="brands.html">View All <i class="bi bi-arrow-right"></i></a>
                <div class="shirts-brand-logos"><span class="hm-logo">H&amp;M</span><span
                        class="zara-logo">ZARA</span><span class="puma-logo">PUMA</span><span
                        class="hm-logo">H&amp;M</span></div>
            </section>
            <section class="shirt-products">
                <article class="shirt-product-card"><a href="product.html" class="shirt-product-image"><img
                            src="assets/images/shirts-product.png" alt="Linen Blend Shirt"><span><i
                                class="bi bi-heart"></i></span></a>
                    <div>
                        <h2>Linen Blend Shirt</h2><small>ZARA</small>
                        <p><b><i class="bi bi-star-fill"></i> 4.3</b><em>120 Rating</em></p>
                        <strong>Rs1350</strong><del>Rs1200</del><mark>40% OFF</mark>
                        <div class="shirt-swatches"><i></i><i></i><i></i></div><a href="product.html"
                            class="shirt-add"><i class="bi bi-cart3"></i> ADD TO CART</a>
                    </div>
                </article>
                <article class="shirt-product-card"><a href="product.html" class="shirt-product-image"><img
                            src="assets/images/shirts-product.png" alt="Linen Blend Shirt"><span><i
                                class="bi bi-heart"></i></span></a>
                    <div>
                        <h2>Linen Blend Shirt</h2><small>ZARA</small>
                        <p><b><i class="bi bi-star-fill"></i> 4.3</b><em>120 Rating</em></p>
                        <strong>Rs1350</strong><del>Rs1200</del><mark>40% OFF</mark>
                        <div class="shirt-swatches"><i></i><i></i><i></i></div><a href="product.html"
                            class="shirt-add"><i class="bi bi-cart3"></i> ADD TO CART</a>
                    </div>
                </article>
                <article class="shirt-product-card"><a href="product.html" class="shirt-product-image"><img
                            src="assets/images/shirts-product.png" alt="Linen Blend Shirt"><span><i
                                class="bi bi-heart"></i></span></a>
                    <div>
                        <h2>Linen Blend Shirt</h2><small>ZARA</small>
                        <p><b><i class="bi bi-star-fill"></i> 4.3</b><em>120 Rating</em></p>
                        <strong>Rs1350</strong><del>Rs1200</del><mark>40% OFF</mark>
                        <div class="shirt-swatches"><i></i><i></i><i></i></div><a href="product.html"
                            class="shirt-add"><i class="bi bi-cart3"></i> ADD TO CART</a>
                    </div>
                </article>
                <article class="shirt-product-card"><a href="product.html" class="shirt-product-image"><img
                            src="assets/images/shirts-product.png" alt="Linen Blend Shirt"><span><i
                                class="bi bi-heart"></i></span></a>
                    <div>
                        <h2>Linen Blend Shirt</h2><small>ZARA</small>
                        <p><b><i class="bi bi-star-fill"></i> 4.3</b><em>120 Rating</em></p>
                        <strong>Rs1350</strong><del>Rs1200</del><mark>40% OFF</mark>
                        <div class="shirt-swatches"><i></i><i></i><i></i></div><a href="product.html"
                            class="shirt-add"><i class="bi bi-cart3"></i> ADD TO CART</a>
                    </div>
                </article>
            </section>
            <section class="shirts-sale"><img src="assets/images/shirts-sale.png" alt="Summer sale"></section>
        </main>
        
        <section class="shirt-filter-sheet" id="shirtFilter">
            <header><strong>Filter</strong><a href="#" aria-label="Close"><i class="bi bi-x-lg"></i></a></header>
            <div class="shirt-filter-line">Category <i class="bi bi-chevron-right"></i></div>
            <h2>Size <i class="bi bi-chevron-right"></i></h2>
            <div class="filter-size-grid">
                <span>XS</span><span>S</span><span>M</span><span>XS</span><span>L</span><span>XL</span><span>XXL</span><span>3XL</span>
            </div>
            <h2>Colour <i class="bi bi-chevron-right"></i></h2>
            <div class="filter-colours"><i></i><i></i><i></i><i></i><i></i><b>+3</b></div>
            <h2>Price <i class="bi bi-chevron-right"></i></h2>
            <div class="filter-price"><span></span><b>Rs499</b><b>Rs2,999</b></div>
            <h2>Discount <i class="bi bi-chevron-right"></i></h2>
            <div class="filter-size-grid discount-grid"><span>10%+</span><span>20%+</span><span>30%+</span><span
                    class="chosen">40%+</span><span>50%+</span><span>60%+</span><span>70%+</span></div>
            <div class="shirt-filter-line">Brand <i class="bi bi-chevron-right"></i></div>
            <h2>Rating <i class="bi bi-chevron-down"></i></h2>
            <div class="filter-rating"><span>4*&amp; above</span><span>3*&amp; above</span><span>2*&amp; above</span>
            </div>
            <h2>Rating <i class="bi bi-chevron-down"></i></h2>
        </section>
    </div>

    @endsection