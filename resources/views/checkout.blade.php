<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check out</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="../assets/icon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@200..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Passion+One:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-200 pt-28 inconsolata-regular">
<div id="header-container"></div>
<div class="max-w-screen-lg mx-auto mb-10">
    <div class="flex flex-col bg-gray-200 p-6 gap-4">
        <div id="shipping" class="flex flex-col items-start relative rounded-lg border border-gray-300 p-5">
            <p class="text-start text-lg md:text-xl font-bold">Shipping address</p>
            <div class="content"></div>
            <button class="edit text-gray-600 text-md md:text-lg absolute top-5 right-6 rounded-lg classic-clicked:hover underline">Edit</button>
        </div>

        <div id="payment" class="flex flex-col items-start relative rounded-lg border border-gray-300 p-5 ">
            <p class="text-start text-lg md:text-xl font-bold">Payment information</p>
            <div class="content"></div>
            <button class="edit text-gray-600 text-md md:text-lg absolute top-5 right-6 rounded-lg classic-clicked:hover underline">Edit</button>
        </div>

        <!-- Cash on Delivery Checkbox -->
        <div class="flex items-center mt-4">
            <input id="cash-on-delivery" type="checkbox" class="form-checkbox text-blue-500" />
            <label for="cash-on-delivery" class="ml-2 text-md">Cash on Delivery</label>
        </div>

        <div class="btn-group rounded-lg h-auto w-full flex mt-4 mb-4 justify-between" data-toggle="buttons">
            <!-- Option 1 -->
            <input id="option-1" type="radio" name="test-toggle" checked class="hidden peer">
            <label for="option-1"
                   class="btn btn-default-toggle-ghost classic active flex flex-col items-center justify-between rounded-lg p-4 w-20 h-20 md:w-25 md:h-25">
                <i class="fa-brands fa-ups text-3xl md:text-4xl"></i>
                <span class="text-xs text-center leading-tight whitespace-normal ">1-2 Days</span>
            </label>

            <!-- Option 2 -->
            <input id="option-2" type="radio" name="test-toggle" class="hidden peer">
            <label for="option-2"
                   class="btn btn-default-toggle-ghost classic active flex flex-col items-center justify-between rounded-lg p-4 w-20 h-20 md:w-25 md:h-25">
                <i class="fa-brands fa-fedex text-3xl md:text-4xl"></i>
                <span class="text-xs  text-center leading-tight whitespace-normal ">3-4 Days</span>
            </label>

            <!-- Option 3 -->
            <input id="option-3" type="radio" name="test-toggle" class="hidden peer">
            <label for="option-3"
                   class="btn btn-default-toggle-ghost classic active flex flex-col items-center justify-between rounded-lg p-4 w-20 h-20 md:w-25 md:h-25">
                <i class="fa-brands fa-dhl text-3xl md:text-4xl"></i>
                <span class="text-xs text-center leading-tight whitespace-normal ">3-7 Days</span>
            </label>

        </div>




        <div class="flex flex-col">
            <div class="flex justify-between">
                <p class="text-md md:text-lg">Order:</p>
                <p class="text-md md:text-lg">193,20</p>
            </div>

            <div class="flex justify-between">
                <p class="text-md md:text-lg">Shipping:</p>
                <p class="text-md md:text-lg">3,65</p>
            </div>

            <div class="flex justify-between font-bold">
                <p class="text-lg lg:text-xl">Total price:</p>
                <p class="text-lg lg:text-xl">€ 193,20</p>
            </div>
        </div>

        <div class="w-full flex justify-center sm:justify-end mt-4 mx-auto">
            <a href="profile.blade.php" class="btn text-center classic-clicked-border text-lg lg:text-xl inconsolata-bold text-black rounded-lg p-2 lg:pd-0 h-12 w-40 classic-clicked">Order</a>
        </div>
    </div>
    </div>
</body>
</html>

<script src="https://unpkg.com/@material-tailwind/html@2.3.2/scripts/collapse.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script src="../script/navbar.js"></script>
<script src="../script/checkout.js"></script>

