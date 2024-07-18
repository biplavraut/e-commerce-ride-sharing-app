<?php

namespace App\Http\Controllers\Api;

use App\Custom\PushNotification;
use App\DefaultConf;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PrescriptionRequest;
use App\Http\Resources\Api\HospitalResource;
use App\Http\Resources\Api\PrescriptionResource;
use App\Notifications\PrescriptionReceived;
use App\Services\HospitalService;
use App\Services\ImageService;
use App\Services\PrescriptionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class PrescriptionController extends Controller
{
    //
    /** @var ImageService */
    private $imageService;

    //
    /** @var PrescriptionService */
    private $prescriptionService;

    //
    /** @var HospitalService */
    private $hospitalService;

    public function __construct(ImageService $imageService, PrescriptionService $prescriptionService, HospitalService $hospitalService)
    {
        $this->imageService = $imageService;
        $this->prescriptionService = $prescriptionService;
        $this->hospitalService = $hospitalService;
    }

    public function store(PrescriptionRequest $request)
    {
        // User Authentication
        $user = auth()->guard('api')->user();
        if (!$user || $user->isBlocked()) {
            $user->update(['blocked' => 1]);
            auth()->guard('api')->logout();
            if ($user->access_token) {
                JWTAuth::setToken($user->access_token)->invalidate();
            }
            return failureResponse("Token Expired.", 401, 401);
        }
        // Shipping Distance
        if ($request->latitude && $request->longitude) {
            //getting air distance of status and pickup point
            $distance =  number_format((float) getDistance(27.733623151865437, 85.34126043319702, $request->latitude, $request->longitude), 2, '.', '');
            // dd($distance);
            if ($distance > 50) {
                return failureResponse('Shipping service Not available in this region.', 403, 403);
            }
        }
        $defaultConf = DefaultConf::firstOrFail();

        if ($request->deliveryArea == "Outside Ring-Road (5KM)") {
            $shippingFee =  $defaultConf->delivery_charge_outside;
        } else {
            $shippingFee = 0;
        }
        try {
            DB::beginTransaction();
            $prescription = $this->prescriptionService->store([
                'user_id' => $user->id,
                'patient_name' => $request->patientName,
                'patient_age' => $request->patientAge,
                'hospital_id' => $request->hospital,
                'doctor_name' => $request->doctorName,
                'doctor_nmc' => $request->doctorNmc,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'delivery_area' => $request->deliveryArea,
                'nearest_landmark' => $request->nearestLandmark,
                'preferred_date' => $request->preferredDate,
                'preferred_time' => $request->preferredTime,
                'alternate_name' => $request->alternateName,
                'alternate_phone' => $request->alternatePhone,
                'additional_detail' => $request->additionalDetail,
                'shipping_fee' => $shippingFee
            ]);
            try {
                foreach ($request->only('images')['images'] ?? [] as $image) {
                    $this->imageService->store([
                        'image' => $image,
                        'model_type' => get_class($prescription),
                        'model_id' => $prescription->id,
                    ]);
                }
            } catch (Exception $e) {
                DB::rollBack();
                return failureResponse('Image Error', 200, 200);
            }
            $notification = new PushNotification(
                $user->devices->pluck('device_token')->toArray(),
                [
                    'title' => 'Prescription Received',
                    'message' => 'We have received your prescription. Team gogo20 will update you soon. Thank you.',
                    'type' => 'prescription',
                ]
            );
            $notification->send();
            $user->myNotifications()->create(['title' => 'Prescription Received', 'message' => 'We have received your prescription. Team gogo20 will update you soon. Thank you.', 'type' => 'prescription']);

            $data['first_name'] = $user->first_name;
            $data['last_name'] = $user->last_name;
            $data['image'] = $prescription->getFirstImage();
            $data['message'] = 'Prescription request received.';
            Notification::send($user, new PrescriptionReceived($data));

            DB::commit();
            return successResponse("Prescription request successfully placed.", 200, 200);
        } catch (Exception $e) {
            DB::rollBack();
            return $e;
            return failureResponse('Something Went wrong', 200, 200);
        }
    }

    public function prescriptionList()
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }
        $prescriptions = $this->prescriptionService->query()->where('user_id', $user->id)->latest()->get();
        return PrescriptionResource::collection($prescriptions)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function cancelPrescription(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }
        try {
            $prescription = $this->prescriptionService->query()->where('id', $request->prescriptionId)->where('user_id', $user->id)->first();
            if (!$prescription) {
                return failureResponse("Prescription not found.", 404, 404);
            }
            $prescription->update(['status' => 'denied', 'remarks' => $request->remarks . " (User)" . "\r\n" . $prescription->remarks]);
            return successResponse('success');
        } catch (Exception $e) {
            return $e;
            return failureResponse('Unable to Update');
        }
        $prescriptions = $this->prescriptionService->query()->where('id', $request->prescriptionId)->latest()->get();
        return PrescriptionResource::collection($prescriptions)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function listHospitals()
    {
        $hospitals = $this->hospitalService->query()->orderBy('title')->get();
        return HospitalResource::collection($hospitals)
            ->additional(['status' => true, 'message' => '', 'statusCode' => 200], 200);;
    }
}
