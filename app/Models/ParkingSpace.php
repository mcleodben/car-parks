<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParkingSpace extends Model
{
    use HasFactory;

    protected $fillable = ['car_park_id'];

    public function carPark(): BelongsTo
    {
        return $this->belongsTo(CarPark::class);
    }
}
