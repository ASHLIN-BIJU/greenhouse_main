<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

// Fortify handles Login and Registration routes automatically via the 'api' prefix in config/fortify.php.

// Override Fortify's default logout to use Sanctum for API
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:sanctum');