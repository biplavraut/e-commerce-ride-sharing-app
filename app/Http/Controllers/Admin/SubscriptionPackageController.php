<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubscriptionPackageRequest;
use App\Http\Resources\Admin\SubscriptionPackageResource;
use App\Services\SubscriptionPackageService;

class SubscriptionPackageController extends CommonController
{
    /** @var SubscriptionPackageService */
    private $subscriptionPackageService;

    public function __construct(SubscriptionPackageService $subscriptionPackageService)
    {
        parent::__construct();
        $this->subscriptionPackageService = $subscriptionPackageService;
    }

    public function index()
    {
        $packages = $this->subscriptionPackageService->getForIndex(
            $this->paginationLimit
        );

        return SubscriptionPackageResource::collection($packages);
    }

    public function store(SubscriptionPackageRequest $request)
    {
        $package = $this->subscriptionPackageService->store($request->validated());

        return new SubscriptionPackageResource($package);
    }

    public function show($packageId)
    {
        $package = $this->subscriptionPackageService->findOrFail($packageId);

        return new SubscriptionPackageResource($package);
    }

    public function update(SubscriptionPackageRequest $request, $packageId)
    {
        $package = $this->subscriptionPackageService->update($packageId, $request->validated());

        return new SubscriptionPackageResource($package);
    }

    public function destroy($packageId)
    {
        $package = $this->subscriptionPackageService->delete($packageId);

        return response('success');
    }

    public function search(Request $request)
    {
        return SubscriptionPackageResource::collection($this->subscriptionPackageService->query()->where('name', 'LIKE', '%' . $request->name . '%')->paginate($this->paginationLimit));
    }
}
