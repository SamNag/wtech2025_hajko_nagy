var productDetail = 1;

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
    mainImage.src = element.src;
}

document.addEventListener('DOMContentLoaded', function() {
    // Set auth status on the body for cart manager
    document.body.setAttribute('data-auth', "{{ Auth::check() ? 'true' : 'false' }}");

    // Add to cart button event listener
    const addToCartBtn = document.getElementById('add-to-cart-btn');

    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', async function() {
            const packageId = document.getElementById('package-select').value;
            const quantity = parseInt(document.getElementById('quantity').textContent);

            // Show loading state
            const originalText = this.textContent;
            this.textContent = 'Adding...';
            this.disabled = true;

            try {
                // Use the cart manager to add the item
                const result = await window.cartManager.addToCart(packageId, quantity);

                if (result.success) {
                    // Success - show confirmation
                    this.textContent = 'Added to Cart!';
                    this.classList.add('bg-gray-300');
                } else {
                    throw new Error(result.message || 'Failed to add item to cart');
                }

                // Reset button after 2 seconds
                setTimeout(() => {
                    this.textContent = originalText;
                    this.classList.remove('bg-gray-300');
                    this.disabled = false;
                }, 2000);
            } catch (error) {
                console.error('Error adding to cart:', error);
                this.textContent = 'Error - Try Again';

                // Reset button after 2 seconds
                setTimeout(() => {
                    this.textContent = originalText;
                    this.disabled = false;
                }, 2000);
            }
        });
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
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const price = selectedOption.getAttribute('data-price');

        // Format price with 2 decimal places and euro sign
        const formattedPrice = new Intl.NumberFormat('de-DE', {
            style: 'currency',
            currency: 'EUR',
            minimumFractionDigits: 2
        }).format(price);

        // Update price display
        document.getElementById('product-price').textContent = formattedPrice;
    }
});
