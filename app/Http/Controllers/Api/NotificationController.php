<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\GlobalNotification;
use App\Services\GlobalNotificationService;
use App\Http\Resources\Api\UserNotifcationResource;
use App\Http\Resources\Api\GlobalNotifcationResource;
// use Google\Service\Docs\Request;
use Illuminate\Http\Request;

class NotificationController extends CommonController
{
    /**
     * @var GlobalNotificationService
     */
    private $globalNotificationService;

    public function __construct(GlobalNotificationService $globalNotificationService)
    {
        $this->globalNotificationService = $globalNotificationService;
    }
    public function globalList(Request $request)
    {
        if ($request->type == "driver") {
            $driver = auth()->guard('driver-api')->user();
            $driverType = ['rider', 'active-rider', 'inactive-rider', 'verified-rider', 'unverified-rider', 'male-rider', 'female-rider', 'blocked-rider', 'blacklisted-rider', 'associated-rider', 'incomplete-rider'];
            if ($driver) {
                $driverType = [
                    'rider', 'active-rider', 'inactive-rider',
                    $driver->verified == 1 ? 'verified-rider' : 'unverified-rider',
                    $driver->gender == 'male' ? 'male-rider' : 'female-rider',
                    $driver->is_blocked == 1 ? 'blocked-rider' :  '',
                    $driver->blacklisted > 0 ? 'blacklisted-rider' : '',
                    $driver->is_associated_rider ? 'associated-rider' : '',
                    $driver->documentState() == 1 ? 'incomplete-rider' : ''
                ];
            }
            $list = $this->globalNotificationService->query()
                ->whereIn('for', $driverType)
                ->where('sent', 1)
                ->whereDate('updated_at', '>=', date('Y-m-d H:i:s', strtotime(now() . ' -30 days')))
                ->orderBy('updated_at', 'DESC')->paginate($this->paginationLimit);
            // if ($driver->is_blocked == 1) {
            //     $list = $this->globalNotificationService->query()
            //         ->whereIn('for', ['rider', 'blocked-rider'])
            //         ->where('sent', 1)
            //         ->whereDate('updated_at', '>=', date('Y-m-d H:i:s', strtotime(now() . ' -30 days')))
            //         ->orderBy('updated_at', 'DESC')->paginate($this->paginationLimit);
            // } else if ($driver->blacklisted > 0) {
            //     $list = $this->globalNotificationService->query()
            //         ->whereIn('for', ['rider', 'blacklisted-rider'])
            //         ->where('sent', 1)
            //         ->whereDate('updated_at', '>=', date('Y-m-d H:i:s', strtotime(now() . ' -30 days')))
            //         ->orderBy('updated_at', 'DESC')->paginate($this->paginationLimit);
            // } else if ($driver->is_associated_rider) {
            //     $list = $this->globalNotificationService->query()
            //         ->whereIn('for', ['rider', 'associated-rider'])
            //         ->where('sent', 1)
            //         ->whereDate('updated_at', '>=', date('Y-m-d H:i:s', strtotime(now() . ' -30 days')))
            //         ->orderBy('updated_at', 'DESC')->paginate($this->paginationLimit);
            // } else if ($driver->verified == 0) {
            //     $list = $this->globalNotificationService->query()
            //         ->whereIn('for', ['rider', 'unverified-rider'])
            //         ->where('sent', 1)
            //         ->whereDate('updated_at', '>=', date('Y-m-d H:i:s', strtotime(now() . ' -30 days')))
            //         ->orderBy('updated_at', 'DESC')->paginate($this->paginationLimit);
            // } else if ($driver->verified == 1) {
            //     $list = $this->globalNotificationService->query()
            //         ->whereIn('for', ['rider', 'verified-rider'])
            //         ->where('sent', 1)
            //         ->whereDate('updated_at', '>=', date('Y-m-d H:i:s', strtotime(now() . ' -30 days')))
            //         ->orderBy('updated_at', 'DESC')->paginate($this->paginationLimit);
            // } else {
            //     $list = $this->globalNotificationService->query()
            //         ->where('for', 'rider')
            //         ->where('sent', 1)
            //         ->whereDate('updated_at', '>=', date('Y-m-d H:i:s', strtotime(now() . ' -30 days')))
            //         ->orderBy('updated_at', 'DESC')->paginate($this->paginationLimit);
            // }
        } else if ($request->type == "vendor") {
            $list = $this->globalNotificationService->query()
                ->where('for', 'vendor')
                ->where('sent', 1)
                ->whereDate('updated_at', '>=', date('Y-m-d H:i:s', strtotime(now() . ' -30 days')))
                ->orderBy('updated_at', 'DESC')->paginate($this->paginationLimit);
        } else {
            $user = auth()->guard('api')->user();
            $userType = ['end-user', 'active-user', 'elite-user', 'male-user', 'female-user'];
            if ($user) {
                $userType = [
                    'end-user', 'active-user',
                    $user->gender == 'male' ? 'male-user' : 'female-user',
                    $user->elite == 1 ? 'elite-user' : '',
                    $user->blocked == 1 ? 'blocked-user' :  ''
                ];
            }
            $list = $this->globalNotificationService->query()
                ->whereIn('for', $userType)
                ->where('sent', 1)
                ->whereDate('updated_at', '>=', date('Y-m-d H:i:s', strtotime(now() . ' -30 days')))
                ->orderBy('updated_at', 'DESC')->paginate($this->paginationLimit);
        }


        return GlobalNotifcationResource::collection($list)->additional(['status' => true, 'message' => '', 'statusCode' => 200], 200);
    }

    public function customNotification()
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $list = $user->myNotifications()
            ->whereDate('created_at', '>=', date('Y-m-d H:i:s', strtotime(now() . ' -30 days')))
            ->latest()->paginate($this->paginationLimit);

        return UserNotifcationResource::collection($list)->additional(['status' => true, 'message' => '', 'statusCode' => 200], 200);
    }
}
