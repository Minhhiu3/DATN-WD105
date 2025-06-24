<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('variant', function (Blueprint $table) {
            $table->softDeletes();    // Thêm cột deleted_at
            $table->timestamps();     // Thêm created_at và updated_at (nếu chưa có)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('variant', function (Blueprint $table) {
            $table->dropColumn(['deleted_at', 'created_at', 'updated_at']);
        });
    }
};
