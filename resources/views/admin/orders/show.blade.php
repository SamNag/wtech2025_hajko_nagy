@extends('admin')

@section('admin-content')
    <div class="py-2">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-xl lg:text-2xl font-bold mb-1 inconsolata-bold">Order Details</h1>
                <h2 class="text-md lg:text-lg text-gray-500 inconsolata-regular">Order #{{ substr($order->id, 0, 8) }}...</h2>
            </div>
            <a href="{{ route('admin.orders') }}" class="text-indigo-600 hover:text-indigo-800">
                <i class="fas fa-arrow-left mr-2"></i>Back to Orders
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Information -->
            <div class="lg:col-span-2">
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-bold mb-4">Order Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Order ID</p>
                            <p class="font-medium">{{ $order->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Date</p>
                            <p class="font-medium">{{ $order->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <span class="px-3 py-1 text-sm font-semibold rounded-md
                                @if($order->status == 'created') bg-blue-100 text-blue-500
                                @elseif($order->status == 'processing') bg-yellow-100 text-yellow-500
                                @elseif($order->status == 'shipped') bg-orange-100 text-orange-500
                                @elseif($order->status == 'delivered') bg-green-100 text-green-500
                                @else bg-red-100 text-red-500
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Payment Method</p>
                            <p class="font-medium">{{ ucfirst($order->payment) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-bold mb-4">Order Items</h3>
                    <table class="w-full">
                        <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Product</th>
                            <th class="text-left py-2">Size</th>
                            <th class="text-center py-2">Quantity</th>
                            <th class="text-right py-2">Price</th>
                            <th class="text-right py-2">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->items as $item)
                            <tr class="border-b">
                                <td class="py-3">{{ $item->package->product->name }}</td>
                                <td class="py-3">{{ $item->package->size }}</td>
                                <td class="text-center py-3">{{ $item->quantity }}</td>
                                <td class="text-right py-3">€{{ number_format($item->package->price, 2) }}</td>
                                <td class="text-right py-3">€{{ number_format($item->package->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr class="font-bold">
                            <td colspan="4" class="text-right py-3">Total:</td>
                            <td class="text-right py-3">€{{ number_format($order->price, 2) }}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Customer & Shipping Information -->
            <div class="lg:col-span-1">
                <!-- Customer Information -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-bold mb-4">Customer Information</h3>
                    @if($order->user)
                        <p class="font-medium">{{ $order->user->name }} {{ $order->user->surname }}</p>
                        <p class="text-gray-600">{{ $order->user->email }}</p>
                        <p class="text-gray-600">{{ $order->user->phone ?? 'No phone provided' }}</p>
                    @else
                        <p class="font-medium">{{ $order->guest_name }}</p>
                        <p class="text-gray-600">{{ $order->guest_email }}</p>
                        <p class="text-gray-600">{{ $order->guest_phone ?? 'No phone provided' }}</p>
                    @endif
                </div>

                <!-- Shipping Information -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-bold mb-4">Shipping Information</h3>
                    @if($order->address)
                        <p>{{ $order->address->street }}</p>
                        <p>{{ $order->address->zip }} {{ $order->address->city }}</p>
                        <p>{{ $order->address->country }}</p>
                    @else
                        <p class="text-gray-500">No shipping address provided</p>
                    @endif
                    <hr class="my-4">
                    <p class="text-sm text-gray-500">Delivery Type</p>
                    <p class="font-medium">{{ ucfirst($order->delivery_type ?? 'Standard') }}</p>
                </div>

                <!-- Status Update -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Update Status</h3>
                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="w-full border border-gray-300 rounded-lg p-2 mb-4">
                            <option value="created" {{ $order->status == 'created' ? 'selected' : '' }}>Created</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
                        </select>
                        <button type="submit" class="w-full bg-indigo-600 text-white rounded-lg py-2 hover:bg-indigo-800">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
