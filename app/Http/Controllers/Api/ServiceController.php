<?php

namespace App\Http\Controllers\Api;

use App\Vendor;
use App\Product;
use App\Category;
use App\VendorOption;
use Illuminate\Http\Request;
use App\VendorOptionCategory;
use App\ProductOptionCategory;
use App\Services\VendorService;
use App\ProductOption as Option;
use App\Services\ProductService;
use App\Services\CategoryService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Services\ProductCategoryService;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\SliderResource;
use App\Http\Resources\Api\VendorResource;
use App\Http\Resources\Api\ProductResource;
use App\Http\Resources\Api\ServiceResource;
use App\Http\Resources\Api\CategoryResource;
use App\Http\Resources\Api\ServiceVendorResource;
use App\Http\Resources\Api\VendorServiceResource;
use App\Http\Resources\Api\ServiceProductResource;
use App\Http\Resources\Api\VendorOptionCategoryResource;
use App\Http\Resources\Api\ProductOptionCategoryResource;
use App\Http\Resources\Api\ServiceProductOptionCategoryResource;
use App\Http\Resources\Api\VendorProductCategoryResource;
use App\ProductCategory;
use App\Slider;
use Exception;
use Illuminate\Database\Eloquent\Builder;

class ServiceController extends CommonController
{
    /**
     * @var CategoryService
     */
    private $categoryService;
    /**
     * @var ProductCategoryService
     */
    private $productCategoryService;

    /**
     * @var VendorService
     */
    private $vendorService;

    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(CategoryService $categoryService, ProductCategoryService $productCategoryService, VendorService $vendorService, ProductService $productService)
    {
        parent::__construct();
        $this->categoryService = $categoryService;
        $this->productCategoryService = $productCategoryService;
        $this->vendorService = $vendorService;
        $this->productService = $productService;
    }
    public function list()
    {
        $services = $this->categoryService->query()->where('enabled', 1)->orderBy('order')->get();
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
            $allDefaultProducts = Product::whereIn("product_category_id", $allproductCategory)->whereHas('vendor', function ($query) {
                $query->where('status', 1);
            })->where(['verified' => 1, 'hide' => 0, 'is_default' => 1])->paginate($this->paginationLimit);
            return (ProductResource::collection($allDefaultProducts))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } catch (\Throwable $th) {
            return failureResponse("Service Not Found.", 404, 404);
        }
    }

    public function serviceCategory($id)
    {
        try {
            $finalCategories = [];
            $lastCategory = [];
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
            $categories =  Cache::remember($service->name, $this->expiresAt, function () use ($finalCategories) {
                return collect($finalCategories);
            });
            return CategoryResource::collection($categories)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } catch (\Throwable $th) {
            return failureResponse("Service Not Found.", 404, 404);
        }
    }

    // API new list of category
    public function newServiceCategory($id)
    {
        try {
            $service = $this->categoryService->findOrFail($id); // Main Category Detail 

            if ($service->show_product_category == 1) {


                $productCategories = $service->productCategories; // List of Parents inside this category 
                // return $productCategories;
                // If has product return Product Count, Check for child category
                $finalList =  $this->productCategory($productCategories);
                $secondCat = $finalList['secondCat'];
                $mythirdCat = $finalList['thirdcat'];
                $fullAndFinal = array();
                foreach ($productCategories as $first_parent) {
                    $catDetail = $this->productCategoryService->query()
                        ->select('id', 'parent_id', 'name', 'image', 'batch')
                        ->withCount('products as productCount')
                        ->with(array('children' => function ($query) use ($secondCat, $mythirdCat) {
                            $query->select('id', 'parent_id', 'name', 'image', 'batch')
                                ->withCount('products as productCount')->with(array('children' => function ($querys) use ($mythirdCat) {
                                    $querys->select('id', 'parent_id', 'name', 'image', 'batch')
                                        ->withCount('products as productCount')
                                        ->whereIn('id', $mythirdCat)->get();
                                }))
                                ->whereIn('id', $secondCat)->orderBy('order')->get();
                        }))
                        ->where('id', $first_parent->id)->first();
                    if ($catDetail->productCount == 0 && count($catDetail->children) <= 0) {
                    } else {
                        array_push($fullAndFinal, $catDetail);
                    }
                }

                return response()->json(['data' => $fullAndFinal, 'status' => true, 'message' => "", 'statusCode' => 200], 200);
            } else {
                return response()->json(['data' => [], 'status' => true, 'message' => "", 'statusCode' => 200], 200);
            }
        } catch (\Throwable $th) {
            return failureResponse("Service Not Found.", 404, 404);
        }
    }

    // Product Categories and Sub Categories with at least a product
    public function productCategory($categorylists)
    {
        $catWithProducts = array();
        $childCatWithProducts = array();
        $subChildCatWithProducts = array();
        foreach ($categorylists as $category) {
            if ($category->products()->count() > 0) {
                array_push($catWithProducts, $category->id);
            }
            if ($this->checkChildren($category->id)) {
                foreach ($category->children as $childCategory) {
                    if ($childCategory->products()->count() > 0) {
                        array_push($childCatWithProducts, $childCategory->id);
                    }
                    if ($this->checkChildren($childCategory->id)) {
                        foreach ($childCategory->children as $subChildCategory) {
                            if ($subChildCategory->products()->count() > 0) {
                                array_push($catWithProducts, $category->id);
                                array_push($childCatWithProducts, $childCategory->id);
                                array_push($subChildCatWithProducts, $subChildCategory->id);
                            }
                        }
                    }
                }
            }
        }
        return ['firstCat' => $catWithProducts, 'secondCat' => $childCatWithProducts, 'thirdcat' => $subChildCatWithProducts];
    }

    public function checkChildren($id)
    {
        return $this->productCategoryService->getChildList($id);
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
        $products = Option::where(["service_id" => $serviceId, "product_option_category_id" => $exploreId])
            ->whereHas('product', function ($q) {
                $q->where([['hide', 0], ['verified', 1]])->whereHas('vendor', function ($query) {
                    $query->where('status', 1);
                });
            })
            ->with(['product'])
            ->paginate($this->paginationLimit);
        // return $products;
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
            $productOptionCategories    =   ProductOptionCategory::where('service_id', $id)->whereHas('productOptions')->orderBy('order', 'ASC')->get();
            $request->request->add(['category_id' => $id]);
            return (ProductOptionCategoryResource::collection($productOptionCategories))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } catch (\Throwable $th) {
            return failureResponse("Service Not Found.", 404, 404);
        }
    }

    public function sliders(Request $request, $id)
    {
        if ($request->type == "utility") {
            $sliders = Slider::whereNull('category_id')->inRandomOrder()->get();

            return (SliderResource::collection($sliders))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } else {
            try {
                $service = $this->categoryService->findOrFail($id);
                $sliders = Slider::where(function ($query) use ($service) {
                    $query->where('category_id', $service->id);
                    $query->where('for_layout', 0);
                })->orWhereNull('category_id')->get();
                return (SliderResource::collection($sliders))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
            } catch (\Throwable $th) {
                return failureResponse("Service Not Found.", 404, 404);
            }
        }
    }

    public function serviceVendorOption(Request $request)
    {
        try {
            $service = $this->categoryService->findOrFail($request->serviceId);
            $vendorOptionCategories    =   VendorOptionCategory::where('service_id', $request->serviceId)->whereHas('vendorOptions')->orderBy('order')->get();
            $request->request->add(['category_id' => $request->serviceId]);
            $request->request->add(['lat' => $request->lat]);
            $request->request->add(['long' => $request->long]);
            return (VendorOptionCategoryResource::collection($vendorOptionCategories))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } catch (\Throwable $th) {
            return failureResponse("Service Not Found.", 404, 404);
        }
    }

    public function exploreServiceVendorOption(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'serviceId' => 'required|integer',
                'exploreId' => 'required|integer',
            ]);
            if ($validator->fails()) {
                foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                    $errors = $messages[0];
                }
                return failureResponse($errors, 418, 418);
            }
            $serviceId = $request->serviceId;
            $exploreId = $request->exploreId;
            $vendorIds = VendorOption::where(["service_id" => $serviceId, "vendor_option_category_id" => $exploreId])->orderBy('order')->pluck('vendor_id')->toArray();
            $orderedIds = implode(',', $vendorIds);

            $vendors = Vendor::whereIn('id', $vendorIds)
                ->where('verified', 1)->Where('status', 1)->where('is_hidden', 0)
                ->whereHas('products', function (Builder $query) {
                    $query->where('verified', 1)->where('hide', 0);
                })
                ->orderByRaw("FIELD(id, $orderedIds)")->get()->filter(function ($model) use ($request) {
                    return $model->with_in_radius($request->lat, $request->long) == true;
                });

            return (VendorResource::collection($vendors))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } catch (Exception $e) {
            return $e;
        }
    }


    public function vendorProductsWithService(Request $request)
    {
        try {
            $service = $this->categoryService->findOrFail($request->serviceId);

            $vendor = $this->vendorService->query()->where('status', 1)->findOrFail($request->vendorId);
            if (!$vendor) {
                return failureResponse("This vendor is currently disabled.", 404, 404);
            }

            $vendorServices = $vendor->services()->pluck('category_id');

            if (!in_array($service->id, $vendorServices->toArray())) {
                return failureResponse("This vendor doesn't provide selected service.", 404, 404);
            }

            return ProductResource::collection($vendor->serviceProducts($service->id, $this->paginationLimit))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } catch (\Throwable $th) {
            return failureResponse("Service/Vendor Not Found.", 404, 404);
        }
    }

    public function vendorDetail($vendorId)
    {
        try {
            $vendor = $this->vendorService->findOrFail($vendorId);
            return (new VendorResource($vendor))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } catch (\Throwable $th) {
            return failureResponse("Service/Vendor Not Found.", 404, 404);
        }
    }

    public function vendorCategories(Request $request)
    {
        try {
            $service = $this->categoryService->findOrFail($request->serviceId);
            $vendor = $this->vendorService->findOrFail($request->vendorId);

            $mainCats = [];


            $products = $this->productService->query()->where('vendor_id', $vendor->id)->where('verified', 1)->where('hide', 0)->get()
                ->filter(function ($item) use ($service) {
                    if ($item->service) {
                        return $item->service->id == $service->id;
                    }
                });


            foreach ($products as $key => $product) {
                if ($product->product_category_id && $product->rootCategory()) {
                    $mainCats[] = collect($product->rootCategory());
                }
            }

            $mainCats = collect(array_unique($mainCats))->pluck('id');


            $finals = ProductCategory::whereIn('id', $mainCats)->where(function ($query) {
                $query->whereHas('products');
                $query->orWhereHas('children');
            })->orderBy('order')->get();

            $request->request->add(['vendor_id' => $vendor->id]);

            return VendorProductCategoryResource::collection($finals)->additional(['status' => true, 'statusCode' => 200, 'message' => ''], 200);
        } catch (\Throwable $th) {
            return failureResponse("Service/Vendor Not Found.", 404, 404);
        }
    }

    public function vendorCategoryProducts(Request $request)
    {
        try {
            $finalProducts = [];

            $vendor = $this->vendorService->findOrFail($request->vendorId);
            $productCategory = $this->productCategoryService->findOrFail($request->categoryId);

            if ($productCategory->products()->count() > 0) {
                $finalProducts = array_merge($finalProducts, $productCategory->products()->where('vendor_id', $vendor->id)->pluck('id')->toArray());
            }

            foreach ($productCategory->children as $key => $child) {

                if ($child->products()->count() > 0) {
                    $finalProducts = array_merge($finalProducts, $child->products()->where('vendor_id', $vendor->id)->pluck('id')->toArray());
                }

                if ($child->children()->count() > 0) {
                    foreach ($child->children as $key => $subChild) {
                        if ($subChild->products()->count() > 0) {
                            $finalProducts = array_merge($finalProducts, $subChild->products()->where('vendor_id', $vendor->id)->pluck('id')->toArray());
                        }
                    }
                }
            }

            $products = Product::whereIn('id', $finalProducts)->paginate($this->paginationLimit);

            return (ProductResource::collection($products))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } catch (\Throwable $th) {
            return failureResponse("Service/Vendor/Product Category Not Found.", 404, 404);
        }
    }

    public function serviceFeature(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'serviceId' => 'required|integer',
            'serviceFeature' => 'required|string|in:takeaway,dinein,delivery',
        ]);
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        try {
            $service = Category::findOrFail($request->serviceId);

            if ($request->serviceFeature == "takeaway") {
                $vendors = $service
                    ->registeredVendor()
                    ->where('takeaway', 1)
                    ->where('is_hidden', 0)
                    ->where('status', 1)
                    ->where('verified', 1)
                    ->paginate($this->paginationLimit)->appends($request->query());
            } else {
                $vendors = $service
                    ->registeredVendor()
                    ->where('dine_in', 1)
                    ->where('is_hidden', 0)
                    ->where('status', 1)
                    ->where('verified', 1)
                    ->paginate($this->paginationLimit)->appends($request->query());
            }
        } catch (\Throwable $th) {
            return failureResponse('Service not found.', 404, 404);
        }
        return (VendorResource::collection($vendors))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }
}
