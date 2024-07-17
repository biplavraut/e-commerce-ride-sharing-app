<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\RidingFareService;
use App\Services\RidingPriceSurgeService;
use App\Http\Requests\Admin\RidingFareRequest;
use App\Http\Resources\Admin\RidingFareResource;

class RidingFareController extends CommonController
{
    /** @var RidingFareService */
    private $ridingFareService;

    /** @var RidingPriceSurgeService */
    private $ridingPriceSurgeService;

    public function __construct(RidingFareService $ridingFareService, RidingPriceSurgeService $ridingPriceSurgeService)
    {
        parent::__construct();
        $this->ridingFareService = $ridingFareService;
        $this->ridingPriceSurgeService = $ridingPriceSurgeService;
    }

    public function index()
    {
        $ridingFares = $this->ridingFareService->getForIndex(
            $this->paginationLimit
        );

        return RidingFareResource::collection($ridingFares);
    }

    public function store(RidingFareRequest $request)
    {

        DB::transaction(function () use ($request, &$ridingFare) {
            $data = $request->validated();

            $ridingFare = $this->ridingFareService->store(array_except($data, ['surges']));


            $surges = $this->ridingPriceSurgeService->saveMany($ridingFare, array_only($data, 'surges')['surges'] ?? []);


            return new RidingFareResource($ridingFare);
        });
    }

    public function show($ridingFareId)
    {
        $ridingFare = $this->ridingFareService->findOrFail($ridingFareId);

        return new RidingFareResource($ridingFare);
    }

    public function update(RidingFareRequest $request, $ridingFareId)
    {

        DB::transaction(function () use ($request, &$ridingFare, $ridingFareId) {
            $data = $request->validated();

            $ridingFare = $this->ridingFareService->update($ridingFareId, array_except($data, ['surges']));


            $surges = $this->ridingPriceSurgeService->updateMany($ridingFare, array_only($data, 'surges')['surges'] ?? []);


            return new RidingFareResource($ridingFare);
        });

        return new RidingFareResource($ridingFare);
    }

    public function destroy($ridingFareId)
    {
        $ridingFare = $this->ridingFareService->delete($ridingFareId);

        return response('success');
    }

    public function search(Request $request)
    {
        return RidingFareResource::collection($this->ridingFareService->query()->where('vehicle', 'LIKE', '%' . $request->name . '%')->take(10)->get());
    }

    public function deleteSurge(Request $request)
    {
        $surge = $this->ridingPriceSurgeService->delete($request->surgeId);
        return response('success');
    }
}
