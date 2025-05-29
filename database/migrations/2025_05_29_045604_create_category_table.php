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
        Schema::create('category', function (Blueprint $table) {
            $table->increments('id_category');
            $table->string('name_category', 100);
        });
    }

    public function down()
    {
        Schema::dropIfExists('category');
    }

};