<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\Api\SensorDataController;
use App\Http\Controllers\Api\DeviceControlController;
use App\Http\Controllers\Api\GreenhouseSettingController;
use App\Http\Controllers\Api\NotificationController;

// Override Fortify's default logout to use Sanctum for API
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:sanctum');

// Authenticated Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/greenhouse/settings', [GreenhouseSettingController::class, 'update']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
});

// Sensor Data and Device Control Routes
Route::post('/sensor-data', [SensorDataController::class, 'store']);
Route::post('/device-control', [DeviceControlController::class, 'update']);
Route::post('/greenhouse/sync', [\App\Http\Controllers\Api\AutomationController::class, 'sync']);

// Disease Detection Routes
Route::group(['prefix' => 'disease'], function () {
    Route::post('/detect', [\App\Http\Controllers\Api\DiseaseController::class, 'detect']);
    Route::get('/', [\App\Http\Controllers\Api\DiseaseController::class, 'index']);
    Route::get('/{id}', [\App\Http\Controllers\Api\DiseaseController::class, 'show']);
});

Route::get('/test', function () {
    return 'api test';
});