<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $user_id
 * @property int $car_park_id
 * @property string $number_plate
 * @property Carbon $date_from
 * @property Carbon $date_to
 * @property float $total_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'car_park_id',
        'number_plate',
        'date_from',
        'date_to',
        'total_price',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function carPark(): BelongsTo
    {
        return $this->belongsTo(CarPark::class);
    }

    public function scopeOverlappingWithDates(Builder $query, Carbon $from, Carbon $to): Builder
    {
        return $query->where(function (Builder $query) use ($from, $to) {
            $query->where('date_from', '<', $to)
                ->where('date_to', '>', $from);
        });
    }
}
