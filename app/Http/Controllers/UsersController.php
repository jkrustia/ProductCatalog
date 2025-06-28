<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    // Dummy user data with permissions
    private $users = [
        [
            'id' => 1,
            'role' => 'Admin',
            'name' => 'Alice Smith',
            'email' => 'alice@example.com',
            'username' => 'alice',
            'password' => 'admin12345',
            'permissions' => ['View Users', 'Edit Products', 'Full Access'],
        ],
        [
            'id' => 2,
            'role' => 'Product Manager',
            'name' => 'David Miller',
            'email' => 'david@example.com',
            'username' => 'david',
            'password' => 'pm12345',
            'permissions' => ['View Products', 'Assign Tasks'],
        ],
        [
            'id' => 3,
            'role' => 'User',
            'name' => 'Eve Adams',
            'email' => 'eve@example.com',
            'username' => 'eve',
            'password' => 'user12345',
            'permissions' => ['Read Only'],
        ],
    ];

    public function index()
    {
        $users = $this->users;
        return view('superadmin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = collect($this->users)->firstWhere('id', $id);
        if (!$user) {
            abort(404);
        }
        return view('superadmin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = collect($this->users)->firstWhere('id', $id);
        if (!$user) {
            abort(404);
        }
        return view('superadmin.users.edit', compact('user'));
    }

    public function create()
    {
        return view('superadmin.users.create');
    }

    public function destroy($id)
    {
        // Dummy delete logic
        return redirect()->route('user.index');
    }

    public function adminList()
    {
        // Fetch all admins and their permissions
        $admin = \App\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'Admin');
        })->with('permissions')->get()->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'permissions' => $user->getPermissionNames(),
            ];
        });

        return view('superadmin.admin.index', compact('admin'));
    }
    public function productManagerList()
    {
        // Fetch all product managers and their permissions
        $prodman = \App\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'Product Manager');
        })->with('permissions')->get()->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'permissions' => $user->getPermissionNames(),
            ];
        });

        return view('superadmin.productmanager.index', compact('prodman'));
    }
    public function showAdmin($id)
    {
        // Fetch the admin user by ID (adjust as needed for your data source)
        $admin = \App\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'Admin');
        })->where('id', $id)->with('permissions')->first();

        if (!$admin) {
            abort(404);
        }

        return view('superadmin.admin.show', compact('admin'));
    }
    public function editAdmin($id)
    {
        // Fetch the admin user by ID (adjust as needed for your data source)
        $admin = collect($this->users)->firstWhere('id', $id);
        if (!$admin || $admin['role'] !== 'Admin') {
            abort(404);
        }
        return view('superadmin.admin.edit', compact('admin'));
    }
    public function destroyAdmin($id)
    {
        // Add your delete logic here (dummy or real)
        // For dummy data, just redirect back for now
        return redirect()->route('admin.index')->with('success', 'Admin deleted successfully.');
    }
    public function createAdmin()
    {
        return view('superadmin.admin.create');
    }
    public function createProductManager()
    {
        return view('superadmin.productmanager.create');
    }
    public function showProductManager($id)
    {
        $prodman = \App\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'Product Manager');
        })->where('id', $id)->with('permissions')->first();

        if (!$prodman) {
            abort(404);
        }

        return view('superadmin.productmanager.show', compact('prodman'));
    }
    public function editProductManager($id)
    {
        $prodman = \App\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'Product Manager');
        })->where('id', $id)->with('permissions')->first();

        if (!$prodman) {
            abort(404);
        }

        return view('superadmin.productmanager.edit', compact('prodman'));
    }
    public function destroyProductManager($id)
    {
        // Add your delete logic here (dummy or real)
        // For now, just redirect back for demonstration
        return redirect()->route('productmanager.index')->with('success', 'Product Manager deleted successfully.');
    }
}