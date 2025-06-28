<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request) // Inject Request object
    {
        // Only allow users with the right role
        if (!auth()->user()->hasRole(['Admin', 'Product Manager'])) {
            abort(403, 'Unauthorized');
        }

        $query = Product::with(['category', 'subCategory']); // Start with eager loading for relationships

        // Search functionality
        if ($request->filled('query')) { // Check if the 'query' parameter is present and not empty
            $searchQuery = $request->input('query');
            $query->where(function($q) use ($searchQuery) {
                $q->where('name', 'like', '%' . $searchQuery . '%') // Search by product name
                  ->orWhere('sku', 'like', '%' . $searchQuery . '%') // Search by SKU
                  ->orWhere('stock_status', 'like', '%' . $searchQuery . '%') // Search by stock status
                  ->orWhere('description', 'like', '%' . $searchQuery . '%'); // Search by description
            });
        }

        // Category Filter
        if ($request->has('category')) { // Check if the 'category' parameter is present
            $categories = $request->input('category');
            $query->whereHas('category', function ($q) use ($categories) {
                $q->whereIn('name', $categories); // Filter by category name
            });
        }

        // Price Filter
        if ($request->has('price')) { // Check if the 'price' parameter is present
            $prices = $request->input('price');
            $query->where(function($q) use ($prices) {
                foreach ($prices as $priceRange) {
                    switch ($priceRange) {
                        case '1000-5000':
                            $q->orWhereBetween('price', [1000, 5000]); // Filter for prices between 1000 and 5000
                            break;
                        case '500-999':
                            $q->orWhereBetween('price', [500, 999]); // Filter for prices between 500 and 999
                            break;
                        case '0-499':
                            $q->orWhereBetween('price', [0, 499]); // Filter for prices between 0 and 499
                            break;
                    }
                }
            });
        }

        // Stock Filter
        // This logic now interprets the string filter values ("Low Stock", etc.)
        // into numeric conditions on the 'stock_status' column (assuming it holds numbers).
        if ($request->has('stock')) {
            $stocks = $request->input('stock');
            $query->where(function ($q) use ($stocks) {
                foreach ($stocks as $stockStatus) {
                    switch ($stockStatus) {
                        case 'Out of Stock':
                            $q->orWhere('stock_status', 0); // Products with 0 stock
                            break;
                        case 'Low Stock':
                            // Products with stock between 1 and 10 (inclusive)
                            $q->orWhereBetween('stock_status', [1, 10]);
                            break;
                        case 'In Stock':
                            // Products with stock greater than 10
                            $q->orWhere('stock_status', '>', 10);
                            break;
                    }
                }
            });
        }

        $products = $query->get(); // Execute the query with all applied filters and search terms
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        $subCategories = SubCategory::all();
        return view('products.create', compact('categories', 'subCategories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'category' => 'required|string',  // For create form
            'subcategory' => 'nullable|string',  // For create form
            'category_id' => 'nullable|exists:categories,id',  // For edit form
            'sub_category_id' => 'nullable|exists:sub_categories,id',  // For edit form
            'price' => 'required|numeric|min:0',
            // Changed validation for 'stock' to be numeric, assuming it's the actual quantity
            'stock' => 'nullable|integer|min:0',
            'quantity' => 'nullable|integer|min:0', // Keeping this if it's still used for string status
            'product_image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('product_images', 'public');
        }

        // Handle different field names for create vs edit
        $categoryName = $validated['category'] ?? null;
        $subcategoryName = $validated['subcategory'] ?? null;
        // If 'stock' is numeric input, use it. If 'stock_status' is a string from a form, use it.
        // Assuming 'stock' (numeric) is the primary source for actual stock level.
        $actualStockQuantity = $validated['stock'] ?? null;
        // If you need to store a string representation of stock status in 'stock_status' column
        // based on the numeric quantity, you would add logic here.
        // For example:
        // if ($actualStockQuantity !== null) {
        //     if ($actualStockQuantity === 0) {
        //         $stockStatusString = 'Out of Stock';
        //     } elseif ($actualStockQuantity <= 10) {
        //         $stockStatusString = 'Low Stock';
        //     } else {
        //         $stockStatusString = 'In Stock';
        //     }
        // } else {
        //     $stockStatusString = null; // Or some default
        // }


        // Find or create category
        $category = null;
        if ($categoryName) {
            $category = Category::firstOrCreate(['name' => $categoryName]);
        } elseif ($validated['category_id'] ?? null) {
            $category = Category::find($validated['category_id']);
        }

        // Find or create subcategory if provided
        $subCategory = null;
        if ($subcategoryName && $category) {
            $subCategory = SubCategory::firstOrCreate([
                'name' => $subcategoryName,
                'category_id' => $category->id
            ]);
        } elseif ($validated['sub_category_id'] ?? null) {
            $subCategory = SubCategory::find($validated['sub_category_id']);
        }

        Product::create([
            'name' => $validated['product_name'],
            'sku' => $validated['sku'],
            'description' => $validated['description'],
            'category_id' => $category ? $category->id : null,
            'sub_category_id' => $subCategory ? $subCategory->id : null,
            'price' => $validated['price'],
            //'stock_status' => $actualStockQuantity, // Store the numeric stock quantity here
            'quantity' => $validated['quantity'],
            'image_path' => $imagePath,
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'subCategory']);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $subCategories = SubCategory::all();
        return view('products.edit', compact('product', 'categories', 'subCategories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'category' => 'nullable|string',  // For create form
            'subcategory' => 'nullable|string',  // For create form
            'category_id' => 'nullable|exists:categories,id',  // For edit form
            'sub_category_id' => 'nullable|exists:sub_categories,id',  // For edit form
            'price' => 'required|numeric|min:0',
            // Changed validation for 'stock' to be numeric, assuming it's the actual quantity
            'quantity' => 'nullable|integer|min:0',
            //'stock_status' might not be directly from input if 'stock' is numeric
            //'stock_status' => 'nullable|string', // Keeping this if it's still used for string status
            'product_image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('product_image')) {
            // Delete old image if it exists
            if ($product->image_path && file_exists(storage_path('app/public/' . $product->image_path))) {
                unlink(storage_path('app/public/' . $product->image_path));
            }

            $imagePath = $request->file('product_image')->store('product_images', 'public');
            $validated['image_path'] = $imagePath;
        }

        // Handle different field names for create vs edit
        $categoryName = $validated['category'] ?? null;
        $subcategoryName = $validated['subcategory'] ?? null;
        $actualStockQuantity = $validated['stock'] ?? null; // Use actual numeric stock

        // Find or create category
        $category = null;
        if ($categoryName) {
            $category = Category::firstOrCreate(['name' => $categoryName]);
        } elseif ($validated['category_id'] ?? null) {
            $category = Category::find($validated['category_id']);
        }

        // Find or create subcategory if provided
        $subCategory = null;
        if ($subcategoryName && $category) {
            $subCategory = SubCategory::firstOrCreate([
                'name' => $subcategoryName,
                'category_id' => $category->id
            ]);
        } elseif ($validated['sub_category_id'] ?? null) {
            $subCategory = SubCategory::find($validated['sub_category_id']);
        }

        $product->update([
            'name' => $validated['product_name'],
            'sku' => $validated['sku'] ?? $product->sku,
            'description' => $validated['description'] ?? $product->description,
            'category_id' => $category ? $category->id : $product->category_id,
            'sub_category_id' => $subCategory ? $subCategory->id : $product->sub_category_id,
            'price' => $validated['price'],
            'quantity' => $validated['quantity'],
            //'stock_status' => $actualStockQuantity ?? $product->stock_status, // Update with numeric stock quantity
            'image_path' => $validated['image_path'] ?? $product->image_path,
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Delete associated image file if it exists
        if ($product->image_path && file_exists(storage_path('app/public/' . $product->image_path))) {
            unlink(storage_path('app/public/' . $product->image_path));
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
