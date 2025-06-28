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
        Schema::table('products', function (Blueprint $table) {
            // Add the 'stock_status' column: string, nullable, after 'price' (or suitable existing column)
            // You can adjust 'after' based on your preferred column order.
            $table->string('stock_status')->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop the 'stock_status' column if the migration is rolled back
            $table->dropColumn('stock_status');
        });
    }
};