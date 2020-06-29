<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\NotificationResource;
class NotificationsController extends Controller
{
    //
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->paginate();//通知数据

        return NotificationResource::collection($notifications);
    }
    //未读通知
    public function stats(Request $request)
    {
        return response()->json([
            'unread_count' => $request->user()->notification_count,
        ]);
    }
    //消息已读
    public function read(Request $request)
    {
        $request->user()->markAsRead();

        return response(null, 204);
    }
}
