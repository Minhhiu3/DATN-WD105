<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            // Nếu chưa có soft delete
            if (! Schema::hasColumn('roles', 'deleted_at')) {
                $table->softDeletes();    // Thêm cột deleted_at (nullable)
            }

            // Nếu bạn cũng muốn thêm created_at + updated_at (nếu chưa có)
            if (! Schema::hasColumn('roles', 'created_at') && ! Schema::hasColumn('roles', 'updated_at')) {
                $table->timestamps();
            }
        });
    }

    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            if (Schema::hasColumn('roles', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
            if (Schema::hasColumn('roles', 'created_at') && Schema::hasColumn('roles', 'updated_at')) {
                $table->dropTimestamps();
            }
        });
    }
};


