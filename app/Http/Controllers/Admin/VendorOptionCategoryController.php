<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Redis;
use App\Services\VendorOptionCategoryService;
use App\Http\Requests\Admin\VendorOptionCategoryRequest;
use App\Http\Resources\Admin\VendorOptionCategoryResource;

class VendorOptionCategoryController extends CommonController
{
    /** @var VendorOptionCategoryService */
    private $vendorOptionCategoryService;

    /** @var CategoryService */
    private $categoryService;

    public function __construct(VendorOptionCategoryService $vendorOptionCategoryService, CategoryService $categoryService)
    {
        parent::__construct();
        $this->vendorOptionCategoryService = $vendorOptionCategoryService;
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $options = $this->vendorOptionCategoryService->query()->orderBy('order')->get();

        return VendorOptionCategoryResource::collection($options);
    }

    public function store(VendorOptionCategoryRequest $request)
    {
        $option = $this->vendorOptionCategoryService->store($request->validated());

        try {
            Redis::set('layoutUpdate_' . $option->service_id, json_encode($option));
        } catch (\Throwable $th) {
            //throw $th;
        }

        return new VendorOptionCategoryResource($option);
    }

    public function show($optionId)
    {
        $option = $this->vendorOptionCategoryService->findOrFail($optionId);

        return new VendorOptionCategoryResource($option);
    }

    public function changeOrder()
    {
        $this->vendorOptionCategoryService->changeOrder(request()->all());
    }

    public function update(VendorOptionCategoryRequest $request, $optionId)
    {
        $option = $this->vendorOptionCategoryService->update($optionId, $request->validated());

        try {
            Redis::set('layoutUpdate_' . $option->service_id, json_encode($option));
        } catch (\Throwable $th) {
            //throw $th;
        }

        return new VendorOptionCategoryResource($option);
    }

    public function byService(Request $request)
    {
        $service = $this->categoryService->findOrFail($request->id);
        $options = $this->vendorOptionCategoryService->query()->where('service_id', $service->id)->orderBy('order')->get();
        return VendorOptionCategoryResource::collection($options);
    }

    public function destroy($optionId)
    {
        $option = $this->vendorOptionCategoryService->delete($optionId);
        return response('success');
    }
}
