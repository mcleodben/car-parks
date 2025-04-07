<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Price;
use App\Models\Booking;
use App\Models\CarPark;
use App\Models\ParkingSpace;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CarParkControllerTest extends TestCase
{
    use RefreshDatabase;

    private CarPark $carPark;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpCarPark();
    }

    public function testShowCarPark(): void
    {
        ParkingSpace::factory()->count(10)->create(['car_park_id' => $this->carPark->id]);

        $this->getJson('/api/carpark/' . $this->carPark->id)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'id'               => $this->carPark->id,
                'total_spaces'     => 10,
                'available_spaces' => 10,
                'total_price'      => '£10.00',
            ]);
    }

    public function testShowCarParkAvailabilityWithDates(): void
    {
        ParkingSpace::factory()->count(3)->create(['car_park_id' => $this->carPark->id]);
        User::factory()->create();

        $dateFrom = now();
        $dateTo   = now()->addDays(7);

        Booking::factory()->count(3)->create([
            'date_from' => $dateFrom,
            'date_to'   => $dateTo,
        ]);

        $params = [
            'date_from' => $dateFrom->toDateString(),
            'date_to'   => $dateTo->toDateString(),
        ];

        $this->getJson('/api/carpark/' . $this->carPark->id . '?' . http_build_query($params))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'id'               => $this->carPark->id,
                'total_spaces'     => 3,
                'available_spaces' => 0,
                'total_price'      => '£80.00',
            ]);
    }

    private function setUpCarPark(): void
    {
        $this->carPark = CarPark::factory()->create();

        $dateFrom = now();
        $dateTo   = now()->addDays(10);

        $currentDate = $dateFrom->copy();
        while ($currentDate <= $dateTo) {
            Price::factory()->create([
                'car_park_id' => $this->carPark->id,
                'date'        => $currentDate->format('Y-m-d'),
                'price'       => 10.00,
            ]);

            $currentDate->addDay();
        }
    }
}
