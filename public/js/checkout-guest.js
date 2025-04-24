/**
 * Enhanced Guest Checkout Handler
 * Handles the complete guest checkout process including cart data management
 */
document.addEventListener('DOMContentLoaded', function() {
    // Determine if user is authenticated
    const isAuthenticated = document.body.getAttribute('data-auth') === 'true';

    // References to important elements
    const checkoutForm = document.getElementById('checkout-form');
    const cartDataInput = document.getElementById('cart-data-input');
    const placeOrderBtn = document.getElementById('place-order-btn');

    // Set up for guests
    if (!isAuthenticated) {
        // Load and display cart items for guests
        loadGuestCartItems();

        // Prepare cart data for submission
        if (checkoutForm && cartDataInput) {
            checkoutForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Temporarily prevent form submission

                // Get and validate cart data
                const cartData = prepareCartData();

                if (!cartData || cartData.length === 0) {
                    // Show error for empty cart
                    showErrorMessage('Your cart is empty. Please add items before checkout.', checkoutForm);
                    return false;
                }

                // Set the cart data in the hidden input
                cartDataInput.value = JSON.stringify(cartData);

                // Continue with form submission
                this.submit();
            });
        }
    }

    // Initialize personal info editing for authenticated users
    initPersonalInfoEditing();

    // Initialize payment method toggling
    initPaymentMethodToggle();
});

/**
 * Load and display cart items for guest users
 */
async function loadGuestCartItems() {
    const subtotalElement = document.getElementById('checkout-subtotal');
    const totalElement = document.getElementById('checkout-total');
    const summaryElement = document.querySelector('.order-items-summary');

    try {
        // Use cart manager to get cart items
        if (window.cartManager) {
            const cartItems = await window.cartManager.getCartItems();

            // Calculate subtotal
            let subtotal = 0;
            cartItems.forEach(item => {
                subtotal += item.price * item.quantity;
            });

            // Update summary elements if they exist
            if (summaryElement) {
                summaryElement.innerHTML = ''; // Clear existing content

                if (cartItems.length === 0) {
                    summaryElement.innerHTML = '<p class="text-center text-gray-500">Your cart is empty</p>';
                } else {
                    // Create a summary list
                    const itemsList = document.createElement('ul');
                    itemsList.className = 'space-y-2';

                    cartItems.forEach(item => {
                        const itemElement = document.createElement('li');
                        itemElement.className = 'flex justify-between';
                        itemElement.innerHTML = `
                            <span>${item.product_name} (${item.package_size}) × ${item.quantity}</span>
                            <span>€${(item.price * item.quantity).toFixed(2)}</span>
                        `;
                        itemsList.appendChild(itemElement);
                    });

                    summaryElement.appendChild(itemsList);
                }
            }

            // Update price elements
            const shippingFee = 3.65; // Fixed shipping fee
            const total = subtotal + shippingFee;

            if (subtotalElement) {
                subtotalElement.textContent = `€${subtotal.toFixed(2)}`;
            }

            if (totalElement) {
                totalElement.textContent = `€${total.toFixed(2)}`;
            }

            // Disable place order button if cart is empty
            const placeOrderBtn = document.getElementById('place-order-btn');
            if (placeOrderBtn && cartItems.length === 0) {
                placeOrderBtn.disabled = true;
                placeOrderBtn.classList.add('opacity-50', 'cursor-not-allowed');

                // Create warning message if not already present
                if (!document.querySelector('.cart-empty-warning')) {
                    const warningEl = document.createElement('div');
                    warningEl.className = 'cart-empty-warning text-red-500 text-center w-full mb-2';
                    warningEl.textContent = 'Your cart is empty. Please add items before checkout.';

                    // Insert before the button
                    placeOrderBtn.parentNode.insertBefore(warningEl, placeOrderBtn);
                }
            }
        }
    } catch (error) {
        console.error('Error loading guest cart items:', error);
    }
}

/**
 * Prepare cart data from localStorage for form submission
 * @returns {Array} Array of cart items with package_id and quantity
 */
function prepareCartData() {
    let cartData = [];

    // If cart manager exists, use it to get cart data
    if (window.cartManager) {
        try {
            const localCart = window.cartManager.getLocalCart();

            // Format cart data for server processing
            localCart.forEach(item => {
                cartData.push({
                    package_id: item.package_id,
                    quantity: item.quantity
                });
            });
        } catch (error) {
            console.error('Error preparing cart data:', error);
            showErrorMessage('Error preparing your cart data. Please try again.', document.getElementById('checkout-form'));
        }
    }

    return cartData;
}

/**
 * Initialize personal information editing for authenticated users
 */
function initPersonalInfoEditing() {
    const editBtn = document.querySelector("#personal-info .edit");

    if (editBtn) {
        editBtn.addEventListener("click", function() {
            const personalInfoDiv = document.getElementById('personal-info');
            const contentDiv = personalInfoDiv.querySelector(".content");
            const editForm = personalInfoDiv.querySelector(".edit-form");

            if (personalInfoDiv.classList.contains("editing")) {
                // Save changes
                personalInfoDiv.classList.remove("editing");
                this.textContent = "Edit";

                // Show content div
                contentDiv.classList.remove("hidden");

                // Hide edit form
                editForm.classList.add("hidden");

                // Get updated values
                const name = editForm.querySelector('input[name="name"]').value;
                const surname = editForm.querySelector('input[name="surname"]').value;
                const email = editForm.querySelector('input[name="email"]').value;
                const phone = editForm.querySelector('input[name="phone"]').value;

                // Update displayed content
                contentDiv.innerHTML = `
                    <p class="text-start text-md md:text-lg">${name} ${surname}</p>
                    <p class="text-start text-md md:text-lg">${email}</p>
                    <p class="text-start text-md md:text-lg">${phone || 'No phone number provided'}</p>
                `;
            } else {
                // Enter edit mode
                personalInfoDiv.classList.add("editing");
                this.textContent = "Save";

                // Hide content div
                contentDiv.classList.add("hidden");

                // Show edit form
                editForm.classList.remove("hidden");
            }
        });
    }
}

/**
 * Initialize payment method toggle functionality
 */
function initPaymentMethodToggle() {
    const cardRadio = document.getElementById('payment-card');
    const cashRadio = document.getElementById('payment-cash');
    const cardDetails = document.getElementById('card-details');

    // Only proceed if we have all elements
    if (!cardRadio || !cashRadio || !cardDetails) return;

    // Function to toggle card details visibility
    function toggleCardDetails() {
        if (cardRadio.checked) {
            cardDetails.classList.remove('hidden');
            // Make card fields required
            document.querySelectorAll('#card-details input').forEach(input => {
                input.setAttribute('required', 'required');
            });
        } else {
            cardDetails.classList.add('hidden');
            // Remove required attribute from card fields
            document.querySelectorAll('#card-details input').forEach(input => {
                input.removeAttribute('required');
            });
        }
    }

    // Set initial state
    toggleCardDetails();

    // Add event listeners
    cardRadio.addEventListener('change', toggleCardDetails);
    cashRadio.addEventListener('change', toggleCardDetails);
}

/**
 * Show error message on the form
 * @param {string} message - The error message to display
 * @param {HTMLElement} parentElement - The parent element to append the message to
 */
function showErrorMessage(message, parentElement) {
    // Remove any existing error messages
    const existingErrors = parentElement.querySelectorAll('.checkout-error');
    existingErrors.forEach(el => el.remove());

    // Create new error message
    const errorEl = document.createElement('div');
    errorEl.className = 'checkout-error bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
    errorEl.innerHTML = `<p>${message}</p>`;

    // Insert at the top of the form
    parentElement.insertBefore(errorEl, parentElement.firstChild);

    // Scroll to the error message
    errorEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

/**
 * Clear checkout errors
 */
function clearErrors() {
    const existingErrors = document.querySelectorAll('.checkout-error');
    existingErrors.forEach(el => el.remove());
}
