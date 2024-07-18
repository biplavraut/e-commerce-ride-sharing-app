<?php

namespace App\Http\Resources\Api;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceDealResource extends JsonResource
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
            'title' => $this->title,
            'subTitle' => $this->sub_title,
            'image' => $this->image,
            'categoryId' => $this->category_id,
            'backgroungColor' => $this->bg_color,
            'textColor' => $this->text_color,
            'currentTime' => Carbon::parse(now())->format('Y-m-d H:i:s'),
            'from'  => $this->from,
            'to' => $this->to,
            'expireIn' => Carbon::parse($this->to)->diffInMilliseconds(now()),
            'products' => $this->dealproducts ? DealProductResource::collection($this->dealproducts) : nullValue(),
            'status' => $this->status
        ];
    }
}
