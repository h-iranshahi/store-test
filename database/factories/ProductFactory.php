<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
 use Illuminate\Support\Str;

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
            'name' => fake()->word(),
            'description' => fake()->paragraph(2),
            'price' => round(rand(200000,1000000), -4),
            'quantity' => rand(0,50),
        ];
    }

}
