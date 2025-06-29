<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role; 
use Spatie\Permission\Models\Role as SpatieRole;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles']);
        $role = Role::create(['name' => $request->name]);
        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }



    public function createRole()
    {
        Role::firstOrCreate(['name' => 'Super Admin']);
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Product Manager']);
        return 'Role created successfully';
    }
    
    
    /**
     * Display a listing of the resource.
     */
   

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Logic to show form for creating a role
    }

    /**
     * Store a newly created resource in storage.
     */
   

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        // Logic to display a specific role
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        // Logic to show form for editing a role
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        // Logic to update a role
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Logic to delete a role
    }
    
    public function assignPermissionToAdmin()
    {
        $role = SpatieRole::findByName('Admin');
        $role->givePermissionTo('edit articles'); // replace with your permission name
    }
}
