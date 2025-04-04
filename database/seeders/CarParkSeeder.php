<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\CarPark;
use App\Models\ParkingSpace;
use Illuminate\Database\Seeder;

class CarParkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carPark = CarPark::factory()->create([
            'name' => 'Manchester Airport Carpark',
        ]);

        ParkingSpace::factory()->count(10)->create([
            'car_park_id' => $carPark->id,
        ]);

        Booking::factory()->count(50)->create();
    }
}
