<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarParkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'total_spaces'     => $this->total_spaces,
            'available_spaces' => $this->available_spaces,
            'date_from'        => Carbon::parse($request->date_from)->format('l, jS F Y'),
            'date_to'          => Carbon::parse($request->date_to)->format('l, jS F Y'),
            'total_price'      => '£' . number_format($this->total_price, 2),
        ];
    }
}
