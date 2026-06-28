<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Order Success - Vastra Express</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        .success-box {
            margin: 40px 16px 18px;
            padding: 28px 18px;
            border-radius: 24px;
            background: #fff;
            text-align: center;
            box-shadow: 0 18px 45px rgba(0,0,0,.08);
        }

        .success-box i {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #0d9f4f;
            color: #fff;
            font-size: 38px;
            margin-bottom: 14px;
        }

        .success-box h1 {
            font-size: 25px;
            margin: 0 0 8px;
        }

        .success-box p {
            color: #666;
            line-height: 1.6;
        }

        .success-box a {
            display: inline-flex;
            margin-top: 16px;
            padding: 13px 22px;
            border-radius: 999px;
            background: #111;
            color: #fff;
            text-decoration: none;
            font-weight: 800;
        }

        .success-items {
            margin: 14px 16px;
            padding: 16px;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 12px 34px rgba(0,0,0,.08);
        }

        .success-items h2 {
            font-size: 17px;
            margin: 0 0 12px;
        }

        .success-items article {
            border-top: 1px solid rgba(0,0,0,.08);
            padding: 10px 0;
        }

        .success-items article:first-of-type {
            border-top: 0;
        }

        .success-items strong {
            display: block;
            font-size: 14px;
        }

        .success-items small {
            color: #666;
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
                <i class="bi bi-heart"></i>
                <i class="bi bi-bag"></i>
            </div>
        </header>

        <main>

            <section class="success-box">
                <i class="bi bi-check-lg"></i>

                <h1>Order Placed Successfully</h1>

                <p>
                    Order Number<br>
                    <b>{{ $order->order_number }}</b>
                </p>

                <p>
                    Total Payable:
                    <b>Rs{{ number_format($order->total_payable, 0) }}</b>
                </p>

                <p>
                    Payment:
                    <b>{{ strtoupper($order->payment_method) }}</b>
                </p>

                <p>
                    Status:
                    <b>{{ ucfirst(str_replace('_', ' ', $order->order_status)) }}</b>
                </p>

                <a href="{{ url('/') }}">
                    Continue Shopping
                </a>
                <a href="{{ route('frontend.orders.index') }}">
    View My Orders
</a>    
            </section>

            <section class="success-items">
                <h2>Order Items</h2>

                @foreach($order->items as $item)
                    <article>
                        <strong>{{ $item->product_name }}</strong>
                        <small>
                            {{ $item->brand_name }}
                            |
                            Qty: {{ $item->qty }}
                            |
                            Rs{{ number_format($item->line_total, 0) }}
                        </small>
                    </article>
                @endforeach
            </section>

        </main>

    </div>

</body>

</html>