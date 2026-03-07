<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

// Laravel Fortify handles Login and Registration routes automatically.
// Since 'prefix' => 'api' is set in config/fortify.php, the following routes are available:
//
// [POST]  /api/login             -> Login
// [GET]   /api/login             -> Login View (if enabled)
// [POST]  /api/register          -> Register
// [GET]   /api/register          -> Register View (if enabled)
// [POST]  /api/logout            -> Logout (Overridden below)
// [GET]   /api/user/confirm-password
// [POST]  /api/user/confirm-password

// Override Fortify's default logout to use Sanctum for API
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:sanctum');