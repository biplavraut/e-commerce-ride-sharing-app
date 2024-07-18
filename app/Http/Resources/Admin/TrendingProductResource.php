<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class TrendingProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'totalQty' => $this->total_quantity,
            'productPrice' => $this->product->price,
            'vendor' => $this->vendor->business_name,
            'avgSellPrice' => $this->avg_price / 100 - ($this->discount_type == 'amount' ? $this->avg_discount : ($this->avg_price / 100 * $this->avg_discount / 100)) + $this->avg_tax_amt + $this->avg_service_charge_amt,
            'disType' => $this->discount_type,
            'avgDisc' => $this->avg_discount,
            'totalRev' => ($this->avg_price / 100 - ($this->discount_type == 'amount' ? $this->avg_discount : ($this->avg_price / 100 * $this->avg_discount / 100)) + $this->avg_tax_amt + $this->avg_service_charge_amt) * $this->total_quantity
        ];
    }
}
