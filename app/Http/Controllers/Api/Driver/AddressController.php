<?php

namespace App\Http\Controllers\Api\Driver;

use App\Municipality;
use App\RiderHomeAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\Driver\AddressResource;
use App\Http\Resources\Api\Driver\DistrictResource;

class AddressController extends Controller
{
    private $driver;

    public function __construct()
    {
        $this->driver = auth()->guard('driver-api')->user();

        if (!$this->driver) {
            return failureResponse("Token Expired.", 401, 401);
        }
    }

    public function getAddress()
    {
        if ($this->driver->address()->first()) {
            return (new AddressResource($this->driver->address()->first()))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        }
        return response()->json(['data' => [], 'status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function setAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'district' => 'bail|required|string|max:255',
            'municipality' => 'bail|required|string|max:255',
            'ward' => 'bail|required|max:255',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }
        if ($this->driver->address()->first()) {
            $address = $this->driver->address()->first()->update(
                [
                    'district' => $request->district,
                    'municipality' => $request->municipality,
                    'ward' => $request->ward
                ]
            );
            $message  = "Home address updated successfully.";
        } else {
            $address = $this->driver->address()->create(
                [
                    'driver_id' => $this->driver->id,
                    'district' => $request->district,
                    'municipality' => $request->municipality,
                    'ward' => $request->ward
                ]
            );
            $message  = "Home address created successfully.";
        }

        return (new AddressResource($this->driver->address()->first()))->additional(['status' => true, 'message' => $message, 'statusCode' => 200], 200);
    }

    public function list()
    {
        $districts = DB::table('municipalities')->select('*')->groupBy('district_name')->get();
        return DistrictResource::collection($districts)->additional(['status' => true, 'message' => '', 'statusCode' => 200], 200);
    }
}
