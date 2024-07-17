<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Product;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CategoryResource;
use App\Http\Resources\Api\ProductOptionCategoryResource;
use App\Http\Resources\Api\ServiceProductOptionCategoryResource;
use App\Http\Resources\Api\ServiceResource;
use App\Http\Resources\Api\ServiceVendorResource;
use App\Http\Resources\Api\SliderResource;
use App\Http\Resources\Api\ProductResource;
use App\Http\Resources\Api\ServiceProductResource;
use App\ProductOption as Option;
use App\ProductOptionCategory;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends CommonController
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
        $services = $this->categoryService->query()->orderBy('order')->paginate(20);
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
            $service = $this->categoryService->findOrFail($id);
            return CategoryResource::collection($service->productCategories()->latest()->get())->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } catch (\Throwable $th) {
            return failureResponse("Service Not Found.", 404, 404);
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
        $serviceId = $request->serviceId;
        $exploreId = $request->explore_id;
        $products = Option::where(["service_id" => $serviceId, "product_option_category_id" => $exploreId])->with(['product'])->paginate(20);
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
