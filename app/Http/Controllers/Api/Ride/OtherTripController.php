<?php

namespace App\Http\Controllers\Api\Ride;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Ride\RentalTripResource;
use App\Http\Resources\Api\Ride\OutstationTripResource;

class OtherTripController extends Controller
{
    public function outstation()
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        return OutstationTripResource::collection($user->outstationTrips()->latest()->get())->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function rental()
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        return RentalTripResource::collection($user->rentalTrips()->latest()->get())->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }
}
