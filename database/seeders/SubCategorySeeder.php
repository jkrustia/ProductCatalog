<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;    // Import Category model
use App\Models\SubCategory; // Import SubCategory model

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the IDs of the categories created by CategorySeeder
        $dogsCategory = Category::where('name', 'Dog Supplies')->first();
        $catsCategory = Category::where('name', 'Cat Supplies')->first();

        if ($dogsCategory) {
            \App\Models\SubCategory::firstOrCreate([
                'category_id' => $dogsCategory->id,
                'name' => 'Dry Food'
            ]);
            \App\Models\SubCategory::firstOrCreate([
                'category_id' => $dogsCategory->id,
                'name' => 'Wet Food'
            ]);
            \App\Models\SubCategory::firstOrCreate([
                'category_id' => $dogsCategory->id,
                'name' => 'Toy'
            ]);
            \App\Models\SubCategory::firstOrCreate([
                'category_id' => $dogsCategory->id,
                'name' => 'Vitamins'
            ]);
        }

        if ($catsCategory) {
            \App\Models\SubCategory::firstOrCreate([
                'category_id' => $catsCategory->id,
                'name' => 'Dry Food'
            ]);
            \App\Models\SubCategory::firstOrCreate([
                'category_id' => $catsCategory->id,
                'name' => 'Wet Food'
            ]);
            \App\Models\SubCategory::firstOrCreate([
                'category_id' => $catsCategory->id,
                'name' => 'Toy'
            ]);
            \App\Models\SubCategory::firstOrCreate([
                'category_id' => $catsCategory->id,
                'name' => 'Litter'
            ]);
        }
    }
}
