<?php
namespace App\Services;

use App\ProductOption;

class ProductOptionService extends ModelService
{
    const MODEL =   ProductOption::class;

    public function saveAndSync($product, $options, $sId)
    {
        $productOptionId    =   array();
        foreach ($options as $option) {
            if ($option['status']=="true") {
                $productOptionId[] =   ProductOption::firstOrCreate(['product_id'=>$product->id,'product_option_category_id' => $option['id'], 'service_id' => $sId])->id;
            }
        }
        ProductOption::where('product_id', $product->id)->whereNotIn('id', $productOptionId)->delete();
    }
}
