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
    public function index()
    {
        $user = Auth::user();

        $greenhouse = Greenhouse::where('user_id', $user->id)->first();

        if (!$greenhouse) {
            return response()->json(['message' => 'Greenhouse not found for this user'], 404);
        }

        $alerts = Alert::where('greenhouse_id', $greenhouse->id)
            ->latest()
            ->paginate(20);

        return response()->json($alerts);
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
