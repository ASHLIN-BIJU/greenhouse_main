<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\Greenhouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get the latest notifications/alerts for the authenticated user's greenhouse.
     */
    public function index(Request $request)
{
    $user = Auth::user();

    $greenhouse = Greenhouse::where('user_id', $user->id)->first();

    if (!$greenhouse) {
        return response()->json(['message' => 'Greenhouse not found'], 404);
    }

    $lastId = $request->query('last_id', 0);

    $newAlert = Alert::where('greenhouse_id', $greenhouse->id)
        ->where('id', '>', $lastId)
        ->latest()
        ->first();

    if ($newAlert) {
        return response()->json([
            'message' => $newAlert->message,
            'id' => $newAlert->id
        ]);
    }

    return response()->json([
        'message' => 'No new notification'
    ]);
}

    /**
     * Delete a notification.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $greenhouse = Greenhouse::where('user_id', $user->id)->first();

        if (!$greenhouse) {
            return response()->json(['message' => 'Greenhouse not found for this user'], 404);
        }

        $alert = Alert::where('id', $id)
            ->where('greenhouse_id', $greenhouse->id)
            ->first();

        if (!$alert) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $alert->delete();

        return response()->json(['message' => 'Notification deleted successfully']);
    }
}
