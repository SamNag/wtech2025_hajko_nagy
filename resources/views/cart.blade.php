<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cart</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/png" href="../assets/icon.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@200..900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Passion+One:wght@400;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
  <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-200 pt-28">
<div id="header-container"></div>
<div class="max-w-screen-2xl mx-auto mb-10">
  <div class="bg-gray-200 p-6">
    <div id="cart-container" class="space-y-4 w-full lg:w-3/4 mx-auto"></div>
    <div class="flex justify-between text-xl py-6 w-full lg:w-3/4 mx-auto">
      <p class="inconsolata-bold text-lg lg:text-xl">Total price:</p>
      <p id="total-price" class="inconsolata-bold text-lg lg:text-xl pe-3">€0.00</p>
    </div>
    <div class="w-full lg:w-3/4 flex justify-center sm:justify-end mt-4 mx-auto">
      <a href="checkout.blade.php" class="btn text-center classic-clicked-border text-lg lg:text-xl inconsolata-bold text-black rounded-lg p-2 lg:pd-0 h-12 w-40 classic-clicked">Check out</a>
    </div>
  </div>
</div>

<script src="https://unpkg.com/@material-tailwind/html@2.3.2/scripts/collapse.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script src="../script/navbar.js"></script>
<script src="../script/cart.js"></script>
</body>
</html>
