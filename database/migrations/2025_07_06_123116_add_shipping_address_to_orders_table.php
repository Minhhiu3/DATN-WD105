<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('province')->after('status');
            $table->string('district')->after('province');
            $table->string('ward')->after('district');
            $table->string('address')->after('ward');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['province', 'district', 'ward', 'address']);
        });
    }
};

