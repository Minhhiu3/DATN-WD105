<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_product' => 'Giày thể thao ' . $this->faker->words(3, true),
            'price' => $this->faker->numberBetween(500000, 3000000),
            'description' => $this->faker->paragraph(5),

            'category_id' => Category::inRandomOrder()->first()->id_category,
            //
        ];
    }
}
