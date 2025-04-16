// Detect if the script is being used in admin-page.blade.php
const isAdminPage = window.location.pathname.includes("admin-page.blade.php");


// Product class for backend integration
class Product {
    constructor(id, name, price, image, category, packages, tags) {
        this.id = id;
        this.name = name;
        this.price = parseFloat(price);
        this.image = image;
        this.category = category; // 'minerals', 'vitamins', 'oils'
        this.packages = packages; // Array of available packages (e.g., ['30pcs', '60pcs'])
        this.tags = tags;
    }

    // Check if product matches all filter criteria
    matchesFilters(filters) {
        // Price range filter
        if (this.price < filters.minPrice || this.price > filters.maxPrice) {
            return false;
        }

        // Category filter
        if (filters.category !== 'all' && this.category !== filters.category) {
            return false;
        }

        // Package filter
        if (filters.package !== 'all' && !this.packages.includes(filters.package)) {
            return false;
        }

        // Search term filter
        if (filters.searchTerm && !this.name.toLowerCase().includes(filters.searchTerm.toLowerCase())) {
            return false;
        }

        return true;
    }
}

// Sample products data with additional properties
const products = [
    new Product(1, "Magnesium", 10.2, "../assets/images/a.png", "minerals", ["30pcs", "60pcs", "90pcs"],["skin", "hair"]),
    new Product(2, "Calcium", 16.7, "../assets/images/mct.png", "minerals", ["30pcs", "60pcs", "120pcs"], ["bones", "teeth"]),
    new Product(3, "Potassium super long name", 26.9, "../assets/images/c.png", "minerals", ["30pcs", "90pcs"], ["muscles", "heart"]),
    new Product(4, "Lecithin", 13.6, "../assets/images/copper.png", "oils", ["5ml", "10ml", "30ml"], ["brain", "liver"]),
    new Product(5, "Multi mineral", 19.8, "../assets/images/grape.png", "minerals", ["30pcs", "60pcs"], ["immunity", "digestion"]),
    new Product(6, "Selenium", 14.4, "../assets/images/hemp.png", "minerals", ["30pcs", "60pcs"], ["thyroid", "antioxidant"]),
    new Product(7, "Zinc", 23.2, "../assets/images/kril.png", "minerals", ["30pcs", "90pcs"], ["skin", "immunity"]),
    // Additional products can be added here
];

// Filter state
let filterState = {
    minPrice: 0,
    maxPrice: 100,
    category: 'all',
    package: 'all',
    sortBy: 'alphabet-asc',
    searchTerm: ''
};

// Initialize event listeners and display products
document.addEventListener("DOMContentLoaded", function () {
    // Initialize all filter elements
    initializeFilters();

    // Initial products display
    displayProducts(filterProducts());
});

// Initialize all filter controls
function initializeFilters() {
    // Category buttons
    document.getElementById('option-1').addEventListener('change', function() {
        if(this.checked) {
            filterState.category = 'all';
            applyFilters();
        }
    });

    document.getElementById('option-2').addEventListener('change', function() {
        if(this.checked) {
            filterState.category = 'minerals';
            applyFilters();
        }
    });

    document.getElementById('option-3').addEventListener('change', function() {
        if(this.checked) {
            filterState.category = 'vitamins';
            applyFilters();
        }
    });

    document.getElementById('option-4').addEventListener('change', function() {
        if(this.checked) {
            filterState.category = 'oils';
            applyFilters();
        }
    });

    // Price range inputs
    document.getElementById('price-range-min').addEventListener('input', function() {
        filterState.minPrice = parseFloat(this.value) || 0;
        applyFilters();
    });

    document.getElementById('price-range-max').addEventListener('input', function() {
        filterState.maxPrice = parseFloat(this.value) || 100;
        applyFilters();
    });

    // Package dropdown
    document.getElementById('package-select').addEventListener('change', function() {
        filterState.package = this.value;
        applyFilters();
    });

    // Sort dropdown
    document.getElementById('sort-select').addEventListener('change', function() {
        filterState.sortBy = this.value;
        applyFilters();
    });

    // Search inputs (both mobile and desktop)
    const searchInputs = ['simple-search', 'simple-search-mobile'];
    searchInputs.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', function() {
                filterState.searchTerm = this.value;
                applyFilters();
            });
        }
    });
}

// Apply all filters and update display
function applyFilters() {
    const filteredProducts = filterProducts();
    displayProducts(filteredProducts);
}

// Filter products based on current filter state
function filterProducts() {
    // First filter by criteria
    let filtered = products.filter(product => product.matchesFilters(filterState));

    // Then sort according to sort option
    return sortProducts(filtered, filterState.sortBy);
}

// Sort products based on sort option
function sortProducts(products, sortOption) {
    const sortedProducts = [...products]; // Create a copy to avoid modifying the original

    switch(sortOption) {
        case 'alphabet-asc':
            sortedProducts.sort((a, b) => a.name.localeCompare(b.name));
            break;
        case 'alphabet-desc':
            sortedProducts.sort((a, b) => b.name.localeCompare(a.name));
            break;
        case 'price-asc':
            sortedProducts.sort((a, b) => a.price - b.price);
            break;
        case 'price-desc':
            sortedProducts.sort((a, b) => b.price - a.price);
            break;
        default:
            // Default to alphabetical
            sortedProducts.sort((a, b) => a.name.localeCompare(b.name));
    }

    return sortedProducts;
}

function displayProducts(productsToDisplay) {
const productContainer = document.getElementById("product-container");

productContainer.innerHTML = '';

if (productsToDisplay.length === 0) {
    productContainer.innerHTML = '<div class="w-full py-10 text-center text-gray-600 inconsolata-regular text-lg md:text-xl">No products match your filters :(</div>';
    return;
}

productsToDisplay.forEach(product => {
    const productCard = document.createElement("div");
    productCard.className = "w-full sm:w-1/2 md:w-1/3 lg:w-1/4 px-4 mt-8";

    const tagsBadges = product.tags.map(pkg =>
        `<span class="text-xs bg-blue-100 text-blue-800 font-medium mr-1 px-2 py-0.5 rounded">${pkg}</span>`
    ).join('');

    const addToCartIcon = isAdminPage
        ? '<i class="fas fa-pen text-sm text-gray-400"></i>'
        : '<i class="fas fa-plus text-sm text-gray-400"></i>';

    productCard.innerHTML = `
    <div class="relative flex flex-col items-center product-card-body cursor-pointer">
        <div class="relative z-10 -mb-10 -mr-20">
            <img src="${product.image}" alt="${product.name}" class="w-full h-32 lg:h-40 xl:h-48 object-contain opacity-[.85] hover:opacity-90 hover:scale-105 transition-transform duration-300">
        </div>
        <div class="bg-gradient-to-tl from-gray-300 to-gray-200 rounded-lg p-4 pt-6 max-w-xs w-full border border-slate-200 shadow-sm relative  hover:shadow-md transition-all duration-300" data-product-id="${product.id}">
            <h2 class="passion-one-regular animated-gradient text-xl md:text-2xl truncate" title="${product.name}">${product.name}</h2>
            <p class="inconsolata-regular text-md md:text-lg">Price: ${product.price.toFixed(1)} €</p>
            <div class="flex flex-wrap mt-1">
                ${tagsBadges}
            </div>
            <button class="add-to-cart-btn absolute bottom-3 right-3 bg-slate-200 hover:bg-white text-gray-100 hover:border hover:border-gray-400 rounded-full w-8 h-8 flex items-center justify-center shadow-md transition-all duration-300 hover:scale-105 z-10" data-product-id="${product.id}">
                 ${addToCartIcon}
            </button>
        </div>
    </div>
`;

    productContainer.appendChild(productCard);

    // Add event listeners
    document.querySelectorAll('.product-card-body').forEach(card => {
        card.addEventListener('click', (e) => {
            const productId = card.getAttribute('data-product-id');
            // Prevent click if the target is the add button or its child
            if (!isAdminPage && !e.target.closest('.add-to-cart-btn')) {
                window.location.href = `product-detail.html?id=${productId}`;
            }
        });
    });

    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation(); // Prevent the card click event from firing
            const productId = button.getAttribute('data-product-id');

            if (isAdminPage) {
                console.log(`Edit Product ${productId} (No action yet)`);
            } else {
                console.log(`Product ${productId} added to cart`);
            }
        });
    });

});
}

// Update price range - helper for the existing price range inputs
function updatePriceRange() {
    const minInput = document.getElementById('price-range-min');
    const maxInput = document.getElementById('price-range-max');

    filterState.minPrice = parseFloat(minInput.value) || 0;
    filterState.maxPrice = parseFloat(maxInput.value) || 100;

    applyFilters();
}

