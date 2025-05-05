<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page
     */
    public function index()
    {
        // Initialize cart items and totals
        $cartItems = [];
        $subtotal = 0;

        if (Auth::check()) {
            // Authenticated user - get cart items from database
            $cartItems = CartItem::with(['package.product'])
                ->where('user_id', Auth::id())
                ->get();

            // Calculate subtotal
            foreach ($cartItems as $item) {
                $subtotal += $item->package->price * $item->quantity;
            }
        } else {
            // Guest user - get cart from session/localStorage via AJAX
            // The actual cart items will be loaded client-side
            // We just need to pass the view for now
        }

        // Set fixed shipping fee
        $shippingFee = 3.65;
        $total = $subtotal + $shippingFee;

        // Pass user data if authenticated
        $userData = null;
        if (Auth::check()) {
            $userData = Auth::user();
        }

        return view('checkout', compact('cartItems', 'subtotal', 'shippingFee', 'total', 'userData'));
    }

    /**
     * Process the checkout
     */
    public function process(Request $request)
    {
        // Validate the request with fields required for both guest and logged-in users
        $validationRules = [
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'payment_method' => 'required|in:card,cash',
            'card_number' => 'required_if:payment_method,card|nullable|string|max:16',
            'card_expiry' => 'required_if:payment_method,card|nullable|string|max:5',
            'card_cvv' => 'required_if:payment_method,card|nullable|string|max:4',
            'shipping_method' => 'required|in:ups,fedex,dhl',
        ];

        // Add additional validation rules for guest users
        if (!Auth::check()) {
            $validationRules = array_merge($validationRules, [
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
            ]);
        }

        $validated = $request->validate($validationRules);

        try {
            // Start a database transaction
            DB::beginTransaction();

            $cartItems = [];
            $isGuest = !Auth::check();
            $userId = $isGuest ? null : Auth::id();

            if ($isGuest) {
                // For guest users, we need to process the cart items from the request
                // The cart items will be sent as JSON in the request
                $cartData = json_decode($request->input('cart_data'), true);

                if (empty($cartData)) {
                    return redirect()->route('cart')->with('error', 'Your cart is empty');
                }

                // Validate each cart item and retrieve from database
                foreach ($cartData as $item) {
                    $package = Package::with('product')->findOrFail($item['package_id']);
                    $cartItems[] = (object)[
                        'package' => $package,
                        'quantity' => $item['quantity'],
                        'product' => $package->product
                    ];
                }
            } else {
                // For authenticated users, get cart items from database
                $cartItems = CartItem::with(['package.product'])
                    ->where('user_id', $userId)
                    ->get();
            }

            // Check if cart is empty
            if (empty($cartItems)) {
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
                'street' => $validated['street'],
                'city' => $validated['city'],
                'zip' => $validated['zip'],
                'country' => $validated['country'],
            ]);

            // Create order (with or without user_id)
            $orderData = [
                'user_id' => $userId,
                'address_id' => $address->id,
                'payment' => $validated['payment_method'],
                'status' => 'created',
                'price' => $total,
                'delivery_type' => $validated['shipping_method'],
            ];

            // For guest orders, store guest information
            if ($isGuest) {
                $orderData['guest_name'] = $validated['name'] . ' ' . $validated['surname'];
                $orderData['guest_email'] = $validated['email'];
                $orderData['guest_phone'] = $validated['phone'];
            }

            $order = Order::create($orderData);

            // Create order items and decrease stock for each item
            foreach ($cartItems as $item) {
                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'package_id' => $item->package->id,
                    'quantity' => $item->quantity,
                ]);

                // IMPORTANT: Reduce stock for each item
                $package = Package::findOrFail($item->package->id);
                $package->stock -= $item->quantity;
                $package->save();
            }

            // Clear the cart
            if (!$isGuest) {
                CartItem::where('user_id', $userId)->delete();
            }
            // Delete session cart data for guest users
            Session::forget('cart_data');




            // Store order ID in session for guest users to access confirmation
            if ($isGuest) {
                Session::put('guest_order_id', $order->id);
                Session::put('guest_email', $validated['email']);
            }

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
                'user_id' => Auth::id() ?? 'guest',
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
        $order = null;

        if (Auth::check()) {
            // For authenticated users
            $order = Order::with(['items.package.product', 'address'])
                ->where('user_id', Auth::id())
                ->findOrFail($orderId);
        } else {
            // For guest users, check session
            $guestOrderId = Session::get('guest_order_id');
            $guestEmail = Session::get('guest_email');

            if ($guestOrderId == $orderId) {
                $order = Order::with(['items.package.product', 'address'])
                    ->where('id', $orderId)
                    ->whereNull('user_id')
                    ->where('guest_email', $guestEmail)
                    ->firstOrFail();
            } else {
                // If no match, redirect to home
                return redirect()->route('home')
                    ->with('error', 'Order not found or access denied');
            }
        }

        return view('order-confirmation', compact('order'));
    }
}
