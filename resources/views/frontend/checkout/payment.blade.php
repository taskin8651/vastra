<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="theme-color" content="#fff">

    <title>Payment Methods - Vastra Express</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        .payment-cards article.disabled {
            opacity: 0.45;
            pointer-events: none;
        }

        .payment-cards article label {
            display: flex;
            align-items: center;
            width: 100%;
            gap: 12px;
            cursor: pointer;
        }

        .payment-cards article input[type="radio"] {
            display: none;
        }

        .payment-bottom {
            position: sticky;
            bottom: 0;
            z-index: 20;
            background: #fff;
            padding: 14px 16px 18px;
            border-radius: 22px 22px 0 0;
            box-shadow: 0 -12px 34px rgba(0, 0, 0, 0.12);
        }

        .payment-bottom button {
            width: 100%;
            min-height: 54px;
            border: 0;
            border-radius: 18px;
            background: #111;
            color: #fff;
            font-size: 15px;
            font-weight: 900;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
        }

        .payment-summary-mini {
            margin: 14px 16px;
            padding: 14px;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 12px 34px rgba(0,0,0,.08);
        }

        .payment-summary-mini p {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            font-size: 14px;
        }

        .payment-summary-mini h3 {
            display: flex;
            justify-content: space-between;
            margin: 12px 0 0;
            font-size: 17px;
        }

        .address-mini-card {
            margin: 14px 16px;
            padding: 14px;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 12px 34px rgba(0,0,0,.08);
        }

        .address-mini-card h2 {
            font-size: 16px;
            margin: 0 0 4px;
        }

        .address-mini-card p {
            margin: 8px 0 0;
            color: #555;
            font-size: 13px;
            line-height: 1.5;
        }

        .address-mini-card a {
            float: right;
            color: #111;
            font-weight: 800;
            text-decoration: none;
            font-size: 13px;
        }
    </style>
</head>

<body class="payment-page">

    <div class="site-wrap">

        <header class="simple-page-header">
            <a href="{{ route('frontend.address.index') }}">
                <i class="bi bi-chevron-left"></i>
            </a>

            <h1>Payment Methods</h1>
        </header>

        <main>

            {{-- SELECTED ADDRESS --}}
            <section class="address-mini-card">
                <a href="{{ route('frontend.address.index') }}">Change</a>

                <h2>{{ $address->full_name }}</h2>

                <strong>+{{ $address->mobile }}</strong>

                <p>
                    {{ $address->flat_house }},
                    {{ $address->area_street }},
                    @if($address->landmark)
                        {{ $address->landmark }},
                    @endif
                    {{ $address->city }},
                    {{ $address->state }} - {{ $address->pincode }}
                </p>
            </section>

            {{-- UPI DISABLED --}}
            <div class="payment-section-title">
                <span>UPI</span>
                <a href="javascript:void(0)">
                    <i class="bi bi-plus"></i>
                    Add New
                </a>
            </div>

            <section class="payment-cards">
                <article class="disabled">
                    <b class="gpay">G Pay</b>

                    <div>
                        <h2>Google Pay</h2>
                        <p>Gateway will be added later</p>
                    </div>

                    <i class="radio-dot"></i>
                </article>

                <article class="disabled">
                    <b class="gpay">UPI</b>

                    <div>
                        <h2>PhonePe / UPI</h2>
                        <p>Gateway will be added later</p>
                    </div>

                    <i class="radio-dot"></i>
                </article>
            </section>

            {{-- CARDS DISABLED --}}
            <h2 class="payment-heading">Cards</h2>

            <section class="payment-cards">
                <article class="disabled">
                    <b class="gpay">CARD</b>

                    <div>
                        <h2>Debit / Credit Card</h2>
                        <p>Gateway will be added later</p>
                    </div>

                    <span class="card-meta">
                        VISA<br>
                        Coming Soon
                    </span>
                </article>

                <article class="disabled">
                    <b class="gpay">CARD</b>

                    <div>
                        <h2>Net Banking</h2>
                        <p>Gateway will be added later</p>
                    </div>

                    <span class="card-meta">
                        Bank<br>
                        Coming Soon
                    </span>
                </article>
            </section>

            {{-- WALLET DISABLED --}}
            <h2 class="payment-heading">Wallet</h2>

            <section class="payment-cards">
                <article class="disabled">
                    <i class="bi bi-wallet2 wallet-icon"></i>

                    <div>
                        <h2>VE Wallet</h2>
                        <p>Wallet payment will be added later</p>
                    </div>

                    <span class="card-meta">Balance: 0</span>
                </article>
            </section>

            {{-- COD WORKING --}}
            <form action="{{ route('frontend.checkout.place-order') }}" method="POST">
                @csrf

                <h2 class="payment-heading">Select COD</h2>

                <section class="payment-cards">
                    <article>
                        <label>
                            <input type="radio" name="payment_method" value="cod" checked>

                            <i class="bi bi-wallet2 wallet-icon"></i>

                            <div>
                                <h2>Cash On Delivery</h2>
                                <p>Pay at your doorstep</p>
                            </div>

                            <i class="selected-dot"></i>
                        </label>
                    </article>
                </section>

                <section class="payment-summary-mini">
                    <p>
                        <span>Total MRP</span>
                        <b>Rs{{ number_format($totalMrp, 0) }}</b>
                    </p>

                    <p>
                        <span>Discount</span>
                        <b>-Rs{{ number_format($discount, 0) }}</b>
                    </p>

                    <p>
                        <span>Home Trial Fee</span>
                        <b>Rs{{ number_format($homeTrialFee, 0) }}</b>
                    </p>

                    <p>
                        <span>Delivery</span>
                        <b>{{ $deliveryCharge > 0 ? 'Rs' . number_format($deliveryCharge, 0) : 'FREE' }}</b>
                    </p>

                    <h3>
                        <span>Total Payable</span>
                        <b>Rs{{ number_format($totalPayable, 0) }}</b>
                    </h3>
                </section>

                <section class="payment-security">
                    <i class="bi bi-lock"></i>
                    UPI, Cards &amp; Wallet are saved securely as per RBI guidelines
                </section>

                <footer class="payment-bottom">
                    <button type="submit">
                        Place COD Order
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </footer>

            </form>

        </main>

    </div>

</body>

</html>
