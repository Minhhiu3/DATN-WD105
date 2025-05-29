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
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id_order_item');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('variant_id');
            $table->integer('quantity');
            $table->string('payment_method', 50);
            $table->dateTime('created_at');

            $table->foreign('order_id')->references('id_order')->on('orders')->onDelete('cascade');
            $table->foreign('variant_id')->references('id_variant')->on('variant')->onDelete('cascade');

            $table->unique(['order_id', 'variant_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }

};