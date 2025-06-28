<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Nếu chưa có cột total_amount
            if (! Schema::hasColumn('orders', 'total_amount')) {
                $table->decimal('total_amount', 12, 2)
                      ->default(0)
                      ->after('user_id');
            }
            // Nếu chưa có cột payment_method
            if (! Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method', 50)
                      ->nullable()
                      ->after('total_amount');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('orders', 'total_amount')) {
                $table->dropColumn('total_amount');
            }
        });
    }
};
