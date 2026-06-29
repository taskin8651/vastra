<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#fff">
    <title>Customer Support - Vastra Express</title>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        .support-hero {
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 14px;
            background: #fdf2ff;
        }

        .support-hero p {
            margin: 0 0 8px;
            color: #c12eff;
            font-size: 13px;
            font-weight: 700;
        }

        .support-hero h2 {
            margin: 0 0 10px;
            color: #17191e;
            font-size: 23px;
            line-height: 1.2;
        }

        .support-hero span {
            color: #555;
            font-size: 13px;
            line-height: 1.5;
        }

        .support-list {
            margin-bottom: 18px;
        }

        .support-list a {
            text-decoration: none;
        }

        .support-list small {
            display: block;
            margin-top: 4px;
            color: #777;
            font-size: 11px;
            font-weight: 500;
        }

        .support-actions {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin: 18px 0;
        }

        .support-actions a {
            min-height: 74px;
            display: grid;
            place-items: center;
            gap: 6px;
            padding: 10px;
            border: 1px solid #f0d5f8;
            border-radius: 10px;
            color: #c12eff;
            text-align: center;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
        }

        .support-actions i {
            font-size: 24px;
        }

        .support-policy {
            display: grid;
            gap: 10px;
            margin-top: 18px;
        }

        .support-policy a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            border-radius: 10px;
            background: #f7f7f8;
            color: #20232a;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
        }
    </style>
</head>

<body class="support-page">
    <div class="site-wrap">
        <header class="simple-page-header">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('frontend.home') }}">
                <i class="bi bi-chevron-left"></i>
            </a>
            <h1>Customer Support</h1>
        </header>

        <main>
            <section class="support-hero">
                <p>VASTRA EXPRESS HELP CENTER</p>
                <h2>How can we help you?</h2>
                <span>Track orders, manage returns, solve payment issues, or connect with our support team.</span>
            </section>

            <section class="support-list">
                <a href="{{ route('frontend.orders.index') }}">
                    <i class="bi bi-box-seam"></i>
                    <span>Orders &amp; Tracking<small>View order status, invoice and delivery updates</small></span>
                    <b class="bi bi-chevron-right"></b>
                </a>

                <a href="{{ route('frontend.orders.index') }}">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    <span>Returns &amp; Refunds<small>Start return request from your delivered orders</small></span>
                    <b class="bi bi-chevron-right"></b>
                </a>

                <a href="{{ route('frontend.checkout.payment') }}">
                    <i class="bi bi-credit-card"></i>
                    <span>Payment Issues<small>Payment methods, failed payments and COD help</small></span>
                    <b class="bi bi-chevron-right"></b>
                </a>

                <a href="{{ route('frontend.trials.index') }}">
                    <i class="bi bi-house-heart"></i>
                    <span>Home Trial Queries<small>Trial orders, fees and doorstep experience</small></span>
                    <b class="bi bi-chevron-right"></b>
                </a>

                <a href="{{ route('frontend.size-guide') }}">
                    <i class="bi bi-rulers"></i>
                    <span>Product &amp; Sizing<small>Find the right size before you order</small></span>
                    <b class="bi bi-chevron-right"></b>
                </a>

                <a href="{{ route('frontend.address.index') }}">
                    <i class="bi bi-geo-alt"></i>
                    <span>Address Help<small>Add, edit or select your delivery address</small></span>
                    <b class="bi bi-chevron-right"></b>
                </a>

                <a href="{{ route('frontend.cart.index') }}">
                    <i class="bi bi-bag"></i>
                    <span>Cart &amp; Checkout<small>Review bag, delivery option and final total</small></span>
                    <b class="bi bi-chevron-right"></b>
                </a>

                <a href="{{ route('frontend.wishlist.index') }}">
                    <i class="bi bi-heart"></i>
                    <span>Wishlist Help<small>Manage saved products and move items to bag</small></span>
                    <b class="bi bi-chevron-right"></b>
                </a>
            </section>

            <section class="support-actions" id="contact">
                <a href="mailto:support@vastraexpress.com">
                    <i class="bi bi-envelope"></i>
                    Email
                </a>

                <a href="tel:01237483424">
                    <i class="bi bi-telephone"></i>
                    Call
                </a>

                <a href="{{ route('frontend.search') }}">
                    <i class="bi bi-search"></i>
                    Search
                </a>
            </section>

            <section class="support-chat" id="chat">
                <p>Still need help?</p>
                <h3>Chat with us</h3>
                <span>We typically reply in a few minutes</span>
                <i class="bi bi-chat-dots"></i>
            </section>

            <section class="support-call">
                <h3>Need Help?</h3>
                <strong>Call us at 0123-748-3424</strong>
                <p>Mon-Sun 9AM-9PM</p>
                <i class="bi bi-telephone"></i>
            </section>

            <section class="support-policy">
                <a href="{{ route('frontend.support') }}#terms">
                    Terms &amp; Conditions
                    <i class="bi bi-chevron-right"></i>
                </a>
                <a href="{{ route('frontend.support') }}#privacy">
                    Privacy Policy
                    <i class="bi bi-chevron-right"></i>
                </a>
                <a href="{{ route('frontend.support') }}#refunds">
                    Refund Policy
                    <i class="bi bi-chevron-right"></i>
                </a>
            </section>
        </main>
    </div>
</body>

</html>
