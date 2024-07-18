<?php

namespace App\Http\Controllers\Api\Driver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Driver\PreferenceResource;
use Illuminate\Support\Facades\Validator;

class PreferenceController extends Controller
{
    public function prefs(Request $request)
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $validator = Validator::make($request->all(), [
            'smoking' => 'required|in:0,1',
            'childSeat' => 'required|in:0,1',
            'handicapSupport' => 'required|in:0,1',
            'rental' => 'required|in:0,1',
            'outstation' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        if ($driver->preference) {
            $driver->preference->update([
                'smoking' => $request->smoking,
                'child_seat' => $request->childSeat,
                'handicap_support' => $request->handicapSupport,
                'rental' => $request->rental,
                'outstation' => $request->outstation,
            ]);
        } else {
            $preference = $driver->preference()->create([
                'smoking' => $request->smoking, 
                'child_seat' => $request->childSeat,
                'handicap_support' => $request->handicapSupport,
                'rental' => $request->rental,
                'outstation' => $request->outstation,
            ]);
        }

        return (new PreferenceResource($driver->preference ? $driver->preference : $preference))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function getPrefs()
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if (!$driver->preference) {
            return response()->json(['data' => null, 'status' => true, 'message' => '', 'statusCode' => 200], 200);
        }

        return (new PreferenceResource($driver->preference))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }
}
