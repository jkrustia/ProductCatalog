<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category; // Import Category model
use App\Models\Product;  // Import Product model

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category_id'];

    /**
     * Get the category that owns the subcategory.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the products for the subcategory.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}