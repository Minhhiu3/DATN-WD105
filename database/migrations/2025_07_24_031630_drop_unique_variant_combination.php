<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Gỡ các foreign key hiện có (phải chính xác theo tên cột)
        Schema::table('variant', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['size_id']);
            $table->dropForeign(['color_id']);
        });

        // Gỡ UNIQUE INDEX nếu tồn tại (tên kiểm tra chính xác từ phpMyAdmin)
        DB::statement('ALTER TABLE variant DROP INDEX variant_product_id_size_id_color_id_unique');

        // Thêm lại foreign keys và unique nếu muốn
        Schema::table('variant', function (Blueprint $table) {
            $table->foreign('product_id')->references('id_product')->on('products')->onDelete('cascade');
            $table->foreign('size_id')->references('id_size')->on('size')->onDelete('cascade');
            $table->foreign('color_id')->references('id_color')->on('colors')->onDelete('cascade');

            // Nếu cần unique trở lại (nếu không, bỏ dòng dưới)
            // $table->unique(['product_id', 'size_id', 'color_id'], 'variant_product_id_size_id_color_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('variant', function (Blueprint $table) {
            // Gỡ foreign key
            $table->dropForeign(['color_id']);

            // Gỡ unique nếu đã thêm
            // DB::statement('ALTER TABLE variant DROP INDEX variant_product_id_size_id_color_id_unique');

            // Có thể thêm lại unique cũ nếu cần
            // $table->unique(['product_id', 'size_id'], 'variant_product_id_size_id_unique');
        });
    }
};




