<?php

namespace App\Http\Controllers\Api;

use App\Vendor;
use App\Product;
use Carbon\Carbon;
use App\VendorOption;
use App\ProductOption;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use App\Services\LayoutManagerService;
use App\Http\Resources\Api\SliderResource;
use App\Http\Resources\Api\VendorResource;
use App\Http\Resources\Api\ProductResource;
use App\Http\Resources\Api\DealProductResource;
use Illuminate\Database\Eloquent\Builder;

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

    public function index(Request $request)
    {
        $service      = $this->categoryService->findOrFail($request->serviceId);

        try {
            $layoutManagerUpdate = Redis::get('layoutUpdate_' . $service->id);

            $cachedLayoutManager = Redis::get('layouts_' . $service->id);

            $cachedLocation = Redis::get('layouts_location' . $request->lat . '.' . $request->long);

            if (isset($layoutManagerUpdate) || !isset($cachedLayoutManager) || !isset($cachedLocation)) {
                return $this->setCacheData($service->id, $request->lat, $request->long);
            } else {
                return $this->getCachedData($service->id, $request->lat, $request->long);
            }
        } catch (\Throwable $th) {
            return failureResponse("Service Not Found.", 404, 404);
        }
    }

    public function setCacheData($serviceId, $lat, $long)
    {
        $cachedLayoutManager = Redis::get('layouts_' . $serviceId);

        $cachedLocation = Redis::get('layouts_location' . $lat . '.' . $long);

        if (isset($cachedLayoutManager)) {
            Redis::del('layouts_' . $serviceId);
        }

        if (isset($cachedLocation)) {
            Redis::get('layouts_location' . $lat . '.' . $long);
        } else {
            Redis::set('layouts_location' . $lat . '.' . $long, $serviceId);
        }

        $layoutManagerUpdate = Redis::get('layoutUpdate_' . $serviceId);

        if (isset($layoutManagerUpdate)) {
            Redis::del('layoutUpdate_' . $serviceId);
        }

        $layouts = $this->layoutManagerService->query()->where('service_id', $serviceId)->orderBy('order')->get();

        $data = null;
        $sliders = null;
        $ads = null;

        $dealAvailable = false;

        foreach ($layouts as $key => $layout) {
            if ($layout->model_type == "App\ProductOptionCategory") {
                foreach ($layout->object as $key => $object) {
                    $productIds     =    ProductOption::where('service_id', $serviceId)->Where('product_option_category_id', $object->id)->orderBy('order')->pluck('product_id')->toArray();
                    $ids = implode(',', $productIds);

                    if (count($productIds) > 0) {
                        $products = Product::whereIn('id', $productIds)->where('verified', 1)->Where('hide', 0)->orderByRaw("FIELD(id, $ids)")->limit(10)->get();
                    } else {
                        $products = Product::whereIn('id', $productIds)->where('verified', 1)->Where('hide', 0)->limit(10)->get();
                    }


                    $data[] = [
                        'id'    =>  $object->id,
                        'title' =>  $object->title,
                        'slug'  =>  $object->slug,
                        'layout'  =>  $object->layout,
                        'products'  => ProductResource::collection($products)
                    ];
                }
            }

            if ($layout->model_type == "App\VendorOptionCategory") {
                foreach ($layout->object as $key => $object) {
                    $vendorIds     =    VendorOption::where('service_id', $serviceId)->Where('vendor_option_category_id', $object->id)->orderBy('order')->pluck('vendor_id')->toArray();
                    $orderedIds = implode(',', $vendorIds);

                    if (count($vendorIds) > 0) {
                        $vendors = Vendor::whereIn('id', $vendorIds)
                            ->where('verified', 1)->where("is_hidden", 0)->Where('status', 1)
                            ->whereHas('products', function (Builder $query) {
                                $query->where('verified', 1)->where('hide', 0);
                            })
                            ->orderByRaw("FIELD(id, $orderedIds)")->limit(10)->get()
                            ->filter(function ($model) use ($lat, $long) {
                                return $model->with_in_radius($lat, $long) == true;
                            });
                    } else {
                        $vendors = Vendor::whereIn('id', $vendorIds)
                            ->where('verified', 1)->where("is_hidden", 0)->Where('status', 1)
                            ->whereHas('products', function (Builder $query) {
                                $query->where('verified', 1)->where('hide', 0);
                            })
                            ->limit(10)->get()
                            ->filter(function ($model) use ($lat, $long) {
                                return $model->with_in_radius($lat, $long) == true;
                            });
                    }


                    $data[] = [
                        'id'    =>  $object->id,
                        'title' =>  $object->title,
                        'slug'  =>  $object->slug,
                        'layout'  =>  $object->layout ?? 0,
                        'vendors'  => VendorResource::collection($vendors)
                    ];
                }
            }

            if ($layout->model_type == "App\Slider") {
                foreach ($layout->object as $key => $object) {
                    $sliders[] = [
                        'id' => $object->id,
                        'name' => $object->name,
                        'image' => $object->image,
                        'url' => $object->url ?? ''
                    ];
                }
                $data[] = ["sliders" => $sliders ?? []];
            }

            if ($layout->model_type == "App\Deal") {
                foreach ($layout->object as $key => $object) {
                    $data[] = [
                        'id' => $object->id,
                        'title' => $object->title,
                        'subTitle' => $object->sub_title ?? '',
                        'image' => Str::contains($object->image, 'no-image') ? '' : $object->image,
                        'deal' => true,
                        'from'  => $object->from,
                        'to' => $object->to,
                        'currentTime' => Carbon::parse(now())->format('Y-m-d H:i:s'),
                        'expireIn' => Carbon::parse($object->to)->diffInMilliseconds(now()),
                        'bgColor' => $object->bg_color ?? '',
                        'textColor' => $object->text_color ?? '',
                        'products' => $object->dealproducts ? DealProductResource::collection($object->dealproducts()->orderBy('order')->get()) : []
                    ];
                    $dealAvailable = true;
                }
            }

            if ($layout->model_type == "App\GogoAd") {
                $data[] = ["ads" => $layout->object];
            }
        }

        Redis::set('layouts_' . $serviceId, json_encode($data));

        if ($dealAvailable) {
            $cachedLayoutManager = Redis::get('layouts_' . $serviceId);
            if (isset($cachedLayoutManager)) {
                Redis::del('layouts_' . $serviceId);
            }
        }


        return response()->json([
            "data" => $data ?? [],
            "status" => true,
            "message" => "",
            "statusCode" => 200
        ], 200);
    }

    public function getCachedData($serviceId, $lat, $long)
    {
        $cachedLayoutManager = Redis::get('layouts_' . $serviceId);


        if (isset($cachedLayoutManager)) {
            $data = json_decode($cachedLayoutManager, false);

            return response()->json([
                "data" => $data ?? [],
                "status" => true,
                "message" => "",
                "statusCode" => 200
            ], 200);
        } else {
            $this->setCacheData($serviceId, $lat, $long);
        }
    }
}
