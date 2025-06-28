<?php

namespace App\Http\Controllers;

use App\Models\Product; // Import the Product model
use Illuminate\Http\Request;

class PriceController extends Controller
{
    /**
     * Display a listing of the prices (products).
     */
    public function index(Request $request)
    {
        $query = $request->input('query');

        if ($query) {
            // Search by product name or price
            $products = Product::where('name', 'like', '%' . $query . '%')
                           ->orWhere('price', 'like', '%' . $query . '%')
                           ->get();
        } else {
            $products = Product::all(); // Get all products
        }

        // Pass 'products' to the view, not 'prices'
        return view('prices.index', ['prices' => $products]);
    }

    /**
     * Show the form for editing the specified product's price.
     */
    public function edit(Product $price) // Use route model binding for Product
    {
        // The $price variable is automatically resolved by Laravel based on the route parameter
        return view('prices.edit', ['price' => $price]);
    }

    /**
     * Update the specified product's price in storage.
     */
    public function update(Request $request, Product $price)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        $price->price = $request->input('price');
        $price->save();

        return redirect()->route('prices.index')->with('success', 'Price updated successfully!');
    }
}