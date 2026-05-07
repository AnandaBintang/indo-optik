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
            $table->index(['status', 'category_id'], 'products_status_category_idx');
            $table->index('is_featured', 'products_is_featured_idx');
            $table->index('stock', 'products_stock_idx');
            $table->index('price', 'products_price_idx');
            $table->index('discount_price', 'products_discount_price_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_status_category_idx');
            $table->dropIndex('products_is_featured_idx');
            $table->dropIndex('products_stock_idx');
            $table->dropIndex('products_price_idx');
            $table->dropIndex('products_discount_price_idx');
        });
    }
};
