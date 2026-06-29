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

if (trendingProducts && !trendingProducts.dataset.serverProducts) {
  trendingProducts.innerHTML = products.slice(0, 2).map(productCard).join("");
}

if (topPickProducts && !topPickProducts.dataset.serverProducts) {
  topPickProducts.innerHTML = products.map(productCard).join("");
}

let cartItems = 0;
const cartCount = document.querySelector(".cart-count");
const toastElement = document.querySelector("#cartToast");
const cartToast = toastElement && window.bootstrap ? new bootstrap.Toast(toastElement, { delay: 1800 }) : null;

function showCartToast(message = "Product added to cart.") {
  if (!toastElement) {
    return;
  }

  const toastBody = toastElement.querySelector(".toast-body");
  if (toastBody) {
    toastBody.textContent = message;
  }

  if (cartToast) {
    cartToast.show();
    return;
  }

  toastElement.classList.add("show");
  toastElement.style.display = "block";
  clearTimeout(toastElement._hideTimer);
  toastElement._hideTimer = setTimeout(() => {
    toastElement.classList.remove("show");
    toastElement.style.display = "none";
  }, 1800);
}

toastElement?.querySelector("[data-bs-dismiss='toast']")?.addEventListener("click", () => {
  if (cartToast) {
    cartToast.hide();
    return;
  }

  toastElement.classList.remove("show");
  toastElement.style.display = "none";
});

function setCartCount(count) {
  document.querySelectorAll(".cart-count").forEach((countElement) => {
    countElement.textContent = count;
    countElement.classList.toggle("show", Number(count) > 0);
  });
}

function updateCartCount() {
  const savedCart = JSON.parse(localStorage.getItem("vastra-cart") || "[]");
  cartItems = savedCart.reduce((total, item) => total + item.quantity, 0);
  setCartCount(cartItems);
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
  if (wishlistButton && !wishlistButton.closest("[data-wishlist-toggle]")) {
    wishlistButton.classList.toggle("active");
    const icon = wishlistButton.querySelector("i");
    icon.classList.toggle("bi-heart");
    icon.classList.toggle("bi-heart-fill");
  }

  const cartButton = event.target.closest(".add-cart-btn");
  if (cartButton) {
    const cartForm = cartButton.closest("form");
    if (cartForm && isCartAddForm(cartForm)) {
      return;
    }

    addProductToCart();

    const originalText = cartButton.innerHTML;
    cartButton.innerHTML = '<i class="bi bi-check2"></i> Added';
    cartButton.disabled = true;

    setTimeout(() => {
      cartButton.innerHTML = originalText;
      cartButton.disabled = false;
    }, 1100);

    showCartToast();
  }

  const productCardElement = event.target.closest("[data-product-card]");
  if (productCardElement && !event.target.closest("a, .add-cart-btn, .wishlist-btn, [data-wishlist-toggle]")) {
    const productUrl = productCardElement.dataset.productUrl || productCardElement.querySelector("a")?.href || "product.html";
    window.location.href = productUrl;
  }
});

function isCartAddForm(form) {
  return form.action && form.action.includes("/cart/add/");
}

document.addEventListener("submit", async (event) => {
  const form = event.target;

  if (event.defaultPrevented) {
    return;
  }

  if (!(form instanceof HTMLFormElement) || !isCartAddForm(form)) {
    return;
  }

  event.preventDefault();

  const submitButton = event.submitter || form.querySelector("button[type='submit'], .add-cart-btn, .shirt-add, .wishlist-bag-btn");
  const originalButtonHtml = submitButton ? submitButton.innerHTML : "";

  if (submitButton) {
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="bi bi-check2"></i> Added';
  }

  try {
    const response = await fetch(form.action, {
      method: "POST",
      headers: {
        "Accept": "application/json",
        "X-Requested-With": "XMLHttpRequest"
      },
      body: new FormData(form)
    });

    if (!response.ok) {
      throw new Error("Cart request failed");
    }

    const data = await response.json();

    if (typeof data.cart_count !== "undefined") {
      setCartCount(data.cart_count);
    }

    showCartToast(data.message || "Product added to cart.");
  } catch (error) {
    form.submit();
    return;
  } finally {
    if (submitButton) {
      setTimeout(() => {
        submitButton.innerHTML = originalButtonHtml;
        submitButton.disabled = false;
      }, 900);
    }
  }
});

function setWishlistButtonState(button, wishlisted) {
  const icon = button.querySelector("i");

  button.classList.toggle("active", wishlisted);

  if (icon) {
    icon.classList.toggle("bi-heart", !wishlisted);
    icon.classList.toggle("bi-heart-fill", wishlisted);
  }
}

function setWishlistProductState(form, wishlisted) {
  const productId = form.dataset.wishlistProduct;
  const forms = productId
    ? document.querySelectorAll(`[data-wishlist-toggle][data-wishlist-product="${productId}"]`)
    : [form];

  forms.forEach((wishlistForm) => {
    const button = wishlistForm.querySelector("button");
    if (button) {
      setWishlistButtonState(button, wishlisted);
    }
  });
}

document.addEventListener("click", (event) => {
  const wishlistButton = event.target.closest("[data-wishlist-toggle] button");

  if (wishlistButton) {
    event.stopPropagation();
  }
});

document.addEventListener("submit", async (event) => {
  const form = event.target;

  if (!(form instanceof HTMLFormElement) || !form.matches("[data-wishlist-toggle]")) {
    return;
  }

  event.preventDefault();

  const button = form.querySelector("button");
  const wasActive = button?.classList.contains("active") || false;

  if (button) {
    setWishlistProductState(form, !wasActive);
    button.disabled = true;
  }

  try {
    const response = await fetch(form.action, {
      method: "POST",
      headers: {
        "Accept": "application/json",
        "X-Requested-With": "XMLHttpRequest"
      },
      body: new FormData(form)
    });

    if (!response.ok) {
      throw new Error("Wishlist request failed");
    }

    const data = await response.json();

    if (button) {
      setWishlistProductState(form, Boolean(data.wishlisted));
    }
  } catch (error) {
    if (button) {
      setWishlistProductState(form, wasActive);
    }

    form.submit();
    return;
  } finally {
    if (button) {
      button.disabled = false;
    }
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
      submitProductSearch(searchInput);
    }
  });
});

function submitProductSearch(searchInput) {
  const query = searchInput.value.trim();
  const searchUrl = searchInput.dataset.searchUrl || "/search";

  if (!query) {
    searchInput.focus();
    return;
  }

  window.location.href = `${searchUrl}?q=${encodeURIComponent(query)}`;
}

document.querySelectorAll("#productSearch").forEach((searchInput) => {
  searchInput.addEventListener("keydown", (event) => {
    if (event.key === "Enter") {
      event.preventDefault();
      submitProductSearch(searchInput);
    }
  });
});

document.querySelectorAll(".search-modal .input-group-text").forEach((searchIcon) => {
  searchIcon.addEventListener("click", () => {
    const searchInput = searchIcon.closest(".input-group")?.querySelector("#productSearch");

    if (searchInput) {
      submitProductSearch(searchInput);
    }
  });
});
