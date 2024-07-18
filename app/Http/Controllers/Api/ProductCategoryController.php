<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Redis;
use App\Services\ProductCategoryService;
use App\Http\Resources\Api\ProductResource;
use App\Http\Resources\Api\CategoryProductPaginateResource;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use App\Http\Resources\Api\Redis\RedisCategoryProductResource;

class ProductCategoryController extends CommonController
{
    /**
     * @var ProductCategoryService
     */
    private $productCategoryService;

    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(ProductCategoryService $productCategoryService, ProductService $productService)
    {
        $this->productCategoryService = $productCategoryService;
        $this->productService = $productService;
    }

    public function categoryProducts(Request $request)
    {
        try {
            $childProductList = [];
            $subchildProductList = [];
            $category = $this->productCategoryService->with(['products', 'children'])->findOrFail($request->categoryId);


            $firstList = $category->products;


            if ($category->isParent() && $category->children()->count() > 0) {

                foreach ($category->children as $key => $childCategory) {
                    if ($childCategory->products()->count() > 0) {
                        $childProductList = collect($childProductList)->merge($childCategory->products()->latest()->get());
                    }

                    if ($childCategory->isParent() && $childCategory->children()->count() > 0) {
                        foreach ($childCategory->children as $key => $subChildCategory) {
                            if ($subChildCategory->products()->count() > 0) {
                                $subchildProductList = collect($subchildProductList)->merge($subChildCategory->products()->latest()->get());
                            }
                        }
                    }
                }
            }



            $mainProductList = collect($firstList)->merge($childProductList)->merge($subchildProductList);

            $finalData =  Cache::remember($category->name, $this->expiresAt, function () use ($mainProductList) {
                return $mainProductList;
            });

            $page = $request->page ?? 1;

            // $data = array_slice($finalData->toArray(), $page == 1 ? 0 : $page * $this->paginationLimit, $page == 1 ? $this->paginationLimit : $page * $this->paginationLimit);
            $data = array_slice($finalData->toArray(), $page == 1 ? 0 : ($page * $this->paginationLimit) - $this->paginationLimit, $page == 1 ? $this->paginationLimit :  $this->paginationLimit * $page);


            $paginator = new Paginator($data, $finalData->count(), $this->paginationLimit,  $page, [
                'path'  => request()->url(),
                'query' => request()->query(),
            ]);


            return CategoryProductPaginateResource::collection($paginator)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } catch (\Throwable $th) {
            return failureResponse("Product Category Not Found.", 404, 404);
        }
    }

    public function categoryTestProducts(Request $request)
    {
        try {
            $isUpdated = Redis::get('productUpdate_' . $request->categoryId);

            $page = $request->page ?? 1;

            $cachedProducts = Redis::get('products___' . $request->categoryId . '-' . $page);

            if (isset($isUpdated) || !isset($cachedProducts)) {

                return $this->setCacheData($request->categoryId, $request);
            } else {
                return $this->getCachedData($request->categoryId, $request);
            }
        } catch (\Throwable $th) {
            return $th;
            return failureResponse("Product Category Not Found.", 404, 404);
        }
    }

    public function setCacheData($id, $request)
    {
        $cachedProducts = Redis::get('products___' . $id . '-' . $request->page ?? 1);


        if (isset($cachedProducts)) {
            Redis::del('products___' . $id . '-' . $request->page ?? 1);
        }

        $isUpdated = Redis::get('productUpdate_' . $id);
        if (isset($isUpdated)) {
            Redis::del('productUpdate_' . $id);
        }

        $childProductList = [];
        $subchildProductList = [];
        $category = $this->productCategoryService->with(['products', 'children'])->findOrFail($request->categoryId);

        $firstList = $category->products()->orderBy('title')->get();


        if ($category->isParent() || $category->children()->count() > 0) {

            foreach ($category->children()->orderBy('order')->get() as $key => $childCategory) {
                if ($childCategory->products()->count() > 0) {
                    $childProductList = collect($childProductList)->merge($childCategory->products()->orderBy('title')->get());
                }

                if ($childCategory->isParent() || $childCategory->children()->count() > 0) {
                    foreach ($childCategory->children()->orderBy('order')->get() as $key => $subChildCategory) {
                        if ($subChildCategory->products()->count() > 0) {
                            $subchildProductList = collect($subchildProductList)->merge($subChildCategory->products()->orderBy('title')->get());
                        }
                    }
                }
            }
        }


        $mainProductList = collect($firstList)->merge($childProductList)->merge($subchildProductList);


        $page = $request->page ?? 1;

        $secondSlice = $page == 1 ? 0 : ($page * $this->paginationLimit) - ($this->paginationLimit + 1);


        $data = array_slice($mainProductList->toArray(), $secondSlice, $this->paginationLimit);



        $paginator = new Paginator($data, $mainProductList->count(), $this->paginationLimit,  $page, [
            'path'  => request()->url(),
            'query' => request()->query(),
        ]);

        $myData = CategoryProductPaginateResource::collection($paginator)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);

        Redis::set('products___' . $id . '-' . $page, $myData->toJson());

        return $myData;
    }

    public function getCachedData($id, $request)
    {
        $page = $request->page ?? 1;

        $cachedProducts = Redis::get('products___' . $id . '-' . $page);



        if (isset($cachedProducts)) {
            $data = json_decode($cachedProducts, FALSE);

            return response()->json(
                [
                    "data" => RedisCategoryProductResource::collection(collect($data->data)),
                    "links" => [
                        "first" => $data->first_page_url,
                        "last" => $data->last_page_url,
                        "prev" => $data->prev_page_url,
                        "next" => $data->next_page_url
                    ],
                    "meta" => [
                        "current_page" => $data->current_page,
                        "from" => $data->from,
                        "last_page" => $data->last_page,
                        "path" => $data->path,
                        "per_page" => $data->per_page,
                        "to" => $data->to,
                        "total" => $data->total
                    ],
                    'status' => true,
                    'message' => "",
                    'statusCode' => 200
                ],
                200
            );
        } else {
            $this->setCacheData($id, $request);
        }
    }
}
