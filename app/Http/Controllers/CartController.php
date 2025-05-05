<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Display the cart page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get cart items for the current user with product and package details
        $cartItems = [];

        if (Auth::check()) {
            // For authenticated users, get items from database
            $cartItems = CartItem::with(['package.product'])
                ->where('user_id', Auth::id())
                ->get();
        }

        // For guest users, client-side JavaScript will handle displaying
        // cart items from localStorage

        return view('cart', compact('cartItems'));
    }

    /**
     * Check if an item is in stock with the requested quantity.
     *
     * @param string $packageId
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkStock($packageId)
    {
        try {
            $package = Package::with('product')->findOrFail($packageId);

            return response()->json([
                'success' => true,
                'stock' => $package->stock,
                'product_name' => $package->product->name,
                'package_size' => $package->size
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking stock'
            ], 500);
        }
    }
    /**
     * Get cart items for a logged-in user (API endpoint for AJAX).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getItems()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $cartItems = CartItem::with(['package.product'])
            ->where('user_id', Auth::id())
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'package_id' => $item->package_id,
                    'quantity' => $item->quantity,
                    'product' => [
                        'id' => $item->package->product->id,
                        'name' => $item->package->product->name,
                        'image' => $item->package->product->image1
                    ],
                    'package' => [
                        'size' => $item->package->size,
                        'price' => $item->package->price
                    ]
                ];
            });

        return response()->json([
            'success' => true,
            'cart_items' => $cartItems
        ]);
    }

    /**
     * Add an item to the cart.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        Log::info('Cart add request received', $request->all());

        try {
            $validated = $request->validate([
                'package_id' => 'required|string|exists:packages,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $packageId = $validated['package_id'];
            $quantity = $validated['quantity'];

            // Get package details for response
            $package = Package::with('product')->findOrFail($packageId);

            if (Auth::check()) {
                // For authenticated users, store in database
                try {
                    Log::info('Adding to cart for authenticated user', [
                        'user_id' => Auth::id(),
                        'package_id' => $packageId
                    ]);

                    $existingItem = CartItem::where([
                        'user_id' => Auth::id(),
                        'package_id' => $packageId
                    ])->first();

                    if ($existingItem) {
                        // If item exists, update quantity
                        $existingItem->quantity += $quantity;
                        $existingItem->save();
                        Log::info('Updated existing cart item', ['item_id' => $existingItem->id, 'new_quantity' => $existingItem->quantity]);
                    } else {
                        // Create new cart item
                        $cartItem = new CartItem([
                            'user_id' => Auth::id(),
                            'package_id' => $packageId,
                            'quantity' => $quantity
                        ]);
                        $cartItem->save();
                        Log::info('Created new cart item', ['item_id' => $cartItem->id]);
                    }

                    $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');
                    Log::info('Current cart count', ['count' => $cartCount]);

                } catch (\Exception $e) {
                    Log::error('Error adding to cart for authenticated user', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Error adding item to cart: ' . $e->getMessage()
                    ], 500);
                }
            } else {
                // For guest users, the front-end will handle localStorage updates
                // Just return package details for the front-end to use
                $cartCount = null; // Client will calculate this

                Log::info('Added to cart for guest user', [
                    'package_id' => $packageId,
                    'quantity' => $quantity
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => $package->product->name . ' added to cart',
                'cart_count' => $cartCount ?? null,
                'package' => [
                    'id' => $package->id,
                    'name' => $package->product->name,
                    'size' => $package->size,
                    'price' => $package->price,
                    'quantity' => $quantity,
                    'image' => $package->product->image1,
                    'product_id' => $package->product->id
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in add to cart', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove an item from the cart.
     *
     * @param string $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove($item)
    {
        if (Auth::check()) {
            // For authenticated users, remove from database
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('id', $item)
                ->firstOrFail();

            $cartItem->delete();

            $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'cart_count' => $cartCount
            ]);
        }

        // For guests, removal is handled client-side with localStorage
        return response()->json([
            'success' => false,
            'message' => 'User not authenticated'
        ]);
    }

    /**
     * Update cart item quantity.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|string',
            'quantity' => 'required|integer|min:1'
        ]);

        $itemId = $validated['item_id'];
        $quantity = $validated['quantity'];

        if (Auth::check()) {
            // For authenticated users, update in database
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('id', $itemId)
                ->firstOrFail();

            $cartItem->quantity = $quantity;
            $cartItem->save();

            $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'Cart updated',
                'cart_count' => $cartCount
            ]);
        }

        // For guests, updates are handled client-side with localStorage
        return response()->json([
            'success' => false,
            'message' => 'User not authenticated'
        ]);
    }

    /**
     * Sync the local storage cart with the database after login.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $validated = $request->validate([
            'cart_items' => 'required|array',
            'cart_items.*.package_id' => 'required|string|exists:packages,id',
            'cart_items.*.quantity' => 'required|integer|min:1'
        ]);

        $localCartItems = $validated['cart_items'];

        foreach ($localCartItems as $item) {
            $existingItem = CartItem::where([
                'user_id' => Auth::id(),
                'package_id' => $item['package_id']
            ])->first();

            if ($existingItem) {
                // Update existing item
                $existingItem->quantity += $item['quantity'];
                $existingItem->save();
            } else {
                // Create new item
                $cartItem = new CartItem([
                    'user_id' => Auth::id(),
                    'package_id' => $item['package_id'],
                    'quantity' => $item['quantity']
                ]);
                $cartItem->save();
            }
        }

        $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Cart synced successfully',
            'cart_count' => $cartCount
        ]);
    }
}
