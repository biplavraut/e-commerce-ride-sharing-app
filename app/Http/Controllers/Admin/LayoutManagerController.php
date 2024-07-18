<?php

namespace App\Http\Controllers\Admin;

use App\Deal;
use App\GogoAd;
use App\Slider;
use App\Product;
use Illuminate\Http\Request;
use App\VendorOptionCategory;
use App\ProductOptionCategory;
use App\Services\CategoryService;
use App\Http\Controllers\Controller;
use App\Services\LayoutManagerService;
use App\Http\Resources\Admin\LayoutManagerResource;
use Illuminate\Support\Facades\Redis;

class LayoutManagerController extends CommonController
{
    /** @var LayoutManagerService */
    private $layoutManagerService;


    /** @var CategoryService */
    private $categoryService;

    public function __construct(LayoutManagerService $layoutManagerService, CategoryService $categoryService)
    {
        parent::__construct();
        $this->layoutManagerService = $layoutManagerService;
        $this->categoryService = $categoryService;
    }

    public function index()
    {

        $layouts = $this->layoutManagerService->query()->orderBy('order')->get();

        return LayoutManagerResource::collection($layouts);
    }

    public function store(Request $request)
    {
        $layout = $this->layoutManagerService->store($request->except('_token'));

        Redis::set('layoutUpdate_' . $layout->service_id, json_encode($layout));

        return new LayoutManagerResource($layout);
    }

    public function show($layoutId)
    {
        $layout = $this->layoutManagerService->findOrFail($layoutId);

        return new LayoutManagerResource($layout);
    }

    public function update(Request $request, $layoutId)
    {
        $layout = $this->layoutManagerService->update($layoutId, $request->except('_method'));

        Redis::set('layoutUpdate_' . $layout->service_id, json_encode($layout));

        return new LayoutManagerResource($layout);
    }

    public function destroy($layoutId)
    {
        $layout = $this->layoutManagerService->delete($layoutId);

        Redis::del('layouts_' . $layout->service_id);
        Redis::set('layoutUpdate_' . $layout->service_id, json_encode($layout));


        return response('success');
    }

    public function byService(Request $request)
    {
        $service = $this->categoryService->findOrFail($request->id);
        $layouts = $this->layoutManagerService->query()->where('service_id', $service->id)->orderBy('order')->get();
        return LayoutManagerResource::collection($layouts);
    }

    public function changeOrder()
    {
        $this->layoutManagerService->changeOrder(request()->all());
    }

    public function modelList()
    {
        $slider = get_class(new Slider());
        $productOptionCategory = get_class(new ProductOptionCategory());
        $vendorOptionCategory = get_class(new VendorOptionCategory());
        $deal = get_class(new Deal());
        $ad = get_class(new GogoAd());

        return response()->json([
            "data" => [
                [
                    "name" => "Service Slider",
                    "value" => $slider
                ],
                [
                    "name" => "Product Option Category",
                    "value" => $productOptionCategory
                ],
                [
                    "name" => "Vendor Option Category",
                    "value" => $vendorOptionCategory
                ],
                [
                    "name" => "Deals",
                    "value" => $deal
                ],
                [
                    "name" => "gogoAds",
                    "value" => $ad
                ]
            ]
        ]);
    }

    public function modelIdList(Request $request)
    {
        $objects = null;
        $model = $request->model;
        if ($model == "App\Slider") {
            if ($request->service) {
                $objects =  $model::where('category_id', $request->service)->where('for_layout', 1)->join('categories', 'sliders.category_id', 'categories.id')->select(['sliders.id', 'sliders.name', 'categories.name as service'])->get();
            } else {
                $objects =  $model::where('for_layout', 1)->join('categories', 'sliders.category_id', 'categories.id')->select(['sliders.id', 'sliders.name', 'categories.name as service'])->get();
            }
        } elseif ($model == "App\ProductOptionCategory") {
            if ($request->service) {
                $objects =  $model::where('service_id', $request->service)->join('categories', 'product_option_categories.service_id', 'categories.id')->select(['product_option_categories.id', 'product_option_categories.title as name', 'categories.name as service'])->get();
            } else {
                $objects =  $model::join('categories', 'product_option_categories.service_id', 'categories.id')->select(['product_option_categories.id', 'product_option_categories.title as name', 'categories.name as service'])->get();
            }
        } elseif ($model == "App\VendorOptionCategory") {
            if ($request->service) {
                $objects =  $model::where('service_id', $request->service)->join('categories', 'vendor_option_categories.service_id', 'categories.id')->select(['vendor_option_categories.id', 'vendor_option_categories.title as name', 'categories.name as service'])->get();
            } else {
                $objects =  $model::join('categories', 'vendor_option_categories.service_id', 'categories.id')->select(['vendor_option_categories.id', 'vendor_option_categories.title as name', 'categories.name as service'])->get();
            }
        } elseif ($model == "App\Deal") {
            if ($request->service) {
                $objects =  $model::where('category_id', $request->service)->where('status', 1)->whereDate('from', '<=', date('Y-m-d H:i:s'))
                    ->whereDate('to', '>', date('Y-m-d H:i:s'))->join('categories', 'deals.category_id', 'categories.id')->select(['deals.id', 'deals.title as name', 'categories.name as service'])->get();
            } else {
                $objects =  $model::where('status', 1)->whereDate('from', '<=', date('Y-m-d H:i:s'))
                    ->whereDate('to', '>', date('Y-m-d H:i:s'))->join('categories', 'deals.category_id', 'categories.id')->select(['deals.id', 'deals.title as name', 'categories.name as service'])->get();
            }
        } elseif ($model == "App\GogoAd") {
            if ($request->service) {
                $objects =  $model::where('service_id', $request->service)->select(['gogo_ads.id', 'gogo_ads.title as name', 'gogo_ads.hide'])->get();
            } else {
                $objects =  $model::where('hide', 0)->select(['gogo_ads.id', 'gogo_ads.title as name'])->get();
            }
        }

        return $objects;
    }
}
