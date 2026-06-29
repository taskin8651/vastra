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

    $productImages = count($product->image_urls) ? $product->image_urls : [asset('assets/images/cotton-shirt.png')];
    $productImage = $productImages[0];

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

    $reviews = collect($reviews ?? []);
    $reviewCount = $reviews->count();
    $reviewAverage = $reviewCount
        ? round($reviews->avg(fn ($review) => (float) data_get($review, 'rating', 0)), 1)
        : null;
    $reviewPhotos = $reviews
        ->map(fn ($review) => data_get($review, 'image_url') ?: data_get($review, 'photo_url'))
        ->filter()
        ->take(4)
        ->values();
    $ratingBreakdown = collect([5, 4, 3, 2, 1])
        ->mapWithKeys(fn ($rating) => [$rating => $reviews->where('rating', $rating)->count()]);

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
                <span>
                    <form action="{{ route('frontend.wishlist.toggle', $product) }}" method="POST" data-wishlist-toggle data-wishlist-product="{{ $product->id }}">
                        @csrf

                        <button type="submit" class="wishlist-floating-btn {{ $isWishlisted ? 'active' : '' }}" aria-label="Toggle {{ $product->name }} wishlist">
                            <i class="bi {{ $isWishlisted ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                        </button>
                    </form>
                </span>

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
                    <form action="{{ route('frontend.wishlist.toggle', $product) }}" method="POST" data-wishlist-toggle data-wishlist-product="{{ $product->id }}">
                        @csrf

                        <button type="submit" class="wishlist-floating-btn {{ $isWishlisted ? 'active' : '' }}" aria-label="Toggle {{ $product->name }} wishlist">
                            <i class="bi {{ $isWishlisted ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                        </button>
                    </form>
                </span>

            </section>

            @if(count($productImages) > 1)
                <section class="product-photos" style="padding-bottom:16px;">
                    <h2>Product photos ({{ count($productImages) }})</h2>

                    <div>
                        @foreach(array_slice($productImages, 0, 4) as $galleryImage)
                            <img src="{{ $galleryImage }}" alt="{{ $product->name }} image">
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- PRODUCT BASIC INFO --}}
            <section class="product-copy">

                <small>VASTRA EXPRESS</small>

                <h1>{{ $product->name }}</h1>

                <b>{{ $brandName }}</b>

                @if($reviewCount)
                    <div class="product-stars">
                        ★★★★★ <strong>{{ number_format($reviewAverage, 1) }}</strong> <span>({{ $reviewCount }} reviews)</span>
                    </div>
                @endif

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

                <div class="product-sizes" id="productSizeOptions">

                    @if(count($sizes))

                        @foreach($sizes as $index => $size)
                            <span role="button" tabindex="0" data-size-option="{{ $size }}">
                                {{ $size }}
                            </span>
                        @endforeach

                    @else

                        @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL'] as $fallbackSize)
                            <span role="button" tabindex="0" data-size-option="{{ $fallbackSize }}">
                                {{ $fallbackSize }}
                            </span>
                        @endforeach

                    @endif

                </div>
                <small class="product-size-alert" id="productSizeAlert" hidden>Please select a size first.</small>

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

            @if($reviewCount)
                {{-- REVIEW TITLE --}}
                <section class="product-review-title">
                    <h2>Customer Review ({{ $reviewCount }})</h2>
                    <i class="bi bi-chevron-right"></i>
                </section>

                {{-- RATING --}}
                <section class="product-rating">

                    <h2>Overall Rating</h2>

                    <div>
                        <strong>{{ number_format($reviewAverage, 1) }}</strong>

                        <span>
                            ★★★★★
                            <small>({{ $reviewCount }} reviews)</small>
                        </span>

                        <p>
                            @foreach($ratingBreakdown as $rating => $count)
                                {{ $rating }} <i style="width: {{ $reviewCount ? max(8, round(($count / $reviewCount) * 70)) : 8 }}px;"></i> {{ $count }}<br>
                            @endforeach
                        </p>
                    </div>

                    <div class="product-review-list">
                        @foreach($reviews->take(3) as $review)
                            <article>
                                <strong>{{ number_format((float) data_get($review, 'rating', 0), 1) }} ★</strong>
                                <p>{{ data_get($review, 'comment') ?: data_get($review, 'review') }}</p>
                                <small>{{ data_get($review, 'user.name') ?: data_get($review, 'name') ?: 'Verified Customer' }}</small>
                            </article>
                        @endforeach
                    </div>

                </section>

                @if($reviewPhotos->isNotEmpty())
                    {{-- CUSTOMER PHOTOS --}}
                    <section class="product-photos">

                        <h2>Customer photos ({{ $reviewPhotos->count() }})</h2>

                        <div>
                            @foreach($reviewPhotos as $photo)
                                <img src="{{ $imageUrl($photo, 'assets/images/product-man.png') }}" alt="Customer photo">
                            @endforeach
                        </div>

                    </section>
                @endif
            @endif

        </main>

       <footer class="product-bottom">

    

    <a href="javascript:void(0)" onclick="document.getElementById('addToCartForm').requestSubmit();">
        Buy Now
        <small>Get in 30-60 mint</small>
    </a>
    

    <a href="javascript:void(0)" onclick="document.getElementById('addToCartForm').requestSubmit();">
        Add to bag
    </a>

</footer>
<form action="{{ route('frontend.cart.add', $product) }}" method="POST" id="addToCartForm">
        @csrf

        <input type="hidden" name="size" id="selectedProductSize" value="">

        <input type="hidden" name="colour" value="{{ $product->colour ?? '' }}">
        <input type="hidden" name="qty" value="1">
    </form>

    </div>

    <div class="toast-container position-fixed end-0 p-3">
        <div id="cartToast" class="toast align-items-center text-bg-dark border-0" role="status" aria-live="polite" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">Product added to cart.</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        const sizeOptions = document.querySelectorAll('[data-size-option]');
        const selectedSizeInput = document.getElementById('selectedProductSize');
        const sizeAlert = document.getElementById('productSizeAlert');
        const addToCartForm = document.getElementById('addToCartForm');

        function selectProductSize(option) {
            sizeOptions.forEach((item) => item.classList.remove('selected'));
            option.classList.add('selected');
            selectedSizeInput.value = option.dataset.sizeOption || option.textContent.trim();
            sizeAlert.hidden = true;
        }

        sizeOptions.forEach((option) => {
            option.addEventListener('click', () => selectProductSize(option));
            option.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    selectProductSize(option);
                }
            });
        });

        addToCartForm.addEventListener('submit', (event) => {
            if (!selectedSizeInput.value) {
                event.preventDefault();
                sizeAlert.hidden = false;
                document.getElementById('productSizeOptions').scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });
    </script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>
