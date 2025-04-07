<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $car_park_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ParkingSpace extends Model
{
    use HasFactory;

    protected $fillable = ['car_park_id'];

    public function carPark(): BelongsTo
    {
        return $this->belongsTo(CarPark::class);
    }
}
