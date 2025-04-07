<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\CarPark;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\BookingResource;
use App\Http\Requests\CreateBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookingController extends Controller
{
    use AuthorizesRequests;

    private const NO_AVAILABLE_DATES_MESSAGE = 'No available spaces for the selected dates';

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBookingRequest $request): JsonResponse
    {
        $this->authorize('create', Booking::class);

        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;

        $carPark = CarPark::findOrFail($data['car_park_id']);

        $from = Carbon::parse($data['date_from']);
        $to   = Carbon::parse($data['date_to']);

        if ($carPark->checkAvailability($from, $to) <= 0) {
            return response()->json(['message' => self::NO_AVAILABLE_DATES_MESSAGE], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data['total_price'] = $carPark->calculatePrice($from, $to);

        $booking = Booking::create($data);

        return (new BookingResource($booking))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking): BookingResource
    {
        $this->authorize('view', $booking);

        return new BookingResource($booking);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, Booking $booking): BookingResource|JsonResponse
    {
        $this->authorize('update', $booking);

        $carPark = CarPark::findOrFail($booking->car_park_id);

        $availableSpaces = $carPark->checkAvailability(
            Carbon::parse($request->date_from ?? $booking->date_from),
            Carbon::parse($request->date_to ?? $booking->date_to),
            $booking->id
        );

        if ($availableSpaces <= 0) {
            return response()->json(['message' => self::NO_AVAILABLE_DATES_MESSAGE], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $booking->update($request->validated());

        return new BookingResource($booking);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking): Response
    {
        $this->authorize('delete', $booking);

        $booking->delete();

        return response()->noContent();
    }
}
