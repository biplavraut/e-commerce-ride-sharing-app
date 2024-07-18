<?php

namespace App\Http\Controllers\Api\Driver;

use App\DefaultConf;
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

        if (!$trip = OutstationTrip::where('id', $request->tripId)->where('driver_id', $driver->id)->where('status', '!=', 'cancelled')->where('status', '!=', 'completed')->first()) {
            return failureResponse("Outstation Trip not found.", 404, 404);
        }


        if ($request->state == "completed") {
            $trip->update(['status' => 'completed', 'completed_at' => now()]);
            $this->paymentSettlement($trip, $driver);
        } else {
            $trip->update(['status' => strtolower($request->state), 'updated_at' => now(), 'logs' => $request->reason ?? '']);
        }

        $conf = DefaultConf::first();
        if ($conf && $conf->purchase_total != 0  && $conf->purchase_total != 0 && $conf->cashback_percent != 0) {
            if ($trip->price >= $conf->purchase_total) {
                $trip->user->update(['reward_point' => $trip->user->reward_point + round(($trip->price * $conf->cashback_percent) / 100)]);
                // if ($trip->user->gogoWallet) {
                //     $trip->user->gogoWallet()->update(['amount' => $trip->user->gogoWallet->amount + round(($trip->price * $conf->cashback_percent) / 100)]);
                // } else {
                //     $trip->user->gogoWallet()->create(['amount' => round(($trip->price * $conf->cashback_percent) / 100)]);
                // }
                $trip->user->transactionHistories()->create(['payment_mode' => 'gogo20', 'point' => round(($trip->price * $conf->cashback_percent) / 100), 'from' => 'Outstation Trip Cashback']);
            }
        }

        return successResponse('Outstation Trip has been set to  ' . $request->state . '.', 200, 200);
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
