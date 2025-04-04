<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\CarPark;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $carPark = CarPark::inRandomOrder()->first();

        $startDate = now()->addDays(fake()->numberBetween(0, 180));
        $endDate = (clone $startDate)->addDays(fake()->numberBetween(3, 20));

        return [
            'number_plate' => fake()->bothify('??##???'),
            'user_id'      => User::inRandomOrder()->value('id'),
            'car_park_id'  => $carPark->id,
            'date_from'    => $startDate->format('Y-m-d'),
            'date_to'      => $endDate->format('Y-m-d'),
            'total_price'  => 14.98,
        ];
    }
}
