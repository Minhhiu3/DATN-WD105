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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id_user');
            $table->string('name', 100);
            $table->string('account_name', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('phone_number', 20)->nullable();
            $table->string('password', 255);
            $table->unsignedInteger('role_id');

            $table->foreign('role_id')->references('id_role')->on('roles')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }

};