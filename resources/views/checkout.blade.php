@extends('layouts.main')

@section('title', 'Checkout - Flakes')

@section('content')
    <div class="max-w-screen-lg mx-auto mb-10">
        <form method="POST" action="{{ route('checkout.process') }}" class="flex flex-col bg-gray-200 p-6 gap-4">
            @csrf

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
            <div class="btn-group rounded-lg h-auto w-full flex mt-4 mb-4 justify-between inconsolata-regular" data-toggle="buttons">
                <input id="option-1" type="radio" name="shipping_method" value="ups" checked class="hidden peer">
                <label for="option-1"
                       class="btn btn-default-toggle-ghost classic active flex flex-col items-center justify-between rounded-lg p-4 w-20 h-20 md:w-25 md:h-25">
                    <i class="fa-brands fa-ups text-3xl md:text-4xl"></i>
                    <span class="text-xs text-center leading-tight whitespace-normal">1-2 Days</span>
                </label>

                <input id="option-2" type="radio" name="shipping_method" value="fedex" class="hidden peer">
                <label for="option-2"
                       class="btn btn-default-toggle-ghost classic active flex flex-col items-center justify-between rounded-lg p-4 w-20 h-20 md:w-25 md:h-25">
                    <i class="fa-brands fa-fedex text-3xl md:text-4xl"></i>
                    <span class="text-xs text-center leading-tight whitespace-normal">3-4 Days</span>
                </label>

                <input id="option-3" type="radio" name="shipping_method" value="dhl" class="hidden peer">
                <label for="option-3"
                       class="btn btn-default-toggle-ghost classic active flex flex-col items-center justify-between rounded-lg p-4 w-20 h-20 md:w-25 md:h-25">
                    <i class="fa-brands fa-dhl text-3xl md:text-4xl"></i>
                    <span class="text-xs text-center leading-tight whitespace-normal">3-7 Days</span>
                </label>
            </div>

            <!-- Order Summary -->
            <div class="flex flex-col">
                <div class="flex justify-between">
                    <p class="text-md md:text-lg">Subtotal:</p>
                    <p class="text-md md:text-lg">€{{ number_format($subtotal, 2) }}</p>
                </div>

                <div class="flex justify-between">
                    <p class="text-md md:text-lg">Shipping:</p>
                    <p class="text-md md:text-lg">€{{ number_format($shippingFee, 2) }}</p>
                </div>

                <div class="flex justify-between font-bold">
                    <p class="text-lg lg:text-xl">Total price:</p>
                    <p class="text-lg lg:text-xl">€{{ number_format($total, 2) }}</p>
                </div>
            </div>

            <!-- Place Order Button -->
            <div class="w-full flex justify-center sm:justify-end mt-4 mx-auto">
                <button type="submit" class="btn text-center classic-clicked-border text-lg lg:text-xl inconsolata-bold text-black rounded-lg p-2 lg:pd-0 h-12 w-40 classic-clicked">
                    Place Order
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cardRadio = document.getElementById('payment-card');
            const cashRadio = document.getElementById('payment-cash');
            const cardDetails = document.getElementById('card-details');

            // Function to toggle card details visibility
            function toggleCardDetails() {
                if (cardRadio.checked) {
                    cardDetails.classList.remove('hidden');
                    // Make card fields required
                    document.querySelectorAll('#card-details input').forEach(input => {
                        input.required = true;
                    });
                } else {
                    cardDetails.classList.add('hidden');
                    // Remove required attribute from card fields
                    document.querySelectorAll('#card-details input').forEach(input => {
                        input.required = false;
                    });
                }
            }

            // Set initial state
            toggleCardDetails();

            // Add event listeners
            cardRadio.addEventListener('change', toggleCardDetails);
            cashRadio.addEventListener('change', toggleCardDetails);
        });
    </script>
@endpush
