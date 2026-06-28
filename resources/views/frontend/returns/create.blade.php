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

    <title>Return Request - Vastra Express</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        .return-form-card,
        .return-reason-card {
            margin: 14px 16px;
            padding: 16px;
            border-radius: 22px;
            background: #fff;
            box-shadow: 0 14px 36px rgba(0,0,0,.08);
        }

        .return-form-card h2,
        .return-reason-card h2 {
            margin: 0 0 14px;
            font-size: 17px;
        }

        .return-item-check {
            display: flex;
            gap: 12px;
            padding: 12px 0;
            border-top: 1px solid rgba(0,0,0,.08);
        }

        .return-item-check:first-of-type {
            border-top: 0;
        }

        .return-item-check input {
            margin-top: 26px;
        }

        .return-item-check img {
            width: 74px;
            height: 92px;
            border-radius: 14px;
            object-fit: cover;
            background: #f4f4f4;
        }

        .return-item-check h3 {
            margin: 0 0 4px;
            font-size: 15px;
        }

        .return-item-check p {
            margin: 0 0 5px;
            font-size: 13px;
            color: #666;
        }

        .return-item-check b {
            font-size: 13px;
        }

        .return-reason-card label {
            display: block;
            margin-bottom: 12px;
            font-size: 13px;
            font-weight: 800;
        }

        .return-reason-card select,
        .return-reason-card textarea {
            width: 100%;
            border: 1px solid rgba(0,0,0,.12);
            border-radius: 14px;
            padding: 12px;
            margin-top: 7px;
            outline: none;
            font-size: 13px;
        }

        .return-reason-card textarea {
            min-height: 95px;
            resize: vertical;
        }

        .return-bottom {
            position: sticky;
            bottom: 0;
            z-index: 20;
            background: #fff;
            padding: 14px 16px 18px;
            border-radius: 22px 22px 0 0;
            box-shadow: 0 -12px 34px rgba(0,0,0,.12);
        }

        .return-bottom button {
            width: 100%;
            min-height: 54px;
            border: 0;
            border-radius: 18px;
            background: #111;
            color: #fff;
            font-size: 15px;
            font-weight: 900;
            cursor: pointer;
        }

        .return-error {
            margin: 12px 16px;
            padding: 12px;
            border-radius: 14px;
            background: #fff0f0;
            color: #d62525;
            font-size: 13px;
            font-weight: 800;
        }
    </style>
</head>

<body class="orders-page">

    <div class="site-wrap">

        <div class="phone-status">
            <span>9:41</span>

            <span class="phone-status-icons">
                <i class="bi bi-reception-4"></i>
                <i class="bi bi-wifi"></i>
                <i class="bi bi-battery-full"></i>
            </span>
        </div>

        <header class="orders-header">
            <a href="{{ route('frontend.orders.show', $order) }}">
                <i class="bi bi-chevron-left"></i>
            </a>

            <h1>Return Request</h1>
        </header>

        <main class="orders-list">

            @if($errors->any())
                <div class="return-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('frontend.returns.store', $order) }}">
                @csrf

                <section class="return-form-card">
                    <h2>Select Items</h2>

                    @foreach($order->items as $item)

                        @php
                            $product = $item->product;

                            $productImage = $product
                                ? $imageUrl($product->image_path)
                                : asset('assets/images/order-shirt.png');
                        @endphp

                        <label class="return-item-check">
                            <input type="checkbox" name="item_ids[]" value="{{ $item->id }}">

                            <img src="{{ $productImage }}" alt="{{ $item->product_name }}">

                            <div>
                                <h3>{{ $item->product_name }}</h3>

                                <p>
                                    {{ $item->brand_name ?? 'Vastra Express' }}
                                    |
                                    Size: {{ $item->size ?? 'Free Size' }}
                                    |
                                    Qty: {{ $item->qty }}
                                </p>

                                <b>Refund Amount: Rs{{ number_format($item->line_total, 0) }}</b>
                            </div>
                        </label>

                    @endforeach
                </section>

                <section class="return-reason-card">
                    <h2>Return Details</h2>

                    <label>
                        Return Reason

                        <select name="reason" required>
                            <option value="">Select Reason</option>
                            <option value="size_issue">Size Issue</option>
                            <option value="damaged_product">Damaged Product</option>
                            <option value="wrong_item">Wrong Item Received</option>
                            <option value="quality_issue">Quality Issue</option>
                            <option value="other">Other</option>
                        </select>
                    </label>

                    <label>
                        Refund Method

                        <select name="refund_method" required>
                            <option value="cash">Cash Refund</option>
                            <option value="upi">UPI Refund</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="wallet">VE Wallet</option>
                        </select>
                    </label>

                    <label>
                        Description Optional

                        <textarea name="description" placeholder="Write issue details...">{{ old('description') }}</textarea>
                    </label>
                </section>

                <footer class="return-bottom">
                    <button type="submit">
                        Submit Return Request
                    </button>
                </footer>

            </form>

        </main>

    </div>

</body>

</html>