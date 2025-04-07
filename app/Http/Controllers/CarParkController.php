<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\CarPark;
use App\Http\Resources\CarParkResource;
use App\Http\Requests\CarParkAvailabilityRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CarParkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CarParkAvailabilityRequest $request): AnonymousResourceCollection
    {
        $carParks = CarPark::all();

        $dateFrom = Carbon::parse($request->date_from ?? now());
        $dateTo   = Carbon::parse($request->date_to ?? now());

        foreach ($carParks as $carPark) {
            $carPark['available_spaces'] = $carPark->checkAvailability($dateFrom, $dateTo);
            $carPark['total_price']      = $carPark->calculatePrice($dateFrom, $dateTo);
        }

        return CarParkResource::collection($carParks);
    }

    /**
     * Check availabilty of a carpark between two given dates.
     * Specify no dates to show available spaces and price for today.
     */
    public function show(CarParkAvailabilityRequest $request, CarPark $carPark): CarParkResource
    {
        $dateFrom = Carbon::parse($request->date_from ?? now());
        $dateTo   = Carbon::parse($request->date_to ?? now());

        $carPark['available_spaces'] = $carPark->checkAvailability($dateFrom, $dateTo);
        $carPark['total_price']      = $carPark->calculatePrice($dateFrom, $dateTo);

        return new CarParkResource($carPark);
    }
}
