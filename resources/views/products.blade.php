<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="../assets/icon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@200..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Passion+One:wght@400;700;900&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="style.css">
</head>

<header class="fixed top-0 left-0 w-full px-4 pb-4 z-50 bg-gray-200 border border-gray-300">
    <nav class="flex flex-grow justify-start md:justify-between items-center space-x-8 mt-4 ml-4 ">
        <a href="home.blade.php" class="passion-one-bold animated-gradient text-4xl hidden md:flex">Flakes</a>

        <form class=" hidden md:flex flex items-center">
            <label for="simple-search" class="sr-only">Search</label>
            <div class="">
                <input type="text" id="simple-search"
                       class="text-gray-700 border bg-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300 block h-10 p-2.5"
                       placeholder="Search product name..." required style="width: 300px;"/>
            </div>
            <button type="submit" class=" bg-gray-200 rounded-lg  min-w-10 h-10 ml-2">
                <i class="fa-solid fa-magnifying-glass fa-md" style="color: #666666;"></i>
            </button>
        </form>


        <div class="hidden md:flex flex items-center space-x-4 mr-2">
            <a href="login.blade.php"
               class="classic-clicked flex items-center justify-center text-black font-bold rounded-lg h-12 w-12 fa-regular fa-user fa-lg"
               style="color: #666666;"></a>
            <a href="cart.blade.php"
               class="classic-clicked flex items-center justify-center text-black font-bold rounded-lg h-12 w-12 fa-regular fa-bag-shopping fa-lg"
               style="color: #666666;"></a>
        </div>
    </nav>

    <nav class="bg-gray-200 md:hidden px-4">
        <div class="max-w-screen-2xl flex flex-wrap items-center justify-center">
            <a href="home.blade.php" class="flex items-center space-x-3 rtl:space-x-reverse mr-2">
                <span class="passion-one-bold animated-gradient text-4xl">Flakes</span>
            </a>
            <div class="flex items-center justify-between flex-grow">
                <form class="md:block flex items-center mr-2 flex-grow">
                    <label for="simple-search" class="sr-only">Search</label>
                    <div class="relative w-full ">
                        <input type="text" id="simple-search-mobile"
                               class="text-gray-700 border bg-gray-200 rounded-lg focus:outline-gray-100 border-gray-300 h-10 p-2.5 w-full "
                               placeholder="Search product name..."/>
                    </div>
                    <button type="submit" class="bg-gray-200 rounded-lg  min-w-10 h-10 ml-2">
                        <i class="fa-solid fa-magnifying-glass" style="color: #666666;"></i>
                    </button>
                </form>

                <button data-collapse-toggle="navbar-default" type="button"
                        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden "
                        aria-controls="navbar-default" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M1 1h15M1 7h15M1 13h15"/>
                    </svg>
                </button>
            </div>
        </div>


        <div class="hidden w-full md:block md:w-auto items-center" id="navbar-default">
            <ul class="font-medium flex flex-col items-center p-4 md:p-0 mt-4 b bg-gray-200 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:bg-gray-200 ">
                <li><a href="products.html" class="inconsolata-regular hover:text-gray-500 text-xl">Products</a></li>
                <li><a href="contact.blade.php" class="inconsolata-regular hover:text-gray-500 text-xl">Contact</a></li>
                <li><a href="about.blade.php" class="inconsolata-regular hover:text-gray-500 text-xl">About</a></li>
                <li><a href="login.blade.php" class="inconsolata-regular hover:text-gray-500 text-xl">Profile</a></li>
                <li><a href="cart.blade.php" class="inconsolata-regular hover:text-gray-500 text-xl">Cart</a></li>
            </ul>
        </div>
    </nav>
</header>


<body class="bg-gray-200 pt-28">
<div class="max-w-screen-2xl mx-auto mb-10 inconsolata-regular text-sm md:text-lg">
    <div class="btn-group classic rounded-lg h-12 max-w-screen-3xl flex mt-4 mb-4 justify-center ml-3 mr-3 mb-10" data-toggle="buttons">
        <input id="option-1" type="radio" name="test-toggle" checked="checked">
        <label for="option-1" class="btn btn-default-toggle-ghost active w-full flex items-center justify-center rounded-l-lg text-gray-600">All</label>
        <input id="option-2" type="radio" name="test-toggle">
        <label for="option-2" class="btn btn-default-toggle-ghost active w-full flex items-center justify-center text-gray-600">Minerals</label>
        <input id="option-3" type="radio" name="test-toggle">
        <label for="option-3" class="btn btn-default-toggle-ghost active w-full flex items-center justify-center text-gray-600">Vitamins</label>
        <input id="option-4" type="radio" name="test-toggle">
        <label for="option-4" class="btn btn-default-toggle-ghost active w-full flex items-center justify-center rounded-r-lg text-gray-600">Oils</label>
    </div>

    <div class="relative pt-2">
        <div class="flex justify-between items-center">
            <h6 class="mb-0">
                <button class="relative flex items-center p-4 inconsolata-bold text-left transition-all ease-in text-gray-400 cursor-pointer rounded-t-1 group" data-collapse-target="animated-collapse-2">
                    <span></span>
                    <i class="absolute right-0 pt-1 text-base transition-transform fa fa-chevron-down group-open:rotate-180"></i>
                </button>
            </h6>
        </div>
        <div data-collapse="animated-collapse-2" class="h-0 overflow-hidden transition-all duration-300 ease-in-out">
            <div class="flex flex-wrap ml-4 mr-4 items-center justify-between max-w-screen-2xl">
                <!-- Price Range -->
                <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 pt-3 pb-3">
                    <div class="flex items-center">
                        <input type="number" id="price-range-min" class="text-gray-400 bg-gray-200 rounded-lg w-1/2 h-10 p-2 border border-gray-300" value="0" oninput="updatePriceRange()">
                        <span class="text-gray-400 mx-2">€</span>
                        <span class="text-gray-400 mx-2">-</span>
                        <input type="number" id="price-range-max" class="text-gray-400 bg-gray-200 rounded-lg w-1/2 h-10 p-2 border border-gray-300" value="100" oninput="updatePriceRange()">
                        <span class="text-gray-400 mx-2">€</span>
                    </div>
                </div>

                <div class="w-full  md:w-1/2  flex space-x-3 justify-between md:justify-end">
                    <!-- Product benefits -->
                    <div class="w-1/2 lg:w-1/3">
                        <select id="benefits-select" class="text-gray-400 bg-gray-200 rounded-lg w-full h-10 p-2 border border-gray-300">
                            <option value="all">All Benefits</option>
                            <option value="bone">Bone Health</option>
                            <option value="muscle">Muscle Function</option>
                            <option value="hair">Hair Health</option>
                            <option value="heart">Heart Health</option>
                            <option value="relaxation">Relaxation</option>
                            <option value="metabolism">Metabolism Support</option>
                            <option value="brain">Brain Function</option>
                            <option value="skin">Skin Health</option>
                        </select>
                    </div>
                    <!-- Package Availability Dropdown -->
                    <div class="w-1/2 md:w-1/3 lg:w-1/4">
                        <select id="package-select" class="text-gray-400 bg-gray-200 rounded-lg w-full h-10 p-2 border border-gray-300">
                            <option value="all">All Packages</option>
                            <option value="30pcs">30 pcs</option>
                            <option value="60pcs">60 pcs</option>
                            <option value="120pcs">120 pcs</option>
                            <option value="10ml">10 ml</option>
                            <option value="30ml">20 ml</option>
                            <option value="50ml">50 ml</option>
                        </select>
                    </div>
                    <!-- Sorting Dropdown -->
                    <div class="w-1/2 lg:w-1/3">
                        <select id="sort-select" class="text-gray-400 bg-gray-200 rounded-lg w-full h-10 p-2 border border-gray-300">
                            <option value="alphabet-asc">Alphabet (A-Z)</option>
                            <option value="alphabet-desc">Alphabet (Z-A)</option>
                            <option value="price-asc">Price (Low to High)</option>
                            <option value="price-desc">Price (High to Low)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--    Product Container-->
    <div class="flex flex-wrap w-full " id="product-container"></div>
    </div>

</body>


</html>
<script src="https://unpkg.com/@material-tailwind/html@2.3.2/scripts/collapse.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>


<script src="../script/products.js"></script>

