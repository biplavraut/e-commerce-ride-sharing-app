<?php
namespace App\Services;

use App\ProductOption;
use Illuminate\Support\Facades\Redis;

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

    public function changeOrder(array $options)
    {
        foreach ($options as $key => $optionId) {
            $this->update($optionId, ['order' => $key + 1]);

            try {
                $data = $this->query()->findOrFail($optionId);
                Redis::set('layoutUpdate_' . $data->service_id, json_encode($data));
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    }
}
