<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'number_plate' => Str::upper($this->number_plate),
            'date_from'    => Carbon::parse($this->date_from)->format('l, jS F Y'),
            'date_to'      => Carbon::parse($this->date_to)->format('l, jS F Y'),
            'total_price'  => '£' . number_format($this->total_price, 2),
        ];
    }
}
