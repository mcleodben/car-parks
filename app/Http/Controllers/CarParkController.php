<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\CarPark;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CarParkResource;
use App\Http\Requests\CarParkAvailabilityRequest;

class CarParkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CarParkAvailabilityRequest $request)
    {
        $carParks = CarPark::all();
        $data = $request->validated();

        $dateFrom = isset($data['date_from']) ? Carbon::parse($data['date_from']) : now();
        $dateTo = isset($data['date_to']) ? Carbon::parse($data['date_to']) : now();

        foreach ($carParks as $carPark) {
            $carPark['available_spaces'] = $carPark->checkAvailability($dateFrom, $dateTo);
            $carPark['total_price'] = $carPark->calculatePrice($dateFrom, $dateTo);
        }

        return CarParkResource::collection($carParks);
    }

    /**
     * Check availabilty of a carpark between two given dates.
     * Specify no dates to show available spaces and price for today.
     */
    public function show(CarParkAvailabilityRequest $request, CarPark $carPark): JsonResponse
    {
        $data = $request->validated();

        $dateFrom = isset($data['date_from']) ? Carbon::parse($data['date_from']) : now();
        $dateTo = isset($data['date_to']) ? Carbon::parse($data['date_to']) : now();

        $carPark['available_spaces'] = $carPark->checkAvailability($dateFrom, $dateTo);
        $carPark['total_price'] = $carPark->calculatePrice($dateFrom, $dateTo);

        return response()->json(new CarParkResource($carPark));
    }
}
