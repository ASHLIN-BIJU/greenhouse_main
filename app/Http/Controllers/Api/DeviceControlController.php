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
            'ac_mode' => 'required|boolean',
        ]);

        $greenhouse = \App\Models\Greenhouse::where('product_id', $validated['device_id'])->first();
        if ($greenhouse && $greenhouse->settings) {
            $greenhouse->settings->update(['control_mode' => 'manual']);
        }

        ControlUpdated::dispatch(
            $validated['device_id'],
            (bool) $validated['pump_mode'],
            (bool) $validated['exhaust_mode'],
            (bool) $validated['ac_mode']
        );

        return response()->json([
            'message' => 'Control command broadcasted and mode set to manual',
            'control_mode' => 'manual'
        ]);
    }
}
