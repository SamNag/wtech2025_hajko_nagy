@extends('layouts.main')

@section('title', 'Login - Flakes')

@section('content')
    <div class="container mx-auto px-4 py-6 flex flex-wrap justify-center items-center">
        <div class="w-full md:w-1/2 lg:w-1/3 bg-gray-200 p-4">
            <h2 class="text-3xl inconsolata-bold animated-gradient text-center md:pt-8">LOGIN</h2>

            <!-- Session Status (for success/error messages) -->
            @if (session('status'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="mt-4">
                @csrf

                <div class="mb-4 pt-4">
                    <input type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}"
                           class="inconsolata-regular w-full px-3 py-2 text-gray-700 border bg-gray-200 rounded-lg focus:outline-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300 @error('email') border-red-500 @enderror">
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <input type="password" name="password" id="password" placeholder="Password"
                           class="inconsolata-regular w-full px-3 py-2 text-gray-700 border rounded-lg bg-gray-200 focus:outline-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300 @error('password') border-red-500 @enderror">
                    @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="remember" class="form-checkbox h-5 w-5 text-gray-600 rounded">
                        <span class="ml-2 text-gray-700 inconsolata-regular">Remember me</span>
                    </label>
                </div>

                <button type="submit"
                        class="inconsolata-regular w-full text-gray-700 rounded-lg px-4 py-2 classic-clicked">Login
                </button>
            </form>

            <!-- Password Reset Link -->
            @if (Route::has('password.request'))
                <div class="mt-4 text-center">
                    <a href="{{ route('password.request') }}" class="text-gray-600 hover:text-gray-800 underline inconsolata-regular">
                        Forgot your password?
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="mx-auto px-4 md:py-4 flex flex-wrap justify-center items-center">
        <div class="w-full md:w-1/2 lg:w-1/3 bg-gray-200 p-8 text-center">
            <h2 class="inconsolata-regular text-gray-500">Don't have an account?
                <a href="{{ route('register') }}" class="inconsolata-regular text-gray-700 rounded-lg px-4 py-2">Register</a>
            </h2>
        </div>
    </div>

    <!-- Add cart sync script to transfer localStorage cart to database after login -->
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
@endsection
