<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            //
            Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'total_amount']);
        });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            //
            Schema::table('order_items', function (Blueprint $table) {
            $table->string('payment_method', 50)->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
        });
        });
    }
};
