<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Custom\PushNotification;
use App\Custom\Sms\AakashSms;
use App\Custom\Sms\Sparrow;
use App\Driver;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PrescriptionResource;
use App\Prescription;
use App\Services\PrescriptionService;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    //
    /** @var PrescriptionService */
    private $prescriptionService;

    public function __construct(PrescriptionService $prescriptionService)
    {
        $this->prescriptionService = $prescriptionService;
    }

    //
    public function list()
    {
        if (auth()->user()->type == 'support') {
            return PrescriptionResource::collection($this->prescriptionService->query()->where('admin_id', auth()->user()->id)->latest()->paginate(10));
        }
        return PrescriptionResource::collection($this->prescriptionService->query()->latest()->paginate(10));
    }

    public function updateStatus(Request $request)
    {
        $prescription = $this->prescriptionService->query()->where('id', $request->formId)->first();
        $prescription->update(['status' => $request->status]);
        return successResponse('success');
    }

    public function assignPharmacist(Request $request)
    {
        if ($request->type == 'admin') {
            $pharmacist = Admin::where('id', $request->adminId)->first();
            $prescription = $this->prescriptionService->query()->where('id', $request->prescriptionId)->first();
            $prescription->update(['admin_id' => $pharmacist->id, 'status' => 'processing']);
            if ($pharmacist->phone != '') {
                $this->sendSMS($pharmacist->phone, $prescription->id);
            }
            return successResponse('Prescription successfully assigned to support.');
        } elseif ($request->type == 'driver') {
            $delivery = Driver::where('id', $request->adminId)->first();
            $prescription = $this->prescriptionService->query()->where('id', $request->prescriptionId)->first();
            $prescription->update(['driver_id' => $delivery->id, 'status' => 'processing']);
            $prescription->user->myNotifications()->create(['title' => 'Prescription Assigned', 'message' => 'Your Prescription ' . $prescription->prescriptionNo() . '  has been assigned to delivery rider.', 'type' => 'prescription', 'task' => $prescription->prescriptionNo()]);
            $notification = new PushNotification(
                $prescription->user->devices->pluck('device_token')->toArray(),
                [
                    'title' => 'Prescription Assigned',
                    'message' => 'Your Prescription ' . $prescription->prescriptionNo() . '  has been assigned to delivery rider.',
                    'type' => 'prescription',
                ]
            );
            $notification->send();
            $driverDeviceToken = $delivery->devices->pluck('device_token')->toArray();
            $notification = new PushNotification(
                $driverDeviceToken,
                [
                    'title' => 'New prescription assigned to you',
                    'delivery' => 'Prescription request ' . $prescription->prescriptionNo() . '  has been assigned to you.',
                    'type' => 'prescription_assigned'
                ]
            );
            $notification->send();
            return successResponse('Prescription successfully assigned to delivery rider.');
        }
    }

    public function remarkPrescription(Request $request)
    {
        $admin =  auth()->user()->name;
        if ($request->remarks != '') {
            $prescription = $this->prescriptionService->query()->where('id', $request->prescriptionId)->first();
            $prescription->update(['remarks' => $request->remarks . " (" . $admin . ")" . "\r\n" . $prescription->remarks]);
            return response('success');
        }
        return failureResponse('Something Went Wrong.');
    }

    public function sendSMS($number, $prescription)
    {
        $numberFormat = $number;
        $message = 'Prescription Request' . ", \n";
        $message .= 'gogo20 have requested you to verify the uploaded prescription.' . " \n";
        // $message .= config('app.url') . '/' . base64_encode($prescription);
        try {
            $sms = new Sparrow($numberFormat, $message);
            $response = $sms->sendMessage();

            if ($response != 200) {
                $sms = new AakashSms(null, $numberFormat, $message);
                $response = $sms->sendMessage();
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        return true;
    }
}
