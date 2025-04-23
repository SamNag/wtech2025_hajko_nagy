/**
 * navbar.js - Handles loading the navbar and initializes cart counters
 */
document.addEventListener("DOMContentLoaded", function() {
    const headerContainer = document.getElementById("header-container");

    // Only attempt to load the header if the container exists
    if (headerContainer) {
        loadHeader();
    } else {
        // Header might be already in the document (not using the container approach)
        initializeNavbarFunctionality();
    }
});

/**
 * Load the header content from the server
 */
function loadHeader() {
    // Attempt to fetch the header.blade.php contents
    fetch("header.blade.php")
        .then(response => response.text())
        .then(data => {
            document.getElementById("header-container").innerHTML = data;

            // Initialize navbar functionality after loading
            initializeNavbarFunctionality();

            // Update cart count if cart manager exists
            if (window.cartManager) {
                window.cartManager.updateCartCountDisplay();
            }

            // Notify any listeners that header has loaded
            document.dispatchEvent(new CustomEvent('header-loaded'));
        })
        .catch(error => {
            console.error("Error loading navbar:", error);

            // Fallback to loading from a static file
            fetch("header.html")
                .then(response => response.text())
                .then(data => {
                    document.getElementById("header-container").innerHTML = data;
                    initializeNavbarFunctionality();

                    // Update cart count
                    if (window.cartManager) {
                        window.cartManager.updateCartCountDisplay();
                    }

                    // Notify any listeners that header has loaded
                    document.dispatchEvent(new CustomEvent('header-loaded'));
                })
                .catch(fallbackError => {
                    console.error("Error loading navbar from fallback:", fallbackError);
                });
        });
}

/**
 * Initialize navbar functionality after the content is loaded
 */
function initializeNavbarFunctionality() {
    // Toggle mobile menu
    const toggleButton = document.querySelector("[data-collapse-toggle='navbar-default']");
    const menu = document.getElementById("navbar-default");

    if (toggleButton && menu) {
        toggleButton.addEventListener("click", function () {
            menu.classList.toggle("hidden");
        });
    }

    // Close mobile menu when clicking outside
    document.addEventListener("click", function(event) {
        if (menu && !menu.classList.contains("hidden") && !event.target.closest("#navbar-default") && !event.target.closest("[data-collapse-toggle='navbar-default']")) {
            menu.classList.add("hidden");
        }
    });
}
