<?php

namespace App\Http\Controllers\Api;

use App\DineInForm;
use Illuminate\Http\Request;
use App\Services\VendorService;
use App\VendorAdvanceSettlement;
use App\Services\DineinFormService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\DineinResource;
use App\Http\Controllers\Api\CommonController;

class DineinController extends CommonController
{
    /**
     * @var VendorService
     */
    private $vendorService;


    /**
     * @var DineinFormService
     */
    private $dineInService;

    public function __construct(VendorService $vendorService, DineinFormService $dineInService)
    {
        parent::__construct();
        $this->vendorService = $vendorService;
        $this->dineInService = $dineInService;
    }

    public function storeRequest(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $validator = Validator::make($request->all(), [
            'vendorId' => 'required',
            'date' => 'required|string',
            'time' => 'required|string',
            'peopleAttend' => 'nullable',
            'specialInstruction' => 'nullable|string'
        ]);
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        $vendor = $this->vendorService->query()
            ->where('id', $request->vendorId)
            ->where('is_hidden', 0)
            ->where('status', 1)
            ->where('verified', 1)
            ->where('dine_in', 1)
            ->first();

        if (!$vendor) {
            return failureResponse('Vendor Not Found.', 404, 404);
        }

        $form =  DineInForm::create([
            'user_id' => $user->id,
            'vendor_id' => $vendor->id,
            'people_attend' => $request->peopleAttend ?? 1,
            'date' => $request->date,
            'time' => $request->time,
            'special_instruction' => $request->specialInstruction
        ]);

        $vendor->myNotifications()->create(['title' => 'DineIn Form Received', 'message' => $user->first_name . ' ' . $user->last_name . ' sent a dine in form request.', 'type' => 'dineine', 'task' => $form->id]);


        return successResponse('Dine in request submitted.', 200, 200);
    }

    public function dineInFormList(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if ($request->status) {
            $forms = $user->dineInForms()->where('status', $request->status)->latest()->paginate($this->paginationLimit)->appends($request->query());
        } else {
            $forms = $user->dineInForms()->latest()->paginate($this->paginationLimit)->appends($request->query());
        }

        return DineinResource::collection($forms)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function dineInComplete(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $validator = Validator::make($request->all(), [
            'dineInId' => 'required',
            'status' => 'nullable|string',
            'totalBill' => "required_without:status",
            'bill' => 'required_without:status|file|mimes:png,jpg,webp',
        ]);
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        $form = $this->dineInService->query()->where('user_id', $user->id)->where('status', '!=', 'completed')->where('status', '!=', 'processing')->where('id', $request->dineInId)->first();

        if (!$form) {
            return failureResponse("Dinein request form not found.", 404, 404);
        }

        $data = [
            'status' => $request->status ?? 'processing',
            'bill' => $request->bill,
            'total_price' => $request->totalBill
        ];

        try {
            $updatedForm = $this->dineInService->update($form->id, $data);

            return successResponse("Dinein form request is in now processing state.", 200, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }

        return failureResponse("We're unable to process this operation.", 422, 422);
    }
}
