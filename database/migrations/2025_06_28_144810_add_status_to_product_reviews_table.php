<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('product_reviews', 'status')) {
                $table->enum('status', ['pending', 'visible', 'hidden'])->default('pending');
            }

            // Thêm timestamps nếu chưa có
            if (!Schema::hasColumn('product_reviews', 'created_at') && 
                !Schema::hasColumn('product_reviews', 'updated_at')) {
                $table->timestamps();
            }

            // Thêm soft delete nếu chưa có
            if (!Schema::hasColumn('product_reviews', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            if (Schema::hasColumn('product_reviews', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumns('product_reviews', ['created_at', 'updated_at'])) {
                $table->dropTimestamps();
            }

            if (Schema::hasColumn('product_reviews', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};

