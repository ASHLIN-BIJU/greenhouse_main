<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Greenhouse;
use App\Traits\HandlesAutomation;
use Illuminate\Http\Request;

class AutomationController extends Controller
{
    use HandlesAutomation;

    /**
     * Synchronize and trigger automation based on latest stored values.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|string|exists:greenhouses,product_id',
            'mode' => 'nullable|in:auto,manual',
        ]);

        $greenhouse = Greenhouse::where('product_id', $validated['product_id'])->first();

        // Run automation with optional mode update
        $result = $this->runAutomation($greenhouse, $validated['mode'] ?? null);

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'success' => false
            ], 400);
        }

        return response()->json([
            'message' => 'Automation triggered successfully',
            'success' => true,
            'control_triggered' => $result['control_triggered'],
            'control_mode' => $result['control_mode'],
            'device_status' => $result['device_status'] ?? null,
            'area_temperature' => $result['area_temperature'],
            'latest_reading' => $result['latest_reading']
        ]);
    }
}
