<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\VendorOptionService;
use App\Services\VendorOptionCategoryService;
use App\Http\Resources\Admin\VendorOptionSortResource;
use App\Http\Resources\Admin\VendorOptionCategoryResource;

class VendorOptionSortController extends CommonController
{
    /** @var VendorOptionCategoryService */
    private $vendorOptionCategoryService;


    /** @var VendorOptionService */
    private $vendorOptionService;

    public function __construct(VendorOptionCategoryService $vendorOptionCategoryService, VendorOptionService $vendorOptionService)
    {
        parent::__construct();
        $this->vendorOptionCategoryService = $vendorOptionCategoryService;
        $this->vendorOptionService = $vendorOptionService;
    }

    public function index()
    {
        $options = $this->vendorOptionService->query()->orderBy('order')->get();

        return VendorOptionSortResource::collection($options);
    }

    public function optionCategoryList()
    {
        $categories = $this->vendorOptionCategoryService->query()->orderBy('order')->get();
        return VendorOptionCategoryResource::collection($categories);
    }

    public function byService(Request $request)
    {
        $category = $this->vendorOptionCategoryService->findOrFail($request->id);
        $options = $this->vendorOptionService->query()->where('vendor_option_category_id', $category->id)->orderBy('order')->get();
        return VendorOptionSortResource::collection($options);
    }

    public function changeOrder()
    {
        $this->vendorOptionService->changeOrder(request()->all());
    }
}
