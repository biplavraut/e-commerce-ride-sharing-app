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
            return $this->query()->where('vendor_id', $vendor->id)->paginate(10);
        }

        return $this->query()->where('title', 'LIKE', '%' . $name . '%')->paginate(10);
    }

    public function vendorProductSearch($keyword, $vendorId)
    {
        return $this->query()->where('title', 'LIKE', $keyword . '%')->where("vendor_id", $vendorId)->paginate(10);
    }

    public function getAdvancedProducts($keyword)
    {
        if (!$keyword) {
            return collect([]);
        }

        $category = ProductCategory::where('name', $keyword)->first();

        if ($category) {
            return $this->query()->where('product_category_id', $category->id)->paginate(10);
        }

        return $this->query()->where('title', 'LIKE', $keyword . '%')->paginate(10);


        $lowerKeyword = strtolower($keyword);
        $upperKeyword = strtoupper($keyword);

        $filters = $this->query();
        $filters->where('vendor_id', auth()->id());
        $filters->where(function ($query) use ($keyword, $lowerKeyword, $upperKeyword, $category) {
            $query->orWhere('product_category_id', $category ? $category->id : '');
            $query->orWhere('title', 'like', "{$keyword}%");
            $query->orWhere('title', 'like', "{$lowerKeyword}%");
            $query->orWhere('title', 'like', "{$upperKeyword}%");
        });
        return $filters->paginate(10);
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

        $vendor = Vendor::where('business_name', $keyword)->where('status', 1)->where('verified', 1)->first();

        if ($vendor) {
            return $this->query()->where('vendor_id', $vendor->id)->where("hide", 0)->Where("verified", 1)->paginate(10);
        }


        return $this->query()
            ->where('title', 'LIKE', '%' . $keyword . '%')
            ->where("hide", 0)
            ->Where("verified", 1)
            ->whereHas('category')
            ->whereHas('vendor', function ($query) {
                $query->where('status', 1);
                $query->Where("verified", 1);
            })->paginate(10);
    }
}
