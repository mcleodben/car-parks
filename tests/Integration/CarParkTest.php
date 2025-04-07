<?php

declare(strict_types=1);

namespace tests\Integration;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Price;
use App\Models\CarPark;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CarParkTest extends TestCase
{
    use RefreshDatabase;

    public function testCalculatesPriceCorrectlyBetweenDates(): void
    {
        $carPark = CarPark::factory()->create();

        $startDate = Carbon::create(2025, 4, 1);
        $endDate   = Carbon::create(2025, 4, 3);

        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            Price::factory()->create([
                'car_park_id' => $carPark->id,
                'date'        => $currentDate->format('Y-m-d'),
                'price'       => 10.00,
            ]);

            $currentDate->addDay();
        }

        $total = $carPark->calculatePrice($startDate, $endDate);

        $this->assertEquals(30.00, $total);
    }
}
