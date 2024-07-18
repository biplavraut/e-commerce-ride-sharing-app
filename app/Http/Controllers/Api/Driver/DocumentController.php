<?php

namespace App\Http\Controllers\Api\Driver;

use App\Vehicle;
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
            'regNumber' => 'nullable|string',
            'manufacturingYear' => 'nullable|string',
            'type' => 'nullable|string',
            'color' => 'nullable|string',
            'plateNo' => 'nullable|string',
            'licenseCategory' => 'nullable|string',
            'licenseNo' => 'nullable|string',
            'license' => 'nullable|mimes:jpg,jpeg,png',
            'blueBook' => 'nullable|mimes:jpg,jpeg,png',
            'blueBookSec' => 'nullable|mimes:jpg,jpeg,png',
            'blueBookTrd' => 'nullable|mimes:jpg,jpeg,png',
            'picture' => 'nullable|mimes:jpg,jpeg,png',
            'image' => 'nullable|mimes:jpg,jpeg,png',
            'ownerName' => 'nullable|string',
            'ownerContact' => 'nullable|string',
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
                'blue_book_sec' => $request->blueBookSec,
                'blue_book_trd' => $request->blueBookTrd,
                'picture' => $request->picture,
                'owner_name' => $request->ownerName,
                'owner_contact' => $request->ownerContact,
            ];

            $document = $this->driverVehicleService->store($data);

            if ($request->image) {
                $driver = $this->driverService->update($user->id, $request->only('image'));
            }

            $vehicle = Vehicle::where('type', 'LIKE', '%' . strtolower($request->type) . '%')->first();
            $user->vehicles()->attach($vehicle);


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
                'license' => $request->license,
                'blue_book' => $request->blueBook,
                'blue_book_sec' => $request->blueBookSec,
                'blue_book_trd' => $request->blueBookTrd,
                'picture' => $request->picture,
                'owner_name' => $request->ownerName,
                'owner_contact' => $request->ownerContact,
            ];

            $document = $this->driverVehicleService->update($user->vehicleDetail->id, $data);

            if ($request->image) {
                $driver = $this->driverService->update($user->id, $request->only('image'));
            }

            return (new VehicleDocumentResource($document))->additional(['status' => true, 'message' => "Your document has been updated. You will be notified after verification.", 'statusCode' => 200], 200);
        }


        return failureResponse("Something went wrong.", 418, 418);
    }
}
