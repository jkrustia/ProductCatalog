<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjManController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\PermissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are all loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// =========================================
// PUBLIC ROUTES (No Authentication Required)
// =========================================

// Guest Home Page (accessible to everyone)
// This route handles the public landing page.
// If a user is logged in and tries to access '/', they will be redirected to their authenticated home.
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home'); // Redirect to authenticated user's home
    }
    return view('home'); // points to resources/views/home.blade.php for guests
})->name('welcome'); // Main welcome route for guests

// Alternative home route (for compatibility)
Route::get('/home-public', function () {
    return view('home');
})->name('home.public');

// =========================================
// AUTHENTICATION ROUTES
// =========================================

// Login Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Registration Routes
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout'); // Alternative logout route

// =========================================
// TEST/DEMO ROUTES
// =========================================

// Test Role Middleware Route (for demonstration/testing purposes)
Route::get('/test-role', function () {
    return 'Role middleware works!';
})->middleware('role:Super Admin');

// =========================================
// AUTHENTICATED USER ROUTES
// =========================================

Route::middleware(['auth'])->group(function () {

    // =========================================
    // GENERAL AUTHENTICATED USER HOME
    // =========================================
    
    // Authenticated User's Home Page
    Route::get('/home', function () {
        return view('user.home'); // points to resources/views/user/home.blade.php
    })->name('home');

    // General Dashboard (with role-based redirection)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // =========================================
    // SUPER ADMIN ROUTES
    // =========================================
    
    Route::group(['middleware' => ['role:Super Admin']], function () {
        // Super Admin Dashboard
        Route::get('/superadmin/dashboard', function () {
            return view('superadmin.index');
        })->name('superadmin.dashboard');

        // User Management (Full CRUD)
        Route::resource('users', UsersController::class);
        
        // Admin Management routes group
        Route::prefix('superadmin/admins')->name('admin.')->group(function () {
            Route::get('/', [UsersController::class, 'adminList'])->name('index');
            Route::get('/create', [UsersController::class, 'createAdmin'])->name('create');
            Route::post('/', [UsersController::class, 'storeAdmin'])->name('store');
            Route::get('/{id}', [UsersController::class, 'showAdmin'])->name('show');
            Route::get('/{id}/edit', [UsersController::class, 'editAdmin'])->name('edit');
            Route::put('/{id}', [UsersController::class, 'updateAdmin'])->name('update');
            Route::delete('/{id}', [UsersController::class, 'destroyAdmin'])->name('destroy');
        });
        
        // Product Manager Management
        Route::get('/superadmin/productmanagers', [UsersController::class, 'productManagerList'])->name('productmanager.index');
        Route::get('/superadmin/productmanagers/create', [UsersController::class, 'createProductManager'])->name('productmanager.create');
        Route::post('/superadmin/productmanagers', [UsersController::class, 'storeProductManager'])->name('productmanager.store'); // Added store route
        Route::get('/superadmin/productmanagers/{id}', [UsersController::class, 'showProductManager'])->name('productmanager.show');
        Route::get('/superadmin/productmanagers/{id}/edit', [UsersController::class, 'editProductManager'])->name('productmanager.edit');
        Route::put('/superadmin/productmanagers/{id}', [UsersController::class, 'updateProductManager'])->name('productmanager.update'); // Added update route
        Route::delete('/superadmin/productmanagers/{id}', [UsersController::class, 'destroyProductManager'])->name('productmanager.destroy');

        // Role and Permission Management
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
    });

    // =========================================
    // ADMIN ROUTES (Full Access)
    // =========================================
    
    Route::group(['middleware' => ['role:Admin']], function () {
        // Primary Admin Dashboard (matches AuthController expectation)
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Alternative admin dashboard route
        Route::get('/dashboard/admin', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard.alt');

        // User Management (except show)
        Route::resource('users', UsersController::class)->except(['show']);
        Route::get('/users', [UsersController::class, 'index'])->name('users.index.admin');
        Route::get('/users/create', [UsersController::class, 'create'])->name('users.create.admin');
        Route::post('/users', [UsersController::class, 'store'])->name('users.store.admin');
        Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit.admin');
        Route::put('/users/{user}', [UsersController::class, 'update'])->name('users.update.admin');
        Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy.admin');

        // Product Management (Full CRUD)
        Route::resource('products', ProductController::class);
        
        // Additional product routes
        Route::post('/products/{product}/assign-category', [ProductController::class, 'assignCategory'])->name('products.assignCategory');

        // Category Management (Full CRUD)
        Route::resource('categories', CategoriesController::class);

        // Inventory Management
        Route::resource('inventory', InventoryController::class);
        Route::get('/inventory/stock-status', [InventoryController::class, 'stockStatus'])->name('inventory.stockStatus');
        
        // Bulk Update Route
        Route::post('/inventory/bulk-update', [InventoryController::class, 'bulkUpdate'])->name('inventory.bulkUpdate');

        // Price Management
        Route::resource('prices', PriceController::class)->except(['destroy']);
    });

    // =========================================
    // PRODUCT MANAGER ROUTES (Limited Access)
    // =========================================
    
    Route::group(['middleware' => ['role:Product Manager']], function () {
        // Primary Product Manager Dashboard (matches AuthController expectation)
        Route::get('/pm/dashboard', [ProjManController::class, 'dashboard'])->name('pm.dashboard');
        
        // Alternative PM dashboard routes
        Route::get('/productmanager/dashboard', function () {
            return view('productmanager.dashboard');
        })->name('productmanager.dashboard');
        Route::get('/dashboard/pm', [DashboardController::class, 'pmDashboard'])->name('pm.dashboard.alt');

        // Product Management (Limited - no delete)
        Route::resource('products', ProductController::class)->only(['index', 'create', 'store', 'edit', 'update']);

        // Inventory Management (Limited - view and edit only)
        Route::resource('inventory', InventoryController::class)->only(['index', 'edit', 'update']);
    });

    // =========================================
    // SHARED AUTHENTICATED ROUTES
    // (Accessible by multiple roles - specific permissions handled in controllers)
    // =========================================
    
    // General resource routes (access controlled within controllers)
    Route::resource('products', ProductController::class);
    Route::resource('inventory', InventoryController::class);
    Route::resource('categories', CategoriesController::class);
    Route::resource('users', UsersController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    // =========================================
    // INVENTORY SPECIFIC ROUTES
    // =========================================
    
    // Stock Status Route (accessible by authenticated users)
    Route::get('/inventory/stock-status', [InventoryController::class, 'stockStatus'])->name('inventory.stockStatus');
    
    // Bulk Update Route
    Route::post('/inventory/bulk-update', [InventoryController::class, 'bulkUpdate'])->name('inventory.bulkUpdate');

    // =========================================
    // DASHBOARD ROUTES (Role-based redirection handled in controller)
    // =========================================
    
    // Individual dashboard routes for each role
    Route::get('/admin/dashboard-view', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard.view');

    Route::get('/productmanager/dashboard', function () {
        return view('productmanager.dashboard');
    })->name('productmanager.dashboard.view');
});
