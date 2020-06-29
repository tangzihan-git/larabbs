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
}
