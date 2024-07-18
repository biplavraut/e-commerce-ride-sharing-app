<?php

namespace App\Http\Controllers\Admin;

use App\GlobalNotification;
use Illuminate\Http\Request;
use App\Services\GlobalNotificationService;
use App\Http\Requests\Admin\GlobalNotificationRequest;
use App\Http\Resources\Admin\GlobalNotificationResource;

class GlobalNotificationController extends CommonController
{
    /** @var GlobalNotificationService */
    private $globalNotificationService;

    public function __construct(GlobalNotificationService $globalNotificationService)
    {
        parent::__construct();
        $this->globalNotificationService = $globalNotificationService;
    }

    public function index()
    {
        $globalNotifications = $this->globalNotificationService->getForIndex(
            $this->paginationLimit
        );

        return GlobalNotificationResource::collection($globalNotifications);
    }

    public function store(GlobalNotificationRequest $request)
    {
        $globalNotification = $this->globalNotificationService->store($request->validated());
        // $this->globalNotificationService->send($globalNotification);

        return new GlobalNotificationResource($globalNotification);
    }

    public function show($globalNotificationId)
    {
        $globalNotification = $this->globalNotificationService->findOrFail($globalNotificationId);

        return new GlobalNotificationResource($globalNotification);
    }

    public function update(GlobalNotificationRequest $request, $globalNotificationId)
    {
        $globalNotification = $this->globalNotificationService->update($globalNotificationId, $request->validated());
        // $this->globalNotificationService->send($globalNotification);

        return new GlobalNotificationResource($globalNotification);
    }

    public function destroy($globalNotificationId)
    {
        $globalNotification = $this->globalNotificationService->delete($globalNotificationId);

        return response('success');
    }

    public function sendNow($id)
    {
        $notification = $this->globalNotificationService->findOrFail($id);
        $notification->update(['updated_at' => now()]);
        if ($this->globalNotificationService->send($notification)) {
            $notification->update(['sent' => 1]);
            return response('success');
        }
        return response('error');
    }

    public function search(Request $request)
    {
        $notifications = $this->globalNotificationService->query()->where('title', 'LIKE', $request->name . '%')->paginate($this->paginationLimit);
        return GlobalNotificationResource::collection($notifications);
    }
}
