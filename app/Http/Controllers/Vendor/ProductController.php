<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Requests\Admin\ProductRequest;
use App\Http\Resources\Admin\ProductCategoryResource;
use App\Http\Resources\Admin\ProductResource;
use App\Http\Resources\Admin\TagResource;
use App\Http\Resources\Vendor\ProductOptionCategoryResource;
use App\Http\Resources\Vendor\VendorProductCategoryResource;
use App\Imports\ProductImport;
use App\ProductOption;
use App\ProductOptionCategory;
use App\Services\ImageService;
use App\Services\ProductCategoryService;
use App\Services\ProductOptionService;
use App\Services\ProductService;
use App\Services\TagService;
use App\Unit;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends CommonController
{
    /** @var ProductService */
    private $productService;

    /** @var ProductCategoryService */
    private $productCategoryService;

    /** @var ImageService */
    private $imageService;

    /** @var TagService */
    private $tagService;

    /** @var ProductOptionService */
    private $productOptionService;

    public function __construct(ProductService $productService, ImageService $imageService, TagService $tagService, ProductCategoryService $productCategoryService, ProductOptionService $productOptionService)
    {
        parent::__construct();
        $this->productService           =   $productService;
        $this->imageService             =   $imageService;
        $this->tagService               =   $tagService;
        $this->productCategoryService   =   $productCategoryService;
        $this->productOptionService     =   $productOptionService;
    }

    public function index()
    {
        return ProductResource::collection($this->productService->query()->where('vendor_id', auth()->id())->orderBy('updated_at', 'DESC')->paginate($this->paginationLimit));
    }

    public function store(ProductRequest $request)
    {
        DB::transaction(function () use ($request, &$product) {
            $data = $request->validated();
            $data['vendor_id'] = auth()->id();

            $product = $this->productService->store(array_except($data, ['images', 'tags', 'product_option_categories']));

            foreach ($request->only('images')['images'] ?? [] as $image) {
                $this->imageService->store([
                    'image' => $image,
                    'model_type' => get_class($product),
                    'model_id' => $product->id,
                ]);
            }
            $tagIds = $this->tagService->saveMany(array_only($data, 'tags')['tags'] ?? []);
            $this->productService->syncTags($product, $tagIds);

            $serviceId = auth()->user()->services->first()->id;

            $this->productOptionService->saveAndSync($product, array_only($data, 'product_option_categories')['product_option_categories'] ?? [], $serviceId);
            return new ProductResource($product);
        });
    }

    public function show($productId)
    {
        $product = $this->productService->query()->where('id', $productId)->Where('vendor_id', auth()->id())->first();
        return new ProductResource($product);
    }

    public function update(ProductRequest $request, $productId)
    {
        // dd($request->all());
        // $product = $this->productService->update($productId, $request->validated());

        // return new ProductResource($product);

        DB::transaction(function () use ($request, &$product, &$productId) {
            $data = $request->validated();

            $product = $this->productService->update($productId, array_except($data, ['images', 'tags', 'product_option_categories']));

            foreach ($request->only('images')['images'] ?? [] as $image) {
                $this->imageService->store([
                    'image' => $image,
                    'model_type' => get_class($product),
                    'model_id' => $product->id,
                ]);
            }

            $tagIds = $this->tagService->saveMany(array_only($data, 'tags')['tags'] ?? []);

            $this->productService->syncTags($product, $tagIds);

            $serviceId = auth()->user()->services->first()->id;

            $this->productOptionService->saveAndSync($product, array_only($data, 'product_option_categories')['product_option_categories'] ?? [], $serviceId);
        });
        return new ProductResource($product);
    }

    public function destroy($productId)
    {
        $product = $this->productService->delete($productId);

        return response('success');
    }
    public function deleteImage(Request $request)
    {
        $response = $this->imageService->deleteImage($request->id, auth()->id());
        if ($request) {
            return response('success');
        }
        return response('error');
    }

    public function excelExport()
    {
        // return (new ProductExport())->download('products.xlsx');
    }

    public function excelImport(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file',
        ]);
        Excel::import(new ProductImport, request()->file('import_file'));
        return response()->json(['message' => 'success']);
    }

    public function getTags(Request $request)
    {
        $tags = $this->tagService->query()->where('name', 'LIKE', '%' . $request->name . '%')->limit(15)->get();

        return TagResource::collection($tags);
    }

    public function subcategory(Request $request)
    {
        return ProductCategoryResource::collection($this->productCategoryService->getSubCategory($request->category));
    }

    public function getUnits(Request $request)
    {
        return Unit::where('product_category_id', $request->category)->get(['id', 'name']);
    }

    public function getProducts(Request $request)
    {
        $vendorId = auth()->id();
        $products = $this->productService->vendorProductSearch($request->name,$vendorId);
        return ProductResource::collection($products);
    }

    public function getAllProductCategory()
    {
        $categories = [];
        foreach (auth()->user()->services as $key => $service) {
            $categories = collect($categories)->merge($service->productCategories);
        }
        return VendorProductCategoryResource::collection($categories);
    }

    public function options(Request $request)
    {
        if ($request->id) {
            $productOptions = ProductOption::where('product_id', $request->id)->with('productOptionCategory')->get();
            $optionCategories = [];
            foreach ($productOptions as $key => $option) {
                $optionCategories[] = $option->productOptionCategory;
            }
            $cat = collect($optionCategories);
            return ProductOptionCategoryResource::collection($cat);
        }
    }

    // public function isHidden(Request $request)
    // {
    //     $hiddenCategory = ProductOptionCategory::where('slug', 'hide')->first();
    //     $option = ProductOption::where('product_id', $request->id)->Where('product_option_category_id', $hiddenCategory->id)->first();
    //     return $option ? "true" : "false";
    // }
}
