<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->increments('id_cart_item');
            $table->unsignedInteger('cart_id');
            $table->unsignedInteger('variant_id');\
            $table->integer('quantity');

            $table->foreign('cart_id')->references('id_cart')->on('carts')->onDelete('cascade');
            $table->foreign('variant_id')->references('id_variant')->on('variant')->onDelete('cascade');

            $table->unique(['cart_id', 'variant_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cart_items');
    }

};