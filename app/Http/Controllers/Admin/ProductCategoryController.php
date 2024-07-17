<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProductCategoryRequest;
use App\Http\Resources\Admin\ProductCategoryResource;
use App\Imports\ProductCategoryImport;
use App\Imports\ProductSubCategoryImport;
use App\ProductCategory;
use App\Services\ProductCategoryService;
use Excel;
use Illuminate\Http\Request;

class ProductCategoryController extends CommonController
{
    /** @var ProductCategoryService */
    private $productCategoryService;

    public function __construct(ProductCategoryService $productCategoryService)
    {
        parent::__construct();
        $this->productCategoryService = $productCategoryService;
    }

    public function index()
    {
        $categories = $this->productCategoryService->query()->orderBy('name', 'ASC')->get();

        return ProductCategoryResource::collection($categories);
    }

    public function store(ProductCategoryRequest $request)
    {
        $productCategory = $this->productCategoryService->store($request->all());

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
            ];
        });

        return response()->json(['category' => $category, 'subCategories' => $subCategories]);
    }

    public function update(ProductCategoryRequest $request, $productCategoryId)
    {
        $productCategory = $this->productCategoryService->update($productCategoryId, $request->all());

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

    public function getRoot()
    {
        return ProductCategoryResource::collection($this->productCategoryService->getRootCategories());
    }

    public function byRoot(Request $request)
    {
        try {
            $categories = $this->productCategoryService->query()->where('category_id', $request->id)->orderBy('name', 'ASC')->get();

            $subCAts = [];

            $childs = [];

            foreach ($categories as $key => $category) {
                foreach ($category->children as $key => $child) {
                    $subCAts[] = $child;

                    foreach ($child->children as $key => $value) {
                        $childs[] = $value;
                    }
                }
            }

            $all = collect($categories)->merge($subCAts)->merge($childs);
            $all = $all->unique('id');

            return ProductCategoryResource::collection($all);
        } catch (\Throwable $th) {
            return response()->json(['data' => []]);
        }
    }

    public function search(Request $request)
    {
        return ProductCategoryResource::collection($this->productCategoryService->query()->where('name', 'LIKE', '%' . $request->name . '%')->take(10)->get());
    }

    public function subcategory(Request $request)
    {
        return ProductCategoryResource::collection($this->productCategoryService->getSubCategory($request->category));
    }
}
