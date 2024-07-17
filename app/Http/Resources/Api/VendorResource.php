<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use App\openingTiming;
use Illuminate\Support\Str;

class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $vendorOpeningTiming = openingTiming::where("vendor_id",$this->vendorRealId())->first();
        if(empty($vendorOpeningTiming)){
            $openTimingData = [
                'vendor_id' =>   $this->vendorRealId(),
                'sun_opening' => 11,
                'sun_closing' => 19,
                'mon_opening' => 11,
                'mon_closing' => 19,
                'tue_opening' => 11,
                'tue_closing' => 19,
                'wed_opening' => 11,
                'wed_closing' => 19,
                'thu_opening' => 11,
                'thu_closing' => 19,
                'fri_opening' => 11,
                'fri_closing' => 19,
                'sat_opening' => 0,
                'sat_closing' => 0,
                'created_at' => date("Y=m-d H:i:s"),
                'updated_at' => date("Y=m-d H:i:s"),
            ];
            try {
               openingTiming::insert($openTimingData);
            } catch (\Throwable $th) {
                dd($th->getMessage());
            }
        }
        $vendorOpeningTiming = openingTiming::where("vendor_id",$this->vendorRealId())->first();
        $today = Str::lower(date("D"));
        $openingColumn = $today."_opening";
        $closingColumn = $today."_closing";
        $openingTime = (int) $vendorOpeningTiming[$openingColumn];
        $closingTime = (int) $vendorOpeningTiming[$closingColumn];
        $formatedOpenTime = $openingTime % 12 > 0 ? $openingTime.":00 AM":$openingTime.":00 PM";
        $formatedCloseTime = $closingTime % 12 > 0 ? ($closingTime-12).":00 PM":($closingTime-12).":00 AM";
        $currentHour = (int) date("H");
        $currentStatus = false;
        if($currentHour >= $openingTime && $currentHour < $closingTime){
            $currentStatus = true;
        }
        return [
            'id'    => $this->id,
            'vendorId'    => $this->vendorId(),
            'businessName'  => $this->business_name,
            'email' => $this->email,
            'image' => $this->image,
            'image50'     => $this->cropImage(50, 50),
            'city' => $this->city,
            'address' => $this->address,
            'phone' => $this->phone,
            'isHidden' => $this->is_hidden == 1?true:false,
            'openingTime' => $formatedOpenTime,
            'closingTime' => $formatedCloseTime,
            'isOpen' => $currentStatus,
        ];
    }
}
