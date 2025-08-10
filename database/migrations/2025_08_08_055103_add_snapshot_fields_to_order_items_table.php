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
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('product_name')->after('variant_id');
            $table->decimal('price', 10, 2)->after('product_name');
            $table->string('color_name')->nullable()->after('price');
            $table->string('size_name')->nullable()->after('color_name');
            $table->string('image')->nullable()->after('size_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['product_name', 'price', 'color_name', 'size_name', 'image']);
        });
    }
};
