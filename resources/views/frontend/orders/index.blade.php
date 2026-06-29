@php
    use Illuminate\Support\Str;

    $status = $status ?? request('status', 'all');

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

    $statusText = function ($orderStatus) {
        return match ($orderStatus) {
            'placed' => 'Processing',
            'confirmed' => 'Processing',
            'packed' => 'Processing',
            'out_for_delivery' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            default => ucfirst(str_replace('_', ' ', $orderStatus)),
        };
    };

    $statusClass = function ($orderStatus) {
        return match ($orderStatus) {
            'delivered' => 'delivered',
            'cancelled' => 'cancelled',
            'out_for_delivery' => 'shipped',
            default => 'shipped',
        };
    };
@endphp

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="theme-color" content="#fff">

    <title>My Order - Vastra Express</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        .orders-empty {
            margin: 18px 16px;
            padding: 24px 18px;
            border-radius: 22px;
            background: #fff;
            text-align: center;
            box-shadow: 0 14px 36px rgba(0,0,0,.08);
        }

        .orders-empty h2 {
            margin: 0 0 8px;
            font-size: 20px;
        }

        .orders-empty p {
            margin: 0 0 16px;
            color: #666;
            font-size: 14px;
        }

        .orders-empty a {
            display: inline-flex;
            padding: 12px 20px;
            border-radius: 999px;
            background: #111;
            color: #fff;
            text-decoration: none;
            font-weight: 800;
        }

        .orders-pagination {
            padding: 16px 16px 28px;
        }

        .orders-pagination nav {
            display: flex;
            justify-content: center;
        }

        .orders-tabs {
            width: 100%;
            max-width: 100%;
            display: flex;
            align-items: stretch;
            gap: 18px;
            overflow-x: auto;
            overflow-y: hidden;
            padding: 0 16px;
            scroll-snap-type: x proximity;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .orders-tabs::-webkit-scrollbar {
            display: none;
        }

        .orders-tabs a {
            flex: 0 0 auto;
            min-width: max-content;
            padding: 0 2px;
            scroll-snap-align: start;
            white-space: nowrap;
        }
    </style>
</head>

<body class="orders-page">

    <div class="site-wrap">

        <header class="orders-header">
            <a href="{{ url('/') }}">
                <i class="bi bi-chevron-left"></i>
            </a>

            <h1>My Order</h1>
        </header>

        <nav class="orders-tabs">
            <a class="{{ $status === 'all' ? 'active' : '' }}"
               href="{{ route('frontend.orders.index') }}">
                All
            </a>

            <a class="{{ $status === 'processing' ? 'active' : '' }}"
               href="{{ route('frontend.orders.index', ['status' => 'processing']) }}">
                Processing
            </a>

            <a class="{{ $status === 'shipped' ? 'active' : '' }}"
               href="{{ route('frontend.orders.index', ['status' => 'shipped']) }}">
                Shipped
            </a>

            <a class="{{ $status === 'delivered' ? 'active' : '' }}"
               href="{{ route('frontend.orders.index', ['status' => 'delivered']) }}">
                Delivered
            </a>

            <a class="{{ $status === 'cancelled' ? 'active' : '' }}"
               href="{{ route('frontend.orders.index', ['status' => 'cancelled']) }}">
                Cancelled
            </a>
        </nav>

        <main class="orders-list">

            @forelse($orders as $order)

                <article class="order-card">

                    <div class="order-top">
                        <div>
                            <h2>Order ID: {{ $order->order_number }}</h2>

                            <p>
                                Placed on {{ $order->created_at->format('d M, Y') }}
                            </p>
                        </div>

                        <strong class="{{ $statusClass($order->order_status) }}">
                            {{ $statusText($order->order_status) }}
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
                            $productLine = trim(($audienceName ? $audienceName . ' ' : '') . ($colour ? $colour . ' ' : '') . ($fitType ?: ''));
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

                                @if($order->order_status === 'delivered')
                                    <small>
                                        Delivered on {{ $order->updated_at->format('d M, Y') }}
                                    </small>
                                @elseif($order->order_status === 'cancelled')
                                    <small>
                                        Cancelled on {{ $order->updated_at->format('d M, Y') }}
                                    </small>
                                @elseif($order->order_status === 'out_for_delivery')
                                    <small>
                                        Your order is out for delivery
                                    </small>
                                @else
                                    <small>
                                        Order is being processed
                                    </small>
                                @endif
                            </div>

                            <strong>
                                Rs{{ number_format($item->line_total, 0) }}
                            </strong>

                        </div>

                    @endforeach

                    <a class="order-detail" href="{{ route('frontend.orders.show', $order) }}">
                        View Details
                    </a>

                </article>

            @empty

                <section class="orders-empty">
                    <h2>No Orders Found</h2>

                    <p>
                        Abhi tak aapka koi order nahi hai.
                    </p>

                    <a href="{{ url('/shop/men') }}">
                        Start Shopping
                    </a>
                </section>

            @endforelse

            @if($orders->hasPages())
                <div class="orders-pagination">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @endif

        </main>

    </div>

</body>

</html>
