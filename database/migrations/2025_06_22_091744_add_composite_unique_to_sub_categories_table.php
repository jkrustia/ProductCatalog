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
        Schema::table('sub_categories', function (Blueprint $table) {
            // First, check if the single 'name' unique index exists and drop it.
            // You might need to adjust the name 'sub_categories_name_unique'
            // based on what Laravel automatically generated or what you defined.
            // You can check your database schema or previous migrations for the exact name.
            // A common convention is 'table_column_unique'.
            if (Schema::hasColumn('sub_categories', 'name') && Schema::hasIndex('sub_categories', 'sub_categories_name_unique')) {
                $table->dropUnique('sub_categories_name_unique'); // Drop the old unique constraint on 'name'
            }

            // Add the new composite unique constraint on 'name' and 'category_id'
            $table->unique(['name', 'category_id'], 'sub_categories_name_category_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_categories', function (Blueprint $table) {
            // Drop the composite unique constraint
            $table->dropUnique('sub_categories_name_category_id_unique');

            // If you dropped a single 'name' unique constraint in 'up',
            // you might want to re-add it here if that was the desired
            // previous state. Otherwise, you can omit this.
            // $table->unique('name', 'sub_categories_name_unique');
        });
    }
};