const cartKey = "vastra-cart";
const addToBag = document.querySelector("#addToBag");

function updateButton() {
  const items = JSON.parse(localStorage.getItem(cartKey) || "[]");
  const product = items.find((item) => item.id === "linen-blend-shirt");
  if (product) addToBag.innerHTML = `<i class="bi bi-check2"></i> Added (${product.quantity})`;
}

document.querySelectorAll(".size-options button").forEach((button) => {
  button.addEventListener("click", () => {
    document.querySelectorAll(".size-options button").forEach((item) => item.classList.remove("selected"));
    button.classList.add("selected");
  });
});

document.querySelector(".detail-wishlist").addEventListener("click", (event) => {
  const icon = event.currentTarget.querySelector("i");
  event.currentTarget.classList.toggle("active");
  icon.classList.toggle("bi-heart");
  icon.classList.toggle("bi-heart-fill");
});

addToBag.addEventListener("click", () => {
  const items = JSON.parse(localStorage.getItem(cartKey) || "[]");
  const product = items.find((item) => item.id === "linen-blend-shirt");
  if (product) product.quantity += 1;
  else items.push({ id: "linen-blend-shirt", quantity: 1 });
  localStorage.setItem(cartKey, JSON.stringify(items));
  updateButton();
});

updateButton();
