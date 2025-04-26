/**
 * Cart Manager - Handles cart operations using localStorage for guest users and API for authenticated users
 */
/**
 * Enhanced Cart Manager - Handles cart operations using localStorage for guest users and API for authenticated users
 * Now supports multiple header layouts with different cart count indicators
 */
class CartManager {
    constructor() {
        // Check authentication status from data attribute
        this.isAuthenticated = document.body.getAttribute('data-auth') === 'true';
        this.storageKey = 'flakes_cart';

        // Try to get CSRF token
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        this.csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

        // All possible cart count elements across different headers
        this.cartCountSelectors = [
            '#cart-count',               // Standard header desktop
            '#cart-count-mobile',        // Standard header mobile
            '#cart-count-home',          // Home header desktop
            '#cart-count-home-mobile',   // Home header mobile
            '#cart-count-products',      // Products header desktop
            '#cart-count-products-mobile' // Products header mobile
        ];

        // Initialize cart counts immediately
        this.updateCartCountDisplay();

        console.log(`Cart Manager initialized. Auth status: ${this.isAuthenticated ? 'Logged in' : 'Guest'}`);
    }

    /**
     * Add an item to the cart
     * @param {string} packageId - The package ID
     * @param {number} quantity - Quantity to add
     * @returns {Promise} - Promise resolving to success status
     */
    async addToCart(packageId, quantity) {
        try {
            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    package_id: packageId,
                    quantity: quantity
                })
            });

            if (!response.ok) throw new Error('Failed to add item to cart');

            const data = await response.json();

            if (this.isAuthenticated) {
                // For authenticated users, server handles cart storage
                this.updateCartCountDisplay(data.cart_count);
            } else {
                // For guests, update localStorage
                this.updateLocalCart(data.package, quantity);
            }

            return { success: true, message: data.message };
        } catch (error) {
            console.error('Error adding to cart:', error);
            return { success: false, message: 'Failed to add item to cart' };
        }
    }

    clearCart() {
        if (this.isAuthenticated) {
            // For authenticated users, the server already handles this
            return;
        } else {
            // For guests, clear localStorage
            localStorage.removeItem(this.storageKey);
            this.updateCartCountDisplay(0);
        }
    }

    /**
     * Update the local cart in localStorage
     * @param {Object} packageData - Package data to save
     * @param {number} quantityToAdd - Quantity to add
     */
    updateLocalCart(packageData, quantityToAdd) {
        const cart = this.getLocalCart();

        const existingItem = cart.find(item => item.package_id === packageData.id);

        if (existingItem) {
            existingItem.quantity += quantityToAdd;
        } else {
            cart.push({
                package_id: packageData.id,
                product_id: packageData.product_id,
                product_name: packageData.name,
                product_image: packageData.image,
                package_size: packageData.size,
                price: packageData.price,
                quantity: quantityToAdd
            });
        }

        localStorage.setItem(this.storageKey, JSON.stringify(cart));
        this.updateCartCountDisplay();
    }

    /**
     * Get the local cart from localStorage
     * @returns {Array} - Array of cart items
     */
    getLocalCart() {
        const cartJson = localStorage.getItem(this.storageKey);
        return cartJson ? JSON.parse(cartJson) : [];
    }

    /**
     * Update all cart count displays in the header
     * @param {number|null} count - Optional count to display
     */
    updateCartCountDisplay(count = null) {
        // Calculate the count if not provided
        if (count === null) {
            if (this.isAuthenticated) {
                // For authenticated users, fetch the count from server
                this.fetchCartCount().then(serverCount => {
                    this.updateAllCountElements(serverCount);
                });
                return;
            } else {
                // Calculate from localStorage for guests
                count = this.calculateLocalCartCount();
            }
        }

        // Update all possible cart count elements
        this.updateAllCountElements(count);
    }

    /**
     * Update all cart count elements with the provided count
     * @param {number} count - Count to display
     */
    updateAllCountElements(count) {
        // Find all cart count elements across different headers
        this.cartCountSelectors.forEach(selector => {
            const element = document.querySelector(selector);
            if (element) {
                element.textContent = count;

                if (count > 0) {
                    element.classList.remove('hidden');
                } else {
                    element.classList.add('hidden');
                }
            }
        });
    }

    /**
     * Fetch cart count from server
     * @returns {Promise<number>} - Cart count
     */
    async fetchCartCount() {
        try {
            const response = await fetch('/cart/items', {
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken
                }
            });

            if (!response.ok) throw new Error('Failed to fetch cart items');

            const data = await response.json();
            const items = data.cart_items || [];
            return items.reduce((total, item) => total + item.quantity, 0);
        } catch (error) {
            console.error('Error fetching cart count:', error);
            return 0;
        }
    }

    /**
     * Calculate the total count of items in the local cart
     * @returns {number} - Total items in cart
     */
    calculateLocalCartCount() {
        const cart = this.getLocalCart();
        return cart.reduce((total, item) => total + item.quantity, 0);
    }

    /**
     * Remove an item from the cart
     * @param {string} itemId - Item ID to remove (packageId for localStorage)
     * @returns {Promise} - Promise resolving to success status
     */
    async removeFromCart(itemId) {
        if (this.isAuthenticated) {
            try {
                const response = await fetch(`/cart/remove/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    }
                });

                if (!response.ok) throw new Error('Failed to remove item');

                const data = await response.json();
                this.updateCartCountDisplay(data.cart_count);

                return { success: true, message: 'Item removed from cart' };
            } catch (error) {
                console.error('Error removing from cart:', error);
                return { success: false, message: 'Failed to remove item from cart' };
            }
        } else {
            // For guests, update localStorage
            const cart = this.getLocalCart();
            const updatedCart = cart.filter(item => item.package_id !== itemId);

            localStorage.setItem(this.storageKey, JSON.stringify(updatedCart));
            this.updateCartCountDisplay();

            return { success: true, message: 'Item removed from cart' };
        }
    }

    /**
     * Update the quantity of an item in the cart
     * @param {string} itemId - Item ID to update
     * @param {number} quantity - New quantity
     * @returns {Promise} - Promise resolving to success status
     */
    async updateCartItemQuantity(itemId, quantity) {
        if (this.isAuthenticated) {
            try {
                const response = await fetch('/cart/update', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        quantity: quantity
                    })
                });

                if (!response.ok) throw new Error('Failed to update item');

                const data = await response.json();
                this.updateCartCountDisplay(data.cart_count);

                return { success: true, message: 'Cart updated' };
            } catch (error) {
                console.error('Error updating cart:', error);
                return { success: false, message: 'Failed to update cart' };
            }
        } else {
            // For guests, update localStorage
            const cart = this.getLocalCart();
            const item = cart.find(item => item.package_id === itemId);

            if (item) {
                item.quantity = quantity;
                localStorage.setItem(this.storageKey, JSON.stringify(cart));
                this.updateCartCountDisplay();
                return { success: true, message: 'Cart updated' };
            }

            return { success: false, message: 'Item not found in cart' };
        }
    }

    /**
     * Sync the local cart with the server after login
     * @returns {Promise} - Promise resolving to success status
     */
    async syncCart() {
        if (!this.isAuthenticated) {
            console.warn('Cannot sync cart: User not authenticated');
            return { success: false, message: 'User not authenticated' };
        }

        const localCart = this.getLocalCart();
        if (localCart.length === 0) {
            console.log('No items to sync from local storage');
            return { success: true, message: 'No items to sync' };
        }

        try {
            console.log('Syncing cart items to server:', localCart);
            const response = await fetch('/cart/sync', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    cart_items: localCart.map(item => ({
                        package_id: item.package_id,
                        quantity: item.quantity
                    }))
                })
            });

            if (!response.ok) throw new Error('Failed to sync cart');

            const data = await response.json();

            // Clear local cart after successful sync
            localStorage.removeItem(this.storageKey);
            this.updateCartCountDisplay(data.cart_count);
            console.log('Cart synced successfully to server');

            return { success: true, message: 'Cart synced successfully' };
        } catch (error) {
            console.error('Error syncing cart:', error);
            return { success: false, message: 'Failed to sync cart with server' };
        }
    }

    /**
     * Get cart items for display
     * @returns {Promise} - Promise resolving to cart items
     */
    async getCartItems() {
        if (this.isAuthenticated) {
            try {
                const response = await fetch('/cart/items', {
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken
                    }
                });

                if (!response.ok) throw new Error('Failed to fetch cart items');

                const data = await response.json();
                return data.cart_items || [];
            } catch (error) {
                console.error('Error fetching cart items:', error);
                return [];
            }
        } else {
            // Return items from localStorage for guests
            return this.getLocalCart();
        }
    }
}

// Create a global instance
window.cartManager = new CartManager();

// Initialize cart sync on pages with login success parameter
document.addEventListener('DOMContentLoaded', function() {
    // Set auth status on the body for cart manager if not already set
    if (!document.body.hasAttribute('data-auth')) {
        // Default to guest if not set
        document.body.setAttribute('data-auth', 'false');
    }

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('loggedIn') && window.cartManager && document.body.getAttribute('data-auth') === 'true') {
        console.log('Detected login success. Syncing cart...');
        window.cartManager.syncCart()
            .then(result => {
                if (result.success) {
                    console.log('Cart synced successfully after login');
                    // Remove the URL parameter to prevent repeated syncs
                    const newUrl = window.location.pathname;
                    window.history.replaceState({}, document.title, newUrl);
                }
            })
            .catch(error => {
                console.error('Error during cart sync after login:', error);
            });
    }
});
