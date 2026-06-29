<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="theme-color" content="#fff">

    <title>Order Confirmed - Vastra Express</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body class="confirmed-page">

    <div class="site-wrap">

        <header class="confirmed-header">

            <i class="bi bi-list"></i>

            <a href="{{ url('/') }}" class="brand">
                <span class="brand-mark">
                    <span>V</span>
                </span>

                <span class="brand-name">
                    VASTRA<span>EXPRESS</span>
                </span>
            </a>

            <b>
                <i class="bi bi-shield-check"></i>
                100% Secure<br>Shopping
            </b>

        </header>

        <nav class="confirmed-steps">
            <span>1<small>Cart</small></span>
            <span>2<small>Address</small></span>
            <span>3<small>Delivery</small></span>
            <span>4<small>Payment</small></span>
            <span>5<small>Confirmation</small></span>
        </nav>

        <main>

            <section class="confirmed-title">
                <i class="bi bi-check-lg"></i>

                <div>
                    <h1>Order Confirmed!</h1>
                    <p>Thank you for shopping with Vastra Express.</p>
                </div>
            </section>

            <section class="confirmed-alert">
                <i class="bi bi-balloon"></i>

                <div>
                    <b>Your order is confirmed and on its way!</b>
                    <span>We'll keep you updated at every step.</span>
                </div>
            </section>

            <section class="confirmed-summary">
                <i class="bi bi-card-checklist"></i>

                <div>
                    <small>Order ID</small>

                    <strong>{{ $order->order_number }}</strong>

                    <b>{{ ucfirst(str_replace('_', ' ', $order->order_status)) }}</b>

                    <span>
                        {{ $order->created_at->format('d M Y, h:i A') }}
                    </span>
                </div>

                <div>
                    <p>
                        Payment Method

                        <b>
                            {{ $order->payment_method === 'cod' ? 'Cash On Delivery' : 'Online Payment' }}
                        </b>
                    </p>

                    <p>
                        Payment Status

                        <b class="{{ $order->payment_status === 'paid' ? 'paid' : '' }}">
                            {{ ucfirst($order->payment_status) }}
                        </b>
                    </p>

                    <p>
                        Total Payable

                        <b class="purple">
                            Rs{{ number_format($order->total_payable, 0) }}
                        </b>
                    </p>
                </div>
            </section>

            <section class="next-steps">

                <h2>What happens next?</h2>

                <article>
                    <i class="bi bi-bag-check"></i>

                    <div>
                        <h3>Order Received</h3>
                        <p>{{ $order->created_at->format('d M Y, h:i A') }}</p>
                    </div>

                    <b class="completed">Completed</b>
                </article>

                <article>
                    <i class="bi bi-bag-check"></i>

                    <div>
                        <h3>Order Processing</h3>
                        <p>Your order is being verified and packed.</p>
                    </div>

                    <b class="progress">In Progress</b>
                </article>

                @if($order->delivery_method === 'home_trial')

                    <article>
                        <i class="bi bi-bag-check"></i>

                        <div>
                            <h3>Style Runner Assigned</h3>
                            <p>A Style Runner will be assigned soon.</p>
                        </div>

                        <b>Upcoming</b>
                    </article>

                    <article>
                        <i class="bi bi-bag-check"></i>

                        <div>
                            <h3>Home Trial at Your Doorstep</h3>
                            <p>Try the product at home with 15-20 minutes window.</p>
                        </div>

                        <b>Upcoming</b>
                    </article>

                    <article>
                        <i class="bi bi-bag-check"></i>

                        <div>
                            <h3>Refund if Not Satisfied</h3>
                            <p>Instant refund support after trial as per policy.</p>
                        </div>

                        <b>Upcoming</b>
                    </article>

                @else

                    <article>
                        <i class="bi bi-bag-check"></i>

                        <div>
                            <h3>Order Packed</h3>
                            <p>Your product will be packed and ready for delivery.</p>
                        </div>

                        <b>Upcoming</b>
                    </article>

                    <article>
                        <i class="bi bi-bag-check"></i>

                        <div>
                            <h3>Out For Delivery</h3>
                            <p>You will receive delivery updates soon.</p>
                        </div>

                        <b>Upcoming</b>
                    </article>

                @endif

            </section>

            @if($order->delivery_method === 'home_trial')
                <section class="about-trial">
                    <i class="bi bi-house-heart"></i>

                    <div>
                        <h2>About Home Trial</h2>

                        <p>◉ Style Runner will reach in 60 minutes</p>
                        <p>◉ Try the product at home with the Style Runner</p>
                        <p>◉ Not satisfied? Get instant refund support</p>
                        <p>◉ Rs{{ number_format($order->home_trial_fee, 0) }} Trial Fee is non-refundable</p>
                    </div>
                </section>
            @else
                <section class="about-trial">
                    <i class="bi bi-truck"></i>

                    <div>
                        <h2>About Standard Delivery</h2>

                        <p>◉ Your order will be packed and shipped soon</p>
                        <p>◉ Cash on Delivery is available</p>
                        <p>◉ Easy return as per store policy</p>
                        <p>◉ Delivery charge: {{ $order->delivery_charge > 0 ? 'Rs' . number_format($order->delivery_charge, 0) : 'FREE' }}</p>
                    </div>
                </section>
            @endif

            <section class="confirmed-help">
                <i class="bi bi-headset"></i>

                <div>
                    <h2>Need Help?</h2>
                    <p>Our support team is here for you.</p>
                </div>

                <a href="{{ route('frontend.support') }}#chat">Chat with Us</a>
                <a href="tel:01237483424">Call Us</a>
            </section>

        </main>

        <footer class="confirmed-bottom">

            <div>
                <strong>Rs{{ number_format($order->total_payable, 0) }}</strong>

                <a href="{{ route('frontend.orders.show', $order) }}"
                   style="display:inline-flex;align-items:center;gap:6px;width:max-content;margin-top:6px;color:#111;font-size:12px;font-weight:700;text-decoration:none;line-height:1;white-space:nowrap; background:none;">
                    View Details
                    <i class="bi bi-chevron-right" style="font-size:13px;line-height:1;"></i>
                </a>
            </div>

            <a href="{{ url('/') }}">
                Continue Shopping 
            </a>

        </footer>

    </div>

</body>

</html>
