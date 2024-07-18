<?php

namespace App\Http\Controllers\Api\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\CommonController;
use App\Http\Resources\Api\UserNotifcationResource;

class NotificationController extends CommonController
{
    public function customNotification(Request $request)
    {
        $vendor = auth()->guard('vendor-api')->user();

        if (!$vendor) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $list = $vendor->myNotifications()
            ->whereDate('created_at', '>=', date('Y-m-d H:i:s', strtotime(now() . ' -30 days')))
            ->latest()->paginate($this->paginationLimit);

        return UserNotifcationResource::collection($list)->additional(['status' => true, 'message' => '', 'statusCode' => 200], 200);
    }
}
