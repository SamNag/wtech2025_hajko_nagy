<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Public routes
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.detail');
Route::get('/about', function () { return view('about'); })->name('about');
Route::get('/contact', function () { return view('contact'); })->name('contact');

// Cart & Checkout
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/items', [CartController::class, 'getItems'])->name('cart.items');
Route::post('/cart/sync', [CartController::class, 'sync'])->name('cart.sync');
// Checkout process
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/order/confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('orders.confirmation');


Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', function (Request $request) {
        // Check if user is admin
        if (!auth()->user()->is_admin) {
            return redirect('/')->with('error', 'You do not have admin access.');
        }

        return app(AdminController::class)->index($request);
    })->name('admin');

    Route::get('/products', function (Request $request) {
        if (!auth()->user()->is_admin) {
            return redirect('/')->with('error', 'You do not have admin access.');
        }
        return app(AdminController::class)->products($request);
    })->name('admin.products');

    Route::get('/orders', function (Request $request) {
        if (!auth()->user()->is_admin) {
            return redirect('/')->with('error', 'You do not have admin access.');
        }
        return app(AdminController::class)->orders($request);
    })->name('admin.orders');

    Route::get('/users', function (Request $request) {
        if (!auth()->user()->is_admin) {
            return redirect('/')->with('error', 'You do not have admin access.');
        }
        return app(AdminController::class)->users($request);
    })->name('admin.users');
});

// Authentication & User Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Email Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/home');
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// First, create a separate middleware for admin check
Route::middleware(['auth', 'admin'])->group(function () {
    // Main admin dashboard
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');

    // Admin prefixed routes
    Route::prefix('admin')->name('admin.')->group(function () {
        // User management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::patch('/users/{id}/toggle-admin', [AdminController::class, 'toggleUserAdmin'])->name('users.toggle-admin');

        // Product management
        Route::get('/products', [AdminController::class, 'products'])->name('products');
        Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
        Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
        Route::get('/products/{id}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
        Route::patch('/products/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('products.delete');

        // Order management
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [AdminController::class, 'showOrder'])->name('orders.show');
        Route::patch('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update-status');

        // Category management
        Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('categories.delete');

        // Search
        Route::get('/search', [AdminController::class, 'search'])->name('search');
    });
});

Route::get('/check-stock/{packageId}', [App\Http\Controllers\CartController::class, 'checkStock'])->name('check-stock');
require __DIR__.'/auth.php';
