@extends('frontend.brands.master')

@section('title', $brand->name . ' Products - Vastra Express')
@section('body_class', strtolower($brand->slug) . '-page')

@section('header_left')
    <a href="{{ route('frontend.brands.index') }}" class="detail-icon">
        <i class="bi bi-arrow-left"></i>
    </a>
@endsection

@section('content')

@php
    use Illuminate\Support\Str;

    $brandHeroImage = $brand->image_url ?: asset('assets/images/zara-hero.png');

    $productImage = function ($product) {
        return $product->image_url ?: asset('assets/images/zara-product.png');
    };

    $audienceImage = function ($audience) {
        if ($audience->image_url) {
            return $audience->image_url;
        }

        return match ($audience->slug) {
            'men' => asset('assets/images/cat-men.png'),
            'women' => asset('assets/images/cat-women.png'),
            'kids' => asset('assets/images/cat-kids.png'),
            'accessories' => asset('assets/images/cat-bag.png'),
            default => asset('assets/images/cat-men.png'),
        };
    };

    $currentSort = request('sort') === 'price_low'
        ? 'Price Low'
        : (request('sort') === 'price_high'
            ? 'Price High'
            : (request('sort') === 'latest' ? 'Latest' : 'Popularity'));
@endphp

<main>

    <div class="zara-title">

        <a href="{{ route('frontend.brands.index') }}">
            <i class="bi bi-chevron-left"></i>
        </a>

        <h1>{{ $brand->name }}</h1>

        <a href="{{ route('frontend.brands.index') }}">
            <i class="bi bi-x-lg"></i>
        </a>

    </div>

    <form action="{{ route('frontend.brands.show', $brand) }}" method="GET" class="zara-search">

        <button type="submit" aria-label="Search" style="border:0;background:transparent;padding:0;color:inherit;">
            <i class="bi bi-search"></i>
        </button>

        <input type="text"
               name="q"
               value="{{ request('q') }}"
               placeholder="Search"
               style="border:0;outline:0;background:transparent;width:100%;">

        @if(request('audience'))
            <input type="hidden" name="audience" value="{{ request('audience') }}">
        @endif

        @if(request('sort'))
            <input type="hidden" name="sort" value="{{ request('sort') }}">
        @endif

        <i class="bi bi-camera"></i>

        <a href="#zaraFilter">
            <i class="bi bi-sliders"></i>
        </a>

    </form>

    <div class="zara-audience">

        @forelse($audiences as $audience)

            <div onclick="window.location='{{ route('frontend.brands.show', [$brand, 'audience' => $audience->slug]) }}'"
                 style="cursor:pointer;">

                <img src="{{ $audienceImage($audience) }}" alt="{{ $audience->name }}">

                <span>{{ $audience->name }}</span>

            </div>

        @empty

            <div>
                <img src="{{ asset('assets/images/cat-men.png') }}" alt="Men">
                <span>Men</span>
            </div>

            <div>
                <img src="{{ asset('assets/images/cat-women.png') }}" alt="Women">
                <span>Women</span>
            </div>

            <div>
                <img src="{{ asset('assets/images/cat-kids.png') }}" alt="Kids">
                <span>Kids</span>
            </div>

            <div>
                <img src="{{ asset('assets/images/cat-kids.png') }}" alt="Kids">
                <span>Kids</span>
            </div>

        @endforelse

    </div>

    <section class="zara-hero">
        <img src="{{ $brandHeroImage }}" alt="{{ $brand->name }} clothing collection">
    </section>

    <section class="zara-toolbar">

        <a href="#zaraFilter">
            <i class="bi bi-sliders"></i> Filter
        </a>

        <span>Sort By:</span>

        <button type="button">
            {{ $currentSort }} <i class="bi bi-chevron-down"></i>
        </button>

    </section>

    <h2 class="zara-count">
        {{ $products->total() }}+ Products
    </h2>

    <section class="zara-products">

        @forelse($products as $product)

            @php
                $discount = null;

                if ($product->compare_at_price && $product->compare_at_price > $product->price) {
                    $discount = round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100);
                }

                $colours = is_array($product->available_colours)
                    ? array_slice($product->available_colours, 0, 3)
                    : [];

                $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
            @endphp

            <article class="zara-product">

                <a href="{{ route('frontend.products.show', $product) }}">
                    <img src="{{ $productImage($product) }}" alt="{{ $product->name }}">
                </a>

                <form action="{{ route('frontend.wishlist.toggle', $product) }}" method="POST" data-wishlist-toggle data-wishlist-product="{{ $product->id }}" class="wishlist-card-form">
                    @csrf
                    <button type="submit" class="wishlist-floating-btn {{ $isWishlisted ? 'active' : '' }}" aria-label="Toggle {{ $product->name }} wishlist">
                        <i class="bi {{ $isWishlisted ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                    </button>
                </form>

                <div>

                    <h3>{{ $product->name }}</h3>

                    <small>{{ optional($product->brand)->name ?? $brand->name }}</small>

                    <p>
                        <b>
                            <i class="bi bi-star-fill"></i> 4.3
                        </b>
                        <em>120 Rating</em>
                    </p>

                    <strong>Rs{{ number_format($product->price, 0) }}</strong>

                    @if($product->compare_at_price)
                        <del>Rs{{ number_format($product->compare_at_price, 0) }}</del>
                    @endif

                    @if($discount)
                        <mark>{{ $discount }}% OFF</mark>
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
                <p>{{ $brand->name }} brand ke products abhi available nahi hain.</p>
            </div>

        @endforelse

    </section>

    @if($products->hasPages())
    <div class="vastra-pagination">

        {{-- Previous --}}
        @if($products->onFirstPage())
            <span class="page-btn disabled">
                <i class="bi bi-chevron-left"></i>
            </span>
        @else
            <a class="page-btn" href="{{ $products->appends(request()->query())->previousPageUrl() }}">
                <i class="bi bi-chevron-left"></i>
            </a>
        @endif

        {{-- Page Numbers --}}
        @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)

            @if($page == $products->currentPage())
                <span class="page-number active">{{ $page }}</span>
            @else
                <a class="page-number" href="{{ $products->appends(request()->query())->url($page) }}">
                    {{ $page }}
                </a>
            @endif

        @endforeach

        {{-- Next --}}
        @if($products->hasMorePages())
            <a class="page-btn" href="{{ $products->appends(request()->query())->nextPageUrl() }}">
                <i class="bi bi-chevron-right"></i>
            </a>
        @else
            <span class="page-btn disabled">
                <i class="bi bi-chevron-right"></i>
            </span>
        @endif

    </div>
@endif
<style>
    .vastra-pagination {
    width: 100%;
    padding: 20px 16px 90px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.vastra-pagination .page-btn,
.vastra-pagination .page-number {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: 1px solid rgba(17, 17, 17, 0.12);
    background: #fff;
    color: #111;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 14px;
    font-weight: 700;
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.07);
}

.vastra-pagination .page-number.active {
    background: #111;
    color: #fff;
    border-color: #111;
}

.vastra-pagination .page-btn.disabled {
    opacity: 0.35;
    pointer-events: none;
}

@media (max-width: 480px) {
    .vastra-pagination {
        gap: 7px;
        padding-bottom: 95px;
    }

    .vastra-pagination .page-btn,
    .vastra-pagination .page-number {
        width: 34px;
        height: 34px;
        font-size: 13px;
    }
}
</style>

</main>

@endsection

@section('filter_sheet')

<section class="brand-filter-sheet" id="zaraFilter">

    <div class="filter-sheet-head">
        <h2>Filter By Category</h2>
        <a href="#" aria-label="Close filter">
            <i class="bi bi-x-lg"></i>
        </a>
    </div>

    <div class="filter-group">

        <a class="{{ request('audience') ? '' : 'selected' }}"
           href="{{ route('frontend.brands.show', $brand) }}">
            <span></span>
            All
            <i class="bi bi-chevron-right"></i>
        </a>

        @foreach($audiences as $audience)
            <a class="{{ request('audience') === $audience->slug ? 'selected' : '' }}"
               href="{{ route('frontend.brands.show', [$brand, 'audience' => $audience->slug]) }}">
                <span></span>
                {{ $audience->name }}
                <i class="bi bi-chevron-right"></i>
            </a>
        @endforeach

    </div>

    <h2 class="filter-title">Sort Products</h2>

    <div class="filter-group">
        <a href="{{ route('frontend.brands.show', [$brand, 'sort' => 'popular', 'audience' => request('audience')]) }}">
            <span></span>Popularity <i class="bi bi-chevron-right"></i>
        </a>

        <a href="{{ route('frontend.brands.show', [$brand, 'sort' => 'latest', 'audience' => request('audience')]) }}">
            <span></span>Latest <i class="bi bi-chevron-right"></i>
        </a>

        <a href="{{ route('frontend.brands.show', [$brand, 'sort' => 'price_low', 'audience' => request('audience')]) }}">
            <span></span>Price Low to High <i class="bi bi-chevron-right"></i>
        </a>

        <a href="{{ route('frontend.brands.show', [$brand, 'sort' => 'price_high', 'audience' => request('audience')]) }}">
            <span></span>Price High to Low <i class="bi bi-chevron-right"></i>
        </a>
    </div>

    <h2 class="filter-title">Price Range</h2>

    <div class="filter-group">
        <a href="#zaraFilter"><span></span>Under Rs999 <i class="bi bi-chevron-right"></i></a>
        <a href="#zaraFilter"><span></span>Rs500 - Rs999 <i class="bi bi-chevron-right"></i></a>
        <a href="#zaraFilter"><span></span>Rs1000 - Rs1999 <i class="bi bi-chevron-right"></i></a>
        <a href="#zaraFilter"><span></span>Rs2000 - Rs2999 <i class="bi bi-chevron-right"></i></a>
        <a href="#zaraFilter"><span></span>Rs3000 and above <i class="bi bi-chevron-right"></i></a>
    </div>

    <div class="filter-actions">
        <a href="{{ route('frontend.brands.show', $brand) }}">CLEAR ALL</a>
        <a href="#">APPLY FILTER</a>
    </div>

</section>

@endsection
