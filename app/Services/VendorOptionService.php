<?php

namespace App\Services;

use App\VendorOption;
use Illuminate\Support\Facades\Redis;

class VendorOptionService extends ModelService
{
    const MODEL = VendorOption::class;

    public function saveAndSync($vendor, $options)
    {
        $vendorOptionId    =   array();
        foreach ($options as $option) {
            if ($option['status'] == "true") {
                $vendorOptionId[] =   VendorOption::firstOrCreate(['vendor_id' => $vendor->id, 'vendor_option_category_id' => $option['id'], 'service_id' => $vendor->services()->first()->id])->id;
            }
        }
        VendorOption::where('vendor_id', $vendor->id)->whereNotIn('id', $vendorOptionId)->delete();
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
