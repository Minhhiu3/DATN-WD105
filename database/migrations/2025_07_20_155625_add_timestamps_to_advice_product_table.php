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
    Schema::table('advice_product', function (Blueprint $table) {
        $table->timestamps();        // ✅ thêm created_at và updated_at
        $table->softDeletes();       // ✅ thêm deleted_at
    });
}

public function down()
{
    Schema::table('advice_product', function (Blueprint $table) {
        $table->dropTimestamps();    // ✅ xóa created_at và updated_at
        $table->dropSoftDeletes();   // ✅ xóa deleted_at
    });
}

};
