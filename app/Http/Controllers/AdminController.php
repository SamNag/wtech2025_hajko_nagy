<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Package;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get time of day greeting
        $hour = Carbon::now()->hour;
        $timeOfDay = $hour < 12 ? 'morning' : ($hour < 18 ? 'afternoon' : 'evening');

        // Get dashboard statistics
        $stats = $this->getDashboardStats();

        // Get chart data
        $charts = $this->getChartData();

        // Get recent orders
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get top selling products
        $topProducts = $this->getTopSellingProducts();

        return view('admin-dashboard', compact(
            'timeOfDay',
            'stats',
            'charts',
            'recentOrders',
            'topProducts'
        ));
    }

    /**
     * Get dashboard statistics.
     *
     * @return array
     */
    private function getDashboardStats()
    {
        // Total orders
        $totalOrders = Order::count();

        // Total revenue
        $totalRevenue = Order::sum('price');

        // Total products sold (sum of order item quantities)
        $productsSold = OrderItem::sum('quantity');

        // Total stock available
        $totalStock = Package::sum('stock');

        return [
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'products_sold' => $productsSold,
            'total_stock' => $totalStock
        ];
    }

    /**
     * Get chart data for dashboard.
     *
     * @return array
     */
    private function getChartData()
    {
        // Get daily revenue for last 7 days
        $dailyRevenue = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(price) as revenue')
        )
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Create arrays for chart data
        $days = [];
        $revenues = [];

        // Fill in data for each of the last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $days[] = Carbon::now()->subDays($i)->format('D'); // Day name (Mon, Tue, etc.)
            $revenues[] = $dailyRevenue->has($date) ? round($dailyRevenue[$date]->revenue, 2) : 0;
        }

        // Get top 5 selling products
        $topProducts = DB::table('order_items')
            ->join('packages', 'order_items.package_id', '=', 'packages.id')
            ->join('products', 'packages.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_sold')
            )
            ->groupBy('products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        $productNames = $topProducts->pluck('name')->toArray();
        $productSales = $topProducts->pluck('total_sold')->toArray();

        return [
            'days' => $days,
            'revenues' => $revenues,
            'product_names' => $productNames,
            'product_sales' => $productSales
        ];
    }

    /**
     * Get top selling products.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getTopSellingProducts()
    {
        return DB::table('products')
            ->select(
                'products.id',
                'products.name',
                'products.category',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('MIN(packages.price) as min_price'),
                DB::raw('MAX(packages.price) as max_price')
            )
            ->join('packages', 'products.id', '=', 'packages.product_id')
            ->leftJoin('order_items', 'packages.id', '=', 'order_items.package_id')
            ->groupBy('products.id', 'products.name', 'products.category')
            ->orderBy('total_sold', 'desc')
            ->limit(8)
            ->get();
    }

    public function products(Request $request)
    {
        $query = Product::with('packages');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $products = $query->paginate(10);

        return view('admin.products', compact('products'));
    }


    /**
     * Display form to create a new product.
     *
     * @return \Illuminate\View\View
     */
    public function createProduct()
    {
        $categories = DB::table('products')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a new product.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Store a new product.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeProduct(Request $request)
    {
        // First, let's validate the structure we're receiving
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'image1' => 'required|image|max:2048',
            'image2' => 'nullable|image|max:2048',
            'packages' => 'required|array|min:1',
            'packages.*.size' => 'required|string|max:50',
            'packages.*.price' => 'required|numeric|min:0',
            'packages.*.stock' => 'required|integer|min:0',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
        ]);

        DB::beginTransaction();

        try {
            // Handle image uploads to different directories
            $image1Path = $request->file('image1')->store('assets/product_images/image', 'public');

            // If there's a second image, store it in the package directory
            $image2Path = null;
            if ($request->hasFile('image2')) {
                $image2Path = $request->file('image2')->store('assets/product_images/package', 'public');
            }

            // Create product
            $product = Product::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'category' => $validated['category'],
                'image1' => $image1Path,
                'image2' => $image2Path ?? $image1Path, // Fallback to image1 if no image2
            ]);

            // Add packages
            foreach ($validated['packages'] as $packageData) {
                // Make sure we're getting the correct data structure
                if (isset($packageData['size']) && isset($packageData['price']) && isset($packageData['stock'])) {
                    $product->packages()->create([
                        'size' => $packageData['size'],
                        'price' => $packageData['price'],
                        'stock' => $packageData['stock'],
                    ]);
                }
            }

            // Add tags
            if (!empty($validated['tags'])) {
                foreach ($validated['tags'] as $tagName) {
                    $product->tags()->create(['tag_name' => $tagName]);
                }
            }

            DB::commit();

            return redirect()->route('admin.products')
                ->with('success', 'Product created successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded images
            if (isset($image1Path)) {
                Storage::disk('public')->delete($image1Path);
            }
            if (isset($image2Path) && $image2Path !== $image1Path) {
                Storage::disk('public')->delete($image2Path);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    /**
     * Display form to edit a product.
     *
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function editProduct($id)
    {
        $product = Product::with(['packages', 'tags'])->findOrFail($id);
        $categories = DB::table('products')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update an existing product.
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProduct(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'images' => 'nullable|array|max:2',
            'images.*' => 'image|max:2048',
            'remove_image1' => 'nullable|string',
            'remove_image2' => 'nullable|string',
            'packages' => 'required|array|min:1',
            'packages.*.id' => 'nullable|string|exists:packages,id',
            'packages.*.size' => 'required|string|max:50',
            'packages.*.price' => 'required|numeric|min:0',
            'packages.*.stock' => 'required|integer|min:0',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
        ]);

        $product = Product::findOrFail($id);

        DB::beginTransaction();

        try {
            $currentImage1 = $product->image1;
            $currentImage2 = $product->image2;

            $removeImage1 = $request->input('remove_image1') === '1';
            $removeImage2 = $request->input('remove_image2') === '1';

            // Initialize array for final images
            $finalImages = [];

            // Add existing images that are not marked for removal
            if (!$removeImage1 && $currentImage1) {
                $finalImages[] = $currentImage1;
            }
            if (!$removeImage2 && $currentImage2) {
                $finalImages[] = $currentImage2;
            }

            // Process new uploaded images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    // Determine directory based on current position
                    $position = count($finalImages);
                    $directory = $position === 0 ? 'assets/product_images/image' : 'assets/product_images/package';

                    // Store the new image
                    $path = $image->store($directory, 'public');
                    $finalImages[] = $path;
                }
            }

            // Ensure we have at least one image
            if (empty($finalImages)) {
                DB::rollBack();
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Product must have at least one image.');
            }

            // Update product with new images
            $product->image1 = $finalImages[0] ?? null;
            $product->image2 = $finalImages[1] ?? null;

            // Update other product fields
            $product->name = $validated['name'];
            $product->description = $validated['description'];
            $product->category = $validated['category'];
            $product->save();

            // Now delete the old images if they were marked for removal
            if ($removeImage1 && $currentImage1 && Storage::disk('public')->exists($currentImage1)) {
                Storage::disk('public')->delete($currentImage1);
            }
            if ($removeImage2 && $currentImage2 && Storage::disk('public')->exists($currentImage2)) {
                Storage::disk('public')->delete($currentImage2);
            }

            // Handle packages with duplicate checking
            $processedPackageIds = [];
            $mergedPackages = [];

            foreach ($validated['packages'] as $packageData) {
                if (!empty($packageData['id'])) {
                    // Update existing package
                    $package = Package::findOrFail($packageData['id']);

                    // Check if another package with the same size exists (excluding current package)
                    $duplicatePackage = $product->packages()
                        ->where('size', $packageData['size'])
                        ->where('id', '!=', $package->id)
                        ->first();

                    if ($duplicatePackage) {
                        // Merge with existing package
                        $duplicatePackage->stock += $packageData['stock'];
                        $duplicatePackage->price = $packageData['price'];
                        $duplicatePackage->save();

                        // Mark the current package for deletion
                        $package->delete();

                        $processedPackageIds[] = $duplicatePackage->id;
                        $mergedPackages[] = $packageData['size'];
                    } else {
                        // No duplicate, update normally
                        $package->size = $packageData['size'];
                        $package->price = $packageData['price'];
                        $package->stock = $packageData['stock'];
                        $package->save();

                        $processedPackageIds[] = $package->id;
                    }
                } else {
                    // Check if a package with this size already exists
                    $existingPackage = $product->packages()
                        ->where('size', $packageData['size'])
                        ->first();

                    if ($existingPackage) {
                        // Update existing package
                        $existingPackage->stock += $packageData['stock'];
                        $existingPackage->price = $packageData['price'];
                        $existingPackage->save();

                        $processedPackageIds[] = $existingPackage->id;
                        $mergedPackages[] = $packageData['size'];
                    } else {
                        // Create new package
                        $package = $product->packages()->create([
                            'size' => $packageData['size'],
                            'price' => $packageData['price'],
                            'stock' => $packageData['stock'],
                        ]);

                        $processedPackageIds[] = $package->id;
                    }
                }
            }

            // Delete packages not in the list
            $product->packages()
                ->whereNotIn('id', $processedPackageIds)
                ->delete();

            // Update tags
            $product->tags()->delete();
            if (!empty($validated['tags'])) {
                foreach ($validated['tags'] as $tagName) {
                    $product->tags()->create(['tag_name' => $tagName]);
                }
            }

            DB::commit();

            $message = 'Product updated successfully.';
            if (!empty($mergedPackages)) {
                $message .= ' Packages with sizes ' . implode(', ', $mergedPackages) . ' were merged.';
            }

            return redirect()->route('admin.products')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    /**
     * Delete a product.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        DB::beginTransaction();

        try {
            // Delete associated images
            if ($product->image1 && Storage::disk('public')->exists($product->image1)) {
                Storage::disk('public')->delete($product->image1);
            }
            if ($product->image2 && $product->image2 !== $product->image1 &&
                Storage::disk('public')->exists($product->image2)) {
                Storage::disk('public')->delete($product->image2);
            }

            // Delete product and all related data (packages, tags)
            $product->delete();

            DB::commit();

            return redirect()->route('admin.products')
                ->with('success', 'Product deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }

    /**
     * Display orders management page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function orders(Request $request)
    {
        $statusFilter = $request->input('status');

        $ordersQuery = Order::with(['user', 'items.package.product']);

        if ($statusFilter) {
            $ordersQuery->where('status', $statusFilter);
        }

        $orders = $ordersQuery->latest()->paginate(10);

        $statusCounts = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('admin.orders', compact('orders', 'statusFilter', 'statusCounts'));
    }

    /**
     * Display single order details.
     *
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function showOrder($id)
    {
        $order = Order::with(['user', 'address', 'items.package.product'])
            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status.
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:created,processing,shipped,delivered,canceled'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Order status updated successfully');
    }

    /**
     * Display users management page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function users(Request $request)
    {
        $searchTerm = $request->input('search');
        $userType = $request->input('type');

        $usersQuery = User::query();

        if ($searchTerm) {
            $usersQuery->where(function($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('surname', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        if ($userType) {
            if ($userType === 'admin') {
                $usersQuery->where('is_admin', true);
            } elseif ($userType === 'customer') {
                $usersQuery->where('is_admin', false);
            }
        }

        $users = $usersQuery->latest()->paginate(10);

        $userCounts = [
            'total' => User::count(),
            'admin' => User::where('is_admin', true)->count(),
            'customer' => User::where('is_admin', false)->count()
        ];

        return view('admin.users', compact('users', 'searchTerm', 'userType', 'userCounts'));
    }

    /**
     * Toggle user admin status.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleUserAdmin($id)
    {
        $user = User::findOrFail($id);

        // Prevent removing admin status from yourself
        if ($user->id === auth()->id() && $user->is_admin) {
            return redirect()->back()->with('error', 'You cannot remove your own admin privileges.');
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        return redirect()->back()->with('success', 'User admin status updated successfully.');
    }

    /**
     * Display the categories management page.
     *
     * @return \Illuminate\View\View
     */
    public function categories()
    {
        $categories = DB::table('products')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->get()
            ->pluck('category');

        return view('admin.categories', compact('categories'));
    }

    /**
     * Store a new category.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255'
        ]);

        // Check if category already exists
        $exists = DB::table('products')
            ->where('category', $request->category)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Category already exists');
        }

        // Since categories exist in the products table,
        // we need a different approach to create a new category.
        // We could create a placeholder product or just store the category in a session
        // and use it when creating new products.

        // For now, we'll just redirect back with a success message
        return redirect()->route('admin.categories')
            ->with('success', 'Category added successfully. You can now use it when creating products.');
    }

    /**
     * Update an existing category.
     *
     * @param Request $request
     * @param string $id (old category name)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|string|max:255'
        ]);

        // Check if new category name already exists
        if ($request->category !== $id) {
            $exists = DB::table('products')
                ->where('category', $request->category)
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->with('error', 'Category already exists');
            }
        }

        // Update all products with the old category
        DB::table('products')
            ->where('category', $id)
            ->update(['category' => $request->category]);

        return redirect()->route('admin.categories')
            ->with('success', 'Category updated successfully');
    }

    /**
     * Delete a category.
     *
     * @param string $id (category name)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteCategory($id)
    {
        // Check if there are products in this category
        $productsCount = DB::table('products')
            ->where('category', $id)
            ->count();

        if ($productsCount > 0) {
            return redirect()->back()
                ->with('error', "Cannot delete category: {$productsCount} products are using this category. Move or delete these products first.");
        }

        // At this point, we know there are no products with this category
        return redirect()->route('admin.categories')
            ->with('success', 'Category removed successfully');
    }

    /**
     * Search across admin resources.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Please enter a search term');
        }

        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->take(5)
            ->get();

        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('surname', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->take(5)
            ->get();

        $orders = Order::with('user')
            ->where('id', 'like', "%{$query}%")
            ->orWhereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('surname', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->take(5)
            ->get();

        return view('admin.search-results', compact('query', 'products', 'users', 'orders'));
    }
}
