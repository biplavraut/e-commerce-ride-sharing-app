<?php

namespace App\Http\Controllers\Api\Driver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\CommonController;
use App\Http\Resources\Api\UserNotifcationResource;

class NotificationController extends CommonController
{
    public function customNotification(Request $request)
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $list = $driver->myNotifications()
            ->whereDate('created_at', '>=', date('Y-m-d H:i:s', strtotime(now() . ' -30 days')))
            ->latest()->paginate($this->paginationLimit);

        return UserNotifcationResource::collection($list)->additional(['status' => true, 'message' => '', 'statusCode' => 200], 200);
    }
}
