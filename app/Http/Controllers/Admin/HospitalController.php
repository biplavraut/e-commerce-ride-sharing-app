<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HospitalRequest;
use App\Http\Resources\Admin\HospitalResource;
use App\Services\HospitalService;
use Illuminate\Http\Request;

class HospitalController extends CommonController
{
    //
    /** @var HospitalService */
    private $hospitalService;

    public function __construct(HospitalService $hospitalService)
    {
        parent::__construct();
        $this->hospitalService = $hospitalService;
    }

    public function index()
    {
        $hospitals = $this->hospitalService->getForIndex(
            $this->paginationLimit
        );

        return HospitalResource::collection($hospitals);
    }

    public function list()
    {
        $hospitals = $this->hospitalService->query()->orderBy('title')->get();

        return HospitalResource::collection($hospitals);
    }

    public function store(HospitalRequest $request)
    {
        $hospital = $this->hospitalService->store($request->validated());

        return new HospitalResource($hospital);
    }

    public function show($hospitalId)
    {
        $hospital = $this->hospitalService->findOrFail($hospitalId);

        return new HospitalResource($hospital);
    }

    public function update(HospitalRequest $request, $hospitalId)
    {
        $hospital = $this->hospitalService->update($hospitalId, $request->validated());

        return new HospitalResource($hospital);
    }

    public function destroy($hospitalId)
    {
        $hospital = $this->hospitalService->delete($hospitalId);

        return response('success');
    }

    public function search(Request $request)
    {
        return HospitalResource::collection($this->hospitalService->query()->where('location', 'LIKE', $request->name . '%')->paginate($this->paginationLimit));
    }
}
