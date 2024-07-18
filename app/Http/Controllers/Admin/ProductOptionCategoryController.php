<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Redis;
use App\Services\ProductOptionCategoryService;
use App\Http\Requests\Admin\ProductOptionCategoryRequest;
use App\Http\Resources\Admin\ProductOptionCategoryResource;

class ProductOptionCategoryController extends CommonController
{
    /** @var ProductOptionCategoryService */
    private $productOptionCategoryService;

    /** @var CategoryService */
    private $categoryService;

    public function __construct(ProductOptionCategoryService $productOptionCategoryService, CategoryService $categoryService)
    {
        parent::__construct();
        $this->productOptionCategoryService = $productOptionCategoryService;
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $options = $this->productOptionCategoryService->query()->orderBy('order')->get();

        return ProductOptionCategoryResource::collection($options);
    }

    public function store(ProductOptionCategoryRequest $request)
    {
        $option = $this->productOptionCategoryService->store($request->validated());

        try {
            Redis::set('layoutUpdate_' . $option->service_id, json_encode($option));
        } catch (\Throwable $th) {
            //throw $th;
        }

        return new ProductOptionCategoryResource($option);
    }

    public function show($optionId)
    {
        $option = $this->productOptionCategoryService->findOrFail($optionId);

        return new ProductOptionCategoryResource($option);
    }

    public function changeOrder()
    {
        $this->productOptionCategoryService->changeOrder(request()->all());
    }

    public function update(ProductOptionCategoryRequest $request, $optionId)
    {
        $option = $this->productOptionCategoryService->update($optionId, $request->validated());

        try {
            Redis::set('layoutUpdate_' . $option->service_id, json_encode($option));
        } catch (\Throwable $th) {
            //throw $th;
        }

        return new ProductOptionCategoryResource($option);
    }

    public function byService(Request $request)
    {
        $service = $this->categoryService->findOrFail($request->id);
        $options = $this->productOptionCategoryService->query()->where('service_id', $service->id)->orderBy('order')->get();
        return ProductOptionCategoryResource::collection($options);
    }

    public function destroy($optionId)
    {
        $option = $this->productOptionCategoryService->delete($optionId);
        return response('success');
    }
}
