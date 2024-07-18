<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'id' => $this->id,
            'product' => new ProductResource($this->product),
            'name' => $this->name,
            'price' => round($this->price),
            'discount_type' => $this->discount_type,
            'discount' => $this->discount,
            'quantity' => $this->quantity,
            'color' => $this->color,
            'size' => $this->size,
            'vendor_id' => $this->vendor_id,
            'date' => $this->date,
            'time' => $this->time,
            'specialInstruction' => $this->special_instruction,
            'serviceChargeAmt' => $this->service_charge_amt,
            'taxAmt' => $this->tax_amt,
            'elitePrice' => $this->elite_price
        ];
    }
}
