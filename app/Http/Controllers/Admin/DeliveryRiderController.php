<?php

namespace App\Http\Controllers\Admin;

use App\Driver;
use App\Vehicle;
use App\DriverStatus;
use App\SubscriptionPackage;
use Illuminate\Http\Request;
use App\Services\DriverService;
use App\Custom\PushNotification;
use App\Http\Controllers\Controller;
use App\Services\DriverVehicleService;
use App\Http\Resources\Admin\DriverResource;
use App\Http\Resources\Admin\ActiveRiderResource;
use App\Http\Resources\Api\Driver\SubscriptionPackageResource;

class DeliveryRiderController extends CommonController
{
    /** @var DriverService */
    private $driverService;

    /** @var DriverVehicleService */
    private $driverVehicleService;

    public function __construct(DriverService $driverService, DriverVehicleService $driverVehicleService)
    {
        parent::__construct();
        $this->driverService = $driverService;
        $this->driverVehicleService = $driverVehicleService;
    }

    public function index()
    {
        $drivers = $this->driverService->query()->where('interested_in', 'delivery')->where('verified', 0)->latest()->paginate($this->paginationLimit);

        return DriverResource::collection($drivers);
    }

    public function subscriptionList()
    {
        $packages = SubscriptionPackage::oldest()->get();
        return SubscriptionPackageResource::collection($packages);
    }


    public function updateInterestedIn(Request $request)
    {
        try {
            $driver = $this->driverService->findOrFail($request->riderId);
            $package = SubscriptionPackage::findOrFail($request->packageId);
            $driver->update(['subscription' => $package->name]);

            if ($driver->packages()->count() > 0) {
                $driver->packages()->detach();
            }
            $driver->packages()->attach($package);
        } catch (\Throwable $th) {
            return response('error');
        }

        return response('success');
    }

    public function getDrivers(Request $request)
    {
        $drivers = $this->driverService->getDrivers($request->name, 'delivery');

        return DriverResource::collection($drivers);
    }

    public function assocatedRider()
    {
        $drivers = $this->driverService->getAssociatedDriver('delivery');
        return DriverResource::collection($drivers);
    }

    public function verifyNow(Request $request)
    {
        $driver = $this->driverService->findOrFail($request->id);
        $driver->verify();

        try {
            if ($driver->vehicleDetail) {
                if ($driver->vehicleDetail->type) {
                    $vehicle = Vehicle::where('type', 'LIKE', '%' . strtolower($driver->vehicleDetail->type) . '%')->first();
                    if (!$driver->myVehicle()) {
                        $driver->vehicles()->attach($vehicle);
                    }
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $this->sendNotification($driver, "Account Verification", "Your account has been verified. Now you can ride.");
        } catch (\Throwable $th) {
            //throw $th;
        }
        return response('success');
    }

    public function destroy($driverId)
    {
        $driver = $this->driverService->delete($driverId);

        return response('success');
    }

    public function verifiedOnly()
    {
        return DriverResource::collection($this->driverService->query()
        ->where('interested_in', 'delivery')
        ->where('verified', 1)
        ->latest()
        ->paginate($this->paginationLimit));
    }

    public function changeAssocaitedStatus(Request $request)
    {
        $driver = $this->driverService->findOrFail($request->id);
        $driver->makeAssociated();
        if ($request->update) {
            $driver->junction()->create(['junction_id' => $request->junction]);
        } else {
            if ($driver->junction) {
                $driver->junction()->delete();
            }
        }
        return response('success');
    }

    public function blockedOnly()
    {
        return DriverResource::collection($this->driverService->query()
        ->where('is_blocked', 1)
        ->where('interested_in', 'delivery')
        ->paginate($this->paginationLimit));
    }

    public function blacklistedOnly()
    {
        return DriverResource::collection($this->driverService->query()
        ->where('blacklisted', '>', 0)
        ->where('interested_in', 'delivery')
        ->paginate($this->paginationLimit));
    }

    public function activeOnly()
    {
        $riders = Driver::whereHas('status', function($query){
            $query->where('status', 'active');
            $query->orWhere('status', 'ongoing');
        })
        ->where('interested_in', 'delivery')
        ->get();


        return ActiveRiderResource::collection($riders);

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
                $driver->update(['is_blocked' => 0, 'log' => '']);
            } else {
                $driver->update(['is_blocked' => 1]);
                $this->sendNotification($driver, "Being Blocked", "Your account has been blocked. Please contact to support for more details.");
            }
        } else {
            $driver->update(['blacklisted' => 0]);
        }

        return response('success');
    }

    public function incompleteDocumentRiderList()
    {
        $list = $this->driverService->query()->whereHas('vehicleDetail', function ($query) {
            $query->where('status', 0);
        })
        ->where('interested_in', 'delivery')
        ->paginate($this->paginationLimit);

        return DriverResource::collection($list);
    }

    public function updateLicense(Request $request)
    {
        $data['license_expiry'] = $request->expiry;
        if ($request->image) {
            $data['license'] = $request->image;
        }

        $info = $this->driverVehicleService->query()->where('driver_id', $request->rider)->first();
        $driver = $this->driverService->findOrFail($request->rider);

        if ($info) {
            $log = $this->driverVehicleService->update($info->id, $data);
        } else {
            $data['driver_id'] = $request->rider;
            $log = $this->driverVehicleService->store($data);
        }

        return (new DriverResource($driver))->additional(['status' => true, "statusCode" => 200], 200);
    }

    public function updateBluebook(Request $request)
    {
        $data['bluebook_expiry'] = $request->expiry;
        if ($request->first) {
            $data['blue_book'] = $request->first;
        }

        if ($request->sec) {
            $data['blue_book_sec'] = $request->sec;
        }

        if ($request->trd) {
            $data['blue_book_trd'] = $request->trd;
        }
        $info = $this->driverVehicleService->query()->where('driver_id', $request->rider)->first();
        $driver = $this->driverService->findOrFail($request->rider);

        if ($info) {
            $log = $this->driverVehicleService->update($info->id, $data);
        } else {
            $data['driver_id'] = $request->rider;
            $log = $this->driverVehicleService->store($data);
        }


        return (new DriverResource($driver))->additional(['status' => true, "statusCode" => 200], 200);
    }

    public function sendNotification($rider, $title, $message)
    {
        $notification = new PushNotification(
            $rider->devices->pluck('device_token')->toArray(),
            [
                'title' => $title,
                'message' => $message,
                'type' => 'rider',
            ]
        );
        $notification->send();
    }

    public function block(Request $request)
    {
        $driver = $this->driverService->findOrFail($request->rider);

        if ($driver->is_blocked == 1) {
            $driver->update(['is_blocked' => 0, 'log' => '']);
            $this->sendNotification($driver, "Block Cleared", "Now you can accept trip request.");
        } else {
            $driver->update(['is_blocked' => 1, 'log' => $request->reason]);
            $this->sendNotification($driver, "Being Blocked", "Your account has been blocked. Please contact to support for more details.");
        }

        return response('success');
    }
}
