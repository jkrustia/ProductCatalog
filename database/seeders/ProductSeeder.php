<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;      // Import Product model
use App\Models\Category;     // Import Category model
use App\Models\SubCategory;  // Import SubCategory model

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dogFoodCat = Category::where('name', 'Dog Supplies')->first();
        $dogFoodSubCat = SubCategory::where('name', 'Food')->first();
        $catToysCat = Category::where('name', 'Cat Supplies')->first();
        $catToysSubCat = SubCategory::where('name', 'Toy')->first();

        if ($dogFoodCat && $dogFoodSubCat) {
            Product::create([
                'name' => 'Premium Adult Dog Food',
                'sku' => 'DS-DF-1',
                'description' => 'Nutrient-rich food for adult dogs.',
                'price' => 45.99,
                'stock_status' => 'In Stock',
                'quantity' => 100,
                'category_id' => $dogFoodCat->id,
                'sub_category_id' => $dogFoodSubCat->id,
            ]);
            Product::create([
                'name' => 'Grain-Free Puppy Kibble',
                'sku' => 'DS-DF-2',
                'description' => 'Healthy start for growing puppies.',
                'price' => 32.50,
                'stock_status' => 'In Stock',
                'quantity' => 200,
                'category_id' => $dogFoodCat->id,
                'sub_category_id' => $dogFoodSubCat->id,
            ]);
        }

        if ($catToysCat && $catToysSubCat) {
            Product::create([
                'name' => 'Feather Teaser Wand',
                'sku' => 'CS-CT-1',
                'description' => 'Interactive toy for playful cats.',
                'price' => 9.99,
                'stock_status' => 'Low Stock',
                'quantity' => 5,
                'category_id' => $catToysCat->id,
                'sub_category_id' => $catToysSubCat->id,
            ]);
            Product::create([
                'name' => 'Catnip Stuffed Mouse',
                'sku' => 'CS-CT-2',
                'description' => 'Classic cat toy with natural catnip.',
                'price' => 5.25,
                'stock_status' => 'Out Of Stock',
                'quantity' => 0,
                'category_id' => $catToysCat->id,
                'sub_category_id' => $catToysSubCat->id,
            ]);
        }
    }
}
