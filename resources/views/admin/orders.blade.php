@extends('admin')

@section('admin-content')
    <div class="py-2">
        <h1 class="text-xl lg:text-2xl font-bold mb-1 inconsolata-bold">Orders</h1>
        <h2 class="text-md lg:text-lg text-gray-500 mb-4 inconsolata-regular">Here is your order list data</h2>

        <!-- Status Filter Pills -->
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('admin.orders') }}"
               class="px-4 py-2 rounded-full text-sm font-medium {{ !$statusFilter ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                All ({{ array_sum($statusCounts) }})
            </a>
            <a href="{{ route('admin.orders', ['status' => 'created']) }}"
               class="px-4 py-2 rounded-full text-sm font-medium {{ $statusFilter == 'created' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                Created ({{ $statusCounts['created'] ?? 0 }})
            </a>
            <a href="{{ route('admin.orders', ['status' => 'processing']) }}"
               class="px-4 py-2 rounded-full text-sm font-medium {{ $statusFilter == 'processing' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                Processing ({{ $statusCounts['processing'] ?? 0 }})
            </a>
            <a href="{{ route('admin.orders', ['status' => 'shipped']) }}"
               class="px-4 py-2 rounded-full text-sm font-medium {{ $statusFilter == 'shipped' ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                Shipped ({{ $statusCounts['shipped'] ?? 0 }})
            </a>
            <a href="{{ route('admin.orders', ['status' => 'delivered']) }}"
               class="px-4 py-2 rounded-full text-sm font-medium {{ $statusFilter == 'delivered' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                Delivered ({{ $statusCounts['delivered'] ?? 0 }})
            </a>
            <a href="{{ route('admin.orders', ['status' => 'canceled']) }}"
               class="px-4 py-2 rounded-full text-sm font-medium {{ $statusFilter == 'canceled' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                Canceled ({{ $statusCounts['canceled'] ?? 0 }})
            </a>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full border border-gray-300 shadow rounded-lg">
                <thead>
                <tr class="border-b border-gray-300 text-black text-left bg-gray-50">
                    <th class="p-4">Order ID</th>
                    <th class="p-4">Date</th>
                    <th class="p-4">Customer</th>
                    <th class="p-4">Location</th>
                    <th class="p-4">Amount</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                    <tr class="border-b border-gray-300 hover:bg-gray-50">
                        <td class="p-4">{{ substr($order->id, 0, 8) }}...</td>
                        <td class="p-4">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="p-4">
                            @if($order->user)
                                {{ $order->user->name }} {{ $order->user->surname }}
                            @else
                                {{ $order->guest_name }}
                            @endif
                        </td>
                        <td class="p-4">
                            @if($order->address)
                                {{ $order->address->street }}, {{ $order->address->city }}
                            @else
                                No address provided
                            @endif
                        </td>
                        <td class="p-4 text-nowrap">€ {{ number_format($order->price, 2) }}</td>
                        <td class="p-4">
                                <span class="px-3 py-1 text-sm text-nowrap font-semibold rounded-md
                                    @if($order->status == 'created') bg-blue-100 text-blue-500
                                    @elseif($order->status == 'processing') bg-yellow-100 text-yellow-500
                                    @elseif($order->status == 'shipped') bg-orange-100 text-orange-500
                                    @elseif($order->status == 'delivered') bg-green-100 text-green-500
                                    @else bg-red-100 text-red-500
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                        </td>
                        <td class="p-4">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                   class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <div class="relative">
                                    <button onclick="openOrderDropdown(event, '{{ $order->id }}')"
                                            class="text-gray-600 hover:text-gray-900">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500">No orders found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="flex flex-col gap-4 md:hidden">
            @forelse($orders as $order)
                <div class="border border-gray-300 rounded-lg p-4 shadow">
                    <div><span class="font-semibold">Order ID:</span> {{ substr($order->id, 0, 8) }}...</div>
                    <div><span class="font-semibold">Date:</span> {{ $order->created_at->format('d M Y') }}</div>
                    <div><span class="font-semibold">Customer:</span>
                        @if($order->user)
                            {{ $order->user->name }} {{ $order->user->surname }}
                        @else
                            {{ $order->guest_name }}
                        @endif
                    </div>
                    <div><span class="font-semibold">Location:</span>
                        @if($order->address)
                            {{ $order->address->street }}, {{ $order->address->city }}
                        @else
                            No address provided
                        @endif
                    </div>
                    <div><span class="font-semibold">Amount:</span> € {{ number_format($order->price, 2) }}</div>
                    <div class="flex items-center mt-2 gap-4">
                        <span class="px-3 py-1 text-sm font-semibold rounded-md
                            @if($order->status == 'created') bg-blue-100 text-blue-500
                            @elseif($order->status == 'processing') bg-yellow-100 text-yellow-500
                            @elseif($order->status == 'shipped') bg-orange-100 text-orange-500
                            @elseif($order->status == 'delivered') bg-green-100 text-green-500
                            @else bg-red-100 text-red-500
                            @endif inline-block">
                            {{ ucfirst($order->status) }}
                        </span>
                        <a href="{{ route('admin.orders.show', $order->id) }}"
                           class="text-blue-600 hover:text-blue-900 underline text-sm">
                            View Details
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500">No orders found</div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- Order Action Dropdown -->
    <div id="order-dropdown" class="hidden absolute bg-white shadow-lg rounded-lg p-3 z-50 border border-gray-200">
        <form id="status-update-form" method="POST" action="">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" id="status-input">
        </form>
        <button id="update-status-btn" class="block w-full text-left px-4 py-2 text-blue-600 hover:bg-gray-100">
            Update Status
        </button>
        <a id="view-order-btn" href="#" class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-gray-100">
            View Details
        </a>
    </div>

    @push('scripts')
        <script>
            let selectedOrderId = null;
            let currentStatus = null;

            // Status progression logic
            const nextStatus = {
                'created': 'processing',
                'processing': 'shipped',
                'shipped': 'delivered',
                'delivered': null,
                'canceled': null
            };

            function openOrderDropdown(event, orderId) {
                event.stopPropagation();
                selectedOrderId = orderId;
                const dropdown = document.getElementById('order-dropdown');
                const updateBtn = document.getElementById('update-status-btn');
                const viewBtn = document.getElementById('view-order-btn');
                const statusForm = document.getElementById('status-update-form');

                // Find the order row and get current status
                const row = event.target.closest('tr');
                if (row) {
                    const statusCell = row.querySelector('td:nth-child(6) span');
                    currentStatus = statusCell.textContent.trim().toLowerCase();
                }

                // Update form action
                statusForm.action = `/admin/orders/${orderId}/status`;

                // Update view button href
                viewBtn.href = `/admin/orders/${orderId}`;

                // Show/hide update button based on status
                if (nextStatus[currentStatus]) {
                    updateBtn.textContent = `Set to ${nextStatus[currentStatus].charAt(0).toUpperCase() + nextStatus[currentStatus].slice(1)}`;
                    updateBtn.classList.remove('hidden');
                } else {
                    updateBtn.classList.add('hidden');
                }

                // Position dropdown
                dropdown.classList.remove('hidden');
                dropdown.style.top = `${event.clientY + window.scrollY}px`;
                dropdown.style.left = `${event.clientX}px`;
            }

            // Update status button click
            document.getElementById('update-status-btn').addEventListener('click', function() {
                if (selectedOrderId && currentStatus && nextStatus[currentStatus]) {
                    const statusInput = document.getElementById('status-input');
                    statusInput.value = nextStatus[currentStatus];
                    document.getElementById('status-update-form').submit();
                }
                document.getElementById('order-dropdown').classList.add('hidden');
            });

            // Close dropdown on click outside
            document.addEventListener('click', function(event) {
                const dropdown = document.getElementById('order-dropdown');
                if (!dropdown.contains(event.target) && !event.target.closest('button[onclick*="openOrderDropdown"]')) {
                    dropdown.classList.add('hidden');
                }
            });
        </script>
    @endpush
@endsection
