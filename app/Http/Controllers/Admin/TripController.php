<?php

namespace App\Http\Controllers\Admin;

use App\Driver;
use Illuminate\Http\Request;
use App\Services\TripService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\TripResource;

class TripController extends CommonController
{
    /** @var TripService */
    private $tripService;

    public function __construct(TripService $tripService)
    {
        parent::__construct();
        $this->tripService = $tripService;
    }

    public function index()
    {
        $trips = $this->tripService->getForIndex(
            $this->paginationLimit
        );

        return TripResource::collection($trips);
    }

    public function destroy($tripId)
    {
        $trip = $this->tripService->findOrFail($tripId);
        if ($trip->status != "completed") {
            $trip->driver->status()->update(['status' => 'active']);
            $trip->update(['done' => 1, 'status' => 'cancelled']);
            return response("success");
        } else {
            return failureResponse('Trip status is in complete state.');
        }

        // $trip = $this->tripService->delete($tripId);

        // return response('success');
    }

    public function search(Request $request)
    {
        return TripResource::collection($this->tripService->query()->where('status', 'LIKE', $request->name . '%')->orWhere('id', $request->name)->orWhere('ref_number', $request->name)->take(10)->get());
    }

    public function locateDriver($riderId)
    {
        try {
            $rider = Driver::findOrFail($riderId);
            return response()->json(['lat' => $rider->status->lat, 'long' => $rider->status->long]);
        } catch (\Throwable $th) {
            return response('error');
        }
    }

    public function getAccidentTrips()
    {
        $trips = $this->tripService->query()->where('status', 'accident')->Where('done', 0)->latest()->paginate($this->paginationLimit);

        return TripResource::collection($trips);
    }

    public function getDisputeTrips()
    {
        $trips = $this->tripService->query()
            ->where('done', 0)
            ->Where(function ($query) {
                $query->where('status', 'dispute');
                $query->orWhere('dispute', '!=', null);
            })->latest()
            ->paginate($this->paginationLimit);

        return TripResource::collection($trips);
    }

    public function getPausedTrips()
    {
        $trips = $this->tripService->query()
            ->where('done', 0)
            ->Where(function ($query) {
                $query->where('status', 'paused');
            })->latest()
            ->paginate($this->paginationLimit);

        return TripResource::collection($trips);
    }

    public function getCompletedTrips()
    {
        $trips = $this->tripService->query()
            ->where('status', 'completed')
            // ->whereDate('completed_at', date('Y-m-d'))
            ->latest()->paginate($this->paginationLimit);
        return TripResource::collection($trips);
    }

    public function getScheduleTrips()
    {
        $trips = $this->tripService->query()->where('status', 'scheduled')->whereHas('schedule')->latest()->with('schedule')->paginate($this->paginationLimit);

        return TripResource::collection($trips);
    }

    public function updateTripIssue($tripId)
    {
        $trip = $this->tripService->findOrFail($tripId);
        $trip->driver->status()->update(['status' => 'active']);
        $trip->update(['done' => 1]);

        return response("success");
    }
}
