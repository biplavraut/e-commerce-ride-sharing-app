<?php

namespace App\Services;

use App\Vendor;
use App\Product;
use App\ProductOption;
use App\ProductCategory;
use App\Events\Admin\ProductStored;

class ProductService extends ModelService
{
    const MODEL = Product::class;

    public function getForIndex($limit = 20, $columns = ['*'])
    {
        return $this->query()->latest()->paginate($limit, $columns);
    }

    public function syncTags($product, $tagIds)
    {
        if (count($tagIds) > 0) {
            $product->tags()->sync($tagIds);
        }
    }

    public function getProducts($name)
    {
        if (!$name) {
            return collect([]);
        }

        $vendor = Vendor::where('business_name', $name)->orWhere('phone', $name)->with('products')->first();

        if ($vendor) {
            return $vendor->products()->latest()->get();
        }

        return $this->query()->where('title', 'LIKE', $name . '%')->take(10)->get();
    }

    public function vendorProductSearch($keyword,$vendorId){
        return $this->query()->where('title', 'LIKE', $keyword . '%')->where("vendor_id",$vendorId)->take(10)->get();
    }

    public function getAdvancedProducts($keyword)
    {
        if (!$keyword) {
            return collect([]);
        }

        $category = ProductCategory::where('name', $keyword)->first();

        if ($category) {
            return $this->query()->where('product_category_id', $category->id)->get();
        }

        return $this->query()->where('title', 'LIKE', $keyword . '%')->take(10)->get();


        $lowerKeyword = strtolower($keyword);
        $upperKeyword = strtoupper($keyword);

        $filters = $this->query();
        $filters->where('vendor_id', auth()->id());
        $filters->where(function ($query) use ($keyword, $lowerKeyword, $upperKeyword, $category) {
            $query->orWhere('product_category_id', $category ? $category->id : '');
            $query->orWhere('title', 'like', "{$keyword}%");
            $query->orWhere('title', 'like', "{$lowerKeyword}%");
            $query->orWhere('title', 'like', "{$upperKeyword}%");
            // $query->orWhere('price', 'like', "{$keyword}%");
            // $query->orWhere('price', 'like', "{$lowerKeyword}%");
            // $query->orWhere('price', 'like', "{$upperKeyword}%");
            // $query->orWhere('code', 'like', "{$keyword}%");
            // $query->orWhere('badge', 'like', "{$lowerKeyword}%");
            // $query->orWhere('badge', 'like', "{$upperKeyword}%");
            // $query->orWhere('description', 'like', "{$keyword}%");
            // $query->orWhere('description', 'like', "{$lowerKeyword}%");
            // $query->orWhere('description', 'like', "{$upperKeyword}%");
        });
        return $filters->get();
    }

    public function getCount()
    {
        return $this->query()->where('verified', 1)->count();
    }

    public function getUnverifiedCount()
    {
        return $this->query()->where('verified', 0)->count();
    }

    public function getVendorProductCount()
    {
        return $this->query()->where('vendor_id', auth()->id())->count();
    }

    public function thisMonthVendorProductData()
    {
        $ThisMonth = $this->query()
            ->selectRaw('count(id) as count, substring(created_at,9,2) as day')
            ->where('vendor_id', auth()->id())
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()->addDay()])
            ->groupBy('day')
            ->pluck('count', 'day')
            ->toArray();

        $returnData  = [];
        $daysInMonth = range(1, now()->daysInMonth);
        foreach ($daysInMonth as $value) {
            $key = sprintf('%02d', $value);

            $returnData[$value] = $ThisMonth[$key] ?? 0;
        }

        return $returnData;
    }

    public function searchProduct($keyword)
    {
        if (!$keyword) {
            return collect([]);
        }

        return $this->query()->where('title', 'LIKE', '%' . $keyword . '%')->where("hide",0)->whereHas('vendor', function ($query){
             $query->where('status', 1);
        })->paginate(10);

        $lowerKeyword = strtolower($keyword);
        $upperKeyword = strtoupper($keyword);

        $filters = $this->query();
        // $filters->where('verified', 1);
        // $filters->where('hide', 0);
        $filters->where(function ($query) use ($keyword, $lowerKeyword, $upperKeyword) {
            $query->orWhere('title', 'like', "{$keyword}%");
            $query->orWhere('title', 'like', "{$lowerKeyword}%");
            $query->orWhere('title', 'like', "{$upperKeyword}%");
            // $query->orWhere('price', 'like', "{$keyword}%");
            // $query->orWhere('price', 'like', "{$lowerKeyword}%");
            // $query->orWhere('price', 'like', "{$upperKeyword}%");
            // $query->orWhere('code', 'like', "{$keyword}%");
            // $query->orWhere('badge', 'like', "{$lowerKeyword}%");
            // $query->orWhere('badge', 'like', "{$upperKeyword}%");
            // $query->orWhere('description', 'like', "{$keyword}%");
            // $query->orWhere('description', 'like', "{$lowerKeyword}%");
            // $query->orWhere('description', 'like', "{$upperKeyword}%"); //->orderBy('title', 'ASC')
        });
        return $filters->where('hide',0)->get();
    }
}
