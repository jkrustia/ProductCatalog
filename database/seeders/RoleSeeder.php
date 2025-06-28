<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{

    public function run()
    {
        // Clear cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        // Products Permissions
        Permission::firstOrCreate(['name' => 'view products', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create products', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit products', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete products', 'guard_name' => 'web']);

        // Category Permissions
        Permission::firstOrCreate(['name' => 'view category', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create category', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit category', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete category', 'guard_name' => 'web']);

        // Prices Permissions
        Permission::firstOrCreate(['name' => 'edit prices', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view prices', 'guard_name' => 'web']);

        // Inventory Permissions
        Permission::firstOrCreate(['name' => 'view inventory', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit inventory', 'guard_name' => 'web']);

        
        // Users Permissions
        Permission::firstOrCreate(['name' => 'view users', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create users', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit users', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete users', 'guard_name' => 'web']);


        // Create roles
        $SuperAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $Admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $ProductManager = Role::firstOrCreate(['name' => 'Product Manager', 'guard_name' => 'web']);

        // Assign permissions to roles
        $SuperAdmin->givePermissionTo(Permission::all());
        $Admin->givePermissionTo([
            'view products', 'create products', 'edit products', 'delete products',
            'view category', 'create category', 'edit category', 'delete category',
            'edit prices', 'view prices',
            'view inventory', 'edit inventory'
        ]);
        $ProductManager->givePermissionTo([
            'view products', 'create products', 'edit products', 'delete products',
            'view inventory', 'edit inventory', 'view category', 'view prices'
        ]);
    }
    
    
}
