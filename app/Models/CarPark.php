<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
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

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function totalSpaces(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->parkingSpaces()->count()
        );
    }

    public function calculatePrice(Carbon $from, Carbon $to): float
    {
        return $this->prices()
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->sum('price');
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
