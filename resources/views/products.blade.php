@extends('layouts.main')

@section('title', 'Products - Flakes')

@section('extra-css')
    <style>
        /* Product-specific styles */
        .preview-image {
            transition: transform 0.3s ease;
        }
        .product-card:hover .preview-image {
            transform: scale(1.05);
        }
        .products-container {
            min-height: 900px; /* Fix height for consistent layout */
        }
    </style>
@endsection

@section('header')
    <header class="fixed top-0 left-0 w-full px-4 pb-4 z-50 bg-gray-200 border border-gray-300">
        <nav class="flex flex-grow justify-start md:justify-between items-center space-x-8 mt-4 ml-4">
            <a href="{{ route('home') }}" class="passion-one-bold animated-gradient text-4xl hidden md:flex">Flakes</a>

            <form class="hidden md:flex flex items-center" method="GET" action="{{ route('products') }}">
                <label for="simple-search" class="sr-only">Search</label>
                <div class="">
                    <input type="text" id="simple-search" name="search"
                           class="text-gray-700 border bg-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300 block h-10 p-2.5"
                           placeholder="Search product name..." value="{{ request('search') }}" style="width: 300px;"/>
                </div>
                <button type="submit" class="bg-gray-200 rounded-lg min-w-10 h-10 ml-2">
                    <i class="fa-solid fa-magnifying-glass fa-md" style="color: #666666;"></i>
                </button>
            </form>

            <div class="hidden md:flex flex items-center space-x-4 mr-2">
                <a href="{{ route('login') }}"
                   class="classic-clicked flex items-center justify-center text-black font-bold rounded-lg h-12 w-12 fa-regular fa-user fa-lg"
                   style="color: #666666;"></a>
                <a href="{{ route('cart') }}"
                   class="classic-clicked flex items-center justify-center text-black font-bold rounded-lg h-12 w-12 fa-regular fa-bag-shopping fa-lg"
                   style="color: #666666;"></a>
            </div>
        </nav>

        <nav class="bg-gray-200 md:hidden px-4">
            <div class="max-w-screen-2xl flex flex-wrap items-center justify-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-3 rtl:space-x-reverse mr-2">
                    <span class="passion-one-bold animated-gradient text-4xl">Flakes</span>
                </a>
                <div class="flex items-center justify-between flex-grow">
                    <form class="md:block flex items-center mr-2 flex-grow" method="GET" action="{{ route('products') }}">
                        <label for="simple-search" class="sr-only">Search</label>
                        <div class="relative w-full">
                            <input type="text" id="simple-search-mobile" name="search"
                                   class="text-gray-700 border bg-gray-200 rounded-lg focus:outline-gray-100 border-gray-300 h-10 p-2.5 w-full"
                                   placeholder="Search product name..." value="{{ request('search') }}"/>
                        </div>
                        <button type="submit" class="bg-gray-200 rounded-lg min-w-10 h-10 ml-2">
                            <i class="fa-solid fa-magnifying-glass" style="color: #666666;"></i>
                        </button>
                    </form>

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
                </div>
            </div>

            <div class="hidden w-full md:block md:w-auto items-center" id="navbar-default">
                <ul class="font-medium flex flex-col items-center p-4 md:p-0 mt-4 b bg-gray-200 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:bg-gray-200">
                    <li><a href="{{ route('products') }}" class="inconsolata-regular hover:text-gray-500 text-xl">Products</a></li>
                    <li><a href="{{ route('contact') }}" class="inconsolata-regular hover:text-gray-500 text-xl">Contact</a></li>
                    <li><a href="{{ route('about') }}" class="inconsolata-regular hover:text-gray-500 text-xl">About</a></li>
                    <li><a href="{{ route('login') }}" class="inconsolata-regular hover:text-gray-500 text-xl">Profile</a></li>
                    <li><a href="{{ route('cart') }}" class="inconsolata-regular hover:text-gray-500 text-xl">Cart</a></li>
                </ul>
            </div>
        </nav>
    </header>
@endsection

@section('content')
    <div class="max-w-screen-2xl mx-auto mb-10 inconsolata-regular text-sm md:text-lg">
        <form method="GET" action="{{ route('products') }}" id="filter-form">
            @if (request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            <div class="btn-group classic rounded-lg h-12 max-w-screen-3xl flex mt-4 mb-4 justify-center ml-3 mr-3 mb-10" data-toggle="buttons">
                <input id="option-1" type="radio" name="category" value="all" {{ request('category', 'all') == 'all' ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()">
                <label for="option-1" class="btn btn-default-toggle-ghost active w-full flex items-center justify-center rounded-l-lg text-gray-600">All</label>

                <input id="option-2" type="radio" name="category" value="minerals" {{ request('category') == 'minerals' ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()">
                <label for="option-2" class="btn btn-default-toggle-ghost active w-full flex items-center justify-center text-gray-600">Minerals</label>

                <input id="option-3" type="radio" name="category" value="vitamins" {{ request('category') == 'vitamins' ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()">
                <label for="option-3" class="btn btn-default-toggle-ghost active w-full flex items-center justify-center text-gray-600">Vitamins</label>

                <input id="option-4" type="radio" name="category" value="oils" {{ request('category') == 'oils' ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()">
                <label for="option-4" class="btn btn-default-toggle-ghost active w-full flex items-center justify-center rounded-r-lg text-gray-600">Oils</label>
            </div>

            <div class="relative pt-2">
                <div class="flex justify-between items-center">
                    <h6 class="mb-0">
                        <button type="button" class="relative flex items-center p-4 inconsolata-bold text-left transition-all ease-in text-gray-400 cursor-pointer rounded-t-1 group" data-collapse-target="animated-collapse-2">
                            <i class="absolute right-0 pt-1 text-base transition-transform fa fa-chevron-down group-open:rotate-180"></i>
                        </button>
                    </h6>
                </div>
                <div data-collapse="animated-collapse-2" class="h-0 overflow-hidden transition-all duration-300 ease-in-out">
                    <div>
                        <div class="flex flex-wrap ml-4 mr-4 items-center justify-between max-w-screen-2xl">
                            <!-- Price Range -->
                            <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 pt-3 pb-3">
                                <div class="flex items-center">
                                    <input type="number" id="price-range-min" name="min_price" class="text-gray-400 bg-gray-200 rounded-lg w-1/2 h-10 p-2 border border-gray-300" value="{{ request('min_price', $minPrice) }}">
                                    <span class="text-gray-400 mx-2">€</span>
                                    <span class="text-gray-400 mx-2">-</span>
                                    <input type="number" id="price-range-max" name="max_price" class="text-gray-400 bg-gray-200 rounded-lg w-1/2 h-10 p-2 border border-gray-300" value="{{ request('max_price', $maxPrice) }}">
                                    <span class="text-gray-400 mx-2">€</span>
                                </div>
                            </div>

                            <div class="w-full md:w-1/2 sm:flex grid grid-cols-2 gap-3 sm:gap-0 sm:flex-row sm:space-x-3 justify-between md:justify-end">
                                <!-- Product benefits (tags) -->
                                <div class="sm:w-1/3">
                                    <select id="tag-select" name="tag" class="text-gray-400 bg-gray-200 rounded-lg w-full h-10 p-2 border border-gray-300">
                                        <option value="">All Benefits</option>
                                        @foreach($availableTags as $tag)
                                            <option value="{{ $tag }}" {{ request('tag') == $tag ? 'selected' : '' }}>{{ ucfirst($tag) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Package Availability Dropdown -->
                                <div class="sm:w-1/3">
                                    <select id="package-select" name="package" class="text-gray-400 bg-gray-200 rounded-lg w-full h-10 p-2 border border-gray-300">
                                        <option value="">All Packages</option>
                                        @foreach($availablePackages as $package)
                                            <option value="{{ $package }}" {{ request('package') == $package ? 'selected' : '' }}>{{ $package }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Sorting Dropdown -->
                                <div class="sm:w-1/3">
                                    <select id="sort-select" name="sort" class="text-gray-400 bg-gray-200 rounded-lg w-full h-10 p-2 border border-gray-300">
                                        <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>Alphabet (A-Z)</option>
                                        <option value="name-desc" {{ request('sort') == 'name-desc' ? 'selected' : '' }}>Alphabet (Z-A)</option>
                                        <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Price (Low to High)</option>
                                        <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Price (High to Low)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Apply and Clear Filters buttons -->
                        <div class="flex items-center gap-3 ml-4 mb-8 mt-4">
                            <button type="submit" class="classic-clicked inconsolata-bold text-gray-600 rounded-lg px-6 py-2 h-10">
                                Apply Filters
                            </button>
                            <a href="{{ route('products', ['category' => request('category', 'all')]) }}" class="inconsolata-regular text-gray-600 hover:text-gray-800 underline ml-4">
                                Clear Filters
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Container with consistent height -->
            <div class="flex flex-wrap w-full products-container mt-4" id="product-container">
                @if($products->isEmpty())
                    <div class="w-full py-10 text-center text-gray-600 inconsolata-regular text-lg md:text-xl">
                        No products match your filters :(
                    </div>
                @else
                    @foreach($products as $product)
                        <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 px-4 mt-8 product-card">
                            <div class="relative flex flex-col items-center product-card-body cursor-pointer">
                                <div class="relative z-10 -mb-10 -mr-20">
                                    <img src="{{ asset($product->image1) }}" alt="{{ $product->name }}"
                                         class="preview-image w-full h-32 lg:h-40 xl:h-48 object-contain opacity-[.85]">
                                </div>
                                <div class="bg-gradient-to-tl from-gray-300 to-gray-200 rounded-lg p-4 pt-6 max-w-xs w-full border border-slate-200 shadow-sm relative hover:shadow-md transition-all duration-300">
                                    <h2 class="passion-one-regular animated-gradient text-xl md:text-2xl truncate" title="{{ $product->name }}">{{ $product->name }}</h2>
                                    <p class="inconsolata-regular text-md md:text-lg">Price: {{ $product->packages->min('price') }} €</p>
                                    <div class="flex flex-wrap mt-1">
                                        @foreach($product->tags as $tag)
                                            <span class="text-xs bg-blue-100 text-blue-800 font-medium mr-1 px-2 py-0.5 rounded">{{ $tag->tag_name }}</span>
                                        @endforeach
                                    </div>
                                    <a href="{{ route('product.detail', $product->id) }}" class="absolute inset-0 z-10"></a>
                                    <button class="add-to-cart-btn absolute bottom-3 right-3 bg-slate-200 hover:bg-white text-gray-100 hover:border hover:border-gray-400 rounded-full w-8 h-8 flex items-center justify-center shadow-md transition-all duration-300 hover:scale-105 z-20" data-product-id="{{ $product->id }}">
                                        <i class="fas fa-plus text-sm text-gray-400"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Results count -->
            <div class="text-center my-8 inconsolata-regular text-gray-600">
                Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
            </div>

            <!-- Pagination -->
            <div class="mt-4 flex justify-center">
                {{ $products->appends(request()->except('page'))->links() }}
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set auth status on the body for cart manager
            document.body.setAttribute('data-auth', "{{ Auth::check() ? 'true' : 'false' }}");

            // Handle category change to reload the page with fresh filters
            const categoryRadios = document.querySelectorAll('input[name="category"]');
            categoryRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Clear all filters except category when category changes
                    const form = document.getElementById('filter-form');

                    // Create a new form to submit only the category
                    const tempForm = document.createElement('form');
                    tempForm.method = 'GET';
                    tempForm.action = '{{ route('products') }}';

                    // Add only the category parameter
                    const categoryInput = document.createElement('input');
                    categoryInput.type = 'hidden';
                    categoryInput.name = 'category';
                    categoryInput.value = this.value;
                    tempForm.appendChild(categoryInput);

                    // If there's a search term, preserve it
                    const searchInput = form.querySelector('input[name="search"]');
                    if (searchInput && searchInput.value) {
                        const searchParam = document.createElement('input');
                        searchParam.type = 'hidden';
                        searchParam.name = 'search';
                        searchParam.value = searchInput.value;
                        tempForm.appendChild(searchParam);
                    }

                    // Add to body and submit
                    document.body.appendChild(tempForm);
                    tempForm.submit();
                });
            });

            // Add to Cart Functionality using CartManager
            const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
            addToCartButtons.forEach(button => {
                button.addEventListener('click', async function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const packageId = this.getAttribute('data-package-id');

                    // Show loading state
                    const originalHTML = this.innerHTML;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin text-sm text-gray-400"></i>';
                    button.disabled = true;

                    try {
                        // Use cart manager to add item
                        const result = await window.cartManager.addToCart(packageId, 1);

                        if (result.success) {
                            // Success - show confirmation
                            button.innerHTML = '<i class="fas fa-check text-sm text-green-500"></i>';
                        } else {
                            throw new Error(result.message || 'Failed to add item to cart');
                        }
                    } catch (error) {
                        console.error('Error adding to cart:', error);
                        button.innerHTML = '<i class="fas fa-times text-sm text-red-500"></i>';
                    }

                    // Reset button after 1.5 seconds
                    setTimeout(() => {
                        button.innerHTML = originalHTML;
                        button.disabled = false;
                    }, 1500);
                });
            });
        });
    </script>
@endpush
