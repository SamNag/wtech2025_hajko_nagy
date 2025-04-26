<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Package;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
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
        $orders = Order::with(['user', 'items.package.product'])
            ->latest()
            ->paginate(10);

        return view('admin.orders', compact('orders'));
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
            'status' => 'required|in:created,shipped,delivered,canceled'
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
        $users = User::latest()->paginate(10);
        return view('admin.users', compact('users'));
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
}
