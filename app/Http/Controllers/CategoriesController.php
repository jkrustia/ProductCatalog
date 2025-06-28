<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Start with all categories and eager load subcategories and count products
        $query = Category::with('subCategories')->withCount('products');

        // Handle search functionality
        if ($request->has('query') && !empty($request->input('query'))) {
            $searchTerm = $request->input('query');
            $query->where('name', 'like', '%' . $searchTerm . '%') // Search by category name
                  ->orWhereHas('subCategories', function ($subQuery) use ($searchTerm) { // Search by sub-category name
                      $subQuery->where('name', 'like', '%' . $searchTerm . '%');
                  });
        }

        $categories = $query->get(); // Get the results

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource (SubCategory).
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all(); // Get all categories to populate the dropdown
        return view('categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage (SubCategory).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id', // Validate category_id
            'name' => 'required|string|max:255|unique:sub_categories,name,NULL,id,category_id,' . $request->category_id, // Validate sub-category name
        ]);

        SubCategory::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'Sub-category created successfully.');
    }

    /**
     * Display the specified resource (Category and its Products).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::with('products.subCategory')->findOrFail($id); // Find category with its products and their subcategories
        $products = $category->products; // Get products related to this category
        return view('categories.show', compact('category', 'products'));
    }

    /**
     * Show the form for editing the specified resource (SubCategory).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subCategory = SubCategory::with('category')->findOrFail($id); // Find the subcategory and eager load its parent category
        $categories = Category::all(); // Get all categories to populate the dropdown
        return view('categories.edit', compact('subCategory', 'categories'));
    }

    /**
     * Update the specified resource in storage (SubCategory).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $subCategory = SubCategory::findOrFail($id); // Find the subcategory to update

        $request->validate([
            'category_id' => 'required|exists:categories,id', // Validate category_id
            'name' => 'required|string|max:255|unique:sub_categories,name,' . $subCategory->id . ',id,category_id,' . $request->category_id, // Validate sub-category name
        ]);

        $subCategory->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'Sub-category updated successfully.');
    }

    /**
     * Remove the specified resource from storage (SubCategory).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subCategory = SubCategory::findOrFail($id); // Find the subcategory by ID
        $subCategory->delete(); // Delete the subcategory
        return redirect()->route('categories.index')->with('success', 'Sub-category deleted successfully.');
    }
}