@php
    use Illuminate\Support\Str;

    $deliveryMethod = $deliveryMethod ?? session('delivery_method');

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
@endphp

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="theme-color" content="#fff">

    <title>My Cart - Vastra Express</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        .cart-qty-control {
            display: inline-flex !important;
            align-items: center;
            gap: 6px;
        }

        .cart-qty-control form {
            display: inline-flex;
            margin: 0;
        }

        .cart-qty-control button {
            width: 24px;
            height: 24px;
            border: 0;
            border-radius: 50%;
            background: #111;
            color: #fff;
            font-size: 14px;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .cart-trash-btn {
            border: 0;
            background: transparent;
            padding: 0;
            color: inherit;
            cursor: pointer;
        }

        .checkout-products article > a {
            display: block;
        }

        .checkout-products article > a img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .delivery-card form {
            margin-top: 12px;
        }

        .delivery-select-btn {
            width: 100%;
            border: 0;
            border-radius: 14px;
            padding: 12px 14px;
            background: #111;
            color: #fff;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            text-align: center;
        }

        .delivery-card.selected .delivery-select-btn {
            background: #0d9f4f;
        }

        .disabled-checkout-link {
            opacity: 0.7;
            pointer-events: auto;
        }

        .delivery-required-alert {
            margin: 12px 16px;
        }

        .checkout-bottom > a {
            display: flex !important;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-width: 0;
            padding: 0 10px;
            white-space: nowrap;
            line-height: 1.2;
            text-align: center;
        }

        .checkout-bottom > a i {
            flex: 0 0 auto;
            margin: 0;
            line-height: 1;
        }
    </style>
</head>

<body class="checkout-page">

    <div class="site-wrap">

        <div class="phone-status">
            <span>8:00</span>

            <span class="phone-status-icons">
                <i class="bi bi-reception-4"></i>
                <i class="bi bi-wifi"></i>
                <i class="bi bi-battery-full"></i>
            </span>
        </div>

        <header class="checkout-header">

            <a href="{{ url('/') }}">
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
                <a href="{{ route('frontend.wishlist.index') }}">
    <i class="bi bi-heart"></i>
</a>
                <i class="bi bi-bag"></i>
            </div>

        </header>

        <nav class="checkout-steps">
            <span>1<small>Cart</small></span>
            <span>2<small>Address</small></span>
            <span>3<small>Delivery</small></span>
            <span>4<small>Payment</small></span>
        </nav>

        <main>

            <h1>
                My Cart ({{ $totalItems }} Items)

                <a href="{{ url('/shop/men') }}">
                    <i class="bi bi-tag"></i>
                    Edit Cart
                </a>
            </h1>

            @if(session('success'))
                <p class="checkout-notice">
                    <i class="bi bi-check-circle"></i>
                    {{ session('success') }}
                </p>
            @endif

            {{-- CART PRODUCTS --}}
            <section class="checkout-products">

                @forelse($items as $item)

                    @php
                        $product = $item['product'];

                        $itemDiscount = null;

                        if ($item['mrp'] > $item['price']) {
                            $itemDiscount = round((($item['mrp'] - $item['price']) / $item['mrp']) * 100);
                        }

                        $productLink = route('frontend.products.show', $product);
                    @endphp

                    <article>

                        <a href="{{ $productLink }}">
                            <img src="{{ $imageUrl($product->image_path) }}" alt="{{ $product->name }}">
                        </a>

                        <div>
                            <small>VASTRA EXPRESS</small>

                            <h2>
                                <a href="{{ $productLink }}" style="color:inherit;text-decoration:none;">
                                    {{ $product->name }}
                                </a>
                            </h2>

                            <b>{{ optional($product->brand)->name ?? 'Vastra' }}</b>

                            <p>
                                <i style="background: {{ $item['colour'] ?? '#111' }}"></i>
                                {{ $product->colour ?? $item['colour'] ?? 'Black' }}
                                &nbsp;
                                {{ $item['size'] ?? 'Free Size' }}
                            </p>

                            <span class="cart-qty-control">

                                <form action="{{ route('frontend.cart.update', $item['key']) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden" name="qty" value="{{ max(0, $item['qty'] - 1) }}">

                                    <button type="submit">-</button>
                                </form>

                                &nbsp; {{ $item['qty'] }} &nbsp;

                                <form action="{{ route('frontend.cart.update', $item['key']) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden" name="qty" value="{{ $item['qty'] + 1 }}">

                                    <button type="submit">+</button>
                                </form>

                            </span>
                        </div>

                        <aside>
                            <strong>Rs{{ number_format($item['line_total'], 0) }}</strong>

                            @if($item['line_mrp'] > $item['line_total'])
                                <del>Rs{{ number_format($item['line_mrp'], 0) }}</del>
                            @endif

                            @if($itemDiscount)
                                <mark>{{ $itemDiscount }}%OFF</mark>
                            @endif

                            <form action="{{ route('frontend.cart.remove', $item['key']) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="cart-trash-btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </aside>

                    </article>

                @empty

                    <article>
                        <div>
                            <small>VASTRA EXPRESS</small>
                            <h2>Your cart is empty</h2>
                            <b>Add products to continue checkout</b>
                            <p>Explore Men, Women, Kids and Accessories collection.</p>
                        </div>

                        <aside>
                            <a href="{{ url('/shop/men') }}" class="shirt-add">
                                SHOP NOW
                            </a>
                        </aside>
                    </article>

                @endforelse

            </section>

            <section class="checkout-coupon">
                <i class="bi bi-ticket-perforated"></i>

                <div>
                    <strong>Apply Coupon</strong>
                    <small>Save extra on this order</small>
                </div>

                <i class="bi bi-chevron-right"></i>
            </section>

            <p class="checkout-notice">
                <i class="bi bi-info-circle"></i>
                Home Trial is available only on <b>Online Payment</b> orders
            </p>

            {{-- DELIVERY OPTION: HOME TRIAL --}}
            <section id="deliveryOptions" class="delivery-card {{ $deliveryMethod === 'home_trial' ? 'selected' : '' }}">
                <h2>
                    Home Trial
                    <span>Trial Fee <b>Rs29</b></span>
                </h2>

                <p>
                    Style Runner waits 15-20 minutes to decide<br>
                    Try the product at your home<br>
                    Instant refund within 15 minutes
                </p>

                <div>✓ If you love it, keep it and make the payment.</div>

                <form action="{{ route('frontend.cart.delivery-method') }}" method="POST">
                    @csrf

                    <input type="hidden" name="delivery_method" value="home_trial">

                    <button type="submit" class="delivery-select-btn">
                        {{ $deliveryMethod === 'home_trial' ? '✓ Home Trial Selected' : 'Select Home Trial' }}
                    </button>
                </form>
            </section>

            {{-- DELIVERY OPTION: STANDARD --}}
            <section class="delivery-card {{ $deliveryMethod === 'standard' ? 'selected' : '' }}">
                <h2>
                    Standard Delivery
                    <span><b>No Trial Fee</b></span>
                </h2>

                <p>
                    Cash on Delivery Available<br>
                    Open Box Delivery &nbsp; Hassle Free Pickup<br>
                    30 Min Easy Return &nbsp; Refund within 7 working days
                </p>

                <form action="{{ route('frontend.cart.delivery-method') }}" method="POST">
                    @csrf

                    <input type="hidden" name="delivery_method" value="standard">

                    <button type="submit" class="delivery-select-btn">
                        {{ $deliveryMethod === 'standard' ? '✓ Standard Delivery Selected' : 'Select Standard Delivery' }}
                    </button>
                </form>
            </section>

            @if($totalItems > 0 && ! $deliveryMethod)
                <p class="checkout-notice delivery-required-alert">
                    <i class="bi bi-info-circle"></i>
                    Please select Home Trial or Standard Delivery before continuing.
                </p>
            @endif

            {{-- PRICE DETAILS --}}
            <section class="price-details">

                <h2>Price Details</h2>

                <p>
                    Total MRP ({{ $totalItems }} ITEMS)
                    <b>Rs{{ number_format($totalMrp, 0) }}</b>
                </p>

                <p>
                    Discount on MRP
                    <b class="red">-Rs{{ number_format($discount, 0) }}</b>
                </p>

                <p>
                    Home Trial Fee<br>
                    Non-Refundable
                    <b>Rs{{ number_format($homeTrialFee, 0) }}</b>
                </p>

                <p>
                    Platform Fee
                    <b>Rs{{ number_format($platformFee, 0) }}</b>
                </p>

                <p>
                    Delivery Charged
                    <b class="green">
                        {{ $deliveryCharge > 0 ? 'Rs' . number_format($deliveryCharge, 0) : 'FREE' }}
                    </b>
                </p>

                <hr>

                <h3>
                    Total Payable
                    <b>Rs{{ number_format($totalPayable, 0) }}</b>
                </h3>

                <small>✓ Inclusive of all applicable tax</small>

            </section>

            <section class="checkout-trial-note">
                <b>HOME TRIAL Rs{{ number_format($homeTrialFee, 0) }}</b>

                <p>
                    Applicable on Prepaid Orders Only<br>
                    Non-Refundable<br>
                    Added to final invoice<br>
                    Style Runner waits 15-20 Minutes
                </p>

                <p>
                    Why Home Trial?<br>
                    Try at your doorstep, pay only for what you keep.
                </p>
            </section>

            <section class="checkout-trust">
                <span>60 min<br><small>Delivery</small></span>
                <span>60 min<br><small>Returns Policy</small></span>
                <span>Home Trial<br><small>Try before you buy</small></span>
                <span>Secure<br><small>Payment</small></span>
            </section>

        </main>

        <footer class="checkout-bottom">

            <div>
                <strong>Rs{{ number_format($totalPayable, 0) }}</strong>
                <small>View Details</small>
            </div>

            @if($totalItems > 0 && $deliveryMethod)
                <a href="{{ route('frontend.address.index') }}">
                    Select Address
                    <i class="bi bi-chevron-right"></i>
                </a>
            @elseif($totalItems > 0 && ! $deliveryMethod)
                <a href="#deliveryOptions" class="disabled-checkout-link">
                    Select Delivery First
                    <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <a href="{{ url('/shop/men') }}">
                    Add Items
                    <i class="bi bi-chevron-right"></i>
                </a>
            @endif

            <p>
                <i class="bi bi-shield-check"></i>
                Safe &amp; secure payment. your data is always protable
            </p>

        </footer>

    </div>

</body>

</html>
