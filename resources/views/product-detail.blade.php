@extends('layouts.main')

@section('content')
    <div class="container mx-auto pt-28 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:space-x-8 lg:space-x-12 items-center justify-center">
            <!-- Product Image Section -->
            <div class="w-full sm:w-3/4 md:w-2/5 lg:w-1/3 flex flex-col items-center mb-8 md:mb-0">
                <div class="w-full flex justify-center">
                    <img id="main-image" src="{{ asset('storage/' . $product->image1) }}" alt="{{ $product->name }}"
                         class="w-4/5 sm:w-full max-w-md object-contain hover:scale-105 transition-transform duration-300">
                </div>

                <!-- Thumbnail Images -->
                <div class="flex space-x-4 mt-4 justify-center">
                    <img src="{{ asset('storage/' . $product->image1) }}" onclick="changeImage(this)"
                         class="w-12 h-12 sm:w-16 sm:h-16 cursor-pointer border-2 border-gray-300 rounded-lg object-cover hover:border-gray-500">
                    <img src="{{ asset('storage/' . $product->image2) }}" onclick="changeImage(this)"
                         class="w-12 h-12 sm:w-16 sm:h-16 cursor-pointer border-2 border-gray-300 rounded-lg object-cover hover:border-gray-500">
                </div>
            </div>

            <!-- Product Details Section -->
            <div class="w-full sm:w-3/4 md:w-1/2 px-4 sm:px-6 pb-8">
                <h1 class="passion-one-regular animated-gradient text-2xl sm:text-3xl md:text-4xl text-center md:text-left">{{ $product->name }}</h1>
                <p class="inconsolata-regular text-md sm:text-lg mt-4 max-w-lg text-center md:text-left">
                    {{ $product->description }}
                </p>
                <p class="inconsolata-bold text-xl sm:text-2xl mt-6 sm:mt-8 text-center md:text-left">
                    Price: <span id="product-price">€{{ number_format($product->packages->first()->price ?? 0, 2) }}</span>
                </p>

                <!-- Package Selection -->
                <div class="mt-4 mb-4 md:max-w-xs">
                    <select id="package-select"
                            class="w-full md:w-64 text-base sm:text-lg inconsolata-regular text-gray-600 bg-gray-200 rounded-lg h-10 sm:h-12 p-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        @foreach ($product->packages as $package)
                            <option value="{{ $package->id }}" data-price="{{ $package->price }}">{{ $package->size }}</option>
                        @endforeach
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
                    <button id="add-to-cart-btn" class="classic-clicked-border text-lg inconsolata-bold text-black rounded-lg h-10 sm:h-12 w-full sm:w-auto px-6 mt-4 sm:mt-0 classic-clicked">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/product-detail.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize price with the first package
            updatePriceDisplay();

            // Add event listener to package select
            document.getElementById('package-select').addEventListener('change', function() {
                updatePriceDisplay();
            });

            // Add to cart button event listener
            document.getElementById('add-to-cart-btn').addEventListener('click', function() {
                const packageId = document.getElementById('package-select').value;
                const quantity = parseInt(document.getElementById('quantity').textContent);
                addToCart(packageId, quantity);
            });

            // Function to update the price display based on selected package
            function updatePriceDisplay() {
                const selectElement = document.getElementById('package-select');
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                const price = selectedOption.getAttribute('data-price');

                // Format price with 2 decimal places
                const formattedPrice = new Intl.NumberFormat('de-DE', {
                    style: 'currency',
                    currency: 'EUR',
                    minimumFractionDigits: 2
                }).format(price);

                // Update price display
                document.getElementById('product-price').textContent = formattedPrice;
            }

            // Function to add product to cart
            function addToCart(packageId, quantity) {
                // Visual feedback to show item was added
                const addButton = document.getElementById('add-to-cart-btn');
                const originalText = addButton.textContent;

                addButton.textContent = 'Added to CartController!';
                addButton.classList.add('bg-gray-300');

                setTimeout(() => {
                    addButton.textContent = originalText;
                    addButton.classList.remove('bg-gray-300');
                }, 1500);

                // Here you would typically make an AJAX call to add to cart
                console.log(`Adding package ${packageId} to cart, quantity: ${quantity}`);

                // Example AJAX call - uncomment and modify as needed for your backend
                /*
                fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        package_id: packageId,
                        quantity: quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
                */
            }
        });
    </script>
@endpush
