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
        Schema::create('banner', function (Blueprint $table) {
            $table->increments('id_banner');
            $table->string('name', 100);
            $table->string('image', 255);
        });
    }

    public function down()
    {
        Schema::dropIfExists('banner');
    }

};