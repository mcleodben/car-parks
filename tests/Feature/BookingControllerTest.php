<?php

declare(strict_types=1);

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Booking;
use App\Models\CarPark;
use Illuminate\Support\Str;
use App\Models\ParkingSpace;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    private CarPark $carPark;
    private User $user;

    private const CAR_PARK_SPACES = 10;

    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse('2025-01-01'));

        $this->carPark = CarPark::factory()->create();
        $this->user = User::factory()->create();

        ParkingSpace::factory()->count(self::CAR_PARK_SPACES)->create([
            'car_park_id' => $this->carPark->id,
        ]);
    }

    public function testShowBooking(): void
    {
        $booking = Booking::factory()->create();

        $this->actingAs($this->user)->getJson('/api/bookings/' . $booking->id)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'id'           => $booking->id,
                'number_plate' => Str::upper($booking->number_plate),
                'total_price'  => '£' . number_format($booking->total_price, 2),
            ]);
    }

    public function testCreateBooking(): void
    {
        $payload = [
            'number_plate' => 'YR10BVD',
            'date_from'    => '2025-06-01',
            'date_to'      => '2025-06-10',
            'car_park_id'  => $this->carPark->id,
        ];

        $this->actingAs($this->user)->postJson('/api/bookings', $payload)
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas(
            'bookings',
            [
                'number_plate' => $payload['number_plate'],
                'date_from'    => $payload['date_from'],
                'date_to'      => $payload['date_to'],
                'user_id'      => $this->user->id,
                'car_park_id'  => $this->carPark->id,
            ]
        );
    }

    public function testCreateBookingFailsBecauseNoAvailability(): void
    {
        $dateFrom = now();
        $dateTo   = now()->addDays(7);

        Booking::factory()->count(self::CAR_PARK_SPACES)->create([
            'date_from' => $dateFrom->format('Y-m-d'),
            'date_to'   => $dateTo->format('Y-m-d'),
        ]);

        $payload = [
            'number_plate' => 'YR10BVD',
            'date_from'    => $dateFrom->format('Y-m-d'),
            'date_to'      => $dateTo->format('Y-m-d'),
            'car_park_id'  => $this->carPark->id,
        ];

        $this->actingAs($this->user)->postJson('/api/bookings', $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertDatabaseMissing(
            'bookings',
            [
                'number_plate' => $payload['number_plate'],
                'date_from'    => $payload['date_from'],
                'date_to'      => $payload['date_to'],
            ]
        );
    }

    public function testUpdateBooking(): void
    {
        $booking = Booking::factory()->create();

        $payload = [
            'date_from' => $booking->date_from,
            'date_to'   => Carbon::parse($booking->date_to)->addDays(2)->format('Y-m-d'),
        ];

        $this->actingAs($this->user)->patchJson('/api/bookings/' . $booking->id, $payload)
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas(
            'bookings',
            [
                'number_plate' => $booking->number_plate,
                'date_from'    => $payload['date_from'],
                'date_to'      => $payload['date_to'],
                'user_id'      => $this->user->id,
                'car_park_id'  => $this->carPark->id,
            ]
        );
    }

    public function testCantUpdateSomeoneElsesBooking(): void
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create(['user_id' => $user->id]);

        $payload = [
            'date_from' => $booking->date_from,
            'date_to'   => Carbon::parse($booking->date_to)->addDays(2)->format('Y-m-d'),
        ];

        $this->actingAs($this->user)->patchJson('/api/bookings/' . $booking->id, $payload)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testUpdateBookingFailsWhenCarParkIsFull(): void
    {
        $dateFrom = now();
        $dateTo   = now()->addDays(7);

        $booking = Booking::factory()->create([
            'date_from' => $dateFrom->format('Y-m-d'),
            'date_to'   => $dateTo->format('Y-m-d'),
        ]);
        Booking::factory()->count(self::CAR_PARK_SPACES)->create([
            'date_from' => (clone $dateTo)->addDays(1)->format('Y-m-d'),
            'date_to'   => (clone $dateTo)->addDays(5)->format('Y-m-d'),
        ]);

        $payload = [
            'date_from' => $booking->date_from,
            'date_to'   => Carbon::parse($booking->date_to)->addDays(2)->format('Y-m-d'),
        ];

        $this->actingAs($this->user)->putJson('/api/bookings/' . $booking->id, $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testDeleteBooking(): void
    {
        $booking = Booking::factory()->create();

        $this->actingAs($this->user)->delete('/api/bookings/' . $booking->id)
            ->assertStatus(Response::HTTP_NO_CONTENT);
        
        $this->assertDatabaseMissing(
            'bookings',
            [
                'id' => $booking->id,
            ]
        );
    }

    public function testCantDeleteSomeoneElsesBooking(): void
    {
        $otherUser = User::factory()->create();
        $booking = Booking::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($this->user)->delete('/api/bookings/' . $booking->id)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
