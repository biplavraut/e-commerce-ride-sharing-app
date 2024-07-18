<?php

namespace App\Http\Controllers\Admin;

use Excel;
use Illuminate\Http\Request;
use App\Services\DeliveryJunctionService;
use App\Http\Requests\Admin\DeliveryJunctionRequest;
use App\Http\Resources\Admin\DeliveryJunctionResource;

class DeliveryJunctionController extends CommonController
{
    /** @var DeliveryJunctionService */
    private $deliveryJunctionService;

    public function __construct(DeliveryJunctionService $deliveryJunctionService)
    {
        parent::__construct();
        $this->deliveryJunctionService = $deliveryJunctionService;
    }

    public function index()
    {
        $places = $this->deliveryJunctionService->getForIndex(
            $this->paginationLimit
        );

        return DeliveryJunctionResource::collection($places);
    }

    public function list()
    {
        $places = $this->deliveryJunctionService->query()->orderBy('location')->get();

        return DeliveryJunctionResource::collection($places);
    }

    public function store(DeliveryJunctionRequest $request)
    {
        $place = $this->deliveryJunctionService->store($request->validated());

        return new DeliveryJunctionResource($place);
    }

    public function show($placeId)
    {
        $place = $this->deliveryJunctionService->findOrFail($placeId);

        return new DeliveryJunctionResource($place);
    }

    public function update(DeliveryJunctionRequest $request, $placeId)
    {
        $place = $this->deliveryJunctionService->update($placeId, $request->validated());

        return new DeliveryJunctionResource($place);
    }

    public function destroy($placeId)
    {
        $place = $this->deliveryJunctionService->delete($placeId);

        return response('success');
    }

    public function search(Request $request)
    {
        return DeliveryJunctionResource::collection($this->deliveryJunctionService->query()->where('location', 'LIKE', $request->name . '%')->paginate($this->paginationLimit));
    }
}
