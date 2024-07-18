<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Custom\PushNotification;
use App\DefaultConf;
use App\VendorAdvanceSettlement;
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
        return DineinResource::collection($this->dineInService->query()->latest()->where('status', '!=', 'completed')->paginate($this->paginationLimit));
    }

    public function updateStatus(Request $request)
    {
        $form = $this->dineInService->query()
            ->where('id', $request->formId)
            ->Where('status', '!=', 'completed')
            ->Where('status', '!=', 'cancelled')
            ->first();

        try {
            $form->update(['status' => $request->status]);

            try {
                $notification = new PushNotification(
                    $form->user->devices->pluck('device_token')->toArray(),
                    [
                        'title' => 'Dinein Form Status',
                        'message' => 'Your dinein form request is now ' . $request->status . '.',
                        'type' => 'dinein',
                    ]
                );
                $notification->send();
            } catch (\Throwable $th) {
                //throw $th;
            }

            if ($request->status == "completed") {

                $conf = DefaultConf::first();

                if ($conf) {
                    if ($conf->dinein_cashback > 0) {
                        $reward = (($form->total_price * $conf->dinein_cashback) / 100);
                        $form->user->update(['reward_point' => ($form->user->reward_point + $conf->dinein_cashback)]);
                        $form->user->transactionHistories()->create(['payment_mode' => 'gogo20', 'point' => $reward, 'from' => 'Dine-In']);
                    }
                }

                try {
                    $commission = (($form->vendor->products()->sum('price') - $form->vendor->products()->sum('price_1')) / $form->vendor->products()->sum('price')) * 100;
                } catch (\Throwable $th) {
                    $commission = 1;
                }


                $amt = (($form->total_price * $commission) / 100);

                $payment = VendorAdvanceSettlement::create(['vendor_id' => $form->vendor_id, 'amount' => $amt, 'remarks' => 'Advanced added from dinein.']);
            }


            $form->user->myNotifications()->create(['title' => "Dinein Form Status", 'message' => 'Your dine-in form request is now ' . $request->status . '.', 'type' => 'dinein', 'task' => $form->id]);

            return successResponse("Operation success.", 200, 200);
        } catch (\Throwable $th) {
            return failureResponse("We're unable to process this operation.", 422, 422);
        }
    }
}
