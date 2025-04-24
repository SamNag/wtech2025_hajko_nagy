@extends('layouts.main')

@section('title', 'Order Confirmation - Flakes')

@section('content')
    <div class="max-w-screen-lg mx-auto mb-10 p-4">
        <div class="bg-gray-200 p-6 rounded-lg border border-gray-300">
            <div class="text-center mb-8">
                <div class="text-6xl text-green-500 mb-4">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold passion-one-regular animated-gradient">Order Placed Successfully!</h1>
                <p class="text-gray-600 mt-2 inconsolata-regular">Order ID: {{ $order->id }}</p>
            </div>

            <div class="bg-white rounded-lg p-6 mb-6 border border-gray-300">
                <h2 class="text-xl font-bold mb-4 inconsolata-bold">Order Summary</h2>

                <div class="space-y-4">
                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="text-lg font-semibold mb-2 inconsolata-bold">Shipping Address</h3>
                        <address class="not-italic inconsolata-regular">
                            {{ $order->address->street }}<br>
                            {{ $order->address->city }}, {{ $order->address->zip }}<br>
                            {{ $order->address->country }}
                        </address>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="text-lg font-semibold mb-2 inconsolata-bold">Payment Method</h3>
                        <p class="inconsolata-regular">{{ $order->payment_method }}</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-2 inconsolata-bold">Order Status</h3>
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium inconsolata-regular">
                        {{ $order->status_label }}
                    </span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-6 mb-6 border border-gray-300">
                <h2 class="text-xl font-bold mb-4 inconsolata-bold">Items Ordered</h2>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                        <tr class="border-b border-gray-200">
                            <th class="py-2 text-left inconsolata-bold">Product</th>
                            <th class="py-2 text-left inconsolata-bold">Package</th>
                            <th class="py-2 text-right inconsolata-bold">Price</th>
                            <th class="py-2 text-center inconsolata-bold">Qty</th>
                            <th class="py-2 text-right inconsolata-bold">Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->items as $item)
                            <tr class="border-b border-gray-200">
                                <td class="py-4 inconsolata-regular">{{ $item->package->product->name }}</td>
                                <td class="py-4 inconsolata-regular">{{ $item->package->size }}</td>
                                <td class="py-4 text-right inconsolata-regular">€{{ number_format($item->package->price, 2) }}</td>
                                <td class="py-4 text-center inconsolata-regular">{{ $item->quantity }}</td>
                                <td class="py-4 text-right inconsolata-regular">€{{ number_format($item->package->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="4" class="py-4 text-right font-bold inconsolata-bold">Subtotal:</td>
                            <td class="py-4 text-right inconsolata-regular">€{{ number_format($order->price - 3.65, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="py-4 text-right font-bold inconsolata-bold">Shipping:</td>
                            <td class="py-4 text-right inconsolata-regular">€3.65</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="py-4 text-right font-bold inconsolata-bold">Total:</td>
                            <td class="py-4 text-right font-bold inconsolata-bold">€{{ number_format($order->price, 2) }}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('home') }}" class="btn text-center classic-clicked text-lg inconsolata-bold text-black rounded-lg p-2 h-12">
                    Continue Shopping
                </a>
                <a href="{{ route('profile') }}" class="btn text-center classic-clicked-border text-lg inconsolata-bold text-black rounded-lg p-2 h-12">
                    View My Orders
                </a>
            </div>
        </div>
    </div>
@endsection
