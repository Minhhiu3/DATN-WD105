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
        Schema::create('shipping_addresses', function (Blueprint $table) {
    $table->id();
    $table->unsignedInteger('order_id'); // đúng kiểu so với orders
    $table->string('province');
    $table->string('ward');
    $table->string('address');
    $table->timestamps();

    $table->foreign('order_id')->references('id_order')->on('orders')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_addresses');
    }
};
