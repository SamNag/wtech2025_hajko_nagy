<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="icon.png">
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


<body class="bg-gray-200 pt-28">
<div id="header-container"></div>

<!-- register form -->
<div class="container mx-auto px-4 py-6 flex flex-wrap justify-center items-center">
    <div class="w-full md:w-1/2 lg:w-1/3 bg-gray-200 p-4">
        <h2 class="text-3xl inconsolata-bold animated-gradient text-center md:pt-8">REGISTER</h2>
        <form action="" method="post" class="mt-4">
            <div class="mb-4 ">
                <input type="text" name="name" id="name" placeholder="Name"
                       class="inconsolata-regular w-full px-3 py-2 text-gray-700 border bg-gray-200 rounded-lg focus:outline-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300">
            </div>
            <div class="mb-4">
                <input type="text" name="surname" id="surname" placeholder="Surname"
                       class="inconsolata-regular w-full px-3 py-2 text-gray-700 border bg-gray-200 rounded-lg focus:outline-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300">

            </div>
            <div class="mb-4">
                <input type="email" name="email" id="email" placeholder="Email"
                       class="inconsolata-regular w-full px-3 py-2 text-gray-700 border bg-gray-200 rounded-lg focus:outline-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300">
            </div>
            <div class="mb-4">
                <input type="tel" name="phone" id="phone" placeholder="Phone"
                       class="inconsolata-regular w-full px-3 py-2 text-gray-700 border bg-gray-200 rounded-lg focus:outline-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300">
            </div>
            <div class="mb-4">
                <input type="password" name="password" id="password" placeholder="Password"
                       class="inconsolata-regular w-full px-3 py-2 text-gray-700 border rounded-lg bg-gray-200 focus:outline-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300">
            </div>
            <div class="mb-4">
                <input type="password" name="re-password" id="re-password" placeholder="Confirm Password"
                       class="inconsolata-regular w-full px-3 py-2 text-gray-700 border rounded-lg bg-gray-200 focus:outline-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300">
            </div>
            <button type="submit"
                    class="inconsolata-regular w-full text-gray-700 rounded-lg px-4 py-2 classic-clicked">Register
            </button>

        </form>
    </div>
</div>


<div class="mx-auto px-4 md:py-4 flex flex-wrap justify-center items-center">
    <div class="w-full md:w-1/2 lg:w-1/3 bg-gray-200 p-8 text-center">
        <h2 class="inconsolata-regular text-gray-500">Already have an account? <a href="login.blade.php"
                                                                                  class="inconsolata-regular text-gray-700 rounded-lg px-4 py-2">Login</a>
        </h2>
    </div>
</div>

</body>


</html>
<script src="https://unpkg.com/@material-tailwind/html@2.3.2/scripts/collapse.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script src="../script/navbar.js"></script>


