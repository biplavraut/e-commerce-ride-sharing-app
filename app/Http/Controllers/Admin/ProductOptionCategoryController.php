<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProductOptionCategoryRequest;
use App\Http\Resources\Admin\ProductOptionCategoryResource;
use App\Services\ProductOptionCategoryService;

class ProductOptionCategoryController extends CommonController
{
    /** @var ProductOptionCategoryService */
    private $productOptionCategoryService;

    public function __construct(ProductOptionCategoryService $productOptionCategoryService)
    {
        parent::__construct();
        $this->productOptionCategoryService = $productOptionCategoryService;
    }

    public function index()
    {
        $options = $this->productOptionCategoryService->query()->orderBy('order')->get();

        return ProductOptionCategoryResource::collection($options);
    }

    public function store(ProductOptionCategoryRequest $request)
    {
        $option = $this->productOptionCategoryService->store($request->validated());

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

        return new ProductOptionCategoryResource($option);
    }

    public function destroy($optionId)
    {
        $option = $this->productOptionCategoryService->delete($optionId);
        return response('success');
    }
}
