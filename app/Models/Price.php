<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $car_park_id
 * @property float $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Price extends Model
{
    /** @use HasFactory<\Database\Factories\PriceFactory> */
    use HasFactory;

    protected $fillable = ['car_park_id', 'price'];

    public function carPark(): BelongsTo
    {
        return $this->belongsTo(CarPark::class);
    }
}
