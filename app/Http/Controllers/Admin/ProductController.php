
<?php

namespace App\Http\Controllers\Admin;

use Excel;
use App\Unit;
use App\Vendor;
use App\ProductCategory;
use Illuminate\Support\Str;
use App\Services\TagService;
use Illuminate\Http\Request;
use App\Imports\ProductImport;
use App\ProductOptionCategory;
use App\Services\ImageService;
use App\Services\VendorService;
use App\Services\ProductService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Services\ProductOptionService;
use App\Services\ProductUpdateService;
use App\Http\Resources\Admin\TagResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\ProductRequest;
use App\Http\Resources\Admin\ProductResource;
use App\Http\Resources\Admin\ProductUpdateResource;
use App\Http\Resources\Vendor\ProductOptionCategoryResource;

class ProductController extends CommonController
{
    /** @var ProductService */
    private $productService;

    /** @var ProductUpdateService */
    private $productUpdateService;

    /** @var ImageService */
    private $imageService;

    /** @var TagService */
    private $tagService;

    /** @var VendorService */
    private $vendorService;

    /** @var ProductOptionService */
    private $productOptionService;

    public function __construct(ProductService $productService, ProductUpdateService $productUpdateService,  ImageService $imageService, TagService $tagService, VendorService $vendorService,  ProductOptionService $productOptionService)
    {
        parent::__construct();
        $this->productService = $productService;
        $this->productUpdateService = $productUpdateService;
        $this->imageService = $imageService;
        $this->tagService = $tagService;
        $this->productOptionService     =   $productOptionService;
        $this->vendorService     =   $vendorService;
    }

    public function index()
    {
        return ProductResource::collection($this->productService->query()->where('verified', 0)->oldest()->paginate($this->paginationLimit));
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
                $serviceId = $product->rootCategory()->service->id;

                $this->productOptionService->saveAndSync($product, array_only($data, 'product_option_categories')['product_option_categories'] ?? [], $serviceId);
            } catch (\Throwable $th) {
                //throw $th;
            }

            try {
                Redis::set('productUpdate_' . $product->rootCategory()->id, json_encode($product));
                Redis::set('layoutUpdate_' . $product->rootCategory()->id, json_encode($product));
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
                $serviceId = $product->rootCategory()->service->id;
                $this->productOptionService->saveAndSync($product, array_only($data, 'product_option_categories')['product_option_categories'] ?? [], $serviceId);
            } catch (\Throwable $th) {
                //throw $th;
            }

            try {
                Redis::set('productUpdate_' . $product->rootCategory()->id, json_encode($product));
                Redis::set('layoutUpdate_' . $product->rootCategory()->id, json_encode($product));
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

        if ($product->price_1 <= 0 || empty($product->price_1)) {
            return response('Please add gogoPrice for this product to verify.');
        }

        if (!$product->product_category_id || empty($product->product_category_id)) {
            return response('Please select product category for this product to verify.');
        }

        $product->verify();

        try {
            Redis::set('productUpdate_' . $product->rootCategory()->id, json_encode($product));
        } catch (\Throwable $th) {
            //throw $th;
        }

        return response('success');
    }

    public function verifyMultiple(Request $request)
    {
        $ids = explode(',', $request->id);
        foreach ($ids as $key => $id) {
            $product = $this->productService->findOrFail($id);


            if (($product->price_1 > 0 || !empty($product->price_1)) && ($product->product_category_id || !empty($product->product_category_id))) {
                $product->verify();
            }
        }
        return response('success');
    }

    public function options(Request $request)
    {
        if ($request->vendorId) {
            $vendor = $this->vendorService->findOrFail($request->vendorId);
            $serviceIds = $vendor->services()->pluck('category_id');
            return ProductOptionCategoryResource::collection(ProductOptionCategory::whereIn('service_id', $serviceIds)->orderBy('order')->get());
        }
        return ProductOptionCategoryResource::collection(ProductOptionCategory::orderBy('order')->get());
    }

    public function deleteProductImage(Request $request)
    {
        $response = $this->imageService->deleteProductImage($request->id, auth()->id());
        if ($request) {
            return response('success');
        }
        return response('error');
    }

    public function excelImport(Request $request)
    {
        // $request->validate([
        //     'import_file' => 'required|file',
        // ]);
        // Excel::import(new ProductImport, request()->file('import_file'));


        $validator = Validator::make($request->all(), [
            'data' => 'required|array',
            'data.*.vendor_phone' => 'required',
            'data.*.title' => 'required|string',
            'data.*.price' => 'required',
            'data.*.gogo_price' => 'required',
            'data.*.stock' => 'required|min:0',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return response()->json(['message' => $errors]);
        }




        foreach ($request->data as $key => $row) {
            $vendor = Vendor::where('phone', $row['vendor_phone'])->first();
            if (!$vendor) {
                return response()->json(['message' => 'Vendor not found.']);
            }
            $categoryId = $row['category_slug']
                ? optional(ProductCategory::where('slug', $row['category_slug'])->first())->id
                : null;

            try {
                if (strlen(trim($row['size'])) == 0) {
                    $sizes = null;
                } elseif ((trim($row['size'])) == "null") {
                    $sizes = null;
                } else {
                    $sizes =   strlen(trim($row['size'])) == 0 ? null : json_encode(explode("|", $row['size']));
                }
            } catch (\Throwable $th) {
                $sizes = [];
            }

            try {
                if (strlen(trim($row['color'])) == 0) {
                    $colors = null;
                } elseif ((trim($row['color'])) == "null") {
                    $colors = null;
                } else {
                    $colors =   strlen(trim($row['color'])) == 0 ? null : json_encode(explode("|", $row['color']));
                }
            } catch (\Throwable $th) {
                $colors = [];
            }


            $data = [
                'title' => $row['title'],
                'slug'      =>  Str::slug($vendor->business_name) . '-' . Str::slug($row['title']) . '-' . Str::slug($row['price']) . '-' . rand(11111, 999999),
                'code'     => Str::slug($row['title']) . '-' . rand(11111, 999999),
                'badge'     => $row['badge'] ?? null,
                'size' =>   $sizes,
                'color' =>  $colors,
                'price' => $row['price'],
                'price_1' => $row['gogo_price'] ?? 0,
                'elite_percent' => $row['elite_percent'] ?? 0,
                'opening_stock' => $row['stock'] ?? 0,
                'description' => $row['description'] ?? null,
                'discount_type' => $row['discount_type'] ?? 'amount',
                'discount' => $row['discount'] ?? 0,
                'expire_date' => $row['expire_date'] ?? null,
                'batch_no' => $row['batch_no'] ?? null,
                'unit' => $row['unit'] ?? null,
                'hide' => $row['hide'] ?? 0,
                'vat_percentage' => $row['vat_percentage'] ?? 0,
                'service_charge_percentage' => $row['service_charge_percentage'] ?? 0,
                'product_category_id' => $categoryId,
                'vendor_id' => $vendor->id
            ];
            $product = $this->productService->store($data);
        }

        // 'title'      => $row['title'] ?? 'NO NAME',
        // 'slug'      =>  $vendorSlug. '-' . Str::slug($row['title']) . '-' . Str::slug($row['price']) . '-' . rand(11111, 999999),
        // 'code'     => Str::slug($row['title']) . '-' . rand(11111, 999999),
        // 'badge'     => $row['badge'],
        // 'size' =>   $sizes,
        // 'color' =>  $colors,
        // 'price' => $row['price'],
        // 'price_1' => $row['gogo_price'] ?? 0,
        // 'elite_percent' => $row['elite_percent'] ?? 0,
        // 'opening_stock' => $row['stock'] ?? 0,
        // 'description' => $row['description'],
        // 'discount_type' => $row['discount_type'] ?? 'amount',
        // 'discount' => $row['discount'] ?? 0,
        // 'expire_date' => $row['expire_date'],
        // 'batch_no' => $row['batch_no'],
        // 'unit' => $row['unit'],
        // 'hide' => $row['hide'] ?? 0,
        // 'vat_percentage' => $row['vat_percentage'] ?? 0,
        // 'service_charge_percentage' => $row['service_charge_percentage'] ?? 0,
        // 'product_category_id' => $categoryId,
        // 'vendor_id' => $vendor ? $vendor->id : null


        return response()->json(['message' => 'success']);
    }

    public function updateList()
    {
        $list = $this->productUpdateService->query()->oldest()->paginate($this->paginationLimit);

        return ProductUpdateResource::collection($list);
    }

    public function revertUpdate(Request $request)
    {
        $product = $this->productService->findOrFail($request->id);

        if ($product->productUpdate) {
            $product->productUpdate()->delete();
        }

        return response('success');
    }

    public function updateVendorChanges(Request $request)
    {
        $product = $this->productService->findOrFail($request->id);

        if ($product->productUpdate) {
            $update = $product->productUpdate;



            $data = [
                "title" => $update->title ?? $product->title,
                "product_category_id" => $update->product_category_id ?? $product->product_category_id,
                "price" => $update->price > 0 ? $update->price : $product->price,
                "size" => count($update->size) > 0 ? $update->size :  $product->size,
                "color" => count($update->color) > 0 ? $update->color :  $product->color,
                "opening_stock" => $update->opening_stock > 0 ? $update->opening_stock : $product->opening_stock,
                "description" => $update->description ?? $product->description,
                "discount_type" => $update->discount_type ?? $product->discount_type,
                "discount" => $update->discount > 0 ? $update->discount : $product->discount,
                "hide" => $update->hide,
                "batch_no" => $update->batch_no ?? $product->batch_no,
                "expire_date" => $update->expire_date ?? $product->expire_date,
                "unit" => $update->unit ?? $product->unit,
                "vat_percentage" => $update->vat_percentage ?? $product->vat_percentage,
                "service_charge_percentage" => $update->service_charge_percentage ?? $product->service_charge_percentage,
            ];

            $update = $this->productService->update($request->id, $data);

            $product->productUpdate()->delete();
        }

        return response('success');
    }
}
