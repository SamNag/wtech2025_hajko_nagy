<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the products with filtering and pagination.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Start with base query
        $baseQuery = Product::with(['packages', 'tags']);

        // Apply category filter first to determine available options for other filters
        if ($request->has('category') && $request->category != 'all') {
            $baseQuery->where('category', $request->category);
        }

        // Clone the query to get available filter options
        $availableOptionsQuery = clone $baseQuery;

        // Get available tags for the current product set
        $availableTags = Tag::whereHas('product', function($query) use ($availableOptionsQuery) {
            $query->whereIn('id', $availableOptionsQuery->pluck('id'));
        })->select('tag_name')->distinct()->pluck('tag_name');

        // Get available packages for the current product set
        $availablePackages = Package::whereHas('product', function($query) use ($availableOptionsQuery) {
            $query->whereIn('id', $availableOptionsQuery->pluck('id'));
        })->select('size')->distinct()->pluck('size');

        // Get price range for the current product set
        $priceRange = Package::whereHas('product', function($query) use ($availableOptionsQuery) {
            $query->whereIn('id', $availableOptionsQuery->pluck('id'));
        })->selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();

        $minPrice = $priceRange ? floor($priceRange->min_price) : 0;
        $maxPrice = $priceRange ? ceil($priceRange->max_price) : 100;

        // Now build the actual filtered query
        $query = Product::with(['packages', 'tags']);

        // Apply category filter
        if ($request->has('category') && $request->category != 'all') {
            $query->where('category', $request->category);
        }

        // Apply search filter - case-insensitive full-text search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                    // Search in tags as well
                    ->orWhereHas('tags', function($tagQuery) use ($searchTerm) {
                        $tagQuery->whereRaw('LOWER(tag_name) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
                    });
            });
        }

        // Apply tag filter
        if ($request->has('tag') && !empty($request->tag)) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tag_name', $request->tag);
            });
        }

        // Apply package filter
        if ($request->has('package') && !empty($request->package)) {
            $query->whereHas('packages', function ($q) use ($request) {
                $q->where('size', $request->package);
            });
        }

        // Apply price range filter
        if ($request->has('min_price') || $request->has('max_price')) {
            $minPriceFilter = $request->min_price ?? $minPrice;
            $maxPriceFilter = $request->max_price ?? $maxPrice;

            $query->whereHas('packages', function ($q) use ($minPriceFilter, $maxPriceFilter) {
                $q->where('price', '>=', $minPriceFilter)
                    ->where('price', '<=', $maxPriceFilter);
            });
        }

        // Apply sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name-asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name-desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'price-asc':
                    $query->join('packages', 'products.id', '=', 'packages.product_id')
                        ->select('products.*')
                        ->groupBy('products.id')
                        ->orderBy(DB::raw('MIN(packages.price)'), 'asc');
                    break;
                case 'price-desc':
                    $query->join('packages', 'products.id', '=', 'packages.product_id')
                        ->select('products.*')
                        ->groupBy('products.id')
                        ->orderBy(DB::raw('MIN(packages.price)'), 'desc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }
        } else {
            // Default sorting
            $query->orderBy('name', 'asc');
        }

        // Get the products with pagination (12 per page)
        $products = $query->paginate(12);

        return view('products', compact('products', 'availableTags', 'availablePackages', 'minPrice', 'maxPrice'));
    }

    /**
     * Display the specified product.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $product = Product::with(['packages', 'tags'])->findOrFail($id);
        return view('product-detail', compact('product'));
    }
}
