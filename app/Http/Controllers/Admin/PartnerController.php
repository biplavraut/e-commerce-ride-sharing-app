<?php

namespace App\Http\Controllers\Admin;

use App\LaunchpadCategory;
use Illuminate\Http\Request;
use App\Services\PartnerService;
use App\Http\Requests\Admin\PartnerRequest;
use App\Http\Requests\Admin\LaunchpadRequest;
use App\Http\Resources\Admin\PartnerResource;
use App\Http\Resources\Admin\LaunchpadResource;

class PartnerController extends CommonController
{
    /** @var PartnerService */
    private $partnerService;

    public function __construct(PartnerService $partnerService)
    {
        parent::__construct();
        $this->partnerService = $partnerService;
    }

    public function index()
    {
        $partners = $this->partnerService->query()->where('parent_id', NULL)->orderBy('order')->get();

        return PartnerResource::collection($partners);
    }

    public function store(PartnerRequest $request)
    {
        if (isset($request->parent_id) && is_numeric($request->parent_id)) {
            $parent = $this->partnerService->query()->findOrFail($request->parent_id);
            $parent->update(['has_branches' => 1]);
            $partner = $this->partnerService->store($request->validated());
            return new PartnerResource($partner);
        } else {
            $partner = $this->partnerService->store($request->validated());
            return new PartnerResource($partner);
        }
    }

    public function show($partnerId)
    {
        $partner = $this->partnerService->findOrFail($partnerId);

        return new PartnerResource($partner);
    }

    public function update(PartnerRequest $request, $partnerId)
    {
        $partner = $this->partnerService->update($partnerId, $request->validated());

        return new PartnerResource($partner);
    }

    public function destroy($partnerId)
    {
        $partner = $this->partnerService->delete($partnerId);

        return response('success');
    }

    public function changeOrder()
    {
        $this->partnerService->changeOrder(request()->all());
    }

    public function search(Request $request)
    {
        return PartnerResource::collection($this->partnerService->query()->where('name', 'LIKE', $request->name . '%')->paginate($this->paginationLimit));
    }

    public function branches(Request $request)
    {
        $partners = $this->partnerService->query()->where('parent_id', $request->parentId)->orderBy('order')->get();

        return PartnerResource::collection($partners);
    }
}
