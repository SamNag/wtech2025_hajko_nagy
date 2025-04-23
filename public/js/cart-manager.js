/**
 * Cart Manager - Handles cart operations using localStorage for guest users and API for authenticated users
 */
class CartManager {
    constructor() {
        this.isAuthenticated = document.body.getAttribute('data-auth') === 'true';
        this.storageKey = 'flakes_cart';
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Initialize the cart counts
        this.updateCartCountDisplay();
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
     * Update the cart count display in the header
     * @param {number|null} count - Optional count to display
     */
    updateCartCountDisplay(count = null) {
        const cartCount = document.getElementById('cart-count');
        if (!cartCount) return;

        if (count === null) {
            if (this.isAuthenticated) {
                // If authenticated but no count provided, we won't update
                return;
            } else {
                // Calculate from localStorage for guests
                count = this.calculateLocalCartCount();
            }
        }

        cartCount.textContent = count;

        if (count > 0) {
            cartCount.classList.remove('hidden');
        } else {
            cartCount.classList.add('hidden');
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
        if (!this.isAuthenticated) return { success: false, message: 'User not authenticated' };

        const localCart = this.getLocalCart();
        if (localCart.length === 0) return { success: true, message: 'No items to sync' };

        try {
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
                return data.cart_items;
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
