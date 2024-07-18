<?php

namespace App\Http\Controllers\Api\Driver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Ride\RentalTripResource;
use App\RentalTrip;

class RentalTripController extends Controller
{
    public function list()
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        return RentalTripResource::collection($driver->rentalTrips()->orderBy('updated_at')->get())->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function markAsCompleted(Request $request)
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if (!$trip = RentalTrip::where('id', $request->tripId)->where('driver_id', $driver->id)->where('status', '!=', 'cancelled')->where('status', '!=', 'completed')->first()) {
            return failureResponse("Rental Trip not found.", 404, 404);
        }

        if ($request->state == "completed") {
            $trip->update(['status' => 'completed', 'completed_at' => now()]);
            $this->paymentSettlement($trip, $driver);
        }else{
            $trip->update(['status' => strtolower($request->state), 'updated_at' => now(), 'logs' => $request->reason ?? '']);
        }

        return successResponse('Rental Trip has been set to '.$request->state.'.', 200, 200);
    }

    public function paymentSettlement($trip, $driver)
    {
        if ($driver->packages()->count() > 0) {
            $currentPackage = $driver->currentPackage();
            if ($currentPackage->duration == "per-ride" && $currentPackage->type == "amount") {
                if ($driver->vehicles()->count() > 0) {
                    $vehicle = $driver->myVehicle();
                    $value = $vehicle->name == "2 Wheeler" ? $currentPackage->two_wheel_value : $currentPackage->four_wheel_value;
                } else {
                    $value = $currentPackage->two_wheel_value;
                }
                $driver->settlement->update(['payable_amount' => ($driver->settlement->payable_amount + $value)]);
            } else if ($currentPackage->duration == "per-ride" && $currentPackage->type == "percent") {
                if ($driver->vehicles()->count() > 0) {
                    $vehicle = $driver->myVehicle();
                    $value = $vehicle->name == "2 Wheeler" ? $currentPackage->two_wheel_value : $currentPackage->four_wheel_value;
                } else {
                    $value = $currentPackage->two_wheel_value;
                }
                $toPay = ($trip->price * $value) / 100;
                $driver->settlement->update(['payable_amount' => ($driver->settlement->payable_amount + $toPay)]);
            }
        } else {
            if ($driver->settlement) {
                $driver->settlement->update(['payable_amount' => ($driver->settlement->payable_amount + 5)]);
            } else {
                $driver->settlement->create(['payable_amount' => 5]);
            }
        }
    }
}
