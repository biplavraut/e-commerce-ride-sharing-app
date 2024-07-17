<?php

namespace App\Http\Controllers\Api;

use App\Device;
use App\Http\Requests\Api\NotificationRequest;
use Symfony\Component\HttpFoundation\Response;

class NotificationController extends CommonController
{
    public function saveDeviceToken(NotificationRequest $request)
    {
        // todo: make Device builder
        // save device token
        Device::firstOrCreate([
            'token'   => $request->input('token'),
            'type'    => $request->input('type'),
            'user_id' => auth()->guard('api')->id(),
        ]);

        return response()->json([
            'status'  => true,
            'code'    => Response::HTTP_OK,
            'message' => ['Device token saved'],
        ], Response::HTTP_OK);
    }
}
