<?php

namespace App\Http\Controllers\Api\Ride;

use App\Driver;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Ride\SavedPlaceResource;
use Firebase\FirebaseLib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserDataController extends Controller
{
    public function __construct()
    {
        // $this->firebase = new FirebaseLib($this->firebaseURL, $this->firebaseSecret);
    }

    //store place
    public function savePlace(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:saved_places,name,' . $user->id,
            'location' => 'required|string|max:155|unique:saved_places,location,' . $user->id,
            'lat' => 'required|string|max:155',
            'long' => 'required|string|max:155',
            'flag' => 'nullable|string|in:home,office,other'
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        $place = $user->savedPlaces()->create(['name' => $request->name, 'location' => $request->location, 'lat' => $request->lat, 'long' => $request->long, 'flag' => $request->flag]);
        // new SavedPlaceResource
        return (new SavedPlaceResource($place))->additional(['status' => true, 'message' => "Place saved.", 'statusCode' => 200], 200);
    }

    //update saved place
    public function updateSavedPlace(Request $request)
    {
        //wop
    }

    public function rateTheRider(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $validator = Validator::make($request->all(), [
            'driverId' => 'required',
            'rating' => 'required|integer',
            'complement' => 'nullable|array',
            'review' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        if (!$driver = Driver::find($request->driverId)) {
            return response()->json(['status' => false, 'message' => 'Rider Not Found.', 'statusCode' => 404], 404);
        }

        $rating = $driver->ratings()->create(['user_id' => $user->id, 'rating' => $request->rating, 'complement' => json_encode($request->complement),  'review' => $request->review]);


        return successResponse("Rated.", 201, 201);
    }

    //list stored places
    public function savedPlace(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if ($request->name) {
            return SavedPlaceResource::collection($user->savedPlaces()->where('name', 'LIKE', '%' . $request->name . '%')->orWhere('flag', $request->name)->latest()->get());
        }

        return SavedPlaceResource::collection($user->savedPlaces()->latest()->get());
    }
}
