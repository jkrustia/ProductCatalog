<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Import User model
use Spatie\Permission\Models\Role; // Correct import for Spatie's Role model
use Illuminate\Support\Facades\Hash; // For password hashing

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch roles after they have been created by RoleSeeder
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $adminRole = Role::where('name', 'Admin')->first();
        $pmRole = Role::where('name', 'Product Manager')->first();

        // It's good practice to check if roles exist before attempting to use them
        if (!$superAdminRole || !$adminRole || !$pmRole) {
            $this->command->warn('Roles not found. Please ensure RoleSeeder runs before UserSeeder.');
            return;
        }

        // Create Super Admin User
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                // 'role_id' => $superAdminRole->id, // REMOVE THIS LINE
            ]
        );
        $superAdminUser->assignRole($superAdminRole); // Assign role using Spatie's method

        // Create Admin User
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                // 'role_id' => $adminRole->id, // REMOVE THIS LINE
            ]
        );
        $adminUser->assignRole($adminRole); // Assign role using Spatie's method

        // Create Product Manager User
        $pmUser = User::firstOrCreate(
            ['email' => 'PM@example.com'],
            [
                'name' => 'PM User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                // 'role_id' => $pmRole->id, // REMOVE THIS LINE
            ]
        );
        $pmUser->assignRole($pmRole); // Assign role using Spatie's method
    }
}