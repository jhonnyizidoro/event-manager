<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Auth;

class NotificationController extends Controller
{
    public function readAll()
    {
        $notifications = Auth::user()->notifications;

        foreach ($notifications as $notification) {
            $notification->pivot->update([ 'is_read' => true ]);
        }

        return response()->json($notifications, 200);
    }
}
