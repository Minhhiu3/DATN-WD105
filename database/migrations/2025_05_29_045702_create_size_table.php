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
        Schema::create('size', function (Blueprint $table) {
            $table->increments('id_size');
            $table->string('name', 50);
        });
    }

    public function down()
    {
        Schema::dropIfExists('size');
    }

};