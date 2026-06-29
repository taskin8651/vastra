@php
    use Illuminate\Support\Str;

    $imageUrl = function ($path, $fallback = 'assets/images/order-shirt.png') {
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
@endphp

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="theme-color" content="#fff">

    <title>My Wishlist - Vastra Express</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        .wishlist-actions form {
            margin: 0;
        }

        .wishlist-heart-btn {
            border: 0;
            background: transparent;
            color: inherit;
            padding: 0;
            cursor: pointer;
        }

        .wishlist-heart-btn i {
            color: #e60023;
        }

        .wishlist-bag-btn {
            border: 0;
            background: #111;
            color: #fff;
            border-radius: 999px;
            padding: 9px 13px;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .wishlist-empty {
            margin: 18px 16px;
            padding: 28px 18px;
            border-radius: 24px;
            background: #fff;
            text-align: center;
            box-shadow: 0 16px 40px rgba(0,0,0,.08);
        }

        .wishlist-empty i {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #fff0f4;
            color: #e60023;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-bottom: 12px;
        }

        .wishlist-empty h2 {
            margin: 0 0 8px;
            font-size: 22px;
        }

        .wishlist-empty p {
            margin: 0 0 16px;
            color: #666;
            font-size: 14px;
        }

        .wishlist-empty a {
            display: inline-flex;
            padding: 12px 20px;
            border-radius: 999px;
            background: #111;
            color: #fff;
            text-decoration: none;
            font-weight: 800;
        }

        .wishlist-message {
            margin: 12px 16px;
            padding: 12px 14px;
            border-radius: 16px;
            background: #effaf3;
            color: #0d7f3d;
            font-size: 13px;
            font-weight: 800;
        }
    </style>
</head>

<body class="wishlist-page">

    <div class="site-wrap">

        <div class="phone-status">
            <span>9:41</span>

            <span class="phone-status-icons">
                <i class="bi bi-reception-4"></i>
                <i class="bi bi-wifi"></i>
                <i class="bi bi-battery-full"></i>
            </span>
        </div>

        <header class="wishlist-header">
            <a href="{{ url('/') }}">
                <i class="bi bi-chevron-left"></i>
            </a>

            <h1>My Wishlist({{ $wishlists->count() }})</h1>

            <a href="{{ route('frontend.cart.index') }}">
                <i class="bi bi-bag"></i>
            </a>
        </header>

        <main class="wishlist-list">

            @if(session('success'))
                <p class="wishlist-message">
                    <i class="bi bi-check-circle"></i>
                    {{ session('success') }}
                </p>
            @endif

            @forelse($wishlists as $wishlist)

                @php
                    $product = $wishlist->product;

                    if (! $product) {
                        continue;
                    }

                    $productImage = $imageUrl($product->image_path);

                    $audienceName = optional(optional($product->category)->audience)->name;
                    $colour = $product->colour;
                    $fitType = $product->fit_type;

                    $line = trim(
                        ($audienceName ? $audienceName . ' ' : '') .
                        ($colour ? $colour . ' ' : '') .
                        ($fitType ?: '')
                    );

                    $sizes = is_array($product->available_sizes) ? $product->available_sizes : [];
                    $defaultSize = $sizes[0] ?? null;

                    $defaultColour = $product->colour;

                    $productLink = route('frontend.products.show', $product);
                @endphp

                <article class="wishlist-item">

                    <a href="{{ $productLink }}">
                        <img src="{{ $productImage }}" alt="{{ $product->name }}">
                    </a>

                    <div>
                        <h2>
                            <a href="{{ $productLink }}" style="color:inherit;text-decoration:none;">
                                {{ $product->name }}
                            </a>
                        </h2>

                        <p>
                            {{ $line ?: optional($product->category)->name ?? 'Vastra Express Product' }}
                        </p>

                        <b>
                            Size: {{ $defaultSize ?? 'Free Size' }}
                            /
                            Qty: 1
                        </b>

                        <strong>
                            Rs{{ number_format($product->price, 0) }}
                        </strong>
                    </div>

                    <div class="wishlist-actions">

                        <form action="{{ route('frontend.wishlist.destroy', $product) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="wishlist-heart-btn">
                                <i class="bi bi-heart-fill"></i>
                            </button>
                        </form>

                        <form action="{{ route('frontend.cart.add', $product) }}" method="POST">
                            @csrf

                            <input type="hidden" name="qty" value="1">

                            @if($defaultSize)
                                <input type="hidden" name="size" value="{{ $defaultSize }}">
                            @endif

                            @if($defaultColour)
                                <input type="hidden" name="colour" value="{{ $defaultColour }}">
                            @endif

                            <button type="submit" class="wishlist-bag-btn">
                                Add to Bag
                            </button>
                        </form>

                    </div>

                </article>

            @empty

                <section class="wishlist-empty">
                    <i class="bi bi-heart"></i>

                    <h2>Your wishlist is empty</h2>

                    <p>
                        Favourite products save karne ke liye heart icon par click karein.
                    </p>

                    <a href="{{ url('/shop/men') }}">
                        Start Shopping
                    </a>
                </section>

            @endforelse

        </main>

    </div>

    <div class="toast-container position-fixed end-0 p-3">
        <div id="cartToast" class="toast align-items-center text-bg-dark border-0" role="status" aria-live="polite" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">Product added to cart.</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>
