<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="/assets/icon.png">
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
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggleButton = document.querySelector("[data-collapse-toggle='navbar-default']");
        const menu = document.getElementById("navbar-default");

        toggleButton.addEventListener("click", function () {
            menu.classList.toggle("hidden");
        });
    });
</script>

<div id="header-container"></div>
<!-- login form -->
<div class="container mx-auto px-4 py-6 flex flex-wrap justify-center items-center ">
    <div class="w-full md:w-1/2 lg:w-1/3 bg-gray-200 p-4  ">
        <h2 class="text-3xl inconsolata-bold animated-gradient text-center md:pt-8">LOGIN</h2>
        <form action="profile.blade.php" method="post">
            <div class="mb-4 pt-4">
                <input type="email" name="email" id="email" placeholder="Email"
                       class=" inconsolata-regular w-full px-3 py-2 text-gray-700 border bg-gray-200 rounded-lg focus:outline-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300">
            </div>
            <div class="mb-4 ">

                <input type="password" name="password" id="password" placeholder="Password"
                       class=" inconsolata-regular w-full px-3 py-2 text-gray-700 border rounded-lg bg-gray-200 focus:outline-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300">
            </div>
            <button type="submit"
                    class="inconsolata-regular w-full  text-gray-700 rounded-lg px-4 py-2 classic-clicked">Login
            </button>
        </form>
    </div>
</div>


<div class="mx-auto px-4 md:py-4 flex flex-wrap justify-center items-center">
    <div class="w-full md:w-1/2 lg:w-1/3 bg-gray-200 p-8 text-center">
        <h2 class="inconsolata-regular text-gray-500">Don't have an account? <a href="register.blade.php"
                                                                                class="inconsolata-regular text-gray-700 rounded-lg px-4 py-2">Register</a>
        </h2>
    </div>
</div>

</body>


</html>
<script src="https://unpkg.com/@material-tailwind/html@2.3.2/scripts/collapse.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script src="../script/navbar.js"></script>

