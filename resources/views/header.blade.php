<header class="px-4 pb-4 bg-gray-200 fixed top-0 left-0 w-full border border-gray-300 z-50">
  <nav class="flex flex-grow justify-start md:justify-between items-center space-x-8 mt-4 ml-4">
    <a href="home.blade.php" class="passion-one-bold animated-gradient text-4xl hidden md:flex">Flakes</a>
    <div class="hidden md:flex items-center space-x-8">
      <a href="products.blade.php" class="inconsolata-regular hover:text-gray-500 text-xl">Products -</a>
      <a href="about.blade.php" class="inconsolata-regular hover:text-gray-500 text-xl">About -</a>
      <a href="contact.blade.php" class="inconsolata-regular hover:text-gray-500 text-xl">Contact -</a>
    </div>
      <div class="hidden md:flex flex items-center space-x-4 mr-2">
          <a href="{{ Auth::check() ? (Auth::user()->is_admin ? route('admin') : route('profile')) : route('login') }}"
             class="classic-clicked flex items-center justify-center text-black font-bold rounded-lg h-12 w-12 fa-regular fa-user fa-lg"
             style="color: #666666;"></a>
          <a href="{{ route('cart') }}"
             class="classic-clicked flex items-center justify-center text-black font-bold rounded-lg h-12 w-12 relative"
             style="color: #666666;">
              <i class="fa-solid fa-cart-shopping fa-lg"></i>
              <span id="cart-count-products" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
          </a>
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
            <ul class="font-medium flex flex-col items-center p-4 md:p-0 mt-4 b bg-gray-200 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:bg-gray-200">
                <li><a href="{{ route('products') }}" class="inconsolata-regular hover:text-gray-500 text-xl">Products</a></li>
                <li><a href="{{ route('contact') }}" class="inconsolata-regular hover:text-gray-500 text-xl">Contact</a></li>
                <li><a href="{{ route('about') }}" class="inconsolata-regular hover:text-gray-500 text-xl">About</a></li>
                <li><a href="{{ Auth::check() ? (Auth::user()->is_admin ? route('admin') : route('profile')) : route('login') }}" class="inconsolata-regular hover:text-gray-500 text-xl">{{ Auth::check() ? (Auth::user()->is_admin ? 'Admin' : 'Profile') : 'Login' }}</a></li>
                @auth
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="inconsolata-regular hover:text-gray-500 text-xl">Logout</button>
                        </form>
                    </li>
                @endauth
                <li>
                    <a href="{{ route('cart') }}" class="inconsolata-regular hover:text-gray-500 text-xl relative">
                        Cart
                        <span id="cart-count-home-mobile" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
  </nav>
</header>
<script src="https://unpkg.com/@material-tailwind/html@2.3.2/scripts/collapse.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
