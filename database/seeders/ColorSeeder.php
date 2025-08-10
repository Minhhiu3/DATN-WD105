<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('colors')->delete();

        DB::table('colors')->insert([
            [
                'name_color' => 'Red',
                'image' => 'colors/red.jpg',
            ],
            [
                'name_color' => 'Blue',
                'image' => 'colors/blue.jpg',
            ],
            [
                'name_color' => 'Green',
                'image' => 'colors/green.jpg',
            ],
            [
                'name_color' => 'Black',
                'image' => 'colors/black.jpg',
            ],
            [
                'name_color' => 'White',
                'image' => 'colors/white.jpg',
            ],
        ]);
    }
}
