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
        Schema::create('advice_product', function (Blueprint $table) {
            $table->increments('id_advice');
            $table->unsignedInteger('product_id');
            $table->decimal('value', 10, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('status');

            $table->foreign('product_id')->references('id_product')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('advice_product');
    }

};