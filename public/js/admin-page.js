document.addEventListener("DOMContentLoaded", function () {
    const sidebarLinks = document.querySelectorAll(".sidebar-link");
    const sections = document.querySelectorAll(".dashboard-section");
    const breadcrumb = document.getElementById("current-section");

    sidebarLinks.forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            sections.forEach(section => section.classList.add("hidden"));
            document.getElementById(this.dataset.section).classList.remove("hidden");

            // Determine if section belongs to MAIN or PRODUCTS
            const isProductSection = ["add-product", "view-products"].includes(this.dataset.section);
            const prefix = isProductSection ? "Products" : "Home";

            // Update breadcrumb
            breadcrumb.innerHTML = `${prefix} > <span class="font-semibold">${this.textContent.trim()}</span>`;
        });
    });
});



function generateLast7Days() {
    const dates = [];
    for (let i = 6; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        dates.push(date.toLocaleDateString('en-US', { weekday: 'short' })); // Mon, Tue, etc.
    }
    return dates;
}


const earningsData = Array.from({ length: 7 }, () => Math.floor(Math.random() * (500 - 100 + 1) + 100));

// Example product sales data in the last 30 days (top 5 products)
const productNames = ["Calcium", "Lecithin", "Magnesium", "Zinc", "Selenium"];
const productSales = [12, 8, 14, 10, 9]; // Random amounts sold

// Line Chart (Earnings for last 7 days)
new Chart(document.getElementById("salesChart"), {
    type: "line",
    data: {
        labels: generateLast7Days(),
        datasets: [{
            label: "Earnings (€)",
            data: earningsData,
            borderColor: "rgba(54, 162, 235, 1)",
            backgroundColor: "rgba(54, 162, 235, 0.2)",
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true }
        },
        plugins: {
            legend: {
                display: false // Hides legend
            }
        }
    }
});

// Bar Chart (Product Sales for last 30 days)
new Chart(document.getElementById("stockChart"), {
    type: "bar",
    data: {
        labels: productNames,
        datasets: [{
            label: "Products Sold",
            data: productSales,
            backgroundColor: [
                "rgba(255, 99, 132, 0.6)",
                "rgba(54, 162, 235, 0.6)",
                "rgba(255, 206, 86, 0.6)",
                "rgba(75, 192, 192, 0.6)",
                "rgba(153, 102, 255, 0.6)"
            ],
            borderColor: [
                "rgba(255, 99, 132, 1)",
                "rgba(54, 162, 235, 1)",
                "rgba(255, 206, 86, 1)",
                "rgba(75, 192, 192, 1)",
                "rgba(153, 102, 255, 1)"
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: { beginAtZero: true }
        },
        plugins: {
            legend: {
                display: false // Hides legend
            }
        }
    }
});


const orders = [
    { id: "#5552351", date: "26 March 2024", customer: "James Witwicky", location: "Corner Street 5th London", amount: 164.52, status: "Pending" },
    { id: "#5552358", date: "26 March 2024", customer: "David Horison", location: "981 St. John's Road London", amount: 24.55, status: "Shipped" },
    { id: "#5552375", date: "26 March 2024", customer: "Emilia Johanson", location: "67 St. John's Road London", amount: 251.16, status: "On Delivery" },
    { id: "#5552356", date: "26 March 2024", customer: "Rendy Greenlee", location: "32 The Green London", amount: 44.99, status: "Delivered" }
];

// Status Colors
const statusColors = {
    "Pending": "bg-red-100 text-red-500",
    "Shipped": "bg-yellow-100 text-yellow-500",
    "On Delivery": "bg-blue-100 text-blue-500",
    "Delivered": "bg-green-100 text-green-500"
};

// Status Progression Logic
const nextStatus = {
    "Pending": "Shipped",
    "Shipped": "On Delivery",
    "On Delivery": "Delivered",
    "Delivered": null
};

function renderOrders() {
    const tableBody = document.getElementById("orders-table");
    const mobileBody = document.getElementById("orders-table-mobile");
    tableBody.innerHTML = "";
    mobileBody.innerHTML = "";

    orders.forEach((order, index) => {
        // Desktop row
        const row = document.createElement("tr");
        row.className = "border-b border-gray-300";
        row.innerHTML = `
      <td class="p-4">${order.id}</td>
      <td class="p-4">${order.date}</td>
      <td class="p-4">${order.customer}</td>
      <td class="p-4">${order.location}</td>
      <td class="p-4 text-nowrap">€ ${order.amount}</td>
      <td class="p-4">
        <span class="px-3 py-1 text-sm text-nowrap font-semibold rounded-md ${statusColors[order.status]}">
          ${order.status}
        </span>
      </td>
      <td class="p-4 relative">
        <button class="text-gray-600 hover:text-gray-900" onclick="openDropdown(event, ${index})">
          <i class="fas fa-ellipsis-h"></i>
        </button>
      </td>
    `;
        tableBody.appendChild(row);

        // Mobile card
        const card = document.createElement("div");
        card.className = "border border-gray-300 rounded-lg p-4 shadow";
        card.innerHTML = `
      <div><span class="font-semibold">Order ID:</span> ${order.id}</div>
      <div><span class="font-semibold">Date:</span> ${order.date}</div>
      <div><span class="font-semibold">Customer:</span> ${order.customer}</div>
      <div><span class="font-semibold">Location:</span> ${order.location}</div>
      <div><span class="font-semibold">Amount:</span> € ${order.amount}</div>
      <div class="flex items-center mt-2 gap-4">
        <span class="px-3 py-1 text-sm font-semibold rounded-md ${statusColors[order.status]} inline-block">
          ${order.status}
        </span>
        <button class="text-gray-600 underline text-sm hover:text-gray-800 flex items-center"
                onclick="openDropdown(event, ${index})">
          Update Status
        </button>
      </div>
    `;
        mobileBody.appendChild(card);
    });
}


let selectedOrderIndex = null;

// Open Dropdown
function openDropdown(event, index) {
    selectedOrderIndex = index;
    const dropdown = document.getElementById("order-dropdown");
    dropdown.classList.remove("hidden");

    const order = orders[index];
    const updateStatusBtn = document.getElementById("update-status-btn");
    const deleteOrderBtn = document.getElementById("delete-order-btn");

    if (nextStatus[order.status]) {
        updateStatusBtn.textContent = `Set to ${nextStatus[order.status]}`;
        updateStatusBtn.classList.remove("hidden");
    } else {
        updateStatusBtn.classList.add("hidden");
    }

    if (order.status === "Delivered") {
        deleteOrderBtn.classList.remove("hidden");
    } else {
        deleteOrderBtn.classList.add("hidden");
    }

    dropdown.style.top = `${event.clientY + window.scrollY}px`;
    dropdown.style.left = `${event.clientX}px`;
}

// Update Status
document.getElementById("update-status-btn").addEventListener("click", () => {
    if (selectedOrderIndex !== null) {
        const order = orders[selectedOrderIndex];
        if (nextStatus[order.status]) {
            order.status = nextStatus[order.status];
            renderOrders();
        }
    }
    document.getElementById("order-dropdown").classList.add("hidden");
});

// Delete Order
document.getElementById("delete-order-btn").addEventListener("click", () => {
    if (selectedOrderIndex !== null) {
        orders.splice(selectedOrderIndex, 1);
        renderOrders();
    }
    document.getElementById("order-dropdown").classList.add("hidden");
});

// Close Dropdown on Click Outside
document.addEventListener("click", (event) => {
    const dropdown = document.getElementById("order-dropdown");

    const isTrigger = event.target.closest("button")?.onclick?.toString().includes("openDropdown");
    if (!dropdown.contains(event.target) && !isTrigger) {
        dropdown.classList.add("hidden");
    }
});


// Initial Render
renderOrders();

const users = [
    {
        fullName: "John Doe",
        email: "john.doe@example.com",
        password: "12345678",
        address: "123 Main St, New York, NY",
        phone: "+1 234 567 890"
    },
    {
        fullName: "Jane Smith",
        email: "jane.smith@example.com",
        password: "abcdefgh",
        address: "456 Park Ave, Los Angeles, CA",
        phone: "+1 987 654 321"
    },
    {
        fullName: "Alice Johnson",
        email: "alice.johnson@example.com",
        password: "qwerty123",
        address: "789 Elm St, Chicago, IL",
        phone: "+1 555 444 333"
    }
];

let selectedUserIndex = null;

function renderUsers() {
    const desktopBody = document.getElementById("users-table-desktop");
    const mobileBody = document.getElementById("users-table-mobile");
    desktopBody.innerHTML = "";
    mobileBody.innerHTML = "";

    users.forEach((user, index) => {
        // Desktop row
        const row = document.createElement("tr");
        row.className = "border-b border-gray-300";
        row.innerHTML = `
      <td class="p-4">${user.fullName}</td>
      <td class="p-4">${user.email}</td>
      <td class="p-4">${"●".repeat(user.password.length)} (${user.password.length} chars)</td>
      <td class="p-4">${user.address}</td>
      <td class="p-4 text-nowrap">${user.phone}</td>
      <td class="p-4 relative">
        <button class="text-gray-600 hover:text-gray-900" onclick="openUserDropdown(event, ${index})">
          <i class="fas fa-ellipsis-h"></i>
        </button>
      </td>
    `;
        desktopBody.appendChild(row);

        // Mobile card
        const card = document.createElement("div");
        card.className = "border border-gray-300 rounded-lg p-4 shadow";
        card.innerHTML = `
      <div><span class="font-semibold">Full Name:</span> ${user.fullName}</div>
      <div><span class="font-semibold">Email:</span> ${user.email}</div>
      <div><span class="font-semibold">Password:</span> ${"●".repeat(user.password.length)} (${user.password.length} chars)</div>
      <div><span class="font-semibold">Address:</span> ${user.address}</div>
      <div><span class="font-semibold">Phone:</span> ${user.phone}</div>

      <!-- Underlined link-style button -->
      <div class="mt-2">
        <button class="text-gray-600 underline text-sm hover:text-gray-800"
                onclick="openUserDropdown(event, ${index})">
          Actions
        </button>
      </div>
    `;
        mobileBody.appendChild(card);
    });
}



// Open Dropdown for Actions
function openUserDropdown(event, index) {
    selectedUserIndex = index;
    const dropdown = document.getElementById("user-dropdown");
    dropdown.classList.remove("hidden");

    dropdown.style.top = `${event.clientY + window.scrollY}px`;
    dropdown.style.left = `${event.clientX}px`;
}

// Change Password (Placeholder)
document.getElementById("change-password-btn").addEventListener("click", () => {
    alert("Change Password functionality coming soon!");
    document.getElementById("user-dropdown").classList.add("hidden");
});

// Delete User
document.getElementById("delete-user-btn").addEventListener("click", () => {
    if (selectedUserIndex !== null) {
        users.splice(selectedUserIndex, 1);
        renderUsers();
    }
    document.getElementById("user-dropdown").classList.add("hidden");
});

document.addEventListener("click", (event) => {
    const dropdown = document.getElementById("user-dropdown");

    // Only close the dropdown if the click was NOT inside the dropdown AND NOT on a manage button
    const isManageButton = event.target.closest("button")?.onclick?.toString().includes("openUserDropdown");

    if (!dropdown.contains(event.target) && !isManageButton) {
        dropdown.classList.add("hidden");
    }
});



// Initial Render
renderUsers();

const fileInput = document.getElementById("file-input");
const dropZone = document.getElementById("drop-zone");
const selectedImages = document.getElementById("selected-images");
const selectButton = document.getElementById("select-button");
const selectedFilesCount = document.getElementById("selected-files-count");

const MAX_IMAGES = 2;

selectButton.addEventListener("click", () => {
    if (selectedImages.children.length < MAX_IMAGES) {
        fileInput.click();
    }
});

fileInput.addEventListener("change", handleFiles);
dropZone.addEventListener("dragover", handleDragOver);
dropZone.addEventListener("dragleave", handleDragLeave);
dropZone.addEventListener("drop", handleDrop);

function handleFiles() {
    const fileList = this.files;
    addImages(fileList);
}

function handleDragOver(event) {
    event.preventDefault();
    dropZone.classList.add("border-blue-500", "text-blue-500");
}

function handleDragLeave(event) {
    event.preventDefault();
    dropZone.classList.remove("border-blue-500", "text-blue-500");
}

function handleDrop(event) {
    event.preventDefault();
    const fileList = event.dataTransfer.files;
    addImages(fileList);
    dropZone.classList.remove("border-blue-500", "text-blue-500");
}

function addImages(fileList) {
    if (selectedImages.children.length >= MAX_IMAGES) return;

    for (const file of fileList) {
        if (selectedImages.children.length < MAX_IMAGES) {
            const imageWrapper = document.createElement("div");
            imageWrapper.classList.add("relative", "mx-2", "mb-2");

            const image = document.createElement("img");
            image.src = URL.createObjectURL(file);
            image.classList.add("preview-image","w-32", "h-32", "object-cover", "rounded-lg");


            const removeButton = document.createElement("button");
            removeButton.innerHTML = "&times;";
            removeButton.classList.add(
                "absolute", "top-1", "right-1", "h-6", "w-6",
                "bg-gray-700", "text-white", "text-xs", "rounded-full",
                "flex", "items-center", "justify-center",
                "opacity-50", "hover:opacity-100", "transition-opacity",
                "focus:outline-none"
            );

            removeButton.addEventListener("click", () => {
                imageWrapper.remove();
                updateUI();
            });

            imageWrapper.appendChild(image);
            imageWrapper.appendChild(removeButton);
            selectedImages.appendChild(imageWrapper);
        }
    }
    updateUI();
}

function updateUI() {
    const count = selectedImages.children.length;
    selectedFilesCount.textContent = count > 0 ? `${count} file${count === 1 ? "" : "s"} selected` : "";

    // Hide the select button if 2 images are uploaded
    if (count >= MAX_IMAGES) {
        selectButton.classList.add("hidden");
        dropZone.classList.add("hidden");
    } else {
        selectButton.classList.remove("hidden");
        dropZone.classList.remove("hidden");
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const categorySelect = document.getElementById("product-category");
    const stockContainer = document.getElementById("stock-options");

    // Define stock options for each category
    const stockOptions = {
        minerals: ["30pcs", "60pcs", "90pcs"],
        vitamins: ["30pcs", "60pcs", "90pcs"],
        oils: ["5ml", "10ml", "30ml"]
    };

    // Function to update stock inputs based on category
    function updateStockInputs(category) {
        stockContainer.innerHTML = ""; // Clear existing options
        stockContainer.classList.remove("hidden");

        if (!stockOptions[category]) return;

        stockOptions[category].forEach((type) => {
            const stockRow = document.createElement("div");
            stockRow.classList.add("flex", "items-center", "gap-8", "mb-2");

            const stockInput = document.createElement("input");
            stockInput.type = "number";
            stockInput.min = "0";
            stockInput.classList.add("w-16", "bg-gray-200", "border", "border-gray-300", "p-2", "rounded-lg");
            stockInput.placeholder = "0";

            const timesSymbol = document.createElement("span");
            timesSymbol.textContent = "X";

            const stockLabel = document.createElement("span");
            stockLabel.textContent = type;
            stockLabel.classList.add("text-gray-700");

            stockRow.appendChild(stockInput);
            stockRow.appendChild(timesSymbol);
            stockRow.appendChild(stockLabel);
            stockContainer.appendChild(stockRow);
        });
    }

    // Auto-select first category and populate stock inputs
    categorySelect.selectedIndex = 0;
    updateStockInputs(categorySelect.value);

    // Update stock inputs when category changes
    categorySelect.addEventListener("change", function () {
        updateStockInputs(this.value);
    });
});

document.getElementById("discard-button").addEventListener("click", function () {
    location.reload(); // Reloads the page to fully reset the form
});

const products = [
    {
        name: "Calcium",
        price: 19.99,
        stock: { "30pcs": 3, "60pcs": 6, "90pcs": 0 },
        category: "minerals"
    },
    {
        name: "Omega 3 Oil",
        price: 24.50,
        stock: { "5ml": 0, "10ml": 2, "30ml": 5 },
        category: "oils"
    },
    {
        name: "Vitamin C",
        price: 14.75,
        stock: { "30pcs": 0, "60pcs": 0, "90pcs": 0 },
        category: "vitamins"
    }
];

// Category colors
const categoryColors = {
    minerals: "bg-blue-100 text-blue-500",
    vitamins: "bg-green-100 text-green-500",
    oils: "bg-yellow-100 text-yellow-500"
};

let selectedProductIndex = null;

// Function to format stock display
function formatStock(stock, onMobile) {
    let stockList = [];
    for (let [type, amount] of Object.entries(stock)) {
        if (amount > 0) {
            stockList.push(`<div>${amount} × ${type}</div>`);
        }
    }
    return stockList.length > 0
        ? stockList.join(onMobile ? "|" : "")
        : `<span class="px-2 py-1 text-sm font-semibold bg-red-500 text-white rounded-md">No Stock</span>`;
}

function renderProducts() {
    const tableBody = document.getElementById("products-table");
    const mobileBody = document.getElementById("products-table-mobile");
    tableBody.innerHTML = "";
    mobileBody.innerHTML = "";

    products.forEach((product, index) => {
        // Desktop row
        const row = document.createElement("tr");
        row.className = "border-b border-gray-300";
        row.innerHTML = `
      <td class="p-4">${product.name}</td>
      <td class="p-4">€ ${product.price.toFixed(2)}</td>
      <td class="p-4">${formatStock(product.stock, false)}</td>
      <td class="p-4">
        <span class="px-3 py-1 text-sm font-semibold rounded-md ${categoryColors[product.category] || "bg-gray-200 text-gray-700"}">
          ${product.category.charAt(0).toUpperCase() + product.category.slice(1)}
        </span>
      </td>
      <td class="p-4 relative">
        <button class="text-gray-600 hover:text-gray-900" onclick="openProductDropdown(event, ${index})">
          <i class="fas fa-ellipsis-h"></i>
        </button>
      </td>
    `;
        tableBody.appendChild(row);

        // Mobile card
        const card = document.createElement("div");
        card.className = "border border-gray-300 rounded-lg p-4 shadow";
        card.innerHTML = `
      <div><span class="font-semibold">Product Name:</span> ${product.name}</div>
      <div><span class="font-semibold">Price:</span> € ${product.price.toFixed(2)}</div>
      <div class="flex gap-2"><span class="font-semibold">Stock: </span><div class="flex gap-2">${formatStock(product.stock, true)}</div></div>
      <div class="flex gap-2 items-center">
        <span class="font-semibold">Category:</span>
        <span class="px-3 py-1 text-sm font-semibold rounded-md inline-block mt-1 ${categoryColors[product.category] || "bg-gray-200 text-gray-700"}">
          ${product.category.charAt(0).toUpperCase() + product.category.slice(1)}
        </span>
      </div>
      <div class="mt-1">
        <button class="text-gray-600 underline text-sm hover:text-gray-800"
                onclick="openProductDropdown(event, ${index})">
          Manage Product
        </button>
      </div>
    `;
        mobileBody.appendChild(card);
    });
}


// Function to open dropdown menu for actions
function openProductDropdown(event, index) {
    selectedProductIndex = index;
    const dropdown = document.getElementById("product-dropdown");
    dropdown.classList.remove("hidden");

    dropdown.style.top = `${event.clientY + window.scrollY}px`;
    dropdown.style.left = `${event.clientX}px`;
}

// Placeholder for editing product
document.getElementById("edit-product-btn").addEventListener("click", () => {
    alert("Edit Product functionality coming soon!");
    document.getElementById("product-dropdown").classList.add("hidden");
});

// Function to delete a product
document.getElementById("delete-product-btn").addEventListener("click", () => {
    if (selectedProductIndex !== null) {
        products.splice(selectedProductIndex, 1);
        renderProducts();
    }
    document.getElementById("product-dropdown").classList.add("hidden");
});

document.addEventListener("click", (event) => {
    const dropdown = document.getElementById("product-dropdown");
    const isTrigger = event.target.closest("button")?.onclick?.toString().includes("openProductDropdown");

    if (!dropdown.contains(event.target) && !isTrigger) {
        dropdown.classList.add("hidden");
    }
});


// Initial render
renderProducts();

document.getElementById("menu-toggle").addEventListener("click", () => {
    document.getElementById("mobile-sidebar").classList.remove("hidden");
});

document.getElementById("close-sidebar").addEventListener("click", () => {
    document.getElementById("mobile-sidebar").classList.add("hidden");
});





