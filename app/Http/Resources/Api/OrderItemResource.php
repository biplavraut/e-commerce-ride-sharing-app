<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Vendor\ProductResource;
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
        $isReturn = false;
        $returnDays = '';
        $returnStatus = '';
        $ticketNo = '';
        if ($this->order->status == "DELIVERED" && $this->product->is_return == 1) {
            if (!$this->orderReturn) {
                $productReturnDays = $this->product->return_days;
                $today = date('Y-m-d H:i:s');
                $applicableDate = date('Y-m-d H:i:s', strtotime("+" . $productReturnDays . " days", strtotime($this->order->updated_at)));
                if ($today <= $applicableDate) {
                    $isReturn = true;
                    $returnDays = $productReturnDays;
                    $returnStatus = '';
                }
            } else {
                $isReturn = false;
                $returnDays = '';
                $returnStatus = $this->orderReturn->status;
                $ticketNo = $this->orderReturn->ticket;
            }
        }
        return [
            'orderItemId' => $this->id,
            'product' => new ProductResource($this->product),
            'name'          =>  $this->name,
            'price'         =>  round($this->price),
            'discount_type' =>  $this->discount_type,
            'discount'      =>  $this->discount,
            'discountedPrice'    =>  $this->discount_type == "percent" ? round($this->price - $this->elite_price - ($this->price * $this->discount / 100)) : round($this->price - $this->elite_price - $this->discount),
            'quantity'      =>  $this->quantity,
            'selectedColor'         =>  $this->color ?? '',
            'selectedSize'          =>  $this->size ?? '',
            'date' => $this->date,
            'time' => $this->time,
            'specialInstruction' => $this->special_instruction,
            'isReturn' => $isReturn,
            'returnDays' => $returnDays ? 'Within ' . $returnDays . ' days' : '',
            'returnStatus' => $returnStatus,
            'ticket' => $ticketNo
        ];
    }
}
