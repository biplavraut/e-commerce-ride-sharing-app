<?php

namespace App\Http\Controllers\Api\Driver;

use App\Category;
use App\Custom\PushNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ConfirmPrescriptionRequest;
use App\Http\Requests\Api\PrescriptionBillRequest;
use App\Http\Resources\Api\PrescriptionResource;
use App\Http\Resources\Api\VendorResource;
use App\Prescription;
use App\PrescriptionBill;
use App\Services\PrescriptionBillService;
use App\Services\PrescriptionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrescriptionController extends Controller
{
    //
    //
    /** @var PrescriptionService */
    private $prescriptionService;

    /** @var PrescriptionBillService */
    private $prescriptionBillService;

    public function __construct(PrescriptionService $prescriptionService, PrescriptionBillService $prescriptionBillService)
    {
        $this->prescriptionService = $prescriptionService;
        $this->prescriptionBillService = $prescriptionBillService;
    }

    public function assignedPrescription()
    {
        $driver = auth()->guard('driver-api')->user();
        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }
        // $prescriptions = $this->prescriptionService->query()->where('driver_id', $driver->id)->whereIn('status', ['requested', 'processing', 'not-found', 'confirmed'])->latest()->get();
        $prescriptions = $this->prescriptionService->query()->where('driver_id', $driver->id)->latest()->get();
        return PrescriptionResource::collection($prescriptions)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function updatePrescriptionStatus(Request $request)
    {
        $driver = auth()->guard('driver-api')->user();
        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }
        try {
            $prescription = $this->prescriptionService->query()->where('id', $request->prescriptionId)->first();
            $prescription->update(['status' => $request->status]);
            return successResponse('success');
        } catch (Exception $e) {
            return $e;
            return failureResponse('Unable to Update');
        }
    }

    public function healthVendors()
    {
        $category = Category::findOrFail(30);
        $healthVendors = $category->registeredVendor()->latest()->get();
        return VendorResource::collection($healthVendors)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function addPrescriptionBill(PrescriptionBillRequest $request)
    {
        try {
            DB::beginTransaction();
            $driver = auth()->guard('driver-api')->user();
            if (!$driver) {
                return failureResponse("Token Expired.", 401, 401);
            }
            //New table to store uploaded data
            //Store the data
            $prescription = $this->prescriptionService->query()->where('id', $request->prescriptionId)->first();
            if (!$prescription) {
                return failureResponse("Prescription Not Found.", 401, 401);
            }
            $data = $this->prescriptionBillService->store([
                'prescription_id' => $request->prescriptionId,
                'driver_id' => $driver->id,
                'type' => $request->type,
                'image' => $request->image,
                'vendor_id' => $request->vendorId,
                'bill_amount' => $request->billAmount,
                'vendor_name' => $request->vendorName,
            ]);
            $vendorTotal = $prescription->billDetail->where('type', 'vendor')->sum('bill_amount');
            $outsideTotal = $prescription->billDetail->where('type', 'other')->sum('bill_amount');
            $subTotal = $prescription->billDetail->sum('bill_amount');
            $otp = $prescription->otp == '' ? randomNumericString(4) : $prescription->otp;
            // If store update the prescription
            $prescription->update([
                'status' => 'confirmed',
                'sub_total' => $subTotal,
                'vendor_total' => $vendorTotal,
                'outside_total' => $outsideTotal,
                'total' => $subTotal  + $prescription->shipping_fee,
                'otp' => $otp
            ]);
            DB::commit();
            return (new PrescriptionResource($prescription))->additional(['status' => true, 'message' => "Success", 'statusCode' => 200], 200);
            // return successResponse('Success');
        } catch (Exception $e) {
            DB::rollBack();
            return $e;
            return failureResponse('Something Went Wrong');
        }
    }

    public function deliveryStart(Request $request)
    {
        $driver = auth()->guard('driver-api')->user();

        $message = "";

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if (!$prescription = $this->prescriptionService->query()->where('id', $request->prescriptionId)->first()) {
            return failureResponse("Prescription not found.", 404, 404);
        }
        $prescription->update(['status' => 'delivery-started']);
        $prescription->user->myNotifications()->create(['title' => 'Prescription Delivery Started', 'message' => 'Your Prescription ' . $prescription->prescriptionNo() . ' is on the way.', 'type' => 'prescription', 'task' => $prescription->prescriptionNo()]);
        $notification = new PushNotification(
            $prescription->user->devices->pluck('device_token')->toArray(),
            [
                'title' => 'Prescription Delivery Started',
                'message' => 'Your Prescription ' . $prescription->prescriptionNo() . ' is on the way.',
                'type' => 'prescription',
            ]
        );
        $notification->send();

        return successResponse('Success.', 200, 200);
    }

    public function prescriptionDeliveryCompleted(Request $request)
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if (!$prescription = $this->prescriptionService->query()->where('id', $request->prescriptionId)->first()) {
            return failureResponse("Prescription not found.", 404, 404);
        }
        // $processDelivery = new ProcessPrescriptionDelivery($delivery->order, $delivery->order->user, $delivery, $driver);
        // $delivered = $processDelivery->completeDelivery();
        if ($prescription) {
            $prescription->update(['status' => 'collected']);
            $driver->status()->update(['status' => 'active']);

            // $this->firebaseTripDelRTD($trip->id);
            $prescription->user->myNotifications()->create(['title' => 'Prescription status', 'message' => 'Your Prescription Order ' . $prescription->prescriptionNo() . ' has been marked as delivered.', 'type' => 'prescription', 'task' => $prescription->prescriptionNo()]);

            return successResponse('Prescription delivery has been set to completed.', 200, 200);
        } else {
            return failureResponse("Something Went wrong.", 404, 404);
        }
    }
}
