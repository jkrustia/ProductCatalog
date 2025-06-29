<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::with('roles', 'permissions')->get()->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->getRoleNames()->first() ?? 'No Role',
                // Get permission names as array of strings
                'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
            ];
        });

        return view('superadmin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with('roles', 'permissions')->findOrFail($id);
        
        // Format user data similar to the dummy structure for view compatibility
        $userData = [
            'id' => $user->id,
            'role' => $user->getRoleNames()->first() ?? 'No Role',
            'name' => $user->name,
            'email' => $user->email,
            'username' => $user->email,
            'permissions' => $user->getAllPermissions()->pluck('name')->toArray(), // Convert to array
            'created_at' => $user->created_at,
        ];
        
        return view('superadmin.users.show', ['user' => $userData]);
    }

    public function edit($id)
    {
        $user = User::with('roles', 'permissions')->findOrFail($id);
        
        // Format user data similar to the dummy structure for view compatibility
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'username' => $user->email,
            'role' => $user->getRoleNames()->first() ?? 'No Role', // Add role for edit form
            'password' => '', // Empty password field for security
            'permissions' => $user->getAllPermissions()->pluck('name')->toArray(), // Convert to array
        ];
        
        return view('superadmin.users.edit', ['user' => $userData]);
    }

    public function create()
    {
        return view('superadmin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:Admin,Product Manager', // Use specific role names
            'username' => 'nullable|string|max:255', // Add username validation
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Assign role to user
        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:Admin,Product Manager', // Use specific role names
            'username' => 'nullable|string|max:255', // Add username validation
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $user->update(['password' => bcrypt($request->password)]);
        }

        // Update role
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function adminList()
    {
        // Fetch all admins and their permissions
        $admin = User::whereHas('roles', function($q) {
            $q->where('name', 'Admin');
        })->with('permissions')->get()->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'permissions' => $user->getAllPermissions()->pluck('name')->toArray(), // Convert to array
                'created_at' => $user->created_at,
            ];
        })->toArray(); // Convert collection to array

        return view('superadmin.admin.index', compact('admin'));
    }

    public function productManagerList()
    {
        // Fetch all product managers and their permissions
        $prodman = User::whereHas('roles', function($q) {
            $q->where('name', 'Product Manager');
        })->with('permissions')->get()->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'permissions' => $user->getAllPermissions()->pluck('name')->toArray(), // Convert to array
                'created_at' => $user->created_at,
            ];
        })->toArray(); // Convert collection to array

        return view('superadmin.productmanager.index', compact('prodman'));
    }

    public function showAdmin($id)
    {
        // Fetch the admin user by ID
        $admin = User::whereHas('roles', function($q) {
            $q->where('name', 'Admin');
        })->where('id', $id)->with('permissions')->first();

        if (!$admin) {
            abort(404);
        }

        // Format admin data for view compatibility
        $adminData = [
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
            'username' => $admin->email,
            'role' => 'Admin',
            'permissions' => $admin->getAllPermissions()->pluck('name')->toArray(),
            'created_at' => $admin->created_at,
        ];

        return view('superadmin.admin.show', ['admin' => $adminData]);
    }

    public function editAdmin($id)
    {
        // Fetch the admin user by ID from database
        $admin = User::whereHas('roles', function($q) {
            $q->where('name', 'Admin');
        })->where('id', $id)->with('permissions')->first();
        
        if (!$admin) {
            abort(404);
        }
        
        // Format admin data for view compatibility
        $adminData = [
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
            'username' => $admin->email,
            'role' => 'Admin',
            'password' => '', // Empty password field for security
            'permissions' => $admin->getAllPermissions()->pluck('name')->toArray(), // Convert to array
        ];
        
        return view('superadmin.admin.edit', ['admin' => $adminData]);
    }

    public function destroyAdmin($id)
    {
        $admin = User::whereHas('roles', function($q) {
            $q->where('name', 'Admin');
        })->where('id', $id)->first();
        
        if (!$admin) {
            abort(404);
        }
        
        $admin->delete();
        
        return redirect()->route('admin.index')->with('success', 'Admin deleted successfully.');
    }

    public function createAdmin()
    {
        return view('superadmin.admin.create');
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'username' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Assign Admin role
        $user->assignRole('Admin');

        return redirect()->route('admin.index')->with('success', 'Admin created successfully.');
    }

    public function updateAdmin(Request $request, $id)
    {
        $admin = User::whereHas('roles', function($q) {
            $q->where('name', 'Admin');
        })->where('id', $id)->first();
        
        if (!$admin) {
            abort(404);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
            'username' => 'nullable|string|max:255',
        ]);

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $admin->update(['password' => bcrypt($request->password)]);
        }

        // Make sure role is still Admin (in case it was changed)
        $admin->syncRoles(['Admin']);

        return redirect()->route('admin.index')->with('success', 'Admin updated successfully.');
    }

    public function createProductManager()
    {
        return view('superadmin.productmanager.create');
    }

    public function storeProductManager(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'username' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Assign Product Manager role
        $user->assignRole('Product Manager');

        return redirect()->route('productmanager.index')->with('success', 'Product Manager created successfully.');
    }

    public function showProductManager($id)
    {
        $prodman = User::whereHas('roles', function($q) {
            $q->where('name', 'Product Manager');
        })->where('id', $id)->with('permissions')->first();

        if (!$prodman) {
            abort(404);
        }

        // Format product manager data for view compatibility
        $prodmanData = [
            'id' => $prodman->id,
            'name' => $prodman->name,
            'email' => $prodman->email,
            'username' => $prodman->email,
            'role' => 'Product Manager',
            'permissions' => $prodman->getAllPermissions()->pluck('name')->toArray(),
            'created_at' => $prodman->created_at,
        ];

        return view('superadmin.productmanager.show', ['prodman' => $prodmanData]);
    }

    public function editProductManager($id)
    {
        $prodman = User::whereHas('roles', function($q) {
            $q->where('name', 'Product Manager');
        })->where('id', $id)->with('permissions')->first();

        if (!$prodman) {
            abort(404);
        }
        
        // Format product manager data for view compatibility
        $prodmanData = [
            'id' => $prodman->id,
            'name' => $prodman->name,
            'email' => $prodman->email,
            'username' => $prodman->email,
            'role' => 'Product Manager',
            'password' => '', // Empty password field for security
            'permissions' => $prodman->getAllPermissions()->pluck('name')->toArray(), // Convert to array
        ];

        return view('superadmin.productmanager.edit', ['prodman' => $prodmanData]);
    }

    public function updateProductManager(Request $request, $id)
    {
        $prodman = User::whereHas('roles', function($q) {
            $q->where('name', 'Product Manager');
        })->where('id', $id)->first();
        
        if (!$prodman) {
            abort(404);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $prodman->id,
            'username' => 'nullable|string|max:255',
        ]);

        $prodman->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $prodman->update(['password' => bcrypt($request->password)]);
        }

        // Make sure role is still Product Manager (in case it was changed)
        $prodman->syncRoles(['Product Manager']);

        return redirect()->route('productmanager.index')->with('success', 'Product Manager updated successfully.');
    }

    public function destroyProductManager($id)
    {
        $prodman = User::whereHas('roles', function($q) {
            $q->where('name', 'Product Manager');
        })->where('id', $id)->first();
        
        if (!$prodman) {
            abort(404);
        }
        
        $prodman->delete();
        
        return redirect()->route('productmanager.index')->with('success', 'Product Manager deleted successfully.');
    }
}
