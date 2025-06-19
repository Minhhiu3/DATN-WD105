<?php

namespace Database\Factories;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BannerFactory extends Factory
{
    protected $model = Banner::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'image' => 'banners/' . Str::random(10) . '.jpg',
        ];
    }
}