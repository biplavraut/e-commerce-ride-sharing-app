<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Services\ProductCategoryService;
use App\Http\Resources\Api\ProductResource;

use App\Http\Resources\Api\CategoryProductPaginateResource;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

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
            $childProductList = null;
            $subchildProductList = null;
            $category = $this->productCategoryService->with(['products', 'children'])->findOrFail($request->categoryId);

            $firstList = $category->products;


            if ($category->isParent()) {
                foreach ($category->allChild as $key => $childCategory) {
                    $childProductList = collect($childProductList)->merge($childCategory->products()->whereHas('vendor')->where('hide', 0)->Where('verified', 1)->latest()->get());

                    if ($childCategory->isParent()) {
                        foreach ($childCategory->allChild as $key => $subChildCategory) {
                            $subchildProductList = collect($subchildProductList)->merge($subChildCategory->products()->whereHas('vendor')->where('hide', 0)->Where('verified', 1)->latest()->get());
                        }
                    }
                }
            }

            $mainProductList = collect($firstList)->merge($childProductList)->merge($subchildProductList);

            $page = $request->page ?? 1;

            $data = array_slice($mainProductList->toArray(), $page == 1 ? 0 : $page * 25, $page == 1 ? 25 : $page * 25);

            $paginator = new Paginator($data, $mainProductList->count(), 25,  $page, [
                'path'  => request()->url(),
                'query' => request()->query(),
            ]);

            return CategoryProductPaginateResource::collection($paginator)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } catch (\Throwable $th) {
            return failureResponse("Product Category Not Found.", 404, 404);
        }
    }
}
