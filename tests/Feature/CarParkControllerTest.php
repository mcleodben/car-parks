<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
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

        $this->carPark = CarPark::factory()->create();
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
            ]);
    }
}
