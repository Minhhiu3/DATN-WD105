<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('product_reviews', 'created_at')) {
            DB::statement('ALTER TABLE product_reviews DROP COLUMN created_at');
        }
    }


    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable();
        });
    }
};
