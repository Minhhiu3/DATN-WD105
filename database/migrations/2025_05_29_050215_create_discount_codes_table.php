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
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->increments('discount_id');
            $table->string('code', 50)->unique();
            $table->string('type', 50);
            $table->decimal('value', 10, 2);
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->decimal('min_order_value', 10, 2)->nullable();
            $table->boolean('user_specific');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('discount_codes');
    }

};