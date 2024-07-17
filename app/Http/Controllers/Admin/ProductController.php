<?php

namespace App\Http\Controllers\Admin;

use Excel;
use App\Unit;
use App\Vendor;
use App\Services\TagService;
use Illuminate\Http\Request;
use App\Imports\ProductImport;
use App\ProductOptionCategory;
use App\Services\ImageService;
use App\Services\ProductService;
use Illuminate\Support\Facades\DB;
use App\Services\ProductOptionService;
use App\Http\Resources\Admin\TagResource;
use App\Http\Requests\Admin\ProductRequest;
use App\Http\Resources\Admin\ProductResource;
use App\Http\Resources\Vendor\ProductOptionCategoryResource;

class ProductController extends CommonController
{
    /** @var ProductService */
    private $productService;

    /** @var ImageService */
    private $imageService;

    /** @var TagService */
    private $tagService;

    /** @var ProductOptionService */
    private $productOptionService;

    public function __construct(ProductService $productService, ImageService $imageService, TagService $tagService,  ProductOptionService $productOptionService)
    {
        parent::__construct();
        $this->productService = $productService;
        $this->imageService = $imageService;
        $this->tagService = $tagService;
        $this->productOptionService     =   $productOptionService;
    }

    public function index()
    {
        return ProductResource::collection($this->productService->query()->where('verified', 0)->oldest()->paginate(10));
    }

    public function store(ProductRequest $request)
    {
        DB::transaction(function () use ($request, &$product) {
            $data = $request->validated();

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

            try {
                $vendor = Vendor::find($request->vendor_id);
                $product->update(['slug' => str_slug($vendor->business_name) . "-" . str_slug($request->title)]);
                $serviceId = $vendor->services->first()->id;

                $this->productOptionService->saveAndSync($product, array_only($data, 'product_option_categories')['product_option_categories'] ?? [], $serviceId);
            } catch (\Throwable $th) {
                //throw $th;
            }

            return new ProductResource($product);
        });
    }

    public function show($productId)
    {
        $product = $this->productService->findOrFail($productId);

        return new ProductResource($product);
    }

    public function update(ProductRequest $request, $productId)
    {
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

            try {
                $serviceId = $product->vendor->services->first()->id;
                $this->productOptionService->saveAndSync($product, array_only($data, 'product_option_categories')['product_option_categories'] ?? [], $serviceId);
            } catch (\Throwable $th) {
                //throw $th;
            }
            return new ProductResource($product);
        });
        return new ProductResource($product);
    }

    public function destroy($productId)
    {
        $product = $this->productService->delete($productId);

        return response('success');
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

    public function getUnits(Request $request)
    {
        return Unit::where('product_category_id', $request->category)->get(['id', 'name']);
    }

    public function getProducts(Request $request)
    {
        $products = $this->productService->getProducts($request->name);

        return ProductResource::collection($products);
    }

    public function verifiedOnly(Request $request)
    {
        $products = $this->productService->query()->where('verified', 1)->paginate($this->paginationLimit);

        return ProductResource::collection($products);
    }

    public function verifyNow(Request $request)
    {
        $product = $this->productService->findOrFail($request->id);
        $product->verify();
        return response('success');
    }

    public function verifyMultiple(Request $request)
    {
        $ids = explode(',', $request->id);
        foreach ($ids as $key => $id) {
            $product = $this->productService->findOrFail($id);
            $product->verify();
        }
        return response('success');
    }

    public function options()
    {
        return ProductOptionCategoryResource::collection(ProductOptionCategory::get());
    }

    public function deleteProductImage(Request $request)
    {
        $response = $this->imageService->deleteProductImage($request->id, auth()->id());
        if ($request) {
            return response('success');
        }
        return response('error');
    }
}
