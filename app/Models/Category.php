<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubCategory; // Import SubCategory model
use App\Models\Product;     // Import Product model

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get the subcategories for the category.
     */
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    /**
     * Get the products for the category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}