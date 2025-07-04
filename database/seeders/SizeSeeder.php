<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
             DB::table('size')->insert([
            ['name' => '36', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '37', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '38', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '39', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '40', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '41', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '42', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '43', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
