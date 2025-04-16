<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
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

<body class="bg-gray-200">
<div id="header-container"></div>

<!-- Product Detail -->
<div class="container mx-auto pt-28 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:space-x-8 lg:space-x-12 items-center justify-center">
        <!-- Product Image Section -->
        <div class="w-full sm:w-3/4 md:w-2/5 lg:w-1/3 flex flex-col items-center mb-8 md:mb-0">
            <div class="w-full flex justify-center">
                <img id="main-image" src="../assets/images/mg.png" alt="product"
                     class="w-4/5 sm:w-full max-w-md object-contain hover:scale-105 transition-transform duration-300">
            </div>

            <!-- Thumbnail Images -->
            <div class="flex space-x-4 mt-4 justify-center">
                <img src="../assets/images/kril.png" onclick="changeImage(this)"
                     class="w-12 h-12 sm:w-16 sm:h-16 cursor-pointer border-2 border-gray-300 rounded-lg object-cover hover:border-gray-500">
                <img src="../assets/images/mg.png" onclick="changeImage(this)"
                     class="w-12 h-12 sm:w-16 sm:h-16 cursor-pointer border-2 border-gray-300 rounded-lg object-cover hover:border-gray-500">
            </div>
        </div>

        <!-- Product Details Section -->
        <div class="w-full sm:w-3/4 md:w-1/2 px-4 sm:px-6 pb-8">
            <h1 class="passion-one-regular animated-gradient text-2xl sm:text-3xl md:text-4xl text-center md:text-left">Magnesium</h1>
            <p class="inconsolata-regular text-md sm:text-lg mt-4 max-w-lg text-center md:text-left">
                Magnesium is a vital mineral crucial for bone health,
                muscle function, and energy production. While found in various foods like leafy greens and nuts,
                supplementation may be necessary for those with inadequate intake or specific medical conditions.
            </p>
            <p class="inconsolata-bold text-xl sm:text-2xl mt-6 sm:mt-8 text-center md:text-left">Price: 20,22 €</p>

            <!-- Package Selection - Fixed Width -->
            <div class="mt-4 mb-4 md:max-w-xs">
                <select id="package-select"
                        class="w-full md:w-64 text-base sm:text-lg inconsolata-regular text-gray-600 bg-gray-200 rounded-lg h-10 sm:h-12 p-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    <option value="30pcs">30 pcs</option>
                    <option value="60pcs">60 pcs</option>
                    <option value="90pcs">120 pcs</option>
                </select>
            </div>

            <!-- Quantity & Buy Button -->
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 sm:gap-4 mt-4 sm:mt-6">
                <div class="flex items-center">
                    <button class="classic-clicked text-lg inconsolata-bold text-black rounded-lg h-10 w-10 sm:h-12 sm:w-12 flex items-center justify-center"
                            onclick="decreaseQuantity()">-
                    </button>
                    <span id="quantity"
                          class="text-lg inconsolata-regular text-center text-black h-10 w-12 sm:h-12 sm:w-16 py-2 sm:py-3 text-center">1</span>
                    <button class="classic-clicked text-lg inconsolata-bold text-black rounded-lg h-10 w-10 sm:h-12 sm:w-12 flex items-center justify-center"
                            onclick="increaseQuantity()">+
                    </button>
                </div>
                <button class="classic-clicked-border text-lg inconsolata-bold text-black rounded-lg h-10 sm:h-12 w-full sm:w-auto px-6 mt-4 sm:mt-0 classic-clicked">
                    Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://unpkg.com/@material-tailwind/html@2.3.2/scripts/collapse.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script src="../script/navbar.js"></script>
<script src="../script/product-detail.js"></script>

<script>
    function changeImage(element) {
        let mainImage = document.getElementById("main-image");
        mainImage.src = element.src;
    }

    function decreaseQuantity() {
        let quantityElement = document.getElementById("quantity");
        let quantity = parseInt(quantityElement.innerText);
        if (quantity > 1) {
            quantityElement.innerText = quantity - 1;
        }
    }

    function increaseQuantity() {
        let quantityElement = document.getElementById("quantity");
        let quantity = parseInt(quantityElement.innerText);
        quantityElement.innerText = quantity + 1;
    }
</script>

</body>
</html>