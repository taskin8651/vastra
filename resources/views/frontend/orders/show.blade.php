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

    $statusLabel = match ($order->order_status) {
        'placed' => 'Order Placed',
        'confirmed' => 'Confirmed',
        'packed' => 'Packed',
        'out_for_delivery' => 'Shipped',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
        default => ucfirst(str_replace('_', ' ', $order->order_status)),
    };

    $statusClass = match ($order->order_status) {
        'delivered' => 'delivered',
        'cancelled' => 'cancelled',
        'out_for_delivery' => 'shipped',
        default => 'shipped',
    };

    $steps = [
        'placed' => 'Placed',
        'confirmed' => 'Confirmed',
        'packed' => 'Packed',
        'out_for_delivery' => 'Shipped',
        'delivered' => 'Delivered',
    ];

    $statusOrder = [
        'placed' => 1,
        'confirmed' => 2,
        'packed' => 3,
        'out_for_delivery' => 4,
        'delivered' => 5,
    ];

    $currentStep = $statusOrder[$order->order_status] ?? 0;

    $activeReturnRequest = null;

    if (method_exists($order, 'returnRequests')) {
        $activeReturnRequest = $order->returnRequests()
            ->whereNotIn('status', ['rejected'])
            ->latest()
            ->first();
    }
@endphp

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="theme-color" content="#fff">

    <title>Order Details - Vastra Express</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        .order-track-card,
        .order-address-card,
        .order-price-card,
        .order-action-card,
        .order-payment-card {
            margin: 14px 16px;
            padding: 16px;
            border-radius: 22px;
            background: #fff;
            box-shadow: 0 14px 36px rgba(0,0,0,.08);
        }

        .order-track-card h2,
        .order-address-card h2,
        .order-price-card h2,
        .order-payment-card h2 {
            margin: 0 0 14px;
            font-size: 17px;
        }

        .order-track-list {
            display: grid;
            gap: 12px;
        }

        .track-step {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #999;
            font-size: 14px;
            font-weight: 700;
        }

        .track-step i {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #eee;
            color: #999;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .track-step.done {
            color: #111;
        }

        .track-step.done i {
            background: #0d9f4f;
            color: #fff;
        }

        .track-step.cancelled {
            color: #d62525;
        }

        .track-step.cancelled i {
            background: #d62525;
            color: #fff;
        }

        .order-address-card p,
        .order-payment-card p {
            margin: 0;
            color: #555;
            font-size: 13px;
            line-height: 1.6;
        }

        .order-price-card p,
        .order-price-card h3 {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            gap: 12px;
        }

        .order-price-card h3 {
            font-size: 17px;
            padding-top: 10px;
            border-top: 1px solid rgba(0,0,0,.08);
        }

        .cancel-order-btn {
            width: 100%;
            border: 0;
            border-radius: 16px;
            padding: 13px 16px;
            background: #fff0f0;
            color: #d62525;
            font-size: 14px;
            font-weight: 900;
            cursor: pointer;
        }

        .continue-shopping-btn,
        .return-request-btn,
        .return-status-btn {
            width: 100%;
            min-height: 50px;
            border-radius: 16px;
            background: #111;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            border: 0;
        }

        .return-request-btn {
            background: #0d9f4f;
        }

        .return-status-btn {
            background: #f1f5f9;
            color: #111;
        }

        .order-message {
            margin: 12px 16px;
            padding: 12px 14px;
            border-radius: 16px;
            background: #effaf3;
            color: #0d7f3d;
            font-size: 13px;
            font-weight: 800;
        }

        .order-danger-message {
            margin: 12px 16px;
            padding: 12px 14px;
            border-radius: 16px;
            background: #fff0f0;
            color: #d62525;
            font-size: 13px;
            font-weight: 800;
        }
    </style>
</head>

<body class="orders-page">

    <div class="site-wrap">

        <header class="orders-header">
            <a href="{{ route('frontend.orders.index') }}">
                <i class="bi bi-chevron-left"></i>
            </a>

            <h1>Order Details</h1>
        </header>

        <main class="orders-list">

            @if(session('message'))
                <p class="order-message">
                    <i class="bi bi-check-circle"></i>
                    {{ session('message') }}
                </p>
            @endif

            @if($errors->any())
                <p class="order-danger-message">
                    <i class="bi bi-info-circle"></i>
                    {{ $errors->first() }}
                </p>
            @endif

            <article class="order-card">

                <div class="order-top">
                    <div>
                        <h2>Order ID: {{ $order->order_number }}</h2>

                        <p>
                            Placed on {{ $order->created_at->format('d M, Y') }}
                        </p>
                    </div>

                    <strong class="{{ $statusClass }}">
                        {{ $statusLabel }}
                    </strong>
                </div>

                @foreach($order->items as $item)

                    @php
                        $product = $item->product;

                        $productImage = $product && $product->image_url
                            ? $product->image_url
                            : asset('assets/images/order-shirt.png');

                        $audienceName = optional(optional(optional($product)->category)->audience)->name;
                        $fitType = optional($product)->fit_type;
                        $colour = $item->colour ?: optional($product)->colour;

                        $productLine = trim(
                            ($audienceName ? $audienceName . ' ' : '') .
                            ($colour ? $colour . ' ' : '') .
                            ($fitType ?: '')
                        );
                    @endphp

                    <div class="order-product">
                        <img src="{{ $productImage }}" alt="{{ $item->product_name }}">

                        <div>
                            <h3>{{ $item->product_name }}</h3>

                            <p>
                                {{ $productLine ?: ($item->category_name ?? 'Vastra Express Product') }}
                            </p>

                            <b>
                                Size: {{ $item->size ?? 'Free Size' }}
                                /
                                Qty: {{ $item->qty }}
                            </b>

                            <small>
                                {{ $item->brand_name ?? 'Vastra Express' }}
                            </small>
                        </div>

                        <strong>
                            Rs{{ number_format($item->line_total, 0) }}
                        </strong>
                    </div>

                @endforeach

            </article>

            <section class="order-track-card">
                <h2>Order Tracking</h2>

                <div class="order-track-list">

                    @if($order->order_status === 'cancelled')

                        <div class="track-step cancelled">
                            <i class="bi bi-x-lg"></i>
                            <span>Order Cancelled</span>
                        </div>

                    @else

                        @foreach($steps as $key => $label)
                            <div class="track-step {{ ($statusOrder[$key] ?? 0) <= $currentStep ? 'done' : '' }}">
                                <i class="bi bi-check-lg"></i>
                                <span>{{ $label }}</span>
                            </div>
                        @endforeach

                    @endif

                </div>
            </section>

            <section class="order-payment-card">
                <h2>Payment & Delivery</h2>

                <p>
                    <strong>Payment Method:</strong>
                    {{ strtoupper($order->payment_method) }}<br>

                    <strong>Payment Status:</strong>
                    {{ ucfirst($order->payment_status) }}<br>

                    <strong>Delivery Method:</strong>
                    {{ ucfirst(str_replace('_', ' ', $order->delivery_method)) }}
                </p>
            </section>

            <section class="order-address-card">
                <h2>Delivery Address</h2>

                <p>
                    <strong>{{ $order->shipping_full_name }}</strong><br>
                    +{{ $order->shipping_mobile }}<br>

                    {{ $order->shipping_flat_house }},
                    {{ $order->shipping_area_street }},

                    @if($order->shipping_landmark)
                        {{ $order->shipping_landmark }},
                    @endif

                    {{ $order->shipping_city }},
                    {{ $order->shipping_state }} -
                    {{ $order->shipping_pincode }}
                </p>
            </section>

            <section class="order-price-card">
                <h2>Price Details</h2>

                <p>
                    <span>Total MRP</span>
                    <b>Rs{{ number_format($order->total_mrp, 0) }}</b>
                </p>

                <p>
                    <span>Discount</span>
                    <b>-Rs{{ number_format($order->discount, 0) }}</b>
                </p>

                <p>
                    <span>Subtotal</span>
                    <b>Rs{{ number_format($order->subtotal, 0) }}</b>
                </p>

                <p>
                    <span>Home Trial Fee</span>
                    <b>Rs{{ number_format($order->home_trial_fee, 0) }}</b>
                </p>

                <p>
                    <span>Platform Fee</span>
                    <b>Rs{{ number_format($order->platform_fee, 0) }}</b>
                </p>

                <p>
                    <span>Delivery Charge</span>
                    <b>
                        {{ $order->delivery_charge > 0 ? 'Rs' . number_format($order->delivery_charge, 0) : 'FREE' }}
                    </b>
                </p>

                <h3>
                    <span>Total Payable</span>
                    <b>Rs{{ number_format($order->total_payable, 0) }}</b>
                </h3>
            </section>

            <section class="order-action-card">

                @if($order->order_status === 'delivered')

                    @if($activeReturnRequest)

                        <a href="javascript:void(0)" class="return-status-btn">
                            Return Request:
                            {{ ucfirst(str_replace('_', ' ', $activeReturnRequest->status)) }}
                        </a>

                    @else

                        <a href="{{ route('frontend.returns.create', $order) }}" class="return-request-btn">
                            Request Return / Refund
                        </a>

                    @endif

                @elseif(! in_array($order->order_status, ['delivered', 'cancelled']))

                    <form action="{{ route('frontend.orders.cancel', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <button type="submit"
                                class="cancel-order-btn"
                                onclick="return confirm('Are you sure you want to cancel this order?')">
                            Cancel Order
                        </button>
                    </form>

                @else

                    <a href="{{ url('/') }}" class="continue-shopping-btn">
                        Continue Shopping
                    </a>

                @endif

            </section>

        </main>

    </div>

</body>

</html>
