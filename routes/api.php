<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\Api\SensorDataController;
use App\Http\Controllers\Api\DeviceControlController;

// Override Fortify's default logout to use Sanctum for API
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:sanctum');

// Sensor Data and Device Control Routes
Route::post('/sensor-data', [SensorDataController::class, 'store']);
Route::post('/device-control', [DeviceControlController::class, 'update']);

Route::get('/test', function () {
    return 'api test';
});