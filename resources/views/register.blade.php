@extends('layouts.main')

@section('title', 'Register - Flakes')

@section('content')
    <div class="container mx-auto px-4 py-6 flex flex-wrap justify-center items-center">
        <div class="w-full md:w-1/2 lg:w-1/3 bg-gray-200 p-4">
            <h2 class="text-3xl inconsolata-bold animated-gradient text-center md:pt-8">REGISTER</h2>

            <form action="{{ route('register') }}" method="POST" class="mt-4">
                @csrf

                <div class="mb-4">
                    <input type="text" name="name" id="name" placeholder="Name" value="{{ old('name') }}"
                           class="inconsolata-regular w-full px-3 py-2 text-gray-700 border bg-gray-200 rounded-lg focus:outline-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 @error('name') border-red-500 @enderror">
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <input type="text" name="surname" id="surname" placeholder="Surname" value="{{ old('surname') }}"
                           class="inconsolata-regular w-full px-3 py-2 text-gray-700 border bg-gray-200 rounded-lg focus:outline-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 @error('surname') border-red-500 @enderror">
                    @error('surname')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <input type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}"
                           class="inconsolata-regular w-full px-3 py-2 text-gray-700 border bg-gray-200 rounded-lg focus:outline-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 @error('email') border-red-500 @enderror">
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <input type="tel" name="phone" id="phone" placeholder="Phone" value="{{ old('phone') }}"
                           class="inconsolata-regular w-full px-3 py-2 text-gray-700 border bg-gray-200 rounded-lg focus:outline-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 @error('phone') border-red-500 @enderror">
                    @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <input type="password" name="password" id="password" placeholder="Password"
                           class="inconsolata-regular w-full px-3 py-2 text-gray-700 border rounded-lg bg-gray-200 focus:outline-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 @error('password') border-red-500 @enderror">
                    @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password"
                           class="inconsolata-regular w-full px-3 py-2 text-gray-700 border rounded-lg bg-gray-200 focus:outline-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                </div>

                <button type="submit"
                        class="inconsolata-regular w-full text-gray-700 rounded-lg px-4 py-2 classic-clicked">Register
                </button>
            </form>
        </div>
    </div>

    <div class="mx-auto px-4 md:py-4 flex flex-wrap justify-center items-center">
        <div class="w-full md:w-1/2 lg:w-1/3 bg-gray-200 p-8 text-center">
            <h2 class="inconsolata-regular text-gray-500">Already have an account?
                <a href="{{ route('login') }}" class="inconsolata-regular text-gray-700 rounded-lg px-4 py-2">Login</a>
            </h2>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // If there's a success URL parameter, the user just logged in
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('loggedIn') && window.cartManager) {
                // Sync the cart from localStorage to the database
                window.cartManager.syncCart().then(result => {
                    if (result.success) {
                        console.log('Cart synced successfully');
                    }
                });
            }
        });
    </script>
@endpush
