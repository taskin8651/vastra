@extends('frontend.audience.master')

@section('content')

@php
    use Illuminate\Support\Str;

    $categorySlug = $category->slug;
    $audienceSlug = $audience->slug;

    $heroPath = "assets/images/{$categorySlug}-hero.png";
    $salePath = "assets/images/{$categorySlug}-sale.png";

    $heroImage = file_exists(public_path($heroPath))
        ? asset($heroPath)
        : asset('assets/images/shirts-hero.png');

    $saleImage = file_exists(public_path($salePath))
        ? asset($salePath)
        : asset('assets/images/shirts-sale.png');

    $imageUrl = function ($path, $fallback = 'assets/images/shirts-product.png') {
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

    $brands = $products->getCollection()
        ->pluck('brand')
        ->filter()
        ->unique('id')
        ->take(4);
@endphp

<main>

    {{-- SEARCH BAR --}}
    <div class="shirt-search">
        <i class="bi bi-search"></i>

        <form action="{{ route('frontend.category.products', [$audience, $category]) }}"
              method="GET"
              style="width:100%; display:flex; align-items:center; gap:8px;">

            <input type="text"
                   name="q"
                   value="{{ request('q') }}"
                   placeholder="Search for clothes..."
                   style="border:0; outline:0; background:transparent; width:100%;">

            <button type="submit"
                    style="border:0; background:transparent; padding:0;">
                <i class="bi bi-search"></i>
            </button>

        </form>

        <a href="#shirtFilter" aria-label="Filter">
            <i class="bi bi-sliders"></i>
        </a>
    </div>

    {{-- HERO --}}
    <section class="shirts-hero">
        <img src="{{ $heroImage }}" alt="{{ $category->name }}">
    </section>

    <div class="hero-dots">
        <span class="active"></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>

    {{-- BENEFITS --}}
    <section class="shirt-benefits">
        <div>
            <i class="bi bi-award"></i>
            <span>Premium<br>Quality</span>
        </div>

        <div>
            <i class="bi bi-patch-check"></i>
            <span>100% Original<br>Product</span>
        </div>

        <div>
            <i class="bi bi-box-seam"></i>
            <span>Easy<br>Returns</span>
        </div>

        <div>
            <i class="bi bi-shield-check"></i>
            <span>Secure<br>Payment</span>
        </div>
    </section>

    {{-- BRAND ROW --}}
    <section class="shirts-brand-row">

        <h1>BEST OF BRANDS</h1>

        <a href="#">
            View All <i class="bi bi-arrow-right"></i>
        </a>

        <div class="shirts-brand-logos">

            @forelse($brands as $brand)

                @php
                    $brandName = strtoupper($brand->name);
                    $brandClass = Str::slug($brand->name) . '-logo';
                @endphp

                <span class="{{ $brandClass }}">
                    {{ $brandName }}
                </span>

            @empty

                <span class="hm-logo">H&amp;M</span>
                <span class="zara-logo">ZARA</span>
                <span class="puma-logo">PUMA</span>
                <span class="hm-logo">H&amp;M</span>

            @endforelse

        </div>
    </section>

    {{-- PRODUCTS --}}
    <section class="shirt-products">

        @forelse($products as $product)

            @php
                $productImage = $imageUrl($product->image_path);

                $productLink = '';

                $discount = null;

                if ($product->compare_at_price && $product->compare_at_price > $product->price) {
                    $discount = round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100);
                }

                $colours = is_array($product->available_colours)
                    ? array_slice($product->available_colours, 0, 3)
                    : [];
            @endphp

            <article class="shirt-product-card">

                <a href="{{ route('frontend.products.show', $product) }}" class="shirt-product-image">

                    <img src="{{ $productImage }}" alt="{{ $product->name }}">

                    <span>
                        <i class="bi bi-heart"></i>
                    </span>

                </a>

                <div>

                    <h2>{{ $product->name }}</h2>

                    <small>
                        {{ optional($product->brand)->name ?? 'Vastra' }}
                    </small>

                    <p>
                        <b>
                            <i class="bi bi-star-fill"></i> 4.3
                        </b>
                        <em>120 Rating</em>
                    </p>

                    <strong>
                        Rs{{ number_format($product->price, 0) }}
                    </strong>

                    @if($product->compare_at_price)
                        <del>
                            Rs{{ number_format($product->compare_at_price, 0) }}
                        </del>
                    @endif

                    @if($discount)
                        <mark>
                            {{ $discount }}% OFF
                        </mark>
                    @endif

                    <div class="shirt-swatches">

                        @if(count($colours))

                            @foreach($colours as $colour)
                                <i style="background: {{ $colour }};"></i>
                            @endforeach

                        @else

                            <i></i>
                            <i></i>
                            <i></i>

                        @endif

                    </div>

                    <form action="{{ route('frontend.cart.add', $product) }}" method="POST">
    @csrf
    <input type="hidden" name="qty" value="1">

    <button type="submit" class="shirt-add" style="border:0;width:100%;">
        <i class="bi bi-cart3"></i>
        ADD TO CART
    </button>
</form>

                </div>

            </article>

        @empty

            <div class="empty-category-box" style="grid-column: 1 / -1;">
                <h3>No Products Found</h3>
                <p>
                    {{ $category->name }} category me abhi koi product add nahi hai.
                </p>
            </div>

        @endforelse

    </section>

    {{-- PAGINATION --}}
    @if($products->hasPages())
        <div class="pagination-wrap">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @endif

    {{-- SALE BANNER --}}
    <section class="shirts-sale">
        <img src="{{ $saleImage }}" alt="{{ $category->name }} Sale">
    </section>

</main>

@endsection