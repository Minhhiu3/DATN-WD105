<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         $products = Product::all();
        $sizes = Size::all();

        foreach ($products as $product) {
            foreach ($sizes as $size) {
                DB::table('variant')->insert([
                    'product_id' => $product->id_product,
                    'size_id' => $size->id_size,
                    'price' => $product->price, // hoặc random giá nếu muốn
                    'quantity' => rand(5, 20),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
