<?php

namespace App\Http\Controllers\Api\Driver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Ride\OutstationTripResource;
use App\OutstationTrip;

class OutstationTripController extends Controller
{
    public function list()
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        return OutstationTripResource::collection($driver->outstationTrips()->latest()->get())->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function markAsCompleted(Request $request)
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if (!$trip = OutstationTrip::where('id', $request->tripId)->where('driver_id', $driver->id)->first()) {
            return failureResponse("Rental Trip not found.", 404, 404);
        }

        $trip->update(['status' => 'completed', 'completed_at' => now()]);
        $this->paymentSettlement($trip, $driver);

        return successResponse('Outstation Trip has been set to completed.', 200, 200);
    }

    public function paymentSettlement($trip, $driver)
    {
        if ($trip->payment_mode == "cod") {
            if ($driver->settlement) {
                $payable = $trip->price < 100 ? 5 : 6;
                $driver->settlement->update(['payable_amount' => $driver->settlement->payable_amount + $payable]);
            } else {
                $payable = $trip->price < 100 ? 5 : 6;
                $driver->settlement()->create(['payable_amount' => $payable]);
            }
        }

        if ($trip->payment_mode != "cod") {
            if ($driver->settlement) {
                $receivable = $trip->price < 100 ? 5 : 6;
                $driver->settlement->update(['receivable_amount' => $driver->settlement->receivable_amount + $receivable]);
            } else {
                $receivable = $trip->price < 100 ? 5 : 6;
                $driver->settlement()->create(['receivable_amount' => $receivable]);
            }
        }
    }
}
