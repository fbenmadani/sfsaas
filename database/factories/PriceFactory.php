<?php

namespace Database\Factories;

use App\Models\Plan;
use App\Models\Price;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Price>
 */
class PriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'plan_id' => Plan::factory(),
            'amount' => $this->faker->numberBetween(1000, 10000),
            'currency' => 'USD',
            'billing_interval' => $this->faker->randomElement(['month', 'year', 'once']),
        ];
    }
}
