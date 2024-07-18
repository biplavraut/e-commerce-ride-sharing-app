<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ProductOptionService;
use App\Services\ProductOptionCategoryService;
use App\Http\Resources\Admin\ProductOptionSortResource;
use App\Http\Resources\Admin\ProductOptionCategoryResource;

class ProductOptionSortController extends CommonController
{
    /** @var ProductOptionCategoryService */
    private $productOptionCategoryService;


    /** @var ProductOptionService */
    private $productOptionService;

    public function __construct(ProductOptionCategoryService $productOptionCategoryService, ProductOptionService $productOptionService)
    {
        parent::__construct();
        $this->productOptionCategoryService = $productOptionCategoryService;
        $this->productOptionService = $productOptionService;
    }

    public function index()
    {
        $options = $this->productOptionService->query()->orderBy('order')->get();

        return ProductOptionSortResource::collection($options);
    }

    public function optionCategoryList()
    {
        $categories = $this->productOptionCategoryService->query()->orderBy('order')->get();
        return ProductOptionCategoryResource::collection($categories);
    }

    public function byService(Request $request)
    {
        $category = $this->productOptionCategoryService->findOrFail($request->id);
        $options = $this->productOptionService->query()->where('product_option_category_id', $category->id)->orderBy('order')->get();
        return ProductOptionSortResource::collection($options);
    }

    public function changeOrder()
    {
        $this->productOptionService->changeOrder(request()->all());
    }
}

