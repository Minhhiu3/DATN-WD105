<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Chỉ xóa nếu tồn tại created_at
            if (Schema::hasColumn('orders', 'created_at')) {
                $table->dropColumn('created_at');
            }

            // Chỉ thêm nếu chưa có
            if (!Schema::hasColumn('orders', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (!Schema::hasColumn('orders', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }

            if (!Schema::hasColumn('orders', 'deleted_at')) {
                $table->softDeletes(); // thêm deleted_at
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'created_at')) {
                $table->dropColumn('created_at');
            }

            if (Schema::hasColumn('orders', 'updated_at')) {
                $table->dropColumn('updated_at');
            }

            if (Schema::hasColumn('orders', 'deleted_at')) {
                $table->dropColumn('deleted_at');
            }

            // Khôi phục lại created_at nếu rollback
            $table->dateTime('created_at')->nullable();
        });
    }
};
