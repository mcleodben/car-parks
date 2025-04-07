<?php

declare(strict_types=1);

namespace tests\Integration;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Price;
use App\Models\Booking;
use App\Models\CarPark;
use App\Models\ParkingSpace;
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

    public function testItCalculatesAvailabilityCorrectly(): void
    {
        $user = User::factory()->create();
        $carPark = CarPark::factory()->create();
        ParkingSpace::factory()->count(5)->create(['car_park_id' => $carPark->id]);

        Booking::factory()->create([
            'date_from' => '2025-07-01',
            'date_to' => '2025-07-03',
        ]);

        Booking::factory()->create([
            'date_from' => '2025-07-02',
            'date_to' => '2025-07-04',
        ]);

        // Booking that wont overlap
        Booking::factory()->create([
            'date_from' => '2025-07-10',
            'date_to' => '2025-07-12',
        ]);

        $from = Carbon::create(2025, 7, 1);
        $to = Carbon::create(2025, 7, 3);

        $availableSpaces = $carPark->checkAvailability($from, $to);

        $this->assertEquals(3, $availableSpaces);
    }
}
