@extends('layouts.main')

@section('title', 'Order Confirmation - Flakes')

@section('content')
    <div class="max-w-screen-lg mx-auto mb-10 p-4">
        <div class="bg-gray-200 p-6 rounded-lg border border-gray-300">
            <div class="text-center mb-8">
                <div class="text-6xl text-green-500 mb-4">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold passion-one-regular animated-gradient">Order Placed Successfully!</h1>
                <p class="text-gray-600 mt-2 inconsolata-regular">Thank you for your order</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('home') }}" class="btn text-center  text-lg inconsolata-regular text-black rounded-lg p-2 h-12">
                    Continue Shopping
                </a>
                <a href="{{ route('profile') }}" class="btn text-center  text-lg inconsolata-regular text-black rounded-lg p-2 h-12">
                    View My Profile
                </a>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Clear the cart once the order is confirmed
            if (window.cartManager) {
                window.cartManager.clearCart();
                console.log('Cart cleared after successful order');
            }
        });
    </script>
@endpush
