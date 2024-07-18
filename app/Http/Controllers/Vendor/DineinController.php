<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Custom\PushNotification;
use App\Services\DineinFormService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\DineinResource;

class DineinController extends CommonController
{
    /**
     * @var DineinFormService
     */
    private $dineInService;

    public function __construct(DineinFormService $dineInService)
    {
        parent::__construct();
        $this->dineInService = $dineInService;
    }

    public function index()
    {
        return DineinResource::collection($this->dineInService->query()->latest()->where('vendor_id', auth()->id())->paginate($this->paginationLimit));
    }

    public function updateStatus(Request $request)
    {
        $form = $this->dineInService->query()
            ->where('id', $request->formId)
            ->Where('vendor_id', auth()->id())
            ->Where('status', '!=', 'confirmed')
            ->Where('status', '!=', 'cancelled')
            ->Where('status', '!=', 'completed')
            ->first();

        try {
            $form->update(['status' => $request->status]);

            try {
                $notification = new PushNotification(
                    $form->user->devices->pluck('device_token')->toArray(),
                    [
                        'title' => 'Dinein Form Status',
                        'message' => 'Your dinein form request is now ' . $request->status.'.',
                        'type' => 'dinein',
                    ]
                );
                $notification->send();
            } catch (\Throwable $th) {
                //throw $th;
            }

            $form->user->myNotifications()->create(['title' => "Dinein Form Status", 'message' => 'Your dinein form request is now ' . $request->status . '.', 'type' => 'dinein', 'task' => $form->id]);

            return successResponse("Operation success.", 200, 200);
        } catch (\Throwable $th) {
            return failureResponse("We're unable to process this operation.", 422, 422);
        }
    }
}
