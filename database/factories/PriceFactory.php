<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CarPark;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Price>
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
            'car_park_id' => CarPark::inRandomOrder()->value('id'),
            'date'        => fake()->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
            'price'       => fake()->randomFloat(2, 11.00, 14.00),
        ];
    }
}
