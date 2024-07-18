<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\AdditionalService;
use App\Http\Controllers\Admin\CommonController;
use App\Http\Requests\Admin\AdditionServiceRequest;
use App\Http\Resources\Admin\AdditionServiceResource;

class AdditionServiceController extends CommonController
{
    /** @var AdditionalService */
    private $additionalService;

    public function __construct(AdditionalService $additionalService)
    {
        parent::__construct();
        $this->additionalService = $additionalService;
    }

    public function index()
    {
        $services = $this->additionalService->query()->orderBy('order')->paginate($this->paginationLimit);

        return AdditionServiceResource::collection($services);
    }

    public function store(AdditionServiceRequest $request)
    {
        // dd($request->all());

        $service = $this->additionalService->store();

        return new AdditionServiceResource($service);

    }

    public function show($serviceId)
    {
        $service = $this->additionalService->findOrFail($serviceId);

        return new AdditionServiceResource($service);
    }

    public function update(AdditionServiceRequest $request, $serviceId)
    {
        $service = $this->additionalService->update($serviceId, $request->except('_method'));


        return new AdditionServiceResource($service);
    }

    public function destroy($serviceId)
    {
        $unit = $this->additionalService->delete($serviceId);

        return response('success');
    }


    public function search(Request $request)
    {
        return AdditionServiceResource::collection($this->additionalService->query()->where('name', 'LIKE', '%'.$request->name.'%')->paginate($this->paginationLimit));
    }

    public function changeOrder()
    {
        foreach (request()->all() as $key => $orderServiceId) {
            $service = $this->additionalService->query()->where('id', $orderServiceId)->first();
            $service->update(['order' => $key + 1]);
        }
    }
}

