<?php

namespace App\Imports;

use App\Product;
use App\ProductCategory;
use App\ProductOption;
use App\Vendor;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VendorProductImport implements ToModel, WithHeadingRow
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

        $vendor = auth()->user();


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

        if (trim($row['title']) != 0 ) {
            $product = Product::create([
                'title'      => $row['title'],
                'slug'      =>  Str::slug($vendor->business_name) . '-' . Str::slug($row['title']) . '-' . Str::slug($row['price']) . '-' . rand(11111, 999999),
                'code'     => Str::slug($row['title']) . '-' . rand(111, 999),
                'badge'     => $row['badge'],
                'size' =>   $sizes,
                'color' =>  $colors,
                'price' => $row['price'],
                'opening_stock' => $row['stock'],
                'description' => $row['description'],
                'discount_type' => $row['discount_type'],
                'discount' => $row['discount'],
                'expire_date' => $row['expire_date'],
                'batch_no' => $row['batch_no'],
                'unit' => $row['unit'],
                'hide' => $row['hide'] ?? 0,
                'vat_percentage' => $row['vat_percentage'] ?? 0,
                'service_charge_percentage' => $row['service_charge_percentage'] ?? 0,
                'product_category_id' => $categoryId,
                'vendor_id' => $vendor->id,
            ]);   
        }
        return;
    }
}
