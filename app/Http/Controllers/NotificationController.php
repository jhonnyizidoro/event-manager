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

    public function read($id)
    {
        $notification = Auth::user()->notifications()->where('notification_id', $id)->first();
        $notification->pivot->update(['is_read' => true]);
        return response()->json('Notificação marcada como lida.', 200);
    }
}
