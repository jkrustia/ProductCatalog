<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable(); // Description can be optional
            $table->decimal('price', 8, 2); // Price format (e.g., 999999.99)

            // Foreign key to categories table
            $table->foreignId('category_id')
                  ->constrained('categories') // Constrain to the 'categories' table
                  ->onDelete('cascade');     // If parent category is deleted, product is too

            // Foreign key to sub_categories table
            $table->foreignId('sub_category_id')
                  ->constrained('sub_categories') // Constrain to the 'sub_categories' table
                  ->onDelete('cascade');         // If parent subcategory is deleted, product is too

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};