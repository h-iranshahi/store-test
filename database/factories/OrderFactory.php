<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Automatically creates a User if none exists
            'total_amount' => $this->faker->randomFloat(2, 10, 1000), // Random total between 10.00 and 1000.00
            'status' => $this->faker->randomElement(['pending', 'completed', 'canceled']), // Random status
        ];
    }

}
