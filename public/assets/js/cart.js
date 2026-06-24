const price = 1350;
const content = document.querySelector("#cartContent");
const checkoutBar = document.querySelector("#checkoutBar");
const total = document.querySelector("#cartTotal");
const bagCount = document.querySelector("#bagCount");

function readCart() { return JSON.parse(localStorage.getItem("vastra-cart") || "[]"); }
function money(value) { return `Rs. ${value.toLocaleString("en-IN")}`; }

function renderCart() {
  const cart = readCart();
  const item = cart.find((entry) => entry.id === "linen-blend-shirt");
  const quantity = item ? item.quantity : 0;
  bagCount.textContent = quantity ? `(${quantity})` : "";
  total.textContent = money(quantity * price);
  checkoutBar.style.display = quantity ? "flex" : "none";
  content.innerHTML = quantity ? `
    <section class="cart-delivery"><i class="bi bi-truck"></i><span><strong>Delivery in 60 mins</strong><small>Fast delivery to your doorstep</small></span></section>
    <section class="cart-items"><article class="cart-item"><img src="assets/images/product-man.png" alt="Linen Blend Shirt"><div class="cart-item-info"><p>ZARA</p><h1>Linen Blend Shirt</h1><span>Pink &middot; Size M</span><strong>${money(price)}</strong><div class="cart-item-actions"><div class="quantity-control"><button type="button" data-change="-1" aria-label="Decrease quantity"><i class="bi bi-dash"></i></button><b>${quantity}</b><button type="button" data-change="1" aria-label="Increase quantity"><i class="bi bi-plus"></i></button></div><button class="remove-btn" type="button">Remove</button></div></div></article></section>
    <section class="cart-summary"><div><span>Subtotal</span><b>${money(quantity * price)}</b></div><div><span>Delivery fee</span><b class="free">Free</b></div><div class="summary-total"><span>Total</span><b>${money(quantity * price)}</b></div></section>
    <section class="cart-secure"><i class="bi bi-shield-check"></i> Safe and secure payments</section>` : `
    <section class="empty-bag"><div><i class="bi bi-bag"></i></div><h1>Your bag is empty</h1><p>Add the styles you love, then come back here.</p><a href="index.html">Start shopping</a></section>`;
}

content.addEventListener("click", (event) => {
  const change = event.target.closest("[data-change]");
  const remove = event.target.closest(".remove-btn");
  const cart = readCart();
  const item = cart.find((entry) => entry.id === "linen-blend-shirt");
  if (change && item) item.quantity += Number(change.dataset.change);
  if (remove && item) item.quantity = 0;
  localStorage.setItem("vastra-cart", JSON.stringify(cart.filter((entry) => entry.quantity > 0)));
  renderCart();
});

renderCart();
