<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Services\DriverService;
use App\Http\Controllers\Controller;
use App\Services\InHouseRiderLogService;
use App\Http\Resources\Admin\InHouseRiderLogResource;

class InHouseRiderLogController extends CommonController
{
    /** @var InHouseRiderLogService */
    private $inHouseRiderLogService;

    /** @var DriverService */
    private $driverService;

    public function __construct(InHouseRiderLogService $inHouseRiderLogService, DriverService $driverService)
    {
        parent::__construct();
        $this->inHouseRiderLogService = $inHouseRiderLogService;
        $this->driverService = $driverService;
    }

    public function index()
    {
        $data = $this->inHouseRiderLogService->query()->whereNull('received_by')->latest()->paginate($this->paginationLimit);

        return InHouseRiderLogResource::collection($data);
    }

    public function search(Request $request)
    {

        $driver = $this->driverService->query()->where('is_associated_rider', 1)->where(function ($query) use ($request) {
            $query->where('first_name', $request->name);
            $query->orWhere('last_name', $request->name);
            $query->orWhere('phone', $request->name);
        })->first();

        if ($driver) {
            return InHouseRiderLogResource::collection($this->inHouseRiderLogService->query()->where('driver_id', $driver->id)->paginate($this->paginationLimit));
        }

        return InHouseRiderLogResource::collection(collect([]));
    }

    public function markAsReceived(Request $request)
    {
        $log = $this->inHouseRiderLogService->findOrFail($request->id);
        $log->update(['received_by' => auth()->guard('admin')->id(), 'log' => $request->log ?? '', 'updated_at' => now()]);
        return response('success');
    }
}
