@extends('admin')

@section('title', 'Admin Dashboard - Flakes')

@section('content')
    <div class="py-6 w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl lg:text-2xl font-bold mb-6">Good {{ $timeOfDay }}, {{ auth()->user()->name }}!</h1>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white border border-gray-300 p-6 rounded-lg text-center shadow-sm">
                    <i class="fas fa-tag text-gray-600 lg:text-3xl md:text-2xl sm:text-xl mb-3"></i>
                    <h2 class="lg:text-xl md:text-lg text-md font-bold">{{ $stats['products_sold'] }}</h2>
                    <p class="text-gray-500">Products Sold</p>
                </div>
                <div class="bg-white border border-gray-300 p-6 rounded-lg text-center shadow-sm">
                    <i class="fas fa-box text-gray-600 lg:text-3xl md:text-2xl sm:text-xl mb-3"></i>
                    <h2 class="lg:text-xl md:text-lg text-md font-bold">{{ $stats['total_stock'] }}</h2>
                    <p class="text-gray-500">Stock</p>
                </div>
                <div class="bg-white border border-gray-300 p-6 rounded-lg text-center shadow-sm">
                    <i class="fas fa-dollar-sign text-gray-600 lg:text-3xl md:text-2xl sm:text-xl mb-3"></i>
                    <h2 class="lg:text-xl md:text-lg text-md font-bold">€{{ number_format($stats['total_revenue'], 2) }}</h2>
                    <p class="text-gray-500">Revenue</p>
                </div>
                <div class="bg-white border border-gray-300 p-6 rounded-lg text-center shadow-sm">
                    <i class="fas fa-shopping-cart text-gray-600 lg:text-3xl md:text-2xl sm:text-xl mb-3"></i>
                    <h2 class="lg:text-xl md:text-lg text-md font-bold">{{ $stats['total_orders'] }}</h2>
                    <p class="text-gray-500">Orders</p>
                </div>
            </div>

            <!-- Graphs -->
            <div class="mb-8 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
                        <div class="p-4 border-b border-gray-300">
                            <h2 class="text-xl inconsolata-bold">Recent Earnings</h2>
                        </div>
                        <div class="p-4">
                            <!-- Chart container with fixed height -->
                            <div class="relative h-64">
                                <canvas id="salesChart" class="w-full h-full"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
                        <div class="p-4 border-b border-gray-300">
                            <h2 class="text-xl inconsolata-bold">Recent Sales</h2>
                        </div>
                        <div class="p-4">
                            <!-- Chart container with fixed height -->
                            <div class="relative h-64">
                                <canvas id="stockChart" class="w-full h-full"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Section -->
            <div class="mb-8">
                <h2 class="text-xl lg:text-2xl inconsolata-bold mb-4">Recent Orders</h2>
                <div class="bg-white overflow-hidden border border-gray-300 rounded-lg shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ substr($order->id, 0, 8) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->user->name }} {{ $order->user->surname }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">€{{ number_format($order->price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($order->status == 'created') bg-blue-100 text-blue-800
                                            @elseif($order->status == 'shipped') bg-yellow-100 text-yellow-800
                                            @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No recent orders found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Top Products Section -->
            <div class="mb-8">
                <h2 class="text-xl lg:text-2xl inconsolata-bold mb-4">Top Selling Products</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($topProducts as $product)
                        <div class="bg-white border border-gray-300 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold truncate" title="{{ $product->name }}">{{ $product->name }}</h3>
                                <div class="mt-2 flex justify-between items-center">
                                    <span class="text-sm text-gray-500">{{ $product->category }}</span>
                                    <span class="text-sm font-medium">{{ $product->total_sold }} sold</span>
                                </div>
                                <div class="mt-1 text-sm font-medium text-gray-900">€{{ number_format($product->min_price, 2) }} - €{{ number_format($product->max_price, 2) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Charts initialization
            document.addEventListener('DOMContentLoaded', function() {
                // Sales chart (daily revenue)
                const salesCtx = document.getElementById('salesChart').getContext('2d');
                new Chart(salesCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($charts['days']) !!},
                        datasets: [{
                            label: 'Revenue (€)',
                            data: {!! json_encode($charts['revenues']) !!},
                            borderColor: 'rgba(54, 162, 235, 1)',
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            fill: true,
                            tension: 0.4,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });

                // Product sales chart
                const stockCtx = document.getElementById('stockChart').getContext('2d');
                new Chart(stockCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($charts['product_names']) !!},
                        datasets: [{
                            label: 'Products Sold',
                            data: {!! json_encode($charts['product_sales']) !!},
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(54, 162, 235, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(75, 192, 192, 0.6)',
                                'rgba(153, 102, 255, 0.6)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
