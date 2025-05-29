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
        Schema::create('variant', function (Blueprint $table) {
            $table->increments('id_variant');
            $table->unsignedInteger('size_id');
            $table->unsignedInteger('product_id');
            $table->decimal('price', 10, 2);
            $table->integer('quantity');

            $table->foreign('size_id')->references('id_size')->on('size')->onDelete('cascade');
            $table->foreign('product_id')->references('id_product')->on('products')->onDelete('cascade');

            $table->unique(['product_id', 'size_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('variant');
    }

};