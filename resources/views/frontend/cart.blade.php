<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#ffffff">
  <title>Bag - Vastra Express</title>
  <link rel="preconnect" href="https://cdn.jsdelivr.net">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="cart-page">
  <div class="site-wrap">
    <div class="phone-status" aria-hidden="true"><span>8:00</span><span class="phone-status-icons"><i class="bi bi-reception-4"></i><i class="bi bi-wifi"></i><i class="bi bi-battery-full"></i></span></div>
    <header class="detail-header"><a class="detail-icon" href="index.html" aria-label="Back"><i class="bi bi-arrow-left"></i></a><strong>My Bag <span id="bagCount"></span></strong><a class="detail-icon" href="index.html" aria-label="Continue shopping"><i class="bi bi-shop"></i></a></header>
    <main id="cartContent"></main>
    <div class="cart-checkout" id="checkoutBar"><span><small>Total</small><strong id="cartTotal">Rs. 0</strong></span><button type="button">Continue</button></div>
  </div>
  <script src="assets/js/cart.js"></script>
</body>
</html>
