@extends('frontend.master')

@section('content')
@php
    use Illuminate\Support\Str;

    $productImage = function ($product) {
        $path = $product->image_path;

        if (! $path) {
            return asset('assets/images/product-man.png');
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (Str::startsWith($path, ['assets/', 'storage/'])) {
            return asset($path);
        }

        return asset('storage/' . $path);
    };

    $discountPercent = function ($product) {
        if (! $product->compare_at_price || $product->compare_at_price <= $product->price) {
            return null;
        }

        return round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100);
    };
@endphp

<main id="home">
    <section class="section-block">
        <div class="container-fluid page-container">
            <div class="section-head">
                <h2>{{ $query ? 'Search: ' . $query : 'Search Products' }}</h2>
                <a href="{{ route('frontend.home') }}">Home <i class="bi bi-arrow-right"></i></a>
            </div>

            <form action="{{ route('frontend.search') }}" method="GET" class="input-group mb-4">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="search" class="form-control" name="q" value="{{ $query }}" placeholder="Search shirts, bags, shoes...">
                <button class="btn btn-dark" type="submit">Search</button>
            </form>

            <div class="product-grid" data-server-products="1">
                @forelse($products as $product)
                    @php
                        $discount = $discountPercent($product);
                        $sizes = is_array($product->available_sizes) ? $product->available_sizes : [];
                        $defaultSize = $sizes[0] ?? null;
                        $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
                    @endphp

                    <article class="product-card" data-product-card data-product-url="{{ route('frontend.products.show', $product) }}">
                        <div class="product-media">
                            <a href="{{ route('frontend.products.show', $product) }}">
                                <img src="{{ $productImage($product) }}" alt="{{ $product->name }}" loading="lazy">
                            </a>

                            <form action="{{ route('frontend.wishlist.toggle', $product) }}" method="POST" data-wishlist-toggle data-wishlist-product="{{ $product->id }}">
                                @csrf
                                <button type="submit" class="wishlist-btn {{ $isWishlisted ? 'active' : '' }}" aria-label="Toggle {{ $product->name }} wishlist">
                                    <i class="bi {{ $isWishlisted ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                </button>
                            </form>
                        </div>

                        <div class="product-body">
                            <a href="{{ route('frontend.products.show', $product) }}" style="color: inherit; text-decoration: none;">
                                <h3 class="product-title">{{ $product->name }}</h3>
                            </a>

                            <span class="product-brand">{{ optional($product->brand)->name ?? 'VASTRA' }}</span>

                            <div class="product-meta">
                                <span class="rating-badge"><i class="bi bi-star-fill"></i>4.3</span>
                                <span class="rating-count">120 Rating</span>
                            </div>

                            <div class="price-row">
                                <span class="current-price">₹{{ number_format($product->price, 0) }}</span>

                                @if($product->compare_at_price && $product->compare_at_price > $product->price)
                                    <span class="old-price">₹{{ number_format($product->compare_at_price, 0) }}</span>
                                @endif

                                @if($discount)
                                    <span class="discount">{{ $discount }}% OFF</span>
                                @endif
                            </div>

                            <form action="{{ route('frontend.cart.add', $product) }}" method="POST">
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
                    <div class="empty-category-box">
                        <h3>No products found</h3>
                        <p>Try searching shirts, jeans, dresses, sneakers, or a brand name.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </section>
</main>
@endsection
