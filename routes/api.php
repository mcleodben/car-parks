<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CarParkController;

Route::controller(AuthController::class)->group(function() {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::controller(CarParkController::class)->group(function() {
    Route::get('/carpark/{carPark}', 'show');
    Route::get('/carpark', 'index');
});

Route::middleware('auth:sanctum')->group(function() {
    Route::resource('bookings', BookingController::class);
    Route::post('logout', [AuthController::class, 'logout']);
});
