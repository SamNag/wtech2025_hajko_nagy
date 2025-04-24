<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page
     */
    public function index()
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to checkout');
        }

        // Get cart items
        $cartItems = CartItem::with(['package.product'])
            ->where('user_id', Auth::id())
            ->get();

        // Check if cart is empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->package->price * $item->quantity;
        }

        $shippingFee = 3.65; // Fixed shipping fee
        $total = $subtotal + $shippingFee;

        // No previous addresses - simplified checkout
        return view('checkout', compact('cartItems', 'subtotal', 'shippingFee', 'total'));
    }

    /**
     * Process the checkout
     */
    public function process(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'payment_method' => 'required|in:card,cash',
            'card_number' => 'required_if:payment_method,card|nullable|string|max:16',
            'card_expiry' => 'required_if:payment_method,card|nullable|string|max:5',
            'card_cvv' => 'required_if:payment_method,card|nullable|string|max:4',
            'shipping_method' => 'required|in:ups,fedex,dhl',
        ]);

        try {
            // Start a database transaction
            DB::beginTransaction();

            // Get cart items
            $cartItems = CartItem::with(['package.product'])
                ->where('user_id', Auth::id())
                ->get();

            // Check if cart is empty
            if ($cartItems->isEmpty()) {
                return redirect()->route('cart')->with('error', 'Your cart is empty');
            }

            // Calculate total price
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item->package->price * $item->quantity;
            }

            $shippingFee = 3.65; // Fixed shipping fee
            $total = $subtotal + $shippingFee;

            // Create a new address for this order
            $address = Address::create([
                'user_id' => Auth::id(),
                'street' => $validated['street'],
                'city' => $validated['city'],
                'zip' => $validated['zip'],
                'country' => $validated['country'],
            ]);

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'address_id' => $address->id,
                'payment' => $validated['payment_method'],
                'status' => 'pending',
                'price' => $total,
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'package_id' => $item->package_id,
                    'quantity' => $item->quantity,
                ]);
            }

            // Clear the cart
            CartItem::where('user_id', Auth::id())->delete();

            // Commit the transaction
            DB::commit();

            // Redirect to order confirmation
            return redirect()->route('orders.confirmation', ['order' => $order->id])
                ->with('success', 'Your order has been placed successfully');

        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollBack();

            Log::error('Checkout process failed: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('error', 'There was an error processing your order. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display order confirmation
     */
    public function confirmation($orderId)
    {
        $order = Order::with(['items.package.product', 'address'])
            ->where('user_id', Auth::id())
            ->findOrFail($orderId);

        return view('order-confirmation', compact('order'));
    }
}
