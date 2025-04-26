
@extends('layouts.main')

@section('title', 'Admin - Flakes')

@section('content')
    <div class="flex min-h-screen pt-28 bg-gray-200">
        <!-- Sidebar -->
        <aside class="hidden lg:flex flex-col w-64 h-full bg-gray-200 border-r border-gray-300 fixed top-0 left-0">
            <div class="flex items-center justify-center h-20 border-b border-gray-300">
                <a href="{{ route('admin.dashboard') }}" class="passion-one-bold text-4xl animated-gradient">Flakes-admin</a>
            </div>

            <nav class="flex flex-col p-4 gap-5 inconsolata-regular text-xl text-gray-600">
                <div class="flex flex-col gap-2">
                    <span class="inconsolata-bold px-1 text-lg text-black">MAIN</span>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link ps-3 hover:bg-gray-300 rounded-md {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i> <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="sidebar-link ps-3 hover:bg-gray-300 rounded-md {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                        <i class="fas fa-user"></i> <span>Users</span>
                    </a>
                    <a href="{{ route('admin.orders') }}" class="sidebar-link ps-3 hover:bg-gray-300 rounded-md {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-cart"></i> <span>Orders</span>
                    </a>
                </div>

                <div class="flex flex-col gap-2">
                    <span class="inconsolata-bold px-1 text-lg text-black">PRODUCTS</span>
                    <a href="{{ route('admin.products.create') }}" class="sidebar-link ps-3 hover:bg-gray-300 rounded-md {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                        <i class="fas fa-plus"></i> <span>Add Product</span>
                    </a>
                    <a href="{{ route('admin.products') }}" class="sidebar-link ps-3 hover:bg-gray-300 rounded-md {{ request()->routeIs('admin.products') && !request()->routeIs('admin.products.create') ? 'active' : '' }}">
                        <i class="fas fa-box"></i> <span>View Products</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col lg:ml-64 px-4 md:px-6 pb-12">
            <!-- Header -->
            <header class="fixed top-0 left-0 w-full z-50 bg-gray-200 border-b border-gray-300 flex items-center h-20">
                <a href="{{ route('admin.dashboard') }}" class="w-64 hidden md:flex items-center justify-center passion-one-bold animated-gradient text-4xl border-r border-gray-300 h-full">Flakes-admin</a>

                <div class="flex-1 flex items-center justify-between px-4 md:px-6">
                    <!-- Search -->
                    <form class="hidden md:flex items-center" action="{{ route('admin.search') }}" method="GET">
                        <input type="text" name="q" placeholder="Search..." required
                               class="text-gray-700 border bg-gray-200 rounded-lg focus:outline-none border-gray-300 h-10 p-2.5 w-72" />
                        <button type="submit" class="bg-gray-200 rounded-lg min-w-10 h-10 ml-2">
                            <i class="fa-solid fa-magnifying-glass text-gray-600"></i>
                        </button>
                    </form>

                    <!-- Profile -->
                    <div class="hidden lg:flex items-center gap-4">
                        <a href="{{ route('home') }}" class="flex items-center inconsolata-bold text-gray-600">
                            <i class="fa-solid fa-store mr-2"></i> View Store
                        </a>

                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center space-x-2">
                                <img src="{{ asset('assets/about/pE42RfqP.jpg') }}" alt="Profile" class="w-10 h-10 rounded-full border border-gray-300" />
                                <span class="inconsolata-bold text-gray-600">{{ auth()->user()->name }}</span>
                            </button>

                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Breadcrumb -->
            <div class="pt-24 pb-4 text-gray-600 inconsolata-regular">
            <span>
                @php
                    $routeName = request()->route()->getName();
                    $routeParts = explode('.', $routeName);
                    $mainPart = ucfirst($routeParts[1] ?? 'Home');
                    $subPart = isset($routeParts[2]) ? ' > ' . ucfirst($routeParts[2]) : '';
                @endphp
                {{ $mainPart . $subPart }}
            </span>
            </div>

            <!-- Flash messages -->
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Page content -->
            <div class="flex-1">
                @yield('admin-content')
            </div>

            <!-- Footer -->
            <footer class="text-center text-gray-600 py-4 border-t border-gray-300 mt-8">
                &copy; {{ date('Y') }} Flakes Admin. All rights reserved.
            </footer>
        </div>
    </div>
@endsection
