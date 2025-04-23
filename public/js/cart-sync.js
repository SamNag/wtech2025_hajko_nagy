/**
 * Cart Sync Script - Handles syncing the cart from localStorage to the database after login
 */
document.addEventListener('DOMContentLoaded', function() {
    // If cartManager exists and user just logged in (check URL param)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('loggedIn') && window.cartManager) {
        console.log('User just logged in, syncing cart...');

        // Check if body has data-auth attribute set to true
        if (document.body.getAttribute('data-auth') === 'true') {
            // Sync the cart from localStorage to the database
            window.cartManager.syncCart().then(result => {
                if (result.success) {
                    console.log('Cart synced successfully');

                    // Remove the URL parameter to prevent repeated syncs on page refresh
                    const newUrl = window.location.pathname;
                    window.history.replaceState({}, document.title, newUrl);
                } else {
                    console.error('Cart sync failed:', result.message);
                }
            }).catch(error => {
                console.error('Error syncing cart:', error);
            });
        }
    }
});
