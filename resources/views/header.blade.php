<header class="px-4 pb-4 bg-gray-200 fixed top-0 left-0 w-full border border-gray-300 z-50">
  <nav class="flex flex-grow justify-start md:justify-between items-center space-x-8 mt-4 ml-4">
    <a href="home.blade.php" class="passion-one-bold animated-gradient text-4xl hidden md:flex">Flakes</a>
    <div class="hidden md:flex items-center space-x-8">
      <a href="products.blade.php" class="inconsolata-regular hover:text-gray-500 text-xl">Products -</a>
      <a href="about.blade.php" class="inconsolata-regular hover:text-gray-500 text-xl">About -</a>
      <a href="contact.blade.php" class="inconsolata-regular hover:text-gray-500 text-xl">Contact -</a>
    </div>
    <div class="hidden md:flex flex items-center space-x-4 mr-2">
      <a href="login.blade.php"
         class="classic-clicked flex items-center justify-center text-gray-600 font-bold rounded-lg h-12 w-12 fa-regular fa-user fa-lg"></a>
      <a href="cart.blade.php"
         class="classic-clicked flex items-center justify-center text-gray-600 font-bold rounded-lg h-12 w-12 fa-regular fa-bag-shopping fa-lg"></a>
    </div>
  </nav>

  <nav class="bg-gray-200 md:hidden px-4">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between">
      <a href="home.blade.php" class="flex items-center space-x-3 rtl:space-x-reverse">
        <span class="passion-one-bold animated-gradient text-4xl">Flakes</span>
      </a>
      <button data-collapse-toggle="navbar-default" type="button"
              class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden"
              aria-controls="navbar-default" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
             viewBox="0 0 17 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M1 1h15M1 7h15M1 13h15"/>
        </svg>
      </button>
      <div class="hidden w-full md:block md:w-auto items-center" id="navbar-default">
        <ul class="font-medium flex flex-col items-center p-4 md:p-0 mt-4 bg-gray-200 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:bg-gray-200">
          <li><a href="products.blade.php" class="inconsolata-regular hover:text-gray-500 text-xl">Products</a></li>
          <li><a href="contact.blade.php" class="inconsolata-regular hover:text-gray-500 text-xl">Contact</a></li>
          <li><a href="about.blade.php" class="inconsolata-regular hover:text-gray-500 text-xl">About</a></li>
          <li><a href="login.blade.php" class="inconsolata-regular hover:text-gray-500 text-xl">Profile</a></li>
          <li><a href="cart.blade.php" class="inconsolata-regular hover:text-gray-500 text-xl">Cart</a></li>
        </ul>
      </div>
    </div>
  </nav>
</header>
<script src="https://unpkg.com/@material-tailwind/html@2.3.2/scripts/collapse.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
