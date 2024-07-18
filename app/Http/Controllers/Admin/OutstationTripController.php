<?php

namespace App\Http\Controllers\Admin;

use App\MyPreference;
use Illuminate\Http\Request;
use App\Services\DriverService;
use App\Custom\PushNotification;
use App\Services\OutstationTripService;
use App\Http\Resources\Admin\TripRiderResource;
use App\Http\Requests\Admin\OutstationTripRequest;
use App\Http\Resources\Admin\OutstationTripResource;

class OutstationTripController extends CommonController
{
	/** @var OutstationTripService */
	private $outstationTripService;

	/** @var DriverService */
	private $driverService;

	public function __construct(OutstationTripService $outstationTripService, DriverService $driverService)
	{
		parent::__construct();
		$this->outstationTripService = $outstationTripService;
		$this->driverService = $driverService;
	}

	public function index()
	{
		$outstationTrips = $this->outstationTripService->getForIndex(
			$this->paginationLimit
		);

		return OutstationTripResource::collection($outstationTrips);
	}

	public function destroy($outstationTripId)
	{
		$outstationTrip = $this->outstationTripService->delete($outstationTripId);

		return response('success');
	}

	public function getTrips(Request $request)
	{
		$trips = $this->outstationTripService->getTrip($request->name);

		return OutstationTripResource::collection($trips);
	}

	public function getRiders()
	{
		// $riders = $this->driverService->query()->where('interested_in', 'outstation')->Where('verified', 1)->get();
		$prefs = MyPreference::where('outstation', 1)->get();
		$riders = [];

		foreach ($prefs as $key => $pref) {
			$riders[] = $pref->driver;
		}

		$riders = collect($riders);

		return TripRiderResource::collection($riders);
	}

	public function updateRider(Request $request)
	{
		$trip = $this->outstationTripService->findOrFail($request->tripId);
		$trip->update(['driver_id' => $request->rider]);
		$this->sendNotification($trip->driver);

		$trip->driver->myNotifications()->create(['title' => 'Rental Trip Assigned', 'message' => $trip->tripId() . ' of Rental Trip has been assigned to you.', 'type' => 'trip']);

		return response('success');
	}

	public function sendNotification($rider)
	{
		$notification = new PushNotification(
			$rider->devices->pluck('device_token')->toArray(),
			[
				'title' => 'Rental Trip Assignment',
				'message' => 'Our team recently assigned you a outstation trip request.',
				'type' => 'rider',
			]
		);
		$notification->send();
	}
}
