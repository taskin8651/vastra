@php
    $isEdit = $address->exists;
@endphp

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="theme-color" content="#fff">

    <title>{{ $isEdit ? 'Edit Address' : 'Add New Address' }} - Vastra Express</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body class="address-form-page">

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

            <a href="{{ route('frontend.address.index') }}">
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

        <nav class="checkout-steps">
            <span>1<small>Cart</small></span>
            <span>2<small>Address</small></span>
            <span>3<small>Delivery</small></span>
            <span>4<small>Payment</small></span>
        </nav>

        <main>

            <h1>{{ $isEdit ? 'Edit Address' : 'Add New Address' }}</h1>

            @if($errors->any())
                <p class="checkout-notice">
                    <i class="bi bi-info-circle"></i>
                    Please check all required fields.
                </p>
            @endif

            <form method="POST"
                  action="{{ $isEdit ? route('frontend.address.update', $address) : route('frontend.address.store') }}">

                @csrf

                @if($isEdit)
                    @method('PUT')
                @endif

                <section>
                    <h2>Contact Information</h2>

                    <label>
                        Full Name
                        <input name="full_name"
                               value="{{ old('full_name', $address->full_name) }}"
                               placeholder="Enter Full name">
                    </label>

                    <label>
                        Mobile Number
                        <input name="mobile"
                               value="{{ old('mobile', $address->mobile) }}"
                               placeholder="Enter 10 digit mobile number">
                    </label>
                </section>

                <section>
                    <h2>Address Details</h2>

                    <label>
                        Pin code
                        <span class="input-wrap">
                            <input name="pincode"
                                   value="{{ old('pincode', $address->pincode) }}"
                                   placeholder="Enter 6 digit pincode">

                            <b type="button" id="detectLocationBtn">
                                <i class="bi bi-pin-map"></i>
                                Detect Location
                            </b>
                        </span>

                        <small id="locationStatus">
                            Location optional hai. Permission na dene par manually address fill karein.
                        </small>
                    </label>

                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $address->latitude) }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $address->longitude) }}">

                    <label>
                        Flat, House No., Building, Company, Apartment*
                        <input name="flat_house"
                               value="{{ old('flat_house', $address->flat_house) }}"
                               placeholder="Enter building name / number">

                        <small>Example: 302, Shree Heights, 12th Cross</small>
                    </label>

                    <label>
                        Area, Street, Sector, Village*
                        <input name="area_street"
                               value="{{ old('area_street', $address->area_street) }}"
                               placeholder="Enter area, street, sector or village">

                        <small>Example: 12th Cross, Koramangala</small>
                    </label>

                    <label>
                        Landmark Optional
                        <input name="landmark"
                               value="{{ old('landmark', $address->landmark) }}"
                               placeholder="Enter nearby landmark">

                        <small>Example: Near Koramangala Police Station</small>
                    </label>

                    <div class="form-city">

                        <label>
                            City / District*
                            <input name="city"
                                   value="{{ old('city', $address->city) }}"
                                   placeholder="Enter city or district">
                        </label>

                        <label>
                            State*
                            <span class="input-wrap">
                                <input name="state"
                                       value="{{ old('state', $address->state) }}"
                                       placeholder="Select state">

                                <i class="bi bi-chevron-down"></i>
                            </span>
                        </label>

                    </div>

                    <label>Address Type</label>

                    <div class="address-type">

                        <label>
                            <input type="radio"
                                   name="address_type"
                                   value="home"
                                   {{ old('address_type', $address->address_type ?: 'home') === 'home' ? 'checked' : '' }}>
                            <b><i class="bi bi-house"></i> Home</b>
                        </label>

                        <label>
                            <input type="radio"
                                   name="address_type"
                                   value="work"
                                   {{ old('address_type', $address->address_type) === 'work' ? 'checked' : '' }}>
                            <b><i class="bi bi-briefcase"></i> Work</b>
                        </label>

                        <label>
                            <input type="radio"
                                   name="address_type"
                                   value="other"
                                   {{ old('address_type', $address->address_type) === 'other' ? 'checked' : '' }}>
                            <b><i class="bi bi-stars"></i> Other</b>
                        </label>

                    </div>

                    <label style="margin-top:14px;">
                        <input type="checkbox" name="is_default" value="1" {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                        Make this my default delivery address
                    </label>

                </section>

                <footer>
                    <div>
                        Save this address for future use
                        <input type="checkbox" name="save_for_future" value="1" checked hidden>
                        <i></i>
                    </div>

                    <button type="submit">
                        Save Address
                    </button>

                    <a href="{{ route('frontend.address.index') }}">
                        Cancel
                    </a>
                </footer>

            </form>

        </main>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const detectBtn = document.getElementById('detectLocationBtn');
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const statusText = document.getElementById('locationStatus');

            if (!detectBtn) return;

            detectBtn.addEventListener('click', function () {
                if (!navigator.geolocation) {
                    statusText.innerText = 'Your browser does not support location. Please fill address manually.';
                    return;
                }

                statusText.innerText = 'Getting your location...';

                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        latInput.value = position.coords.latitude;
                        lngInput.value = position.coords.longitude;

                        statusText.innerText = 'Location detected successfully. Please complete address details manually.';
                    },
                    function () {
                        statusText.innerText = 'Location permission denied. Please fill address manually.';
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            });
        });
    </script>

</body>

</html>