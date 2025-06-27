<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Nếu bạn cần dùng change(), đảm bảo đã cài doctrine/dbal
        // composer require doctrine/dbal

        Schema::table('orders', function (Blueprint $table) {
            // Ví dụ: đổi sang enum gồm 5 trạng thái
            $table->enum('status', [
                'pending',
                'processing',
                'shipping',
                'completed',
                'canceled'
            ])
            ->default('pending')
            ->change();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Quay lại string(50) nếu trước đây là string
            $table->string('status', 50)->default('pending')->change();
        });
    }
};
