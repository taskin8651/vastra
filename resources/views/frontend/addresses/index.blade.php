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
</head>

<body class="address-page">

    <div class="site-wrap">

        <div class="phone-status">
            <span>9:41</span>

            <span class="phone-status-icons">
                <i class="bi bi-reception-4"></i>
                <i class="bi bi-wifi"></i>
                <i class="bi bi-battery-full"></i>
            </span>
        </div>

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

                @foreach($addresses as $address)

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

                @endforeach

            </section>

        </main>

    </div>

</body>

</html>
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

.address-type label {
    display: inline-flex;
    cursor: pointer;
}

.address-type input {
    display: none;
}

.address-type input:checked + b {
    background: #111;
    color: #fff;
    border-color: #111;
}

#detectLocationBtn {
    cursor: pointer;
    white-space: nowrap;
}

.checkout-notice {
    margin: 12px 16px;
}
</style>