const products = [
  {
    title: "Linen Blend Shirt",
    brand: "ZARA",
    rating: "4.3",
    reviews: "120 Rating",
    price: 1350,
    oldPrice: 1200,
    discount: "40% OFF",
    image: "assets/images/product-man.png"
  },
  {
    title: "Linen Blend Shirt",
    brand: "ZARA",
    rating: "4.3",
    reviews: "120 Rating",
    price: 1350,
    oldPrice: 1200,
    discount: "40% OFF",
    image: "assets/images/product-man.png"
  },
  {
    title: "Premium Casual Shirt",
    brand: "ZARA",
    rating: "4.5",
    reviews: "152 Rating",
    price: 1499,
    oldPrice: 1999,
    discount: "25% OFF",
    image: "assets/images/product-man.png"
  },
  {
    title: "Relaxed Summer Shirt",
    brand: "ZARA",
    rating: "4.4",
    reviews: "98 Rating",
    price: 1299,
    oldPrice: 1699,
    discount: "24% OFF",
    image: "assets/images/product-man.png"
  }
];

const formatPrice = (value) =>
  new Intl.NumberFormat("en-IN", {
    style: "currency",
    currency: "INR",
    maximumFractionDigits: 0
  }).format(value);

function productCard(product) {
  return `
    <article class="product-card" data-product-card>
      <div class="product-media">
        <img src="${product.image}" alt="${product.title}" loading="lazy">
        <button type="button" class="wishlist-btn" aria-label="Add ${product.title} to wishlist">
          <i class="bi bi-heart"></i>
        </button>
      </div>

      <div class="product-body">
        <h3 class="product-title">${product.title}</h3>
        <span class="product-brand">${product.brand}</span>

        <div class="product-meta">
          <span class="rating-badge"><i class="bi bi-star-fill"></i>${product.rating}</span>
          <span class="rating-count">${product.reviews}</span>
        </div>

        <div class="price-row">
          <span class="current-price">${formatPrice(product.price)}</span>
          <span class="old-price">${formatPrice(product.oldPrice)}</span>
          <span class="discount">${product.discount}</span>
        </div>

        <div class="swatches" aria-label="Available colors">
          <span class="swatch pink"></span>
          <span class="swatch black"></span>
          <span class="swatch white"></span>
        </div>

        <button type="button" class="add-cart-btn">
          <i class="bi bi-cart3"></i> Add to cart
        </button>
      </div>
    </article>
  `;
}

const trendingProducts = document.querySelector("#trendingProducts");
const topPickProducts = document.querySelector("#topPickProducts");

if (trendingProducts) {
  trendingProducts.innerHTML = products.slice(0, 2).map(productCard).join("");
}

if (topPickProducts) {
  topPickProducts.innerHTML = products.map(productCard).join("");
}

let cartItems = 0;
const cartCount = document.querySelector(".cart-count");
const toastElement = document.querySelector("#cartToast");
const cartToast = toastElement ? new bootstrap.Toast(toastElement, { delay: 1800 }) : null;

function updateCartCount() {
  const savedCart = JSON.parse(localStorage.getItem("vastra-cart") || "[]");
  cartItems = savedCart.reduce((total, item) => total + item.quantity, 0);
  if (cartCount) {
    cartCount.textContent = cartItems;
    cartCount.classList.toggle("show", cartItems > 0);
  }
}

function addProductToCart() {
  const savedCart = JSON.parse(localStorage.getItem("vastra-cart") || "[]");
  const existing = savedCart.find((item) => item.id === "linen-blend-shirt");
  if (existing) {
    existing.quantity += 1;
  } else {
    savedCart.push({ id: "linen-blend-shirt", quantity: 1 });
  }
  localStorage.setItem("vastra-cart", JSON.stringify(savedCart));
  updateCartCount();
}

updateCartCount();

document.addEventListener("click", (event) => {
  const wishlistButton = event.target.closest(".wishlist-btn");
  if (wishlistButton) {
    wishlistButton.classList.toggle("active");
    const icon = wishlistButton.querySelector("i");
    icon.classList.toggle("bi-heart");
    icon.classList.toggle("bi-heart-fill");
  }

  const cartButton = event.target.closest(".add-cart-btn");
  if (cartButton) {
    addProductToCart();

    const originalText = cartButton.innerHTML;
    cartButton.innerHTML = '<i class="bi bi-check2"></i> Added';
    cartButton.disabled = true;

    setTimeout(() => {
      cartButton.innerHTML = originalText;
      cartButton.disabled = false;
    }, 1100);

    cartToast?.show();
  }

  const productCardElement = event.target.closest("[data-product-card]");
  if (productCardElement && !event.target.closest(".add-cart-btn, .wishlist-btn")) {
    window.location.href = "product.html";
  }
});

document.querySelectorAll(".category-nav a").forEach((link) => {
  link.addEventListener("click", () => {
    document.querySelectorAll(".category-nav a").forEach((item) => item.classList.remove("active"));
    link.classList.add("active");
  });
});

document.querySelectorAll(".bottom-nav a").forEach((link) => {
  link.addEventListener("click", () => {
    document.querySelectorAll(".bottom-nav a").forEach((item) => item.classList.remove("active"));
    link.classList.add("active");
  });
});

document.querySelectorAll(".search-suggestions button").forEach((button) => {
  button.addEventListener("click", () => {
    const searchInput = document.querySelector("#productSearch");
    if (searchInput) {
      searchInput.value = button.textContent.trim();
      searchInput.focus();
    }
  });
});
