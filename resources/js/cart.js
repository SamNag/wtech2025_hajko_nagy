// This file should be saved as ../script/cart.js

const cartItems = [
    { id: 1, name: "Magnesium", package: "60pcs", price: 22.62, quantity: 1, img: "../assets/images/mg.png" },
    { id: 2, name: "Zinc", package: "100pcs", price: 19.99, quantity: 1, img: "../assets/images/calcium.png" },
    { id: 3, name: "Vitamin C super long name", package: "120pcs", price: 29.99, quantity: 1, img: "../assets/images/c.png" }
];

function renderCart() {
    const cartContainer = document.getElementById("cart-container");
    cartContainer.innerHTML = "";
    let totalPrice = 0;

    cartItems.forEach(item => {
        totalPrice += item.price * item.quantity;
        cartContainer.innerHTML += `

      <div class='relative flex flex-col sm:flex-row sm:items-center justify-between bg-gray-200 p-4 rounded-lg border border-gray-300'>
  <!-- Delete/Trash icon in top right corner using Font Awesome -->
  <button class='absolute top-2 right-2 text-gray-400 hover:text-gray-600 transition-colors' onclick='removeItem(${item.id})'>
    <i class="fas fa-trash"></i>
  </button>
  
  <!-- Product info - fixed width on larger screens -->
  <div class='flex items-center mb-4 sm:mb-0 sm:w-1/2 md:w-auto'>
    <img src='${item.img}' alt='Product' class='w-16 h-16 lg:w-20 lg:h-20 object-contain'>
    <div class="h-16 border-l-2 border-gray-300 mx-4"></div>
    <div class='min-w-0 flex-1'>
      <p class='passion-one-regular text-xl lg:text-2xl animated-gradient text-start truncate'>${item.name}</p>
      <p class='inconsolata-regular text-md lg:text-lg'>${item.package}</p>
    </div>
  </div>
  
  <!-- Quantity and price - always aligned and fixed width -->
  <div class='flex items-center justify-between sm:justify-end w-full sm:w-auto gap-4 md:gap-8'>
    <!-- Quantity controls in flex container with fixed width -->
    <div class='flex items-center justify-between w-32 lg:w-40'>
      <button class='classic-clicked text-xl lg:text-2xl inconsolata-regular text-black rounded-lg h-8 w-8 lg:h-10 lg:w-10 flex items-center justify-center' onclick='updateQuantity(${item.id}, -1)'>-</button>
      <span class='inconsolata-regular text-lg text-center w-8 lg:w-12'>${item.quantity}</span>
      <button class='classic-clicked text-xl lg:text-2xl inconsolata-regular text-black rounded-lg h-8 w-8 lg:h-10 lg:w-10 flex items-center justify-center' onclick='updateQuantity(${item.id}, 1)'>+</button>
    </div>
    
    <!-- Price with fixed width -->
    <p class='text-lg lg:text-xl inconsolata-bold w-24 text-right'>€${(item.price * item.quantity).toFixed(2)}</p>
  </div>
</div>
    `;
    });

    document.getElementById("total-price").innerText = `€${totalPrice.toFixed(2)}`;
}

function updateQuantity(id, change) {
    const item = cartItems.find(item => item.id === id);
    if (item) {
        item.quantity = Math.max(1, item.quantity + change);
        renderCart();
    }
}

function removeItem(id) {
    const index = cartItems.findIndex(item => item.id === id);
    if (index > -1) {
        cartItems.splice(index, 1);
        renderCart();
    }
}

document.addEventListener("DOMContentLoaded", renderCart);