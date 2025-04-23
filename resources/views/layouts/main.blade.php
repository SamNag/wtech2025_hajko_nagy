<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Flakes - Premium Vitamins & Supplements')</title>

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="{{ asset('assets/icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@200..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Passion+One:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    @vite(['resources/css/app.css'])

    @yield('extra-css')
</head>
<body class="bg-gray-200 @yield('body-class')" data-auth="{{ Auth::check() ? 'true' : 'false' }}">
@yield('pre-header')

<!-- Standard Header for most pages -->
@section('header')
    @include('partials.header')
@show

<main class="@yield('main-class', 'pt-28')">
    @yield('content')
</main>

<!-- Footer Section -->
@section('footer')
    @include('partials.footer')
@show

<!-- Base Scripts -->
<script src="{{ asset('js/cart-manager.js') }}"></script>
<script src="{{ asset('js/cart-initializer.js') }}"></script>
<script src="{{ asset('js/navbar.js') }}"></script>

<script src="https://unpkg.com/@material-tailwind/html@2.3.2/scripts/collapse.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

<!-- Page-specific scripts -->
@stack('scripts')
</body>
</html>
