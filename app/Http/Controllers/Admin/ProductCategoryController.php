<?php

namespace App\Http\Controllers\Admin;

use Excel;
use App\ProductCategory;
use Illuminate\Http\Request;
use App\Services\VendorService;
use Illuminate\Support\Facades\Redis;
use App\Imports\ProductCategoryImport;
use App\Services\ProductCategoryService;
use App\Imports\ProductSubCategoryImport;
use App\Http\Requests\Admin\ProductCategoryRequest;
use App\Http\Resources\Admin\ProductCategoryResource;
use App\Http\Resources\Admin\ProductCategoryLessResource;

class ProductCategoryController extends CommonController
{
    /** @var ProductCategoryService */
    private $productCategoryService;


    /** @var VendorService */
    private $vendorService;

    public function __construct(ProductCategoryService $productCategoryService, VendorService $vendorService)
    {
        parent::__construct();
        $this->productCategoryService = $productCategoryService;
        $this->vendorService = $vendorService;
    }

    public function index()
    {
        $categories = $this->productCategoryService->query()->whereNull('parent_id')->orderBy('order')->get();

        // $categories = $this->productCategoryService->query()->orderBy('name', 'ASC')->get();
        // return ProductCategoryResource::collection($categories);

        return ProductCategoryResource::collection($categories);
    }

    public function getChildList(Request $request)
    {
        $categories = $this->productCategoryService->query()->where('parent_id', $request->root)->orderBy('order')->get();
        return ProductCategoryResource::collection($categories);
    }

    public function store(ProductCategoryRequest $request)
    {
        $productCategory = $this->productCategoryService->store($request->all());

        try {
            Redis::set('productCategoryUpdate_' . $productCategory->originalService()->id, json_encode($productCategory));
        } catch (\Throwable $th) {
            //throw $th;
        }

        return new ProductCategoryResource($productCategory);
    }

    public function show($productCategoryId)
    {
        $category      = $this->productCategoryService->with('children')->find($productCategoryId);
        $subCategories = $category->children->map(function ($child) {
            return [
                'id'    => $child->id,
                'category_id'    => $child->category_id,
                'name'  => $child->name,
                'slug'  => $child->slug,
                'image' => $child->cropImage(200, 200),
                'isParent' => $child->isParentOfAll(),
            ];
        });

        return response()->json(['category' => new ProductCategoryResource($category), 'subCategories' => $subCategories]);
    }

    public function update(ProductCategoryRequest $request, $productCategoryId)
    {
        $productCategory = $this->productCategoryService->update($productCategoryId, $request->all());

        try {
            Redis::set('productCategoryUpdate_' . $productCategory->originalService()->id, json_encode($productCategory));
        } catch (\Throwable $th) {
            //throw $th;
        }

        return response()->json(['message' => 'Success', 'category' => $productCategory]);
    }

    public function destroy($productCategoryId)
    {
        $productCategory = $this->productCategoryService->delete($productCategoryId);

        return response()->json(['message' => 'Successfully Deleted.']);
    }

    public function excelExport()
    {
        // return (new CategoryExport())->download('categories.xlsx');
    }

    public function importCategory(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file',
            'type' => 'required|in:category,subcategory'
        ]);

        if ($request->type == 'category') {
            Excel::import(new ProductCategoryImport, request()->file('import_file'));
        } else {
            Excel::import(new ProductSubCategoryImport, request()->file('import_file'));
        }

        return response('success');
    }

    public function getAll()
    {
        return ProductCategoryResource::collection($this->productCategoryService->getCategories());
    }

    public function getRoot(Request $request)
    {
        if ($request->vendor != null) {
            $categories = array();
            $vendor = $this->vendorService->findOrFail($request->vendor);

            foreach ($vendor->services as $key => $service) {
                $categories = array_merge($categories, $service->productCategories->toArray());
            }

            $categories = collect(array_unique($categories, 0));

            return ProductCategoryLessResource::collection($categories);
        } else {
            return ProductCategoryResource::collection($this->productCategoryService->getRootCategories());
        }
    }

    public function byRoot(Request $request)
    {
        // try {
        // $categories = $this->productCategoryService->query()->where('category_id', $request->id)->orderBy('order')->get();

        //     $subCAts = [];

        //     $childs = [];

        //     foreach ($categories as $key => $category) {
        //         foreach ($category->children as $key => $child) {
        //             $subCAts[] = $child;

        //             foreach ($child->children as $key => $value) {
        //                 $childs[] = $value;
        //             }
        //         }
        //     }

        //     $all = collect($categories)->merge($subCAts)->merge($childs);
        //     $all = $all->unique('id');

        // return ProductCategoryResource::collection($all);
        // } catch (\Throwable $th) {
        //     return response()->json(['data' => []]);
        // }
        $categories = $this->productCategoryService->query()->where('category_id', $request->id)->whereNull('parent_id')->orderBy('order')->get();
        return ProductCategoryResource::collection($categories);
    }

    public function search(Request $request)
    {
        return ProductCategoryResource::collection($this->productCategoryService->query()->where('name', 'LIKE', '%' . $request->name . '%')->take(10)->get());
    }

    public function subcategory(Request $request)
    {
        return ProductCategoryResource::collection($this->productCategoryService->getSubCategory($request->category));
    }

    public function changeOrder()
    {
        foreach (request()->all() as $key => $orderedCategoryId) {
            $category =  ProductCategory::where('id', $orderedCategoryId)->first();
            $category->update(['order' => $key + 1]);

            try {
                Redis::set('productCategoryUpdate_' . $category->originalService()->id, json_encode($category));
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    }
}
