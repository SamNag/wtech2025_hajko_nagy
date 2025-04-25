/**
 * Cart Indicator Initializer
 * This script ensures cart count indicators are updated when the header is loaded
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize cart manager if it doesn't exist yet
    if (!window.cartManager) {
        console.log('Creating cart manager instance');
        window.cartManager = new CartManager();
    }

    // Update cart counts
    window.cartManager.updateCartCountDisplay();

    document.addEventListener('header-loaded', function() {
        console.log('Header loaded event detected, updating cart indicators');
        window.cartManager.updateCartCountDisplay();
    });
});

