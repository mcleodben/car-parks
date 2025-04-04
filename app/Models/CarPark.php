<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarPark extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'total_spaces'];

    public function parkingSpaces(): HasMany
    {
        return $this->hasMany(ParkingSpace::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function totalSpaces(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->parkingSpaces()->count()
        );
    }

    public function checkAvailability(Carbon $from, Carbon $to, ?int $excludeId = null): int
    {
        $overlappingBookingsCount = $this->bookings()
            ->overlappingWithDates($from, $to)
            ->when($excludeId, function ($query) use ($excludeId) {
                $query->where('id', '!=', $excludeId);
            })
            ->count();

        $availableSpaces = $this->total_spaces - $overlappingBookingsCount;

        return max($availableSpaces, 0);
    }
}
