<?php

namespace App\Http\Controllers\Admin;

use App\DriverStatus;
use Illuminate\Http\Request;
use App\Services\DriverService;
use App\Custom\PushNotification;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\DriverResource;
use App\Driver;

class DriverController extends CommonController
{
    /** @var DriverService */
    private $driverService;

    public function __construct(DriverService $driverService)
    {
        parent::__construct();
        $this->driverService = $driverService;
    }

    public function index()
    {
        // return DriverResource::collection($this->driverService->query()->where('verified', 0)->oldest()->take(15)->get());
        $drivers = $this->driverService->getForIndex(
            $this->paginationLimit
        );

        return DriverResource::collection($drivers);
    }

    public function updateInterestedIn(Request $request){
        $newSubscriptionType = $request->subscriptionType;
        $driverId = $request->rider_id;
        $driver = Driver::findOrFail($driverId);
        $driver->subscription = $newSubscriptionType;
        $driver->save();
    }

    public function getDrivers(Request $request)
    {
        $drivers = $this->driverService->getDrivers($request->name);

        return DriverResource::collection($drivers);
    }

    public function assocatedRider()
    {
        $drivers = $this->driverService->getAssociatedDriver();
        return DriverResource::collection($drivers);
    }

    public function verifyNow(Request $request)
    {
        $driver = $this->driverService->findOrFail($request->id);
        $driver->verify();
        $this->sendNotification($driver);
        return response('success');
    }

    public function destroy($driverId)
    {
        $driver = $this->driverService->delete($driverId);

        return response('success');
    }

    public function verifiedOnly()
    {
        return DriverResource::collection($this->driverService->query()->where('verified', 1)->latest()->paginate($this->paginationLimit));
    }

    public function changeAssocaitedStatus(Request $request)
    {
        $driver = $this->driverService->findOrFail($request->id);
        $driver->makeAssociated();
        return response('success');
    }

    public function blockedOnly()
    {
        return DriverResource::collection($this->driverService->query()->where('is_blocked', 1)->paginate($this->paginationLimit));
    }

    public function blacklistedOnly()
    {
        return DriverResource::collection($this->driverService->query()->where('blacklisted', '>', 0)->paginate($this->paginationLimit));
    }

    public function activeOnly()
    {
        $riders = [];
        $activeStats = DriverStatus::where('status', 'active')->orWhere('status', 'ongoing')->get();

        foreach ($activeStats as $key => $state) {
            $riders[] = $state->driver;
        }

        $riders = collect($riders);

        return DriverResource::collection($riders);
    }

    public function updateExpiry(Request $request)
    {
        $driver = $this->driverService->findOrFail($request->id);
        if ($request->licenseExpiryDate) {
            $driver->vehicleDetail->update(['license_expiry' => $request->licenseExpiryDate]);
            return response('success');
        }

        if ($request->bluebookExpireDate) {
            $driver->vehicleDetail->update(['bluebook_expiry' => $request->bluebookExpireDate]);
            return response('success');
        }
    }

    public function clearBlockBlackList(Request $request)
    {
        $driver = $this->driverService->findOrFail($request->id);

        if ($request->type == "block") {
            if ($driver->is_blocked == 1) {
                $driver->update(['is_blocked' => 0]);
            } else {
                $driver->update(['is_blocked' => 1]);
            }
        } else {
            $driver->update(['blacklisted' => 0]);
        }

        return response('success');
    }

    public function sendNotification($rider)
    {
        $notification = new PushNotification(
            [$rider->device->device_token],
            [
                'title' => 'Account Verified',
                'message' => 'Your account has been verified. Now you can ride.',
                'type' => 'rider',
            ]
        );
        $notification->send();
    }
}
