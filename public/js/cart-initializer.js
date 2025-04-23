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

    // For dynamic content loading (if you're using AJAX to load parts of the page)
    document.addEventListener('header-loaded', function() {
        console.log('Header loaded event detected, updating cart indicators');
        window.cartManager.updateCartCountDisplay();
    });
});

// Helper function to trigger header loaded event (call this after loading header via AJAX)
function notifyHeaderLoaded() {
    document.dispatchEvent(new CustomEvent('header-loaded'));
}
