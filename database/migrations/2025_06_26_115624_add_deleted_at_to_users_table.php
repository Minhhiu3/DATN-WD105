<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Chỉ thêm nếu chưa có
            if (! Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes(); // tạo cột deleted_at nullable
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Chỉ drop nếu có
            if (Schema::hasColumn('users', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};

