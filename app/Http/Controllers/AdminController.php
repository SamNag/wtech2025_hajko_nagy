<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get counts for dashboard stats
        $stats = [

        ];

        return view('admin-page', compact('stats'));
    }

    /**
     * Display products management page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function products(Request $request)
    {
        $products = Product::with('packages')->paginate(10);
        return view('admin.products', compact('products'));
    }

    /**
     * Display orders management page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function orders(Request $request)
    {
        $orders = Order::with(['user', 'items.package.product'])->latest()->paginate(10);
        return view('admin.orders', compact('orders'));
    }

    /**
     * Display users management page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function users(Request $request)
    {
        $users = User::latest()->paginate(10);
        return view('admin.users', compact('users'));
    }

    /**
     * Store a new product.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|in:minerals,vitamins,oils',
            'image1' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'packages' => 'required|array|min:1',
            'packages.*.size' => 'required|string|max:50',
            'packages.*.price' => 'required|numeric|min:0',
            'packages.*.stock' => 'required|integer|min:0',
        ]);

        // Create product
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->category = $request->category;

        // Handle image uploads
        if ($request->hasFile('image1')) {
            $image1Path = $request->file('image1')->store('products', 'public');
            $product->image1 = $image1Path;
        }

        if ($request->hasFile('image2')) {
            $image2Path = $request->file('image2')->store('products', 'public');
            $product->image2 = $image2Path;
        }

        $product->save();

        // Create packages
        foreach ($request->packages as $packageData) {
            $product->packages()->create([
                'size' => $packageData['size'],
                'price' => $packageData['price'],
                'stock' => $packageData['stock'],
            ]);
        }

        return redirect()->route('admin.products')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Update product status (e.g., enable/disable).
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProductStatus(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->active = $request->status ? true : false;
        $product->save();

        return redirect()->back()
            ->with('success', 'Product status updated successfully.');
    }

    /**
     * Update order status.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()
            ->with('success', 'Order status updated successfully.');
    }

    /**
     * Toggle user admin status.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleUserAdmin($id)
    {
        $user = User::findOrFail($id);

        // Prevent removing admin status from yourself
        if ($user->id === auth()->id() && $user->is_admin) {
            return redirect()->back()
                ->with('error', 'You cannot remove your own admin privileges.');
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        return redirect()->back()
            ->with('success', 'User admin status updated successfully.');
    }
}
