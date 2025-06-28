<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call your individual seeders in the correct order of dependencies
        $this->call([
            RoleSeeder::class,        // Roles before Users
            CategorySeeder::class,    // Categories before SubCategories
            SubCategorySeeder::class, // SubCategories after Categories
            UserSeeder::class,        // Users after Roles
            ProductSeeder::class,     // Products after Categories & SubCategories
        ]);
    }
}