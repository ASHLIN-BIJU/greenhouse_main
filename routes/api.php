<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\Api\SensorDataController;
use App\Http\Controllers\Api\DeviceControlController;
use App\Http\Controllers\Api\GreenhouseSettingController;

// Override Fortify's default logout to use Sanctum for API
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:sanctum');

// Authenticated Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/greenhouse/settings', [GreenhouseSettingController::class, 'update']);
});

// Sensor Data and Device Control Routes
Route::post('/sensor-data', [SensorDataController::class, 'store']);
Route::post('/device-control', [DeviceControlController::class, 'update']);

Route::get('/test', function () {
    return 'api test';
});