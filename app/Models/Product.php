<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;
use App\Models\SubCategory;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $fillable = [
        'name',
        'sku',
        'description',
        'category_id',
        'sub_category_id',
        'price',
        'quantity',
        'stock_status',
        'image_path',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    /**
     * Relationship with Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship with SubCategory
     */
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    /**
     * Get the full URL for the product image
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return asset('images/img-placeholder.png');
    }

    /**
     * Check if product has image
     */
    public function hasImage()
    {
        return !empty($this->image_path);
    }

    protected static function boot()
    {
        parent::boot();
        
        // Sets stock_status when creating a product
        static::creating(function ($product) {
            $product->stock_status = $product->calculateStockStatus();
        });
        
        // Update stock_status when quantity changes
        static::updating(function ($product) {
            if ($product->isDirty('quantity')) {
                $product->stock_status = $product->calculateStockStatus();
            }
        });
    }

    /**
     * Calculate stock status based on quantity
     */
    public function calculateStockStatus()
    {
        if ($this->quantity <= 0) {
            return 'Out of Stock';
        } elseif ($this->quantity <= 10) {
            return 'Low Stock';
        } else {
            return 'In Stock';
        }
    }
    
}
