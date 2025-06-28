<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function dashboard() {
        // This method can be used to show the admin dashboard
        // You can implement any logic needed for the dashboard here
        
        // For now, just return the dashboard view
        // Ensure you have a view file at resources/views/dashboard/index.blade.php
        return view('dashboard.index');
    }
}