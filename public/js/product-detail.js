
// Initialize variables
let productDetail = 1;

function increaseQuantity() {
    productDetail++;
    document.getElementById("quantity").innerText = productDetail;
}

function decreaseQuantity() {
    if (productDetail > 1) {
        productDetail--;
        document.getElementById("quantity").innerText = productDetail;
    }
}

function changeImage(element) {
    let mainImage = document.getElementById("main-image");
    if (mainImage && element) {
        mainImage.src = element.src;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log("Product detail page loaded");

    // Set auth status on the body for cart manager if not already set
    if (!document.body.hasAttribute('data-auth')) {
        // Use Laravel's Auth check from the view
        const isAuthenticated = document.querySelector('meta[name="is-authenticated"]')?.getAttribute('content') === 'true';
        document.body.setAttribute('data-auth', isAuthenticated ? 'true' : 'false');
    }

    const isAuthenticated = document.body.getAttribute('data-auth') === 'true';
    console.log("Authentication status from data-auth:", isAuthenticated);

    // Add to cart button event listener
    const addToCartBtn = document.getElementById('add-to-cart-btn');

    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', async function() {
            const packageSelect = document.getElementById('package-select');
            if (!packageSelect) {
                console.error('Package select element not found');
                return;
            }

            const packageId = packageSelect.value;
            const quantity = parseInt(document.getElementById('quantity').textContent);

            console.log("Adding to cart:", packageId, quantity);

            // No visual changes to the button
            // Just temporarily disable to prevent multiple clicks
            this.disabled = true;

            try {
                // Make sure cart manager is initialized
                if (!window.cartManager) {
                    console.error('Cart manager not initialized');
                    // Try to initialize it
                    window.cartManager = new CartManager();
                }

                const result = await window.cartManager.addToCart(packageId, quantity);

                if (result.success) {
                    console.log('Successfully added to cart:', result);
                } else {
                    console.error('Error from server:', result.message);
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
            }

            // Re-enable the button
            this.disabled = false;
        });
    } else {
        console.warn('Add to cart button not found on this page');
    }

    // Package selection change handler to update price
    const packageSelect = document.getElementById('package-select');
    if (packageSelect) {
        packageSelect.addEventListener('change', function() {
            updatePriceDisplay();
        });

        // Initialize price display
        updatePriceDisplay();
    }

    // Function to update price display based on selected package
    function updatePriceDisplay() {
        const selectElement = document.getElementById('package-select');
        if (!selectElement) return;

        const selectedOption = selectElement.options[selectElement.selectedIndex];
        if (!selectedOption) return;

        const price = selectedOption.getAttribute('data-price');
        if (!price) return;

        // Format price with 2 decimal places and euro sign
        const formattedPrice = `€${parseFloat(price).toFixed(2)}`;

        // Update price display
        const priceElement = document.getElementById('product-price');
        if (priceElement) {
            priceElement.textContent = formattedPrice;
        }
    }
});
