<?php

namespace App\Http\Controllers\Vendor;

use Excel;
use App\Unit;
use App\ProductOption;
use App\ProductUpdate;
use App\Services\TagService;
use function Sodium\compare;
use Illuminate\Http\Request;
use App\Imports\ProductImport;
use App\ProductOptionCategory;
use App\Services\ImageService;
use App\Services\ProductService;
use Illuminate\Support\Facades\DB;
use App\Imports\VendorProductImport;
use App\Services\ProductOptionService;
use App\Services\ProductUpdateService;
use App\Services\ProductCategoryService;
use App\Http\Resources\Admin\TagResource;
use App\Http\Requests\Admin\ProductRequest;
use App\Http\Resources\Admin\ProductResource;

use App\Http\Resources\Admin\ProductCategoryResource;
use App\Http\Resources\Vendor\ProductOptionCategoryResource;
use App\Http\Resources\Vendor\VendorProductCategoryResource;

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

    /** @var ProductUpdateService */
    private $productUpdateService;

    public function __construct(ProductService $productService, ProductUpdateService $productUpdateService, ImageService $imageService, TagService $tagService, ProductCategoryService $productCategoryService, ProductOptionService $productOptionService)
    {
        parent::__construct();
        $this->productService           =   $productService;
        $this->productUpdateService           =   $productUpdateService;
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
        DB::transaction(function () use ($request, &$product, &$productId) {
            $data = $request->validated();


            $product = $this->productService->findOrFail($productId);

            $data['size'] = $data['size'] == null ? [] : $data['size'];
            $data['color'] = $data['color'] == null ? [] : $data['color'];
            $data['hide'] = $data['hide'] == "true" ? 1 : 0;

            $update = [
                'product_id' => $productId,
                'title' => $this->compare($product->title, $data['title']) ? $data['title'] : null,
                'product_category_id' => $this->compare($product->product_category_id, $data['product_category_id']) ? $data['product_category_id'] : null,
                'price' => $this->compare($product->price, $data['price']) ? $data['price'] : null,
                'size' => $this->compare(implode(',', $product->size), implode(',', $data['size'])) ? $data['size'] : 0,
                'color' => $this->compare(implode(',', $product->color), implode(',', $data['color'])) ? $data['color'] : null,
                'opening_stock' => $this->compare($product->opening_stock, $data['opening_stock']) ? $data['opening_stock'] : 0,
                'description' => $this->compare($product->description, $data['description']) ? $data['description'] : null,
                'discount_type' => $this->compare($product->discount_type, $data['discount_type']) ? $data['discount_type'] : null,
                'discount' => $this->compare($product->discount, $data['discount']) ? $data['discount'] : null,
                'batch_no' => $this->compare($product->batch_no, $data['batch_no']) ? $data['batch_no'] : null,
                'expire_date' => $this->compare($product->expire_date, $data['expire_date']) ? $data['expire_date'] : null,
                'hide' => $this->compare($product->hide, $data['hide']) ? $data['hide'] : null,
                'unit' => $this->compare($product->unit, $data['unit']) ? $data['unit'] : null,
                'vat_percentage' => $this->compare($product->vat_percentage, $data['vat_percentage']) ? $data['vat_percentage'] : null,
                'service_charge_percentage' => $this->compare($product->service_charge_percentage, $data['service_charge_percentage']) ? $data['service_charge_percentage'] : null,

            ];

            $isUpdate = $this->productUpdateService->query()->where('product_id', $productId)->first();


            if (
                $this->compare($product->title, $data['title'])
                ||  $this->compare($product->price, $data['price'])
                || $this->compare($product->discount_type, $data['discount_type'])
                || $this->compare($product->discount, $data['discount'])
                || $this->compare($product->vat_percentage, $data['vat_percentage'])
                || $this->compare($product->service_charge_percentage, $data['service_charge_percentage'])
                || $this->compare($product->product_category_id, $data['product_category_id'])
                || $this->compare(implode(',', $product->size), implode(',', $data['size']))
                || $this->compare(implode(',', $product->color), implode(',', $data['color']))
                || $this->compare($product->opening_stock, $data['opening_stock'])
                || $this->compare($product->description, $data['description'])
                || $this->compare($product->hide, $data['hide'])
                || $this->compare($product->unit, $data['unit'])
            ) {
                if ($isUpdate) {
                    $success = $this->productUpdateService->update($isUpdate->id, $update);
                } else {
                    $success = $this->productUpdateService->store($update);
                }
            }


            // $product = $this->productService->update($productId, array_except($data, ['images', 'tags', 'product_option_categories']));

            // foreach ($request->only('images')['images'] ?? [] as $image) {
            //     $this->imageService->store([
            //         'image' => $image,
            //         'model_type' => get_class($product),
            //         'model_id' => $product->id,
            //     ]);
            // }

            // $tagIds = $this->tagService->saveMany(array_only($data, 'tags')['tags'] ?? []);

            // $this->productService->syncTags($product, $tagIds);

            // $serviceId = auth()->user()->services->first()->id;

            // $product->update(['verified' => 0]);

            // $this->productOptionService->saveAndSync($product, array_only($data, 'product_option_categories')['product_option_categories'] ?? [], $serviceId);
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
        Excel::import(new VendorProductImport, request()->file('import_file'));
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
        $products = $this->productService->vendorProductSearch($request->name, $vendorId);
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

    public function compare($a, $b)
    {
        return strcmp($a, $b) == 0 ? false : true;
    }
}
