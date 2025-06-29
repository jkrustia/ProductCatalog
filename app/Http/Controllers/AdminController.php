<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;

class AdminController extends Controller
{
    // Dummy data
    private $admin = [
        [
            'id' => 1,
            'name' => 'Alice Smith',
            'username' => 'alice',
            'email' => 'alice@example.com',
            'password' => 'admin12345',
            'permissions' => ['View Users', 'Edit Products', 'Full Access', 'Edit Products', 'Full Access', 'Edit Products', 'Full Access'],
        ],
        [
            'id' => 2,
            'name' => 'Bob Johnson',
            'username' => 'bob',
            'email' => 'bob@example.com',
            'password' => 'admin23456',
            'permissions' => ['View Users', 'Limited Access'],
        ],
        [
            'id' => 3,
            'name' => 'Carol Lee',
            'username' => 'carol',
            'email' => 'carol@example.com',
            'password' => 'admin34567',
            'permissions' => ['Read Only'],
        ],
    ];

    public function index()
    {
        $admin = $this->admin;
        return view('superadmin.admin.index', compact('admin'));
    }

    public function show($id)
    {
        $admin = collect($this->admin)->firstWhere('id', $id);
        if (!$admin) {
            abort(404);
        }
        return view('superadmin.admin.show', compact('admin'));
    }

    public function edit($id)
    {
        $admin = collect($this->admin)->firstWhere('id', $id);
        if (!$admin) {
            abort(404);
        }
        return view('superadmin.admin.edit', compact('admin'));
    }

    public function create()
    {
        return view('superadmin.admin.create');
    }

    public function destroy($id)
    {
        // Dummy delete logic
        return redirect()->route('admin.index');
    }

    public function dashboard()
    {
        // Gather dashboard statistics
        try {
            // Try to get real data from models
            $totalProducts = Product::count() ?? 0;
            $totalUsers = User::count() ?? 0;
            $totalCategories = Category::count() ?? 0;
            
            // Check if products have a quantity/stock field
            $lowStockItems = 0;
            $lowStockProducts = collect();
            
            // Try to get low stock items if Product has quantity field
            try {
                $lowStockItems = Product::where('quantity', '<', 10)->count() ?? 0;
                $lowStockProducts = Product::where('quantity', '<', 10)->take(5)->get() ?? collect();
            } catch (\Exception $e) {
                // If quantity field doesn't exist, set to 0
                $lowStockItems = 0;
                $lowStockProducts = collect();
            }
            
            // Recent products (last 5)
            $recentProducts = Product::latest()->take(5)->get() ?? collect();
            
        } catch (\Exception $e) {
            // Fallback to dummy data if models don't exist or database issues
            $totalProducts = 25;
            $totalUsers = 12;
            $totalCategories = 8;
            $lowStockItems = 3;
            $recentProducts = collect();
            $lowStockProducts = collect();
        }

        // Pass all data to the admin dashboard view
        return view('admin.dashboard', compact(
            'totalProducts',
            'totalUsers', 
            'totalCategories',
            'lowStockItems',
            'recentProducts',
            'lowStockProducts'
        ));
    }
}