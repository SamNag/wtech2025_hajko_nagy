@extends('layouts.main')

@section('title', 'Flakes - Premium Vitamins & Supplements')

@section('body-class', '')

@section('extra-css')
    <style>
        /* Any home-specific styles can go here */
    </style>
@endsection

@section('pre-header')
    <!-- Special transparent header for home page that overrides the default header -->
    <header class="px-4 pb-4 bg-transparent absolute top-0 left-0 w-full z-50 bg-transparent">
        <nav class="flex flex-grow justify-start md:justify-between items-center space-x-8 mt-4 ml-4">
            <a href="{{ route('home') }}" class="passion-one-bold animated-gradient text-4xl hidden md:flex">Flakes</a>
            <div class="hidden md:flex items-center space-x-8 text-blue-200">
                <a href="{{ route('products') }}" class="inconsolata-regular hover:text-gray-100 text-xl">Products -</a>
                <a href="{{ route('about') }}" class="inconsolata-regular hover:text-gray-100 text-xl">About -</a>
                <a href="{{ route('contact') }}" class="inconsolata-regular hover:text-gray-100 text-xl">Contact -</a>
            </div>
            <div class="hidden md:flex flex items-center space-x-4 mr-2">
                <a href="{{ route('login') }}"
                   class="flex items-center justify-center text-blue-200 hover:text-gray-100 font-bold rounded-lg h-12 w-12 fa-regular fa-user fa-lg"></a>
                <a href="{{ route('cart') }}"
                   class="flex items-center justify-center text-blue-200 hover:text-gray-100 font-bold rounded-lg h-12 w-12 fa-regular fa-bag-shopping fa-lg"></a>
            </div>
        </nav>

        <nav class="bg-transparent md:hidden px-4">
            <div class="max-w-screen-xl flex flex-wrap items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <span class="passion-one-bold animated-gradient text-4xl">Flakes</span>
                </a>
                <button data-collapse-toggle="navbar-default" type="button"
                        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-blue-200 rounded-lg md:hidden hover:bg-black hover:bg-opacity-20"
                        aria-controls="navbar-default" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M1 1h15M1 7h15M1 13h15"/>
                    </svg>
                </button>
                <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                    <ul class="font-medium flex flex-col items-center p-4 md:p-0 mt-4 rounded-lg bg-black bg-opacity-50 backdrop-blur-sm md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-transparent">
                        <li><a href="{{ route('products') }}" class="block py-2 px-3 inconsolata-regular text-xl text-blue-200 hover:text-white">Products</a></li>
                        <li><a href="{{ route('contact') }}" class="block py-2 px-3 inconsolata-regular text-xl text-blue-200 hover:text-white">Contact</a></li>
                        <li><a href="{{ route('about') }}" class="block py-2 px-3 inconsolata-regular text-xl text-blue-200 hover:text-white">About</a></li>
                        <li><a href="{{ route('login') }}" class="block py-2 px-3 inconsolata-regular text-xl text-blue-200 hover:text-white">Profile</a></li>
                        <li><a href="{{ route('cart') }}" class="block py-2 px-3 inconsolata-regular text-xl text-blue-200 hover:text-white">Cart</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
@endsection

@section('header')
    <!-- Override the default header with empty content since we're using pre-header -->
@endsection

@section('main-class', '')

@section('content')
    <!-- Video Section -->
    <div class="relative w-full h-screen">
        <video autoplay loop muted playsinline class="absolute top-0 left-0 w-full h-full object-cover">
            <source src="{{ asset('assets/start.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="absolute inset-0 flex flex-col items-center justify-center text-center text-white bg-black bg-opacity-40">
            <p class="text-4xl md:text-6xl passion-one-bold text-blue-200">Nature's Power Packed into Every Piece!</p>
        </div>
    </div>

    <!-- Introductory Text -->
    <p class="text-center text-xl md:text-2xl pt-8 mt-8 inconsolata-regular w-2/3 mx-auto">Elevate your wellness with Flakes' premium vitamins sourced from nature's best. Pure, sustainable, and GMO-free, each capsule embodies our commitment to your health. Experience the difference with Flakes.</p>

    <!-- Feature Icons Section -->
    <div class="container w-5/6 mx-auto px-4 flex flex-wrap justify-center mt-20">
        <div class="w-full md:w-1/3 p-4">
            <div class="flex flex-col items-center pb-6">
                <div class="w-32 h-32 classic rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-droplet fa-2xl" style="color: rgba(0,52,255)"></i>
                </div>
                <h3 class="text-xl font-bold text-center pt-4 inconsolata-bold">Pure Ingredients</h3>
                <p class="text-center text-gray-700 max-w-56 inconsolata-regular">Flakes delivers pure, bio-based nutrients
                    sourced from nature for your well-being.</p>
            </div>
        </div>

        <div class="w-full md:w-1/3 p-4">
            <div class="flex flex-col items-center pb-6">
                <div class="w-32 h-32 classic rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-heart fa-2xl" style="color: rgb(201,0,0)"></i>
                </div>
                <h3 class="text-xl font-bold text-center pt-4 inconsolata-bold">Optimal Health</h3>
                <p class="text-center text-gray-700 max-w-56 inconsolata-regular">Elevate your health with Flakes'
                    scientifically backed vitamins for vitality.</p>
            </div>
        </div>

        <div class="w-full md:w-1/3 p-4">
            <div class="flex flex-col items-center pb-6">
                <div class="w-32 h-32 classic rounded-full flex items-center justify-center">
                    <i class="fa-brands fa-envira fa-2xl" style="color: rgb(0,162,16)"></i>
                </div>
                <h3 class="text-xl font-bold text-center pt-4 inconsolata-bold">Sustainable</h3>
                <p class="text-center text-gray-700 max-w-56 inconsolata-regular">With eco-friendly practices, Flakes
                    ensures your wellness journey is also sustainable.</p>
            </div>
        </div>
    </div>

    <!-- Accordion Section -->
    <div class="w-2/3 lg:w-3/5 mx-auto px-4 flex-wrap items-center mt-20">
        <div class="relative mb-4 px-4 classic rounded-lg">
            <h6 class="mb-0">
                <button
                    class="relative flex items-center w-full p-4 inconsolata-bold text-left transition-all ease-in cursor-pointer text-slate-700 rounded-t-1 group"
                    data-collapse-target="animated-collapse-1"
                >
                    <span>Our company</span>
                    <i class="absolute right-0 pt-1 text-base transition-transform fa fa-chevron-down group-open:rotate-180"></i>
                </button>
            </h6>
            <div
                data-collapse="animated-collapse-1"
                class="h-0 overflow-hidden transition-all duration-300 ease-in-out"
            >
                <div class="p-4 text-sm leading-normal text-blue-gray-500/80 inconsolata-regular">
                    Flakes is a trusted provider of premium-quality vitamins and supplements, dedicated to enhancing the
                    health and wellness of individuals worldwide. Established with a vision to empower people to live their
                    best lives through optimal nutrition, Flakes is committed to excellence, integrity, and innovation in
                    everything we do.
                </div>
            </div>
        </div>
        <div class="relative mb-4 px-4 classic rounded-lg">
            <h6 class="mb-0">
                <button
                    class="relative flex items-center w-full p-4 inconsolata-bold text-left transition-all ease-in cursor-pointer text-slate-700 rounded-t-1 group"
                    data-collapse-target="animated-collapse-2"
                >
                    <span>Licenses</span>
                    <i class="absolute right-0 pt-1 text-base transition-transform fa fa-chevron-down group-open:rotate-180"></i>
                </button>
            </h6>
            <div
                data-collapse="animated-collapse-2"
                class="h-0 overflow-hidden transition-all duration-300 ease-in-out"
            >
                <div class="p-4 text-sm leading-normal text-blue-gray-500/80 inconsolata-regular">
                    Flakes holds all necessary licenses and certifications required by regulatory authorities to
                    manufacture, distribute, and sell vitamins and supplements. Our facilities adhere to Good Manufacturing
                    Practices (GMP) guidelines set forth by regulatory agencies, ensuring that our products are produced in
                    a safe and controlled environment.
                </div>
            </div>
        </div>
        <div class="relative mb-4 px-4 classic rounded-lg">
            <h6 class="mb-0">
                <button
                    class="relative flex items-center w-full p-4 inconsolata-bold text-left transition-all ease-in cursor-pointer text-slate-700 rounded-t-1 group"
                    data-collapse-target="animated-collapse-3"
                >
                    <span>Suppliers</span>
                    <i class="absolute right-0 pt-1 text-base transition-transform fa fa-chevron-down group-open:rotate-180"></i>
                </button>
            </h6>
            <div
                data-collapse="animated-collapse-3"
                class="h-0 overflow-hidden transition-all duration-300 ease-in-out"
            >
                <div class="p-4 text-sm leading-normal text-blue-gray-500/80 inconsolata-regular">
                    Flakes partners with a network of trusted suppliers who adhere to strict quality control measures and
                    ethical sourcing practices. We conduct thorough due diligence to ensure that our suppliers meet our
                    stringent criteria for reliability, integrity, and compliance with regulatory standards.
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <p class="text-center text-3xl mt-20 inconsolata-bold pb-2 max-w-xl mx-auto">There's nothing here,</p>
    <div class="text-center text-3xl mb-20 inconsolata-regular max-w-xl mx-auto">so go buy our products.</div>
    <div class="flex justify-center">
        <a href="{{ route('products') }}"
           class="inconsolata-bold text-gray-600 classic-clicked rounded-lg px-8 py-4 text-xl">Shop Now</a>
    </div>
@endsection

