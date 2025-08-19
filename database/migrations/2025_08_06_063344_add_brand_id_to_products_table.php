<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
{
    Schema::table('products', function (Blueprint $table) {
        // Thêm cột brand_id trước
        $table->unsignedBigInteger('brand_id')->nullable()->after('category_id');

        // Thêm foreign key
        $table->foreign('brand_id')
              ->references('id_brand') // cột khóa chính của bảng brands
              ->on('brands')
              ->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropForeign(['brand_id']);
        $table->dropColumn('brand_id');
    });
}

};
