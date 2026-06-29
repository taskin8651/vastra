<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="theme-color" content="#fff">

    <title>My Address - Vastra Express</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        .address-select-btn,
        .address-delete-btn {
            width: 100%;
            border: 0;
            border-radius: 14px;
            padding: 10px 12px;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
        }

        .address-select-btn {
            background: #111;
            color: #fff;
        }

        .address-card.selected .address-select-btn {
            background: #0d9f4f;
        }

        .address-delete-btn {
            background: #fff0f0;
            color: #d62525;
        }

        .checkout-notice {
            margin: 12px 16px;
        }

        .address-bottom {
            position: sticky;
            bottom: 0;
            z-index: 20;
            background: #fff;
            padding: 14px 16px 18px;
            box-shadow: 0 -12px 34px rgba(0, 0, 0, 0.12);
            border-radius: 22px 22px 0 0;
        }

        .address-bottom a,
        .address-bottom span {
            width: 100%;
            min-height: 52px;
            border-radius: 18px;
            background: #111;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 15px;
            font-weight: 900;
        }

        .address-bottom span {
            background: #999;
        }
    </style>
</head>

<body class="address-page">

    <div class="site-wrap">

        <header class="address-header">
            <a href="{{ route('frontend.cart.index') }}">
                <i class="bi bi-chevron-left"></i>
            </a>

            <h1>My Address</h1>
        </header>

        <main>

            <a class="address-add" href="{{ route('frontend.address.create') }}">
                <i class="bi bi-plus"></i>
                Add
            </a>

            @if(session('message'))
                <p class="checkout-notice">
                    <i class="bi bi-check-circle"></i>
                    {{ session('message') }}
                </p>
            @endif

            <section class="address-list">

                @forelse($addresses as $address)

                    <article class="address-card {{ $selectedAddressId == $address->id ? 'selected' : '' }}">

                        <a href="{{ route('frontend.address.edit', $address) }}">
                            Edit
                        </a>

                        <h2>{{ $address->full_name }}</h2>

                        <strong>+{{ $address->mobile }}</strong>

                        <p>
                            <i></i>
                            {{ $address->flat_house }},<br>
                            {{ $address->area_street }},<br>

                            @if($address->landmark)
                                {{ $address->landmark }},<br>
                            @endif

                            {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}<br>
                            India
                        </p>

                        <form action="{{ route('frontend.address.select', $address) }}" method="POST" style="margin-top:10px;">
                            @csrf

                            <button type="submit" class="address-select-btn">
                                {{ $selectedAddressId == $address->id ? 'Selected Address' : 'Deliver Here' }}
                            </button>
                        </form>

                        <form action="{{ route('frontend.address.destroy', $address) }}" method="POST" style="margin-top:8px;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="address-delete-btn">
                                Delete
                            </button>
                        </form>

                    </article>

                @empty

                    <article class="address-card">
                        <h2>No Address Found</h2>
                        <strong>Please add delivery address</strong>
                        <p><i></i>Add your address to continue checkout.</p>
                    </article>

                @endforelse

            </section>

        </main>

        <footer class="address-bottom">

            @if($selectedAddressId)
                <a href="{{ route('frontend.checkout.payment') }}">
                    Continue to Payment
                    <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <span>
                    Select Address First
                    <i class="bi bi-info-circle"></i>
                </span>
            @endif

        </footer>

    </div>

</body>

</html>
