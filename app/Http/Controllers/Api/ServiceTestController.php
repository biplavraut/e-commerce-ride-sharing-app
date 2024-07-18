<?php

namespace App\Http\Controllers\Api;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use App\ProductOptionCategory;
use App\ProductOption as Option;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\SliderResource;
use App\Http\Resources\Api\ProductResource;
use App\Http\Resources\Api\ServiceResource;
use App\Http\Resources\Api\CategoryResource;
use App\Http\Resources\Api\ServiceVendorResource;
use App\Http\Resources\Api\ServiceProductResource;
use App\Http\Resources\Api\ProductOptionCategoryResource;
use App\Http\Resources\Api\Redis\RedisCategoryResource;

class ServiceTestController extends CommonController
{
    /**
     * @var CategoryService
     */
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public function list()
    {
        $services = $this->categoryService->query()->where('enabled', 1)->orderBy('order')->paginate(20);
        return ServiceResource::collection($services)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function category(Request $request)
    {
        if ($request->serviceId) {
            return $this->serviceCategory($request->serviceId);
        }

        if ($request->vendorServiceId) {
            return $this->serviceVendor($request->vendorServiceId);
        }
    }

    public function serviceProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'serviceId' => 'required|integer',
        ]);
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }
        try {
            $allproductCategory = Category::findOrFail($request->serviceId)->productCategories->pluck("id");
            $allDefaultProducts = Product::whereIn("product_category_id", $allproductCategory)->where(['verified' => 1, 'hide' => 0, 'is_default' => 1])->paginate(20);
            return (ProductResource::collection($allDefaultProducts))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } catch (\Throwable $th) {
            return failureResponse("Service Not Found.", 404, 404);
        }
    }

    public function serviceCategory($id)
    {
        try {
            $isCategoryUpdated = Redis::get('productCategoryUpdate_' . $id);

            $cachedCategory = Redis::get('categories___' . $id);

            if (isset($isCategoryUpdated) || !isset($cachedCategory)) {
                return $this->setCacheData($id);
            } else {
                return $this->getCachedData($id);
            }
        } catch (\Throwable $th) {
            return failureResponse("Service Not Found.", 404, 404);
        }
    }

    public function setCacheData($id)
    {

        $cachedCategory = Redis::get('categories___' . $id);

        if (isset($cachedCategory)) {
            Redis::del('categories___' . $id);
        }

        $isCategoryUpdated = Redis::get('productCategoryUpdate_' . $id);
        if (isset($isCategoryUpdated)) {
            Redis::del('productCategoryUpdate_' . $id);
        }

        $finalCategories = [];
        $service = $this->categoryService->findOrFail($id);

        $productCategories = $service->productCategories;

        foreach ($productCategories as $firstCategory) {
            if ($firstCategory->products()->count() > 0) {
                $finalCategories[] = $firstCategory;
            } else {
                foreach ($firstCategory->children as $secondCategory) {
                    if ($secondCategory->products()->count() > 0) {
                        $finalCategories[]  = $firstCategory;
                    } else {
                        foreach ($secondCategory->children as $lastCategory) {
                            if ($lastCategory->products()->count() > 0) {

                                $finalCategories[] = $firstCategory;
                            }
                        }
                    }
                }
            }
        }

        $finalCategories = array_unique($finalCategories);

        $data = CategoryResource::collection(collect($finalCategories))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);

        Redis::set('categories___' . $id, json_encode($data));

        return $data;
    }

    public function getCachedData($id)
    {
        $cachedCategory = Redis::get('categories___' . $id);

        if (isset($cachedCategory)) {
            $data = json_decode($cachedCategory, FALSE);
            return  RedisCategoryResource::collection(collect($data))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } else {
            $this->setCacheData($id);
        }
    }


    public function exploreServiceProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'serviceId' => 'required|integer',
            'explore_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }
        $products = Option::where(["service_id" => $request->serviceId, "product_option_category_id" => $request->exploreId])->with(['product'])->paginate(20);
        return (ServiceProductResource::collection($products))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function serviceVendor($id)
    {
        try {
            $service = $this->categoryService->with('children')->findOrFail($id);
            return ServiceVendorResource::collection($service->vendors()->with(['products'])->latest()->get())->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } catch (\Throwable $th) {
            return failureResponse("Service Not Found.", 404, 404);
        }
    }

    public function explore(Request $request, $id)
    {
        try {

            $productOptionCategories    =   ProductOptionCategory::whereHas('productOptions')->OrderBy('order', 'ASC')->get();
            $request->request->add(['category_id' => $id]);
            return (ProductOptionCategoryResource::collection($productOptionCategories))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } catch (\Throwable $th) {
            return failureResponse("Service Not Found.", 404, 404);
        }
    }

    public function sliders(Request $request, $id)
    {
        try {
            $service = $this->categoryService->findOrFail($id);
            return (SliderResource::collection($service->sliders))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } catch (\Throwable $th) {
            return failureResponse("Service Not Found.", 404, 404);
        }
    }
}
