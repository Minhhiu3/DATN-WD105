<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
Schema::create('user_vouchers', function (Blueprint $table) {
    $table->id('id_user_voucher');
$table->unsignedInteger('user_id');
$table->unsignedInteger('discount_id');
    $table->boolean('used')->default(false);
    $table->timestamp('used_at')->nullable();
    $table->timestamps();


$table->foreign('user_id')->references('id_user')->on('users')->onDelete('cascade');

    $table->foreign('discount_id')->references('discount_id')->on('discount_codes')->onDelete('cascade');
});

    }

    public function down()
    {
        Schema::dropIfExists('user_vouchers');
    }
};
