@extends('layouts.main')

@section('title', 'Admin - Flakes')
@section('header')
    <!-- Header and menu inside the same container for consistent width -->
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold mb-4 mt-4 text-center passion-one-regular animated-gradient">Flakes Admin Panel</h1>
        <!-- Admin Menu - 6 Elements -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <a href="{{ route('admin') }}"
                   class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg text-center transition {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200 border-2 border-gray-400' : '' }}">
                    <i class="fas fa-chart-pie text-2xl mb-2 text-gray-700"></i>
                    <p class="inconsolata-bold">Dashboard</p>
                </a>

                <a href="{{ route('admin.orders') }}"
                   class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg text-center transition {{ request()->routeIs('admin.orders*') ? 'bg-gray-200 border-2 border-gray-400' : '' }}">
                    <i class="fas fa-shopping-cart text-2xl mb-2 text-gray-700"></i>
                    <p class="inconsolata-bold">Orders</p>
                </a>

                <a href="{{ route('admin.users') }}"
                   class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg text-center transition {{ request()->routeIs('admin.users') ? 'bg-gray-200 border-2 border-gray-400' : '' }}">
                    <i class="fas fa-user text-2xl mb-2 text-gray-700"></i>
                    <p class="inconsolata-bold">Users</p>
                </a>

                <a href="{{ route('admin.products') }}"
                   class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg text-center transition {{ request()->routeIs('admin.products') && !request()->routeIs('admin.products.create') ? 'bg-gray-200 border-2 border-gray-400' : '' }}">
                    <i class="fas fa-box text-2xl mb-2 text-gray-700"></i>
                    <p class="inconsolata-bold">Products</p>
                </a>

                <a href="{{ route('home') }}"
                   class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg text-center transition">
                    <i class="fas fa-store text-2xl mb-2 text-gray-700"></i>
                    <p class="inconsolata-bold">View Store</p>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline-block">
                    @csrf
                    <button type="submit" class="w-full h-full bg-gray-100 hover:bg-gray-200 p-4 rounded-lg text-center transition">
                        <i class="fas fa-sign-out-alt text-2xl mb-2 text-gray-700"></i>
                        <p class="inconsolata-bold">Logout</p>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container mx-auto px-4">
        <!-- Flash messages -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-md">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-md">
                {{ session('error') }}
            </div>
        @endif

        <!-- Main Content Area -->
        <div class="bg-white rounded-lg shadow-md p-6">
            @yield('admin-content')
        </div>
    </div>

    @push('scripts')
        <script>
            // Check if Font Awesome is loaded, if not, load it
            if(!document.querySelector('link[href*="fontawesome"]')) {
                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css';
                document.head.appendChild(link);
            }
        </script>
    @endpush
@endsection
