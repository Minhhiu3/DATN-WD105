<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id('id_brand');
            $table->string('name', 255)->unique(); // Tên thương hiệu
            $table->string('slug', 255)->unique(); // Đường dẫn SEO
            $table->string('logo')->nullable(); // Logo thương hiệu
            $table->enum('status', ['visible', 'hidden'])->default('visible'); // Hiển thị / Ẩn
            $table->timestamps();
            $table->softDeletes(); // Xóa mềm
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};

