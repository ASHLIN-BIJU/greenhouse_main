<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Events\ControlUpdated;
use Illuminate\Http\Request;

class DeviceControlController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'required|string',
            'pump_mode' => 'required|boolean',
            'exhaust_mode' => 'required|boolean',
        ]);

        ControlUpdated::dispatch(
            $validated['device_id'],
            (bool) $validated['pump_mode'],
            (bool) $validated['exhaust_mode']
        );

        return response()->json(['message' => 'Control command broadcasted successfully']);
    }
}
