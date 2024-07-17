<?php

namespace App\Http\Controllers\Admin;

use App\MyPreference;
use Illuminate\Http\Request;
use App\Services\DriverService;
use App\Custom\PushNotification;
use App\Services\RentalTripService;
use App\Http\Resources\Admin\TripRiderResource;
use App\Http\Resources\Admin\RentalTripResource;
use App\Http\Requests\Admin\RentalPackageRequest;

class RentalTripController extends CommonController
{
    /** @var RentalTripService */
    private $rentalTripService;

    /** @var DriverService */
    private $driverService;


    public function __construct(RentalTripService $rentalTripService, DriverService $driverService)
    {
        parent::__construct();
        $this->rentalTripService = $rentalTripService;
        $this->driverService = $driverService;
    }

    public function index()
    {
        $trips = $this->rentalTripService->getForIndex($this->paginationLimit);

        return RentalTripResource::collection($trips);
    }

    public function destroy($rentalTripId)
    {
        $rentalTrip = $this->rentalTripService->delete($rentalTripId);

        return response('success');
    }

    public function getTrips(Request $request)
    {
        $trips = $this->rentalTripService->getTrip($request->name);

        return RentalTripResource::collection($trips);
    }

    public function getRiders()
    {
        // $riders = $this->driverService->query()->where('interested_in', 'rental')->Where('verified', 1)->get();

        $prefs = MyPreference::where('rental', 1)->get();
        $riders = [];

        foreach ($prefs as $key => $pref) {
            $riders[] = $pref->driver;
        }

        $riders = collect($riders);


        return TripRiderResource::collection($riders);
    }

    public function updateRider(Request $request)
    {
        $trip = $this->rentalTripService->findOrFail($request->tripId);
        $trip->update(['driver_id' => $request->rider]);
        $this->sendNotification($trip->driver);
        return response('success');
    }

    public function sendNotification($rider)
    {
        $notification = new PushNotification(
            [$rider->device->device_token],
            [
                'title' => 'Rental Trip Assignment',
                'message' => 'Our team recently assigned you a rental trip request.',
                'type' => 'rider',
            ]
        );
        $notification->send();
    }
}
