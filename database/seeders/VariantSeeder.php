<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VariantSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $sizes = Size::all();
        $colors = Color::all();

        if ($products->isEmpty() || $sizes->isEmpty() || $colors->isEmpty()) {
            $this->command->warn(" Không thể seed variants vì thiếu dữ liệu (product, size hoặc color).");
            return;
        }

        foreach ($products as $product) {
            foreach ($sizes as $size) {
                $randomColor = $colors->random(); // sẽ an toàn vì đã kiểm tra ở trên

                DB::table('variant')->insert([
                    'product_id' => $product->id_product,
                    'size_id' => $size->id_size,
                    'color_id' => $randomColor->id_color,
                    'price' => $product->price,
                    'quantity' => rand(5, 20),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
