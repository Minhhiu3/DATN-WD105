<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('advice_product', function (Blueprint $table) {
            // Sửa cột `value` thành kiểu INT
            $table->integer('value')->change();

            // Sửa cột `status` thành ENUM với các giá trị 'on', 'off'
            $table->enum('status', ['on', 'off'])->default('off')->change();
        });
    }

    public function down(): void
    {
        Schema::table('advice_product', function (Blueprint $table) {
            // Quay lại kiểu cũ
            $table->decimal('value', 10, 2)->change();
            $table->tinyInteger('status')->default(0)->change();
        });
    }
};

