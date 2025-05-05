@extends('layouts.main')

@section('title', 'Shopping Cart - Flakes')

@section('extra-css')
    <style>
        /* Cart item loading animation */
        .cart-loading-placeholder {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading-wave 1.5s infinite;
        }

        @keyframes loading-wave {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
@endsection

@section('content')
    <div class="max-w-screen-2xl mx-auto mb-10">
        <div class="bg-gray-200 p-6">
            <h1 class="text-2xl md:text-3xl passion-one-regular animated-gradient text-center mb-8">Shopping Cart</h1>

            <div id="cart-container" class="space-y-4 w-full lg:w-3/4 mx-auto">
                <!-- Cart items will be loaded here -->
                <div class="cart-loading-placeholder h-24 w-full rounded-lg"></div>
                <div class="cart-loading-placeholder h-24 w-full rounded-lg"></div>
            </div>

            <div class="flex justify-between text-xl py-6 w-full lg:w-3/4 mx-auto">
                <p class="inconsolata-bold text-lg lg:text-xl">Total price:</p>
                <p id="total-price" class="inconsolata-bold text-lg lg:text-xl pe-3">€0.00</p>
            </div>

            <div class="w-full lg:w-3/4 flex justify-center sm:justify-end mt-4 mx-auto">
                <a href="{{ route('checkout') }}" id="checkout-btn" class="btn text-center classic-clicked-border text-lg lg:text-xl inconsolata-bold text-black rounded-lg p-2 lg:pd-0 h-12 w-40 classic-clicked">Check out</a>
            </div>
        </div>
    </div>

    <!-- Empty Cart Template -->
    <template id="empty-cart-template">
        <div class="text-center py-10">
            <div class="text-5xl text-gray-300 mb-4">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <p class="text-xl inconsolata-bold text-gray-500">Your cart is looking a bit empty</p>
            <p class="text-gray-500 mt-2">Add some of our delicious flakes to your cart and treat yourself!</p>
            <a href="{{ route('products') }}" class="classic-clicked text-lg inconsolata-bold text-gray-600 rounded-lg px-6 py-2 inline-block mt-6">Discover Our Products</a>
        </div>
    </template>

    <!-- Cart Item Template -->
    <template id="cart-item-template">
        <div class="cart-item relative flex flex-col sm:flex-row sm:items-center justify-between bg-gray-200 p-4 rounded-lg border border-gray-300">
            <!-- Delete/Trash icon in top right corner -->
            <button class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 transition-colors remove-item">
                <i class="fas fa-trash"></i>
            </button>

            <!-- Product info - fixed width on larger screens -->
            <div class="flex items-center mb-4 sm:mb-0 sm:w-1/2 md:w-auto">
                <img src="" alt="Product" class="product-image w-16 h-16 lg:w-20 lg:h-20 object-contain">
                <div class="h-16 border-l-2 border-gray-300 mx-4"></div>
                <div class="min-w-0 flex-1">
                    <p class="product-name passion-one-regular text-xl lg:text-2xl animated-gradient text-start truncate"></p>
                    <p class="package-size inconsolata-regular text-md lg:text-lg"></p>
                </div>
            </div>

            <!-- Quantity and price - always aligned and fixed width -->
            <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-4 md:gap-8">
                <!-- Quantity controls in flex container with fixed width -->
                <div class="flex items-center justify-between w-32 lg:w-40">
                    <button class="classic-clicked text-xl lg:text-2xl inconsolata-regular text-black rounded-lg h-8 w-8 lg:h-10 lg:w-10 flex items-center justify-center decrease-quantity">-</button>
                    <span class="item-quantity inconsolata-regular text-lg text-center w-8 lg:w-12">1</span>
                    <button class="classic-clicked text-xl lg:text-2xl inconsolata-regular text-black rounded-lg h-8 w-8 lg:h-10 lg:w-10 flex items-center justify-center increase-quantity">+</button>
                </div>

                <!-- Price with fixed width -->
                <p class="item-price text-lg lg:text-xl inconsolata-bold w-24 text-right">€0.00</p>
            </div>
        </div>
    </template>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set auth status on the body for cart manager
            document.body.setAttribute('data-auth', "{{ Auth::check() ? 'true' : 'false' }}");

            // Store base URL for assets
            const storageUrl = "{{ asset('storage/') }}";
            const assetUrl = "{{ asset('') }}";

            // Load cart items
            loadCartItems();

            // Check stock before checkout
            const checkoutBtn = document.getElementById('checkout-btn');
            if (checkoutBtn) {
                checkoutBtn.addEventListener('click', async function(e) {
                    e.preventDefault();

                    // Show loading state
                    const originalText = this.textContent;
                    this.textContent = "Checking availability...";
                    this.disabled = true;

                    // Validate stock
                    const isStockValid = await validateCartStock();

                    // Reset button
                    this.textContent = originalText;
                    this.disabled = false;

                    if (isStockValid) {
                        // Proceed to checkout
                        window.location.href = "{{ route('checkout') }}";
                    }
                });
            }

            // Validation function
            async function validateCartStock() {
                try {
                    // Get current cart items
                    const cartItems = await window.cartManager.getCartItems();

                    if (!cartItems || cartItems.length === 0) {
                        showStockAlert("Your cart is empty", "Please add items before proceeding to checkout.");
                        return false;
                    }

                    // Check stock for each item
                    for (const item of cartItems) {
                        const packageId = item.package_id;
                        const quantity = item.quantity;

                        // Make request to check stock
                        const response = await fetch(`/check-stock/${packageId}`);

                        if (!response.ok) {
                            throw new Error('Failed to check stock');
                        }

                        const data = await response.json();

                        if (!data.success) {
                            showStockAlert("Error", "Could not verify product availability. Please try again.");
                            return false;
                        }

                        if (quantity > data.stock) {
                            const productName = item.product ? item.product.name : data.product_name;
                            const packageSize = item.package ? item.package.size : data.package_size;

                            showStockAlert("Limited Stock",
                                `Sorry, only ${data.stock} units of ${productName} (${packageSize}) are available.`);
                            return false;
                        }
                    }

                    // All items are in stock
                    return true;
                } catch (error) {
                    console.error('Error validating cart:', error);
                    showStockAlert("Error", "Could not check product availability. Please try again later.");
                    return false;
                }
            }

            // Nice alert function with title
            function showStockAlert(title, message) {
                // Create modal container
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50';

                // Create modal content
                modal.innerHTML = `
            <div class="bg-gray-200 p-6 rounded-lg shadow-xl max-w-md mx-auto">
                <div class="text-red-500 text-xl mb-4">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    ${title}
                </div>
                <p class="text-gray-700 mb-4">${message}</p>
                <div class="flex justify-end">
                    <button class="classic-clicked text-lg inconsolata-bold text-black rounded-lg px-4 py-2">
                        OK
                    </button>
                </div>
            </div>
        `;

                // Add to body
                document.body.appendChild(modal);

                // Close on button click
                modal.querySelector('button').addEventListener('click', () => {
                    modal.remove();
                });
            }

            // Function to load cart items from server or localStorage
            async function loadCartItems() {
                try {
                    const cartContainer = document.getElementById('cart-container');
                    const items = await window.cartManager.getCartItems();

                    console.log("Cart items:", items);

                    // Clear loading placeholders
                    cartContainer.innerHTML = '';

                    // Always update the total price to 0 by default
                    document.getElementById('total-price').textContent = "€0.00";

                    if (items.length === 0) {
                        console.log("Cart is empty, showing empty template");
                        // Show empty cart message
                        const template = document.getElementById('empty-cart-template');
                        cartContainer.innerHTML = template.innerHTML;

                        // Hide checkout button
                        document.getElementById('checkout-btn').parentElement.classList.add('hidden');
                        return;
                    } else {
                        document.getElementById('checkout-btn').parentElement.classList.remove('hidden');
                    }

                    items.sort((a, b) => {
                        const nameA = a.product ? a.product.name : a.product_name;
                        const nameB = b.product ? b.product.name : b.product_name;
                        return nameA.localeCompare(nameB);
                    });

                    // Show each cart item
                    const template = document.getElementById('cart-item-template');
                    let totalPrice = 0;

                    items.forEach(item => {
                        const itemElement = document.importNode(template.content, true).querySelector('.cart-item');

                        // Set unique ID for the cart item
                        const itemId = {{ Auth::check() ? 'item.id' : 'item.package_id' }};
                        itemElement.setAttribute('data-item-id', itemId);

                        // Populate item details
                        const productName = itemElement.querySelector('.product-name');
                        const productImage = itemElement.querySelector('.product-image');
                        const packageSize = itemElement.querySelector('.package-size');
                        const quantity = itemElement.querySelector('.item-quantity');
                        const price = itemElement.querySelector('.item-price');

                        // Set values based on authenticated vs guest user
                        if ({{ Auth::check() ? 'true' : 'false' }}) {
                            productName.textContent = item.product.name;

                            // FIX: Format image path similar to product-detail.blade.php
                            // For logged-in users
                            let imagePath = item.product.image;
                            if (imagePath) {
                                // If the path starts with "storage/" or has no path prefix
                                if (imagePath.startsWith('storage/')) {
                                    productImage.src = `${assetUrl}${imagePath}`;
                                } else if (!imagePath.startsWith('http') && !imagePath.startsWith('/')) {
                                    productImage.src = `${storageUrl}/${imagePath}`;
                                } else {
                                    productImage.src = imagePath;
                                }
                            } else {
                                // Fallback image
                                productImage.src = `${assetUrl}assets/images/product-placeholder.png`;
                            }

                            productImage.alt = item.product.name;
                            packageSize.textContent = item.package.size;
                            quantity.textContent = item.quantity;

                            const itemTotal = item.package.price * item.quantity;
                            price.textContent = `€${itemTotal.toFixed(2)}`;
                            totalPrice += itemTotal;
                        } else {
                            productName.textContent = item.product_name;

                            // FIX: Format image path similar to product-detail.blade.php
                            // For guest users
                            let imagePath = item.product_image;
                            if (imagePath) {
                                // If the path starts with "storage/" or has no path prefix
                                if (imagePath.startsWith('storage/')) {
                                    productImage.src = `${assetUrl}${imagePath}`;
                                } else if (!imagePath.startsWith('http') && !imagePath.startsWith('/')) {
                                    productImage.src = `${storageUrl}/${imagePath}`;
                                } else {
                                    productImage.src = imagePath;
                                }
                            } else {
                                // Fallback image
                                productImage.src = `${assetUrl}assets/images/product-placeholder.png`;
                            }

                            productImage.alt = item.product_name;
                            packageSize.textContent = item.package_size;
                            quantity.textContent = item.quantity;

                            const itemTotal = item.price * item.quantity;
                            price.textContent = `€${itemTotal.toFixed(2)}`;
                            totalPrice += itemTotal;
                        }

                        // Add event listeners
                        const removeBtn = itemElement.querySelector('.remove-item');
                        removeBtn.addEventListener('click', () => removeCartItem(itemId));

                        const decreaseBtn = itemElement.querySelector('.decrease-quantity');
                        decreaseBtn.addEventListener('click', () => updateQuantity(itemId, -1));

                        const increaseBtn = itemElement.querySelector('.increase-quantity');
                        increaseBtn.addEventListener('click', () => updateQuantity(itemId, 1));

                        cartContainer.appendChild(itemElement);
                    });

                    // Update total price
                    document.getElementById('total-price').textContent = `€${totalPrice.toFixed(2)}`;
                } catch (error) {
                    console.error('Error loading cart items:', error);
                }
            }

            // Function to remove a cart item
            async function removeCartItem(itemId) {
                try {
                    const result = await window.cartManager.removeFromCart(itemId);

                    if (result.success) {
                        // Instead of just removing the element, reload all items to ensure accurate totals
                        loadCartItems();
                    }
                } catch (error) {
                    console.error('Error removing item:', error);
                }
            }

            // Function to update item quantity
            async function updateQuantity(itemId, change) {
                const itemElement = document.querySelector(`.cart-item[data-item-id="${itemId}"]`);
                const quantityElement = itemElement.querySelector('.item-quantity');
                const currentQuantity = parseInt(quantityElement.textContent);
                const newQuantity = Math.max(1, currentQuantity + change);

                // If quantity didn't change, do nothing
                if (newQuantity === currentQuantity) return;

                try {
                    const result = await window.cartManager.updateCartItemQuantity(itemId, newQuantity);

                    if (result.success) {
                        // Refresh all cart items to ensure accurate pricing
                        loadCartItems();
                    }
                } catch (error) {
                    console.error('Error updating quantity:', error);
                }
            }
        });
    </script>
@endpush
