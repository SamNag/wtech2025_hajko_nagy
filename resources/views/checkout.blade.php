@extends('layouts.main')

@section('title', 'Checkout - Flakes')

@section('extra-css')
    <style>
        /* Checkout specific styles */
        .editing .content {
            display: none;
        }
        .edit-form {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        .editing .edit-form {
            max-height: 500px;
        }
    </style>
@endsection

@section('content')
    <div class="max-w-screen-lg mx-auto mb-10">
        <!-- Checkout Header -->
        <div class="bg-gray-200 p-6 rounded-t-lg border border-gray-300 border-b-0">
            <h1 class="text-2xl md:text-3xl passion-one-regular animated-gradient text-center">Checkout</h1>
            <p class="text-center inconsolata-regular text-gray-600 mt-2">Complete your order by providing the information below</p>
        </div>

        <!-- Main Checkout Form -->
        <div class="bg-gray-200 p-6 rounded-b-lg border border-gray-300">
            <form method="POST" action="{{ route('checkout.process') }}" class="flex flex-col lg:flex-row gap-6" id="checkout-form">
                @csrf

                <!-- Cart Data field for guest users -->
                @if(!Auth::check())
                    <input type="hidden" name="cart_data" id="cart-data-input">
                @endif

                <!-- Left Column - Customer Information -->
                <div class="w-full lg:w-2/3 flex flex-col gap-6">
                    <!-- Personal Information Section -->
                    <div id="personal-info" class="flex flex-col items-start relative rounded-lg border border-gray-300 p-5 {{ Auth::check() ? 'has-data' : '' }}">
                        <p class="text-start text-lg md:text-xl font-bold">Personal information</p>

                        @if(Auth::check())
                            <!-- For logged in users -->
                            <div class="content w-full mt-4">
                                <p class="text-start text-md md:text-lg">{{ Auth::user()->name }} {{ Auth::user()->surname }}</p>
                                <p class="text-start text-md md:text-lg">{{ Auth::user()->email }}</p>
                                <p class="text-start text-md md:text-lg">{{ Auth::user()->phone ?? 'No phone number provided' }}</p>
                            </div>
                            <button type="button" class="edit text-gray-600 text-md md:text-lg absolute top-5 right-6 rounded-lg hover:underline">Edit</button>

                            <!-- Form for editing (hidden by default) -->
                            <div class="edit-form hidden w-full">
                                <div class="flex flex-col w-full gap-2 pt-2">
                                    <div class="flex flex-col md:flex-row gap-2 md:gap-4">
                                        <div class="w-full md:w-1/2">
                                            <label class="block text-md inconsolata-bold">Name</label>
                                            <input type="text" name="name"
                                                   class="edit-input w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 @error('name') border-red-500 @enderror"
                                                   value="{{ Auth::user()->name }}" placeholder="Enter your name">
                                            @error('name')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="w-full md:w-1/2">
                                            <label class="block text-md inconsolata-bold">Surname</label>
                                            <input type="text" name="surname"
                                                   class="edit-input w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 @error('surname') border-red-500 @enderror"
                                                   value="{{ Auth::user()->surname }}" placeholder="Enter your surname">
                                            @error('surname')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-md inconsolata-bold">Email</label>
                                        <input type="email" name="email"
                                               class="edit-input w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 @error('email') border-red-500 @enderror"
                                               value="{{ Auth::user()->email }}" placeholder="Enter your email">
                                        @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-md inconsolata-bold">Phone</label>
                                        <input type="text" name="phone"
                                               class="edit-input w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 @error('phone') border-red-500 @enderror"
                                               value="{{ Auth::user()->phone }}" placeholder="Enter your phone number">
                                        @error('phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        @else
                            <!-- For guest users -->
                            <div class="w-full mt-4 space-y-4">
                                <div class="flex flex-col md:flex-row gap-2 md:gap-4">
                                    <div class="w-full md:w-1/2">
                                        <label class="block text-md inconsolata-bold">Name</label>
                                        <input type="text" name="name" required
                                               class="w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 @error('name') border-red-500 @enderror"
                                               value="{{ old('name') }}" placeholder="Enter your name">
                                        @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="w-full md:w-1/2">
                                        <label class="block text-md inconsolata-bold">Surname</label>
                                        <input type="text" name="surname" required
                                               class="w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 @error('surname') border-red-500 @enderror"
                                               value="{{ old('surname') }}" placeholder="Enter your surname">
                                        @error('surname')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-md inconsolata-bold">Email</label>
                                    <input type="email" name="email" required
                                           class="w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 @error('email') border-red-500 @enderror"
                                           value="{{ old('email') }}" placeholder="Enter your email">
                                    @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-md inconsolata-bold">Phone</label>
                                    <input type="text" name="phone" required
                                           class="w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 @error('phone') border-red-500 @enderror"
                                           value="{{ old('phone') }}" placeholder="Enter your phone number">
                                    @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Shipping Address Section -->
                    <div id="shipping-address" class="flex flex-col items-start relative rounded-lg border border-gray-300 p-5">
                        <p class="text-start text-lg md:text-xl font-bold">Shipping address</p>

                        <div class="w-full mt-4 space-y-4">
                            <div class="flex flex-col">
                                <label class="block text-md inconsolata-bold">Street Address</label>
                                <input type="text" name="street" required
                                       class="w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 @error('street') border-red-500 @enderror"
                                       value="{{ old('street') }}" placeholder="Enter your street address">
                                @error('street')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex flex-col md:flex-row gap-2 md:gap-4">
                                <div class="w-full md:w-1/2">
                                    <label class="block text-md inconsolata-bold">City</label>
                                    <input type="text" name="city" required
                                           class="w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 @error('city') border-red-500 @enderror"
                                           value="{{ old('city') }}" placeholder="Enter city">
                                    @error('city')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="w-full md:w-1/2">
                                    <label class="block text-md inconsolata-bold">Postal Code</label>
                                    <input type="text" name="zip" required
                                           class="w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 @error('zip') border-red-500 @enderror"
                                           value="{{ old('zip') }}" placeholder="Enter postal code" maxlength="10">
                                    @error('zip')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-md inconsolata-bold">Country</label>
                                <input type="text" name="country" required
                                       class="w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 @error('country') border-red-500 @enderror"
                                       value="{{ old('country') }}" placeholder="Enter country">
                                @error('country')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information Section -->
                    <div id="payment-information" class="flex flex-col items-start relative rounded-lg border border-gray-300 p-5">
                        <p class="text-start text-lg md:text-xl font-bold">Payment information</p>

                        <!-- Payment Method Selection -->
                        <div class="w-full mt-4 space-y-4">
                            <div class="flex space-x-4">
                                <div class="flex items-center">
                                    <input id="payment-card" type="radio" name="payment_method" value="card"
                                           class="peer hidden" {{ old('payment_method', 'card') == 'card' ? 'checked' : '' }}>
                                    <label for="payment-card"
                                           class="ml-2 text-md inconsolata-regular px-3 py-2 rounded-lg bg-gray-200 cursor-pointer peer-checked:bg-gray-300 peer-checked:shadow-none peer-checked:ring-2 peer-checked:ring-gray-400 transition">
                                        Credit Card
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="payment-cash" type="radio" name="payment_method" value="cash"
                                           class="peer hidden" {{ old('payment_method') == 'cash' ? 'checked' : '' }}>
                                    <label for="payment-cash"
                                           class="ml-2 text-md inconsolata-regular px-3 py-2 rounded-lg bg-gray-200 cursor-pointer peer-checked:bg-gray-300 peer-checked:shadow-none peer-checked:ring-2 peer-checked:ring-gray-400 transition">
                                        Cash on Delivery
                                    </label>
                                </div>
                            </div>

                            <!-- Credit Card Details (shown when credit card is selected) -->
                            <div id="card-details" class="space-y-4">
                                <div>
                                    <label class="block text-md inconsolata-bold">Card Number</label>
                                    <input type="text" name="card_number"
                                           class="w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 @error('card_number') border-red-500 @enderror"
                                           value="{{ old('card_number') }}" placeholder="Enter card number" maxlength="16" oninput="this.value = this.value.replace(/\D/g, '')">
                                    @error('card_number')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex flex-col md:flex-row gap-2 md:gap-4">
                                    <div class="w-full md:w-1/2">
                                        <label class="block text-md inconsolata-bold">Expiration Date</label>
                                        <input type="text" name="card_expiry"
                                               class="w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 @error('card_expiry') border-red-500 @enderror"
                                               value="{{ old('card_expiry') }}" placeholder="MM/YY" maxlength="5"
                                               oninput="
                                               this.value = this.value.replace(/[^\d\/]/g, '');
                                               if (this.value.length > 2 && this.value.indexOf('/') === -1) {
                                                   this.value = this.value.slice(0, 2) + '/' + this.value.slice(2);
                                               }
                                           ">
                                        @error('card_expiry')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="w-full md:w-1/2">
                                        <label class="block text-md inconsolata-bold">Security Code</label>
                                        <input type="password" name="card_cvv"
                                               class="w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 @error('card_cvv') border-red-500 @enderror"
                                               value="{{ old('card_cvv') }}" placeholder="CVV" maxlength="4" oninput="this.value = this.value.replace(/\D/g, '')">
                                        @error('card_cvv')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Method -->
                    <div class="w-full rounded-lg border border-gray-300 p-5">
                        <p class="text-start text-lg md:text-xl font-bold mb-4">Shipping method</p>
                        <div class="btn-group rounded-lg h-auto w-full flex justify-between inconsolata-regular gap-2" data-toggle="buttons">
                            <input id="option-1" type="radio" name="shipping_method" value="ups" checked class="hidden peer">
                            <label for="option-1"
                                   class="btn btn-default-toggle-ghost classic active flex-1 flex flex-col items-center justify-between rounded-lg p-4">
                                <i class="fa-brands fa-ups text-3xl md:text-4xl"></i>
                                <span class="text-xs text-center leading-tight whitespace-normal">1-2 Days</span>
                            </label>

                            <input id="option-2" type="radio" name="shipping_method" value="fedex" class="hidden peer">
                            <label for="option-2"
                                   class="btn btn-default-toggle-ghost classic active flex-1 flex flex-col items-center justify-between rounded-lg p-4">
                                <i class="fa-brands fa-fedex text-3xl md:text-4xl"></i>
                                <span class="text-xs text-center leading-tight whitespace-normal">3-4 Days</span>
                            </label>

                            <input id="option-3" type="radio" name="shipping_method" value="dhl" class="hidden peer">
                            <label for="option-3"
                                   class="btn btn-default-toggle-ghost classic active flex-1 flex flex-col items-center justify-between rounded-lg p-4">
                                <i class="fa-brands fa-dhl text-3xl md:text-4xl"></i>
                                <span class="text-xs text-center leading-tight whitespace-normal">3-7 Days</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Order Summary -->
                <div class="w-full lg:w-1/3">
                    <div class="sticky top-24 rounded-lg border border-gray-300 p-5 bg-gray-200">
                        <h2 class="text-xl font-bold mb-4 pb-2 border-b border-gray-300">Order Summary</h2>

                        <!-- Order items summary - will be filled by JS for guests -->
                        <div class="order-items-summary mb-6">
                            @if(Auth::check() && isset($cartItems) && count($cartItems) > 0)
                                <ul class="space-y-2">
                                    @foreach($cartItems as $item)
                                        <li class="flex justify-between">
                                            <span>{{ $item->package->product->name }} ({{ $item->package->size }}) × {{ $item->quantity }}</span>
                                            <span>€{{ number_format($item->package->price * $item->quantity, 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <!-- For guests, will be filled by JS -->
                                <p class="text-center text-gray-500">Loading cart items...</p>
                            @endif
                        </div>

                        <!-- Pricing Breakdown -->
                        <div class="space-y-2 pt-2 border-t border-gray-300">
                            <div class="flex justify-between">
                                <p class="text-md md:text-lg">Subtotal:</p>
                                <p class="text-md md:text-lg" id="checkout-subtotal">€{{ number_format($subtotal ?? 0, 2) }}</p>
                            </div>

                            <div class="flex justify-between">
                                <p class="text-md md:text-lg">Shipping:</p>
                                <p class="text-md md:text-lg">€{{ number_format($shippingFee ?? 3.65, 2) }}</p>
                            </div>

                            <div class="flex justify-between font-bold pt-2 border-t border-gray-300 mt-2">
                                <p class="text-lg lg:text-xl">Total:</p>
                                <p class="text-lg lg:text-xl" id="checkout-total">€{{ number_format($total ?? 3.65, 2) }}</p>
                            </div>
                        </div>

                        <!-- Create Account Option for Guest Users -->
                        @if(!Auth::check())
                            <div class="mt-6 pt-4 border-t border-gray-300">
                                <p class="text-gray-600 mb-2">Want to track your order and shop faster next time?</p>
                                <a href="{{ route('register') }}" class="text-blue-600 hover:underline block text-center">Create an account before checkout</a>
                            </div>
                        @endif

                        <!-- Place Order Button -->
                        <div class="w-full mt-6">
                            <button type="submit" id="place-order-btn" class="w-full btn text-center classic-clicked-border text-lg inconsolata-bold text-black rounded-lg py-3 classic-clicked">
                                Place Order
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/checkout-guest.js') }}"></script>
@endpush
