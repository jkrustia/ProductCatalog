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
*/

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/', function () {
    return view('home');
})->name('home');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// New Registration Routes
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/test-role', function () {
    return 'Role middleware works!';
})->middleware('role:Super Admin');
// // Product Catalog Application Routes
// Implement Role-Based Access Control (RBAC) using Spatie Laravel Permission Middleware
// Roles: Superadmin, Admin, Product Manager
// =========================================

// 1. **Authentication**:
// - Routes for login and logout for both Admin and Product Manager.
// - Redirect users to their respective dashboards after login.
// - Logout should clear the session.
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); // Add this line
//Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('/dashboard/admin', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard')->middleware(['auth', 'role:Admin']);
Route::get('/dashboard/pm', [DashboardController::class, 'pmDashboard'])->name('pm.dashboard')->middleware(['auth', 'role:Product Manager']);


// Authentication routes

// 2. **Role-Based Access Control (RBAC)**:
// - Superadmin: Access to all routes for managing users, roles, permissions, and system settings.
// - Admin: Full access to product, pricing, category, inventory, and settings management.
// - Product Manager: Access limited to product and inventory management (cannot access pricing or financials).
// - Use Spatie Laravel Permission Middleware to enforce these roles and permissions.
Route::group(['middleware' => ['auth', 'role:Super Admin']], function () {
    Route::resource('users', UsersController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::get('/superadmin/admins', [UsersController::class, 'adminList'])->name('admin.index');
    Route::get('/superadmin/productmanagers', [UsersController::class, 'productManagerList'])->name('productmanager.index');
    Route::get('/superadmin/admins/{id}', [UsersController::class, 'showAdmin'])->name('admin.show');
    Route::get('/superadmin/admins/{id}/edit', [UsersController::class, 'editAdmin'])->name('admin.edit');
    Route::delete('/superadmin/admins/{id}', [UsersController::class, 'destroyAdmin'])->name('admin.destroy');
    Route::get('/superadmin/admins/create', [UsersController::class, 'createAdmin'])->name('admin.create');
    Route::get('/superadmin/productmanagers/create', [UsersController::class, 'createProductManager'])->name('productmanager.create');
    Route::get('/superadmin/productmanagers/{id}', [UsersController::class, 'showProductManager'])->name('productmanager.show');
    Route::get('/superadmin/productmanagers/{id}/edit', [UsersController::class, 'editProductManager'])->name('productmanager.edit');
    Route::delete('/superadmin/productmanagers/{id}', [UsersController::class, 'destroyProductManager'])->name('productmanager.destroy');
});

Route::group(['middleware' => ['auth', 'role:Product Manager']], function () {
    Route::resource('products', ProductController::class)->only(['index','create', 'store', 'edit', 'update']);
    Route::resource('inventory', InventoryController::class)->only(['index', 'edit', 'update']);
    
});

// 3. **Admin Routes** (Full Access):
// - Manage products (add, update, delete, view).
// - Manage categories (add, update, delete, view).
// - Manage inventory (view and update inventory).
// - Manage pricing (add and update product prices).
// - Access dashboard and reports.

Route::group(['middleware' => ['auth', 'role:Admin']], function () {
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoriesController::class);
    Route::resource('inventory', InventoryController::class);
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});


// Admin routes
Route::group(['middleware' => ['auth', 'role:Admin']], function () {
    // Admin dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard'); 
    // User management
    Route::resource('users', UsersController::class)->except(['show']);
    // View users, create new users, edit existing users, and update user details.
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy'); 

    // Product management
    Route::resource('products', ProductController::class)->except(['destroy']);
    // View products, create new products, edit existing products, and update product details.
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');

});
    //
    // 
    //  Assign products to categories
    Route::post('/products/{product}/assign-category', [ProductController::class, 'assignCategory'])->name('products.assignCategory');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    


    // Category management
    Route::resource('categories', CategoriesController::class)->except(['destroy']);
    Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoriesController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoriesController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoriesController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoriesController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoriesController::class, 'destroy'])->name('categories.destroy');

    // Inventory management
    Route::resource('inventory', InventoryController::class)->except(['destroy']);
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/{product}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('/inventory/{product}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::get('/inventory/stock-status', [InventoryController::class, 'stockStatus'])->name('inventory.stockStatus');

    

   

// 4. **Product Manager Routes** (Limited Access):
// - Manage products (add and edit only).
// - View and update inventory.
// - No access to pricing or categories.


// Product Manager routes
Route::group(['middleware' => ['auth', 'role:Product Manager']], function () {
    Route::resource('products', ProductController::class)->only(['index', 'create', 'store', 'edit', 'update']);
    Route::resource('inventory', InventoryController::class)->only(['index', 'edit', 'update']);
    Route::get('/pm/dashboard', [ProjManController::class, 'dashboard'])->name('pm.dashboard');
    
});


// 5. **General Requirements**:
// - Stock status display: Display "In Stock", "Low Stock", or "Out of Stock" based on inventory levels.
// - Define a route for inventory stock status.
Route::resource('prices', PriceController::class)->except(['destroy']);
Route::get('/inventory/stock-status', [InventoryController::class, 'stockStatus'])->name('inventory.stockStatus');

// - Use a middleware to check user roles and permissions for accessing specific routes.            
Route::middleware(['auth'])->group(function () {
    Route::get('/inventory/stock-status', [InventoryController::class, 'stockStatus'])->name('inventory.stockStatus');
});
// - Implement a method in the InventoryController to check stock levels and return appropriate status.
Route::get('/inventory/stock-status', [InventoryController::class, 'stockStatus'])->name('inventory.stockStatus');


// 6. **Superadmin Routes**:
// - Manage users, roles, and permissions.
// - Access to all system settings (Superadmin only).

// Superadmin routes
// This group of routes is protected by the 'auth' middleware and restricted to users with the 'Super Admin' role.
// It allows Super Admins to manage users, roles, permissions, and access the superadmin dashboard.
Route::group(['middleware' => ['auth', 'role:Super Admin']], function () {  
    Route::resource('users', UsersController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::get('/superadmin/dashboard', function () {
        return view('superadmin.index');
    })->name('superadmin.dashboard');
});

// Admin dashboard
Route::group(['middleware' => ['auth', 'role:Admin']], function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Product Manager dashboard
Route::group(['middleware' => ['auth', 'role:Product Manager']], function () {
    Route::get('/productmanager/dashboard', function () {
        return view('productmanager.dashboard');
    })->name('productmanager.dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('inventory', InventoryController::class);
    Route::resource('categories', CategoriesController::class);
    Route::resource('users', UsersController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
});
Route::post('/inventory/bulk-update', [InventoryController::class, 'bulkUpdate'])->name('inventory.bulkUpdate');



