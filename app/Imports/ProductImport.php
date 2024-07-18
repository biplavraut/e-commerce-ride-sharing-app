<?php

namespace App\Imports;

use App\Product;
use App\ProductCategory;
use App\ProductOption;
use App\Vendor;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{


    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $categoryId = $row['category_slug']
            ? optional(ProductCategory::where('slug', $row['category_slug'])->first())->id
            : null;

        try {
            $vendor = Vendor::where('phone', trim($row['vendor_phone']))->first();
        } catch (\Throwable $th) {
            $vendor = null;
        }

        if (strlen(trim($row['size'])) == 0) {
            $sizes = null;
        } elseif ((trim($row['size'])) == "null") {
            $sizes = null;
        } else {
            $sizes =   strlen(trim($row['size'])) == 0 ? null : json_encode(explode("|", $row['size']));
        }

        if (strlen(trim($row['color'])) == 0) {
            $colors = null;
        } elseif ((trim($row['color'])) == "null") {
            $colors = null;
        } else {
            $colors =   strlen(trim($row['color'])) == 0 ? null : json_encode(explode("|", $row['color']));
        }

        if ($row['title']) {
            $vendorSlug = $vendor? Str::slug($vendor->business_name): $row['price'] ;
            $product = Product::create([
                
                'title'      => $row['title'] ?? 'NO NAME',
                'slug'      =>  $vendorSlug. '-' . Str::slug($row['title']) . '-' . Str::slug($row['price']) . '-' . rand(11111, 999999),
                'code'     => Str::slug($row['title']) . '-' . rand(11111, 999999),
                'badge'     => $row['badge'],
                'size' =>   $sizes,
                'color' =>  $colors,
                'price' => $row['price'],
                'price_1' => $row['gogo_price'] ?? 0,
                'elite_percent' => $row['elite_percent'] ?? 0,
                'opening_stock' => $row['stock'] ?? 0,
                'description' => $row['description'],
                'discount_type' => $row['discount_type'] ?? 'amount',
                'discount' => $row['discount'] ?? 0,
                'expire_date' => $row['expire_date'],
                'batch_no' => $row['batch_no'],
                'unit' => $row['unit'],
                'hide' => $row['hide'] ?? 0,
                'vat_percentage' => $row['vat_percentage'] ?? 0,
                'service_charge_percentage' => $row['service_charge_percentage'] ?? 0,
                'product_category_id' => $categoryId,
                'vendor_id' => $vendor ? $vendor->id : null,
            ]);
        }
       


        return;
    }
}
