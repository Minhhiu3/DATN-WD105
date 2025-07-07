<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
           DB::table('category')->insert([
            ['name_category' => 'Giày Nam', 'created_at' => now(), 'updated_at' => now()],
            ['name_category' => 'Giày Nữ', 'created_at' => now(), 'updated_at' => now()],
            ['name_category' => 'Giày Trẻ Em', 'created_at' => now(), 'updated_at' => now()],
            ['name_category' => 'Giày Thể Thao', 'created_at' => now(), 'updated_at' => now()],
            ['name_category' => 'Sneaker', 'created_at' => now(), 'updated_at' => now()],
            ['name_category' => 'Sandal', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
