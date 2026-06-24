const categoryData = {
  men: {
    label: "MEN'S WEAR", intro: "Stylish looks for every occasion.", heroImage: "assets/images/product-man.png", trialImage: "assets/images/promo-large-men.png",
    tiles: [["Shirt", "category-men.png"], ["Formal Shirt", "cat-men.png"], ["Casual Shirt", "category-kids.png"], ["Jeans", "cat-shoe.png"], ["Cargo Pants", "product-man.png"], ["Chinos", "cat-bag.png"], ["Joggers", "cat-men.png"], ["Shorts", "category-men.png"], ["Footwear", "cat-shoe.png"], ["Watch", "cat-watch.png"], ["Activewear", "cat-men.png"], ["Accessories", "cat-bag.png"], ["Shirt", "category-men.png"], ["Formal Shirt", "cat-men.png"], ["Casual Shirt", "category-kids.png"]]
  },
  women: {
    label: "WOMEN'S WEAR", intro: "Trendy styles for every Occasion.", heroImage: "assets/images/category-women.png", trialImage: "assets/images/promo-large-women.png",
    tiles: [["Kurtas", "category-women.png"], ["Ethnic Wear", "promo-small-women.png"], ["Top", "cat-women.png"], ["Shirts", "category-kids.png"], ["T-Shirts", "cat-men.png"], ["Jeans", "cat-shoe.png"], ["Shorts", "cat-shoe.png"], ["Dresses", "category-women.png"], ["Sarees", "promo-small-women.png"], ["Loungewear", "cat-women.png"], ["Activewear", "category-women.png"], ["Accessories", "cat-bag.png"], ["Suit", "cat-women.png"], ["Co-Ord Sets", "category-women.png"], ["Blazer", "cat-men.png"]]
  },
  kids: {
    label: "KIDS WEAR", intro: "Adorable style for Every little moment.", heroImage: "assets/images/category-kids.png", trialImage: "assets/images/promo-large-men.png",
    tiles: [["Shirts", "category-men.png"], ["Top", "category-kids.png"], ["T-shirts", "cat-kids.png"], ["Hoodies", "cat-kids.png"], ["Dresses", "category-women.png"], ["Jeans", "cat-shoe.png"], ["Shorts", "category-kids.png"], ["Ethnic Wear", "promo-small-women.png"], ["Bags", "cat-bag.png"], ["Loungewear", "category-kids.png"], ["Activewear", "cat-kids.png"], ["Accessories", "cat-bag.png"], ["Winter Wear", "category-kids.png"], ["Gift Sets", "cat-bag.png"], ["Rainwear", "category-kids.png"]]
  },
  accessories: {
    label: "ACCESSORIES", intro: "The details that complete your look.", heroImage: "assets/images/cat-bag.png", trialImage: "assets/images/promo-large-women.png",
    tiles: [["Watches", "cat-watch.png"], ["Bags", "cat-bag.png"], ["Sunglasses", "cat-men.png"], ["Footwear", "cat-shoe.png"], ["Wallets", "cat-bag.png"], ["Belts", "cat-men.png"], ["Jewellery", "cat-women.png"], ["Caps", "cat-kids.png"], ["Travel", "cat-bag.png"], ["Fragrance", "category-women.png"], ["Tech", "cat-watch.png"], ["Gift Cards", "promo-small-men.png"]]
  }
};

const type = document.body.dataset.category || new URLSearchParams(window.location.search).get("type") || "men";
const data = categoryData[type] || categoryData.men;
document.title = `${data.label} - Vastra Express`;
const hero = document.querySelector("#categoryHero");
const tiles = document.querySelector("#categoryTiles");
const trial = document.querySelector(".home-trial-banner");

if (["men", "women", "kids"].includes(type)) {
  document.body.classList.add(`reference-${type}-page`);
  hero.classList.add(`reference-${type}-hero`);
  hero.innerHTML = `<img src="assets/images/${type}-hero.png" alt="${data.label}">`;
  tiles.innerHTML = Array.from({ length: 15 }, (_, index) => `<a href="product.html" class="category-tile reference-${type}-tile"><img src="assets/images/${type}-tile-${index + 1}.png" alt="${data.label} category"></a>`).join("");
  trial.classList.add(`reference-${type}-trial`);
  trial.innerHTML = `<img src="assets/images/${type}-trial.png" alt="Vastra Express home trial">`;
} else {
  hero.innerHTML = `<div class="category-hero-copy"><p>CATEGORY</p><h1>${data.label}</h1><span>${data.intro}</span></div><img src="${data.heroImage}" alt="${data.label}">`;
  tiles.innerHTML = data.tiles.map(([name, image]) => `<a href="product.html" class="category-tile"><img src="assets/images/${image}" alt="${name}"><strong>${name}</strong></a>`).join("");
  document.querySelector("#trialImage").src = data.trialImage;
}

const cartCount = document.querySelector(".cart-count");
const cart = JSON.parse(localStorage.getItem("vastra-cart") || "[]");
const itemCount = cart.reduce((total, item) => total + item.quantity, 0);
if (itemCount) { cartCount.textContent = itemCount; cartCount.classList.add("show"); }
