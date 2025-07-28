<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('variant', function (Blueprint $table) {
            // Gỡ các FOREIGN KEY (tên này bạn nên xem kỹ từ DB)
            $table->dropForeign(['product_id']);
            $table->dropForeign(['size_id']);
        });

        // Gỡ UNIQUE INDEX bằng raw SQL vì Laravel bị lỗi nếu bị ràng buộc
        DB::statement('ALTER TABLE variant DROP INDEX variant_product_id_size_id_unique');

        Schema::table('variant', function (Blueprint $table) {
            // Thêm color_id
            $table->unsignedBigInteger('color_id')->after('product_id');
            $table->foreign('color_id')->references('id_color')->on('colors')->onDelete('cascade');


            // Thêm lại các FOREIGN KEY
            $table->foreign('product_id')->references('id_product')->on('products')->onDelete('cascade');
            $table->foreign('size_id')->references('id_size')->on('size')->onDelete('cascade');
            
            // Thêm unique mới
            $table->unique(['product_id', 'size_id', 'color_id']);
        });
    }

    public function down(): void
    {
        Schema::table('variant', function (Blueprint $table) {
            $table->dropForeign(['color_id']);
            $table->dropColumn('color_id');

            $table->dropUnique(['product_id', 'size_id', 'color_id']);

            // Có thể thêm lại unique cũ nếu cần
            $table->unique(['product_id', 'size_id']);
        });
    }
};
