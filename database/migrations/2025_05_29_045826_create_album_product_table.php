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
        Schema::create('album_product', function (Blueprint $table) {
            $table->increments('id_album_product');
            $table->unsignedInteger('product_id');
            $table->string('image', 255);

            $table->foreign('product_id')->references('id_product')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('album_product');
    }

};