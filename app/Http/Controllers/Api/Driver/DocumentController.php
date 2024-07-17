<?php

namespace App\Http\Controllers\Api\Driver;

use Illuminate\Http\Request;
use App\Services\DriverService;
use App\DriverPaymentSettlement;
use App\Http\Controllers\Controller;
use App\Services\DriverVehicleService;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\VehicleDocumentResource;

class DocumentController extends Controller
{
    /**
     * @var DriverVehicleService
     */
    private $driverVehicleService;

    /**
     * @var DriverService
     */
    private $driverService;



    public function __construct(DriverVehicleService $driverVehicleService, DriverService $driverService)
    {
        $this->driverVehicleService = $driverVehicleService;
        $this->driverService = $driverService;
    }

    public function index()
    {
        $user = auth()->guard('driver-api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if ($user->vehicleDetail) {
            return (new VehicleDocumentResource($user->vehicleDetail))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        }

        return failureResponse("Please upload your documents to get trips.", 418, 418);
    }

    public function store(Request $request)
    {
        $user = auth()->guard('driver-api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }


        $validator = Validator::make($request->all(), [
            'regNumber' => 'required|string',
            'manufacturingYear' => 'required|string',
            'type' => 'required|string',
            'color' => 'nullable|string',
            'plateNo' => 'nullable|string',
            'licenseCategory' => 'required|string',
            'licenseNo' => 'required|string',
            'license' => 'required|mimes:jpg,jpeg,png',
            'blueBook' => 'required|mimes:jpg,jpeg,png',
            'picture' => 'required|mimes:jpg,jpeg,png',
            'image' => 'required|mimes:jpg,jpeg,png',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }


        if (!$user->vehicleDetail) {
            $data = [
                'driver_id' => $user->id,
                'reg_no' => $request->regNumber,
                'type' => $request->type,
                'manufacturing_year' => $request->manufacturingYear,
                'license_category' => $request->licenseCategory,
                'color' => $request->color,
                'plate_no' => $request->plateNo,
                'license_no' => $request->licenseNo,
                'license' => $request->license,
                'blue_book' => $request->blueBook,
                'picture' => $request->picture,
            ];

            $document = $this->driverVehicleService->store($data);

            $driver = $this->driverService->update($user->id, $request->only('image'));

            return (new VehicleDocumentResource($document))->additional(['status' => true, 'message' => "Your document has been submitted. You will be notified after verification.", 'statusCode' => 200], 200);
        } else {
            $data = [
                'reg_no' => $request->regNumber,
                'type' => $request->type,
                'manufacturing_year' => $request->manufacturingYear,
                'license_category' => $request->licenseCategory,
                'color' => $request->color,
                'plate_no' => $request->plateNo,
                'license_no' => $request->licenseNo,
            ];

            $document = $this->driverVehicleService->update($user->vehicleDetail->id, $data);

            return (new VehicleDocumentResource($document))->additional(['status' => true, 'message' => "Your document has been submitted. You will be notified after verification.", 'statusCode' => 200], 200);
        }

        return failureResponse("Something went wrong.", 418, 418);
    }
}
