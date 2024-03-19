<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function store($user_id, $title, $body)
    {
        try {
            Notification::create([
                'user_id' => $user_id,
                'title' => $title,
                'body' => $body
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function read($notification_id)
    {
        try {
            Notification::where('id', $notification_id)->update([
                'is_read' => true
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
