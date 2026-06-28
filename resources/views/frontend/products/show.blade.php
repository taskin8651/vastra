@php
    use Illuminate\Support\Str;

    $brandName = optional($product->brand)->name ?? 'VASTRA EXPRESS';
    $categoryName = optional($product->category)->name ?? 'Category';
    $audience = optional(optional($product->category)->audience);
    $audienceName = $audience->name ?? 'Men';
    $audienceSlug = $audience->slug ?? 'men';

    $imageUrl = function ($path, $fallback = 'assets/images/cotton-shirt.png') {
        if (! $path) {
            return asset($fallback);
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (Str::startsWith($path, ['assets/', 'storage/'])) {
            return asset($path);
        }

        return asset('storage/' . $path);
    };

    $productImage = $imageUrl($product->image_path);

    $colours = is_array($product->available_colours)
        ? $product->available_colours
        : [];

    $sizes = is_array($product->available_sizes)
        ? $product->available_sizes
        : [];

    $discount = null;

    if ($product->compare_at_price && $product->compare_at_price > $product->price) {
        $discount = round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100);
    }

    $backUrl = '#';

    if ($product->category && $product->category->audience) {
        $backUrl = route('frontend.category.products', [
            $product->category->audience,
            $product->category
        ]);
    }

    $homeTrialImage = asset('assets/images/product-home-trial.png');
@endphp

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="theme-color" content="#fff">

    <title>{{ $product->name }} - Vastra Express</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body class="product-full-page">

    <div class="site-wrap">

        <div class="phone-status">
            <span>8:00</span>

            <span class="phone-status-icons">
                <i class="bi bi-reception-4"></i>
                <i class="bi bi-wifi"></i>
                <i class="bi bi-battery-full"></i>
            </span>
        </div>

        <header class="product-full-header">

            <a href="{{ $backUrl }}">
                <i class="bi bi-list"></i>
            </a>

            <a href="{{ url('/') }}" class="brand">
                <span class="brand-mark">
                    <span>V</span>
                </span>

                <span class="brand-name">
                    VASTRA<span>EXPRESS</span>
                </span>
            </a>

            <div>
                <a href="#">
                    <i class="bi bi-heart"></i>
                </a>

                <a href="{{ url('/cart') }}">
                    <i class="bi bi-bag"></i>
                </a>
            </div>

        </header>

        <nav class="shirts-top-nav">
            <a href="{{ url('/') }}">Home</a>

            <a class="{{ $audienceSlug === 'men' ? 'active' : '' }}" href="{{ url('/shop/men') }}">
                Men
            </a>

            <a class="{{ $audienceSlug === 'women' ? 'active' : '' }}" href="{{ url('/shop/women') }}">
                Women
            </a>

            <a class="{{ $audienceSlug === 'kids' ? 'active' : '' }}" href="{{ url('/shop/kids') }}">
                Kids
            </a>

            <a class="{{ $audienceSlug === 'accessories' ? 'active' : '' }}" href="{{ url('/shop/accessories') }}">
                Accessories
            </a>
        </nav>

        <main>

            {{-- PRODUCT IMAGE --}}
            <section class="product-main-photo">

                <img src="{{ $productImage }}" alt="{{ $product->name }}">

                <span>
                    <i class="bi bi-heart"></i>
                </span>

            </section>

            {{-- PRODUCT BASIC INFO --}}
            <section class="product-copy">

                <small>VASTRA EXPRESS</small>

                <h1>{{ $product->name }}</h1>

                <b>{{ $brandName }}</b>

                <div class="product-stars">
                    ★★★★★ <strong>4.0</strong> <span>(45 reviews)</span>
                </div>

                <p>
                    {{ $product->description ?: 'Premium fashion product crafted for comfort, style and everyday confidence.' }}
                </p>

                <div class="product-price">

                    <strong>
                        Rs{{ number_format($product->price, 0) }}
                    </strong>

                    @if($product->compare_at_price)
                        <del>
                            Rs{{ number_format($product->compare_at_price, 0) }}
                        </del>
                    @endif

                    @if($discount)
                        <mark>{{ $discount }}%OFF</mark>
                    @endif

                </div>

                <div class="product-delivery">

                    <span>
                        <i class="bi bi-lightning-charge"></i>
                        Delivery in 30-60 mint
                    </span>

                    <b>
                        <i class="bi bi-tag"></i>
                        Best Price Today
                    </b>

                </div>

            </section>

            {{-- COLOUR --}}
            <section class="product-option">

                <h2>
                    Colour : {{ $product->colour ?? 'Black' }}
                </h2>

                <div class="product-colours">

                    @if(count($colours))

                        @foreach($colours as $colour)
                            <i style="background: {{ $colour }};"></i>
                        @endforeach

                    @else

                        <i></i>
                        <i></i>
                        <i></i>
                        <i></i>
                        <i></i>

                    @endif

                </div>

            </section>

            {{-- SIZE --}}
            <section class="product-option">

                <h2>
                    Select Size

                    <a href="{{ url('/size-guide') }}">
                        <i class="bi bi-tag"></i>
                        Size Guide
                    </a>
                </h2>

                <div class="product-sizes">

                    @if(count($sizes))

                        @foreach($sizes as $index => $size)
                            <span class="{{ $index === 2 ? 'selected' : '' }}">
                                {{ $size }}
                            </span>
                        @endforeach

                    @else

                        <span>XS</span>
                        <span>S</span>
                        <span>M</span>
                        <span class="selected">L</span>
                        <span>XL</span>
                        <span>XXL</span>
                        <span>3XL</span>

                    @endif

                </div>

            </section>

            {{-- PRODUCT DETAILS --}}
            <section class="product-accordion">

                <h2>
                    Product details
                    <i class="bi bi-chevron-down"></i>
                </h2>

                <div class="product-detail-grid">

                    <h3>Product Details</h3>

                    <p>
                        <b>Closure Type</b>
                        {{ $product->closure_type ?? 'N/A' }}
                    </p>

                    <p>
                        <b>Fashion Type</b>
                        {{ $product->fashion_type ?? 'N/A' }}
                    </p>

                    <p>
                        <b>Gender</b>
                        {{ $audienceName }}
                    </p>

                    <p>
                        <b>Category</b>
                        {{ $categoryName }}
                    </p>

                    <p>
                        <b>SKU</b>
                        {{ $product->sku ?? 'N/A' }}
                    </p>

                    <p>
                        <b>Hemline</b>
                        {{ $product->hemline ?? 'N/A' }}
                    </p>

                    <p>
                        <b>Knit or Woven</b>
                        {{ $product->knit_or_woven ?? 'N/A' }}
                    </p>

                    <p>
                        <b>Product Length</b>
                        {{ $product->product_length ?? 'N/A' }}
                    </p>

                    <p>
                        <b>Season</b>
                        {{ $product->season ?? 'N/A' }}
                    </p>

                    <p>
                        <b>Transparency</b>
                        {{ $product->transparency ?? 'N/A' }}
                    </p>

                    <p>
                        <b>Stretchable</b>
                        {{ $product->stretchability ?? 'N/A' }}
                    </p>

                    <p>
                        <b>Wash Care</b>
                        {{ $product->wash_care ?? 'N/A' }}
                    </p>

                    <p>
                        <b>Fit Type</b>
                        {{ $product->fit_type ?? 'N/A' }}
                    </p>

                    <p>
                        <b>Fabric Details</b>
                        {{ $product->fabric_details ?? 'N/A' }}
                    </p>

                    <p>
                        <b>Fabric Composition in %</b>
                        {{ $product->fabric_composition ?? 'N/A' }}
                    </p>

                    <p>
                        <b>Occasion</b>
                        {{ $product->occasion ?? 'N/A' }}
                    </p>

                    <p>
                        <b>Pattern Type</b>
                        {{ $product->pattern_type ?? 'N/A' }}
                    </p>

                    <p>
                        <b>Sleeve Length</b>
                        {{ $product->sleeve_length ?? 'N/A' }}
                    </p>

                    <p>
                        <b>Stock</b>
                        {{ $product->stock_quantity > 0 ? $product->stock_quantity . ' Available' : 'Out of Stock' }}
                    </p>

                </div>

            </section>

            {{-- HOME TRIAL --}}
            <section class="product-trial">
                <img src="{{ $homeTrialImage }}" alt="Home trial details">
            </section>

            {{-- REVIEW TITLE --}}
            <section class="product-review-title">
                <h2>Customer Review (124)</h2>
                <i class="bi bi-chevron-right"></i>
            </section>

            {{-- RATING --}}
            <section class="product-rating">

                <h2>Overall Rating</h2>

                <div>
                    <strong>4.8</strong>

                    <span>
                        ★★★★★
                        <small>(45 reviews)</small>
                    </span>

                    <p>
                        5 <i></i> 912<br>
                        4 <i></i> 912<br>
                        3 <i></i> 912<br>
                        2 <i></i> 912<br>
                        1 <i></i> 912
                    </p>
                </div>

            </section>

            {{-- CUSTOMER PHOTOS --}}
            <section class="product-photos">

                <h2>Customer photos (248)</h2>

                <div>
                    <img src="{{ $productImage }}" alt="Customer photo">
                    <img src="{{ $productImage }}" alt="Customer photo">
                    <img src="{{ $productImage }}" alt="Customer photo">

                    <b>
                        +244<br>More
                    </b>
                </div>

            </section>

        </main>

       <footer class="product-bottom">

    <form action="{{ route('frontend.cart.add', $product) }}" method="POST" id="addToCartForm">
        @csrf

        @if(count($sizes))
            <input type="hidden" name="size" value="{{ $sizes[0] }}">
        @endif

        <input type="hidden" name="colour" value="{{ $product->colour ?? '' }}">
        <input type="hidden" name="qty" value="1">
    </form>

    <a href="javascript:void(0)" onclick="document.getElementById('addToCartForm').submit();">
        Add to bag
    </a>

    <a href="javascript:void(0)" onclick="document.getElementById('addToCartForm').submit();">
        Buy Now
        <small>Get in 30-60 mint</small>
    </a>

</footer>

    </div>

</body>

</html>