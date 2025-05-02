@extends('admin')

@section('admin-content')
    <div class="py-2">
        <h2 class="text-2xl font-bold mb-4">Dashboard</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white border border-gray-200 p-6 rounded-lg text-center shadow-sm">
                <i class="fas fa-shopping-cart text-gray-600 text-3xl mb-3"></i>
                <h3 class="text-xl font-bold">{{ $stats['total_orders'] }}</h3>
                <p class="text-gray-500">Orders</p>
            </div>

            <div class="bg-white border border-gray-200 p-6 rounded-lg text-center shadow-sm">
                <i class="fas fa-dollar-sign text-gray-600 text-3xl mb-3"></i>
                <h3 class="text-xl font-bold">€{{ number_format($stats['total_revenue'], 2) }}</h3>
                <p class="text-gray-500">Revenue</p>
            </div>

            <div class="bg-white border border-gray-200 p-6 rounded-lg text-center shadow-sm">
                <i class="fas fa-tag text-gray-600 text-3xl mb-3"></i>
                <h3 class="text-xl font-bold">{{ $stats['products_sold'] }}</h3>
                <p class="text-gray-500">Products Sold</p>
            </div>

            <div class="bg-white border border-gray-200 p-6 rounded-lg text-center shadow-sm">
                <i class="fas fa-box text-gray-600 text-3xl mb-3"></i>
                <h3 class="text-xl font-bold">{{ $stats['total_stock'] }}</h3>
                <p class="text-gray-500">Stock Available</p>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold">Revenue (Last 7 Days)</h3>
                </div>
                <div class="p-4">
                    <div class="h-64">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold">Top Selling Products</h3>
                </div>
                <div class="p-4">
                    <div class="h-64">
                        <canvas id="stockChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
            <div class="p-4 border-b border-gray-200">
                <h3 class="text-lg font-bold">Recent Orders</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentOrders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ substr($order->id, 0, 8) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->user ? $order->user->name . ' ' . $order->user->surname : $order->guest_name }}
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
            <div class="p-4 border-t border-gray-200 text-center">
                <a href="{{ route('admin.orders') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">View All Orders</a>
            </div>
        </div>

        <!-- Top Products Grid -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="p-4 border-b border-gray-200">
                <h3 class="text-lg font-bold">Top Selling Products</h3>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($topProducts as $product)
                        <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <div class="p-4">
                                <h4 class="font-semibold truncate" title="{{ $product->name }}">{{ $product->name }}</h4>
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
            <div class="p-4 border-t border-gray-200 text-center">
                <a href="{{ route('admin.products') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">View All Products</a>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
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
