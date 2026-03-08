<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorReading;
use App\Events\SensorDataUpdated;
use Illuminate\Http\Request;

class SensorDataController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'required|string',
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
            'soil_moisture' => 'required|numeric',
        ]);

        // 1. Fetch the latest reading for this device
        $latest = SensorReading::where('device_id', $validated['device_id'])
            ->latest()
            ->first();

        // 2. Check if data has changed
        $hasChanged = !$latest ||
            round((float) $latest->temperature, 2) !== round((float) $validated['temperature'], 2) ||
            round((float) $latest->humidity, 2) !== round((float) $validated['humidity'], 2) ||
            round((float) $latest->soil_moisture, 2) !== round((float) $validated['soil_moisture'], 2);

        $stored = false;
        if ($hasChanged) {
            // Store data if there's a change or it's the first reading
            SensorReading::create($validated);
            $stored = true;
        }

        // 3. Always dispatch the broadcast event for real-time dashboard updates
        SensorDataUpdated::dispatch(
            $validated['device_id'],
            (float) $validated['temperature'],
            (float) $validated['humidity'],
            (float) $validated['soil_moisture']
        );

        return response()->json([
            'message' => $stored ? 'Sensor data stored and broadcasted' : 'Data unchanged; broadcasted only',
            'stored' => $stored
        ]);
    }
}
