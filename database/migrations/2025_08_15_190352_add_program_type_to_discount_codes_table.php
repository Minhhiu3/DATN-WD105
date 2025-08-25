<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('discount_codes', function (Blueprint $table) {
            $table->enum('program_type', ['input_code', 'choose_voucher'])
                  ->after('is_active')
                  ->nullable()
                  ->comment('input_code: Nhập mã giảm giá, choose_voucher: Chọn mã giảm giá');
        });
    }

    public function down()
    {
        Schema::table('discount_codes', function (Blueprint $table) {
            $table->dropColumn('program_type');
        });
    }
};
