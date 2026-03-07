<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('greenhouse.{deviceId}', function ($user, $deviceId) {
    return true; // Simplify for initial setup
});

Broadcast::channel('control.{deviceId}', function ($user, $deviceId) {
    return true; // Simplify for initial setup
});

