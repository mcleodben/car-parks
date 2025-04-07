<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Price;
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

        $startDate = Carbon::today();
        $endDate   = Carbon::today()->addYears(3);

        while ($startDate <= $endDate) {
            Price::factory()->create([
                'car_park_id' => $carPark->id,
                'date'        => $startDate->copy()->format('Y-m-d'),
            ]);

            $startDate->addDay();
        }

        Booking::factory()->count(50)->create();
    }
}
