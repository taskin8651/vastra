@php
    use Illuminate\Support\Str;

    $tab = $tab ?? request('tab', 'upcoming');

    $imageUrl = function ($path, $fallback = 'assets/images/trial-look-one.png') {
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

    <title>My Trial - Vastra Express</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        .trial-empty {
            margin: 18px 16px;
            padding: 28px 18px;
            border-radius: 24px;
            background: #fff;
            text-align: center;
            box-shadow: 0 16px 42px rgba(0,0,0,.08);
        }

        .trial-empty i {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #f4f0ff;
            color: #6d35ff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-bottom: 12px;
        }

        .trial-empty h2 {
            margin: 0 0 8px;
            font-size: 21px;
        }

        .trial-empty p {
            margin: 0 0 16px;
            color: #666;
            font-size: 14px;
        }

        .trial-empty a {
            display: inline-flex;
            padding: 12px 20px;
            border-radius: 999px;
            background: #111;
            color: #fff;
            text-decoration: none;
            font-weight: 800;
        }

        .trial-images img {
            object-fit: cover;
        }

        .trial-pagination {
            padding: 16px;
        }
    </style>
</head>

<body class="trial-page">

    <div class="site-wrap">

        <header class="trial-header">
            <a href="{{ url('/') }}">
                <i class="bi bi-chevron-left"></i>
            </a>

            <h1>My Trail</h1>
        </header>

        <nav class="trial-tabs">
            <a class="{{ $tab === 'upcoming' ? 'active' : '' }}"
               href="{{ route('frontend.trials.index', ['tab' => 'upcoming']) }}">
                Upcoming
            </a>

            <a class="{{ $tab === 'past' ? 'active' : '' }}"
               href="{{ route('frontend.trials.index', ['tab' => 'past']) }}">
                Past
            </a>
        </nav>

        <main>

            <h2 class="trial-how-title">How Home Trail Works?</h2>

            <section class="trial-how">
                <p>
                    <i class="bi bi-star-fill"></i>
                    We deliver your selected items
                </p>

                <p>
                    <i class="bi bi-star-fill"></i>
                    Try at your doorstep
                </p>

                <p>
                    <i class="bi bi-star-fill"></i>
                    Pay Only for what you keep
                </p>

                <p>
                    <i class="bi bi-star-fill"></i>
                    Return the rest
                </p>
            </section>

            @forelse($trials as $trial)

                @php
                    $items = $trial->items;

                    $firstItem = $items->get(0);
                    $secondItem = $items->get(1);

                    $firstProduct = optional($firstItem)->product;
                    $secondProduct = optional($secondItem)->product;

                    $firstImage = $firstProduct && $firstProduct->image_url
                        ? $firstProduct->image_url
                        : asset('assets/images/trial-look-one.png');

                    $secondImage = $secondProduct && $secondProduct->image_url
                        ? $secondProduct->image_url
                        : asset('assets/images/trial-look-two.png');

                    $deliveryBy = $trial->created_at->copy()->addMinutes(60);

                    $trialStatusText = match ($trial->order_status) {
                        'placed' => 'Order Placed',
                        'confirmed' => 'Confirmed',
                        'packed' => 'Preparing',
                        'out_for_delivery' => 'Runner On The Way',
                        'delivered' => 'Completed',
                        'cancelled' => 'Cancelled',
                        default => ucfirst(str_replace('_', ' ', $trial->order_status)),
                    };
                @endphp

                <section class="trial-card">

                    <div class="trial-card-head">
                        <div>
                            <h2>Trial ID: {{ $trial->order_number }}</h2>

                            <p>
                                Delivery by:
                                {{ $deliveryBy->format('d M, Y h:i A') }}
                            </p>
                        </div>

                        <b>
                            {{ $items->sum('qty') }} Items
                        </b>
                    </div>

                    <div class="trial-images">
                        <img src="{{ $firstImage }}" alt="Trial item one">

                       
                    </div>

                    <div class="trial-summary">
                        <span>
                            Total Items
                            <strong>{{ $items->sum('qty') }}</strong>
                        </span>

                        <span>
                            Trial Fee
                            <strong>Rs{{ number_format($trial->home_trial_fee, 0) }}</strong>
                            <small>(Non-Refundable)</small>
                        </span>
                    </div>

                    <div class="trial-summary" style="margin-top:10px;">
                        <span>
                            Status
                            <strong>{{ $trialStatusText }}</strong>
                        </span>

                        <span>
                            Payable
                            <strong>Rs{{ number_format($trial->total_payable, 0) }}</strong>
                        </span>
                    </div>

                    <a href="{{ route('frontend.orders.show', $trial) }}">
                        View Details
                    </a>

                </section>

            @empty

                <section class="trial-empty">
                    <i class="bi bi-house-heart"></i>

                    <h2>No Trial Found</h2>

                    <p>
                        Abhi aapka koi {{ $tab === 'past' ? 'past' : 'upcoming' }} home trial order nahi hai.
                    </p>

                    <a href="{{ url('/shop/men') }}">
                        Start Shopping
                    </a>
                </section>

            @endforelse

            @if($trials->hasPages())
                <div class="trial-pagination">
                    {{ $trials->appends(request()->query())->links() }}
                </div>
            @endif

            <section class="trial-help">
                <h2>Need Help ?</h2>

                <strong>Call us at 0123-748-3424998</strong>

                <p>Mon-Sun 9AM-9PM</p>

                <i class="bi bi-telephone"></i>
            </section>

        </main>

    </div>

</body>

</html>
