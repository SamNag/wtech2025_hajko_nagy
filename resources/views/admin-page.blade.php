<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="../assets/icon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@200..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Passion+One:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-gray-200 pt-28 flex flex-col">
<!-- Sidebar -->
<aside class="hidden lg:flex w-64 h-screen bg-gray-200 pb-4 fixed top-0 left-0 flex-col border-r border-gray-300">
    <div class="flex items-center justify-center h-20 w-full border-b border-gray-300">
        <a href="home.blade.php" class="passion-one-bold text-center animated-gradient text-4xl hidden md:flex">Flakes-admin</a>
    </div>

    <nav class="flex flex-col p-4 gap-5 inconsolata-regular text-xl text-gray-600">
        <div class="flex flex-col gap-2">
            <span class="inconsolata-bold px-1 text-lg text-black">MAIN</span>
            <a href="#" data-section="dashboard" class="sidebar-link active ps-3 hover:bg-gray-300 rounded-md">
                <i class="fas fa-chart-pie"></i> <span>Dashboard</span>
            </a>
            <a href="#" data-section="users" class="sidebar-link ps-3 hover:bg-gray-300 rounded-md">
                <i class="fas fa-user"></i> <span>Users</span>
            </a>
            <a href="#" data-section="orders" class="sidebar-link ps-3 hover:bg-gray-300 rounded-md">
                <i class="fas fa-shopping-cart"></i> <span>Orders</span>
            </a>
        </div>

        <div class="flex flex-col gap-2">
            <span class="inconsolata-bold px-1 text-lg text-black">PRODUCTS</span>
            <a href="#" data-section="add-product" class="sidebar-link ps-3 hover:bg-gray-300 rounded-md">
                <i class="fas fa-plus"></i> <span>Add Product</span>
            </a>
            <a href="#" data-section="view-products" class="sidebar-link ps-3 hover:bg-gray-300 rounded-md">
                <i class="fas fa-box"></i> <span>View Products</span>
            </a>
        </div>
    </nav>
</aside>

<header class="fixed top-0 left-0 w-full z-50 bg-gray-200 border-b border-gray-300">

    <div class="flex items-center">
        <!-- Logo -->
        <a href="home.blade.php" class="w-64 passion-one-bold animated-gradient text-4xl text-center md:border-r md:border-gray-300 h-20 items-center flex justify-center">Flakes-admin</a>
        <!-- Top row -->
        <div class="flex items-center justify-end md:justify-between px-4 md:px-6 h-20 flex-1">
            <!-- Desktop search -->
            <form class="hidden md:flex items-center">
                <input type="text" id="topbar-search"
                       class="text-gray-700 border bg-gray-200 rounded-lg focus:outline-none border-gray-300 h-10 p-2.5"
                       placeholder="Search product name..." required style="width: 300px;" />
                <button type="submit" class="bg-gray-200 rounded-lg min-w-10 h-10 ml-2">
                    <i class="fa-solid fa-magnifying-glass fa-md text-gray-600"></i>
                </button>
            </form>

            <!-- Desktop logout + profile -->
            <div class="hidden lg:flex items-center gap-4">
                <a href="home.blade.php" class="flex inconsolata-bold text-gray-600 items-center">
                    <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i>
                    <span>Logout</span>
                </a>
                <img src="../assets/about/pE42RfqP.jpg" alt="User Profile"
                     class="w-10 h-10 rounded-full border border-gray-300" />
            </div>

            <!-- Hamburger on mobile -->
            <button id="menu-toggle" class="lg:hidden text-2xl text-gray-600 px-2">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Mobile search -->
    <div class="px-4 pb-4 block md:hidden">
        <form class="flex items-center">
            <input type="text" id="topbar-search-mobile"
                   class="w-full text-gray-700 border bg-gray-200 rounded-lg border-gray-300 h-10 p-2.5"
                   placeholder="Search product name..." />
            <button type="submit" class="bg-gray-200 rounded-lg min-w-10 h-10 ml-2">
                <i class="fa-solid fa-magnifying-glass text-gray-600"></i>
            </button>
        </form>
    </div>
</header>

<!-- Mobile Sidebar Drawer -->
<div id="mobile-sidebar" class="fixed inset-0 z-50 bg-black bg-opacity-30 hidden lg:hidden">
    <aside class="w-64 h-full bg-gray-200 p-4 border-r border-gray-300 flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <span class="passion-one-bold animated-gradient text-2xl">Menu</span>
            <button id="close-sidebar" class="text-2xl text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>



        <!-- Sidebar Navigation -->
        <nav class="flex flex-col gap-5 inconsolata-regular text-xl text-gray-600">
            <div class="flex flex-col gap-2">
                <span class="inconsolata-bold px-1 text-lg text-black">MAIN</span>

                <a href="#" data-section="dashboard" class="sidebar-link active ps-3 hover:bg-gray-300 rounded-md">
                    <i class="fas fa-chart-pie"></i> <span>Dashboard</span>
                </a>
                <a href="#" data-section="users" class="sidebar-link ps-3 hover:bg-gray-300 rounded-md">
                    <i class="fas fa-user"></i> <span>Users</span>
                </a>
                <a href="#" data-section="orders" class="sidebar-link ps-3 hover:bg-gray-300 rounded-md">
                    <i class="fas fa-shopping-cart"></i> <span>Orders</span>
                </a>
            </div>

            <div class="flex flex-col gap-2">
                <span class="inconsolata-bold px-1 text-lg text-black">PRODUCTS</span>
                <a href="#" data-section="add-product" class="sidebar-link ps-3 hover:bg-gray-300 rounded-md">
                    <i class="fas fa-plus"></i> <span>Add Product</span>
                </a>
                <a href="#" data-section="view-products" class="sidebar-link ps-3 hover:bg-gray-300 rounded-md">
                    <i class="fas fa-box"></i> <span>View Products</span>
                </a>
            </div>

            <div class="flex flex-col gap-2">
                <!-- Logout inside drawer -->
                <span class="inconsolata-bold px-1 text-lg text-black">OTHERS</span>
                <a href="home.blade.php" class="sidebar-link active text-gray-600 hover:bg-gray-300 ps-3 rounded-md">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                </a>
            </div>
        </nav>
    </aside>
</div>


<!-- Main Content -->
<main class="px-4 md:px-6 bg-gray-200  lg:ml-64 pb-4">

    <!-- Breadcrumb -->
    <div id="breadcrumb" class="text-gray-600 mb-4 inconsolata-regular">
        <span id="current-section">Home > <span class="font-semibold">Dashboard</span></span>
    </div>


    <!-- Sections -->
    <section id="dashboard" class="dashboard-section inconsolata-regular">
        <h1 class="text-xl lg:text-2xl font-bold mb-4">Good afternoon, Sarah!</h1>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 lg:gap-4 mb-4 lg:mb-6">
            <div class="border border-gray-300 p-4 rounded-lg text-center">
                <i class="fas fa-tag text-gray-600 lg:text-3xl md: text-2xl sm:text-xl"></i>
                <h2 class="lg:text-xl md:text-lg text-md font-bold">237</h2>
                <p>Products Sold</p>
            </div>
            <div class="border border-gray-300 p-4 rounded-lg text-center">
                <i class="fas fa-box text-gray-600 lg:text-3xl md: text-2xl sm:text-xl"></i>
                <h2 class="lg:text-xl md:text-lg text-md font-bold">345</h2>
                <p>Stock</p>
            </div>
            <div class="border border-gray-300 p-4 rounded-lg text-center">
                <i class="fas fa-dollar-sign text-gray-600 lg:text-3xl md: text-2xl sm:text-xl"></i>
                <h2 class="lg:text-xl md:text-lg text-md font-bold">€10,542</h2>
                <p>Revenue</p>
            </div>
            <div class="border border-gray-300 p-4 rounded-lg text-center">
                <i class="fas fa-shopping-cart text-gray-600 lg:text-3xl md: text-2xl sm:text-xl"></i>
                <h2 class="lg:text-xl md:text-lg text-md font-bold">53</h2>
                <p>Orders</p>
            </div>
        </div>


        <!-- Graphs -->
        <div class="overflow-auto w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="space-y-4">
                    <h2 class="text-xl lg:text-2xl inconsolata-bold">Recent Earnings</h2>
                    <div class="border border-gray-300 p-4 rounded-lg">
                        <!-- 16:9 Aspect Ratio with Minimum Height -->
                        <div class="relative w-full aspect-[16/9] min-h-[200px] max-h-[400px]">
                            <canvas id="salesChart" class="absolute top-0 left-0 w-full h-full"></canvas>
                        </div>

                    </div>
                </div>
                <div class="space-y-4">
                    <h2 class="text-xl lg:text-2xl inconsolata-bold">Recent Sales</h2>
                    <div class="border border-gray-300 p-4 rounded-lg">
                        <!-- 16:9 Aspect Ratio with Minimum Height -->
                        <div class="relative w-full aspect-[16/9] min-h-[200px] max-h-[400px]">
                            <canvas id="stockChart" class="absolute top-0 left-0 w-full h-full"></canvas>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </section>

    <!-- Users Section -->
    <section id="users" class="dashboard-section hidden inconsolata-regular">
        <h1 class="text-xl lg:text-2xl font-bold mb-1">Users</h1>
        <h2 class="text-md lg:text-lg text-gray-500 mb-4">Here is your user list data</h2>

        <!-- Desktop Table -->
        <div class="hidden md:block">
            <table class="w-full border border-gray-300 shadow rounded-lg">
                <thead>
                <tr class="border-b border-gray-300 text-black text-left">
                    <th class="p-4">Full Name</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Password</th>
                    <th class="p-4">Address</th>
                    <th class="p-4">Phone</th>
                    <th class="p-4">Actions</th>
                </tr>
                </thead>
                <tbody id="users-table-desktop">
                <!-- Filled by JS on large screens -->
                </tbody>
            </table>
        </div>

        <!-- Mobile Table (Cards) -->
        <div id="users-table-mobile" class="flex flex-col gap-4 md:hidden">
            <!-- Filled by JS on mobile -->
        </div>

        <!-- Dropdown for both views -->
        <div id="user-dropdown" class="hidden absolute bg-gray-100 shadow-lg rounded-lg p-3">
            <button id="change-password-btn" class="block w-full text-left px-4 py-2 text-blue-600 hover:bg-gray-200">
                Change Password
            </button>
            <button id="delete-user-btn" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-200">
                Delete User
            </button>
        </div>
    </section>

    <!-- Orders Section -->
    <!-- Orders Section -->
    <section id="orders" class="dashboard-section hidden inconsolata-regular">
        <h1 class="text-xl lg:text-2xl font-bold mb-1">Orders</h1>
        <h2 class="text-lg lg:text-lg text-gray-500 mb-4">Here is your order list data</h2>

        <!-- Desktop Table -->
        <div class="hidden md:block">
            <table class="w-full border border-gray-300 shadow rounded-lg">
                <thead>
                <tr class="border-b border-gray-300 text-black text-left">
                    <th class="p-4">Order ID</th>
                    <th class="p-4">Date</th>
                    <th class="p-4">Customer</th>
                    <th class="p-4">Location</th>
                    <th class="p-4">Amount</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Actions</th>
                </tr>
                </thead>
                <tbody id="orders-table">
                <!-- Orders will be injected here by JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div id="orders-table-mobile" class="flex flex-col gap-4 md:hidden">
            <!-- Mobile cards will be injected here -->
        </div>

        <!-- Dropdown Menu for Order Actions -->
        <div id="order-dropdown" class="hidden absolute bg-gray-100 shadow-lg rounded-lg p-3 z-50">
            <button id="update-status-btn" class="block w-full text-left px-4 py-2 text-blue-600 hover:bg-gray-200">Update Status</button>
            <button id="delete-order-btn" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-200 hidden">Delete Order</button>
        </div>
    </section>

    <!-- Products Section -->
    <section id="view-products" class="dashboard-section hidden inconsolata-regular">
        <h1 class="text-xl lg:text-2xl font-bold mb-1">Products</h1>
        <h2 class="text-md lg:text-lg text-gray-500 mb-4">Here is your product list data</h2>

        <!-- Desktop Table -->
        <div class="hidden md:block">
            <table class="w-full border border-gray-300 shadow rounded-lg">
                <thead>
                <tr class="border-b border-gray-300 text-black text-left">
                    <th class="p-4">Product Name</th>
                    <th class="p-4">Price (€)</th>
                    <th class="p-4">Stock</th>
                    <th class="p-4">Category</th>
                    <th class="p-4">Actions</th>
                </tr>
                </thead>
                <tbody id="products-table">
                <!-- Filled by JS -->
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div id="products-table-mobile" class="flex flex-col gap-4 md:hidden">
            <!-- Filled by JS -->
        </div>
    </section>

    <!-- Product Action Dropdown -->
    <div id="product-dropdown" class="hidden absolute bg-gray-100 shadow-lg rounded-lg p-3 z-50">
        <button id="edit-product-btn" class="block w-full text-left px-4 py-2 text-blue-600 hover:bg-gray-200">Edit</button>
        <button id="delete-product-btn" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-200">Delete</button>
    </div>


    <section id="add-product" class="dashboard-section hidden inconsolata-regular">
        <!-- Top Buttons -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl lg:text-2xl font-bold">Add Product</h1>

            <!-- Desktop Buttons -->
            <div class="hidden sm:flex items-center">
                <button id="discard-button"
                        class="px-4 py-2 rounded-lg text-gray-600 classic-clicked:hover underline">
                    Discard
                </button>
                <button class="classic-clicked-border text-black rounded-lg classic-clicked px-4 py-2 ml-2">
                    Save Product
                </button>
            </div>

            <!-- Mobile Icon Buttons -->
            <div class="flex sm:hidden items-center gap-2">
                <button id="discard-button-mobile"
                        class="classic-clicked text-gray-600 rounded-lg p-2"
                        title="Discard">
                    <i class="fas fa-trash px-1"></i>
                </button>
                <button id="save-button-mobile"
                        class="classic-clicked text-gray-600 rounded-lg p-2"
                        title="Save">
                    <i class="fa-solid fa-floppy-disk px-1"></i>
                </button>
            </div>
        </div>


        <!-- Main Grid: Media + Product Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Media Upload Section -->
            <div class="p-6 rounded-lg border border-gray-300">
                <h2 class="text-lg font-bold mb-4">Media</h2>
                <label for="drop-zone" class="block text-gray-700 font-semibold">Product images</label>
                <div id="drop-zone"
                     class="w-full h-60 border-2 border-dashed border-gray-300 rounded-lg flex flex-col justify-center items-center text-gray-400 text-lg">
                    <button id="select-button"
                            class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded-full transition-colors duration-300">
                        Select Image
                    </button>
                    <span class="mt-2 text-sm text-gray-500">Drag and drop files here</span>
                </div>
                <div id="selected-files-count" class="text-gray-500 text-sm font-medium mt-2"></div>
                <div id="selected-images" class="flex flex-wrap -mx-2 mt-4"></div>
                <input id="file-input" type="file" class="hidden" accept="image/*" multiple />
            </div>

            <!-- Product Information Section -->
            <div class="p-6 rounded-lg border border-gray-300">
                <h2 class="text-lg font-bold mb-4">Product Information</h2>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">Title</label>
                    <input type="text" id="product-name"
                           class="w-full bg-gray-200 border border-gray-300 p-2 rounded-lg"
                           placeholder="Enter product title">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">Description</label>
                    <textarea id="product-description"
                              class="w-full bg-gray-200 border border-gray-300 p-2 rounded-lg h-32"
                              placeholder="Enter product description"></textarea>
                </div>
            </div>
        </div>

        <!-- Pricing & Packaging Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <!-- Pricing -->
            <div class="p-6 rounded-lg border border-gray-300">
                <h2 class="text-lg font-bold mb-4">Pricing and Category</h2>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">Select Category</label>
                    <select id="product-category"
                            class="w-full bg-gray-200 border border-gray-300 p-2 rounded-lg">
                        <option value="minerals" selected>Minerals</option>
                        <option value="vitamins">Vitamins</option>
                        <option value="oils">Oils</option>
                    </select>
                </div>
                <div class="w-auto mb-4">
                    <label class="block text-gray-700 font-semibold">Price (€)</label>
                    <input type="number" id="product-price"
                           class="w-full bg-gray-200 border border-gray-300 p-2 rounded-lg"
                           placeholder="Enter price">
                </div>
            </div>

            <!-- Packaging / Stock Management -->
            <div class="p-6 rounded-lg border border-gray-300">
                <h2 class="text-lg font-bold mb-4">Packaging Stock</h2>
                <div id="stock-options" class="hidden">
                    <!-- Stock inputs will be dynamically added here -->
                </div>
            </div>
        </div>
    </section>

</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../script/admin-page.js"></script>

</body>
</html>
