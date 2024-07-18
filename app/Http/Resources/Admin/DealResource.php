<?php

namespace App\Http\Resources\Admin;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DealResource extends JsonResource
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
            'id'  =>  $this->id,
            'title' =>  $this->title,
            'sub_title' =>  $this->sub_title ?? '',
            'categoryId' => $this->category_id,
            'categoryName' => $this->category->name,
            'image' =>  $this->image,
            'bg_color' => $this->bg_color,
            'text_color' => $this->text_color,
            'from' => $this->from,
            'from_date' => Carbon::parse($this->from)->format('Y-m-d'),
            'from_time' => Carbon::parse($this->from)->format('H:i:s'),
            'to' => $this->to,
            'to_date' => Carbon::parse($this->to)->format('Y-m-d'),
            'to_time' => Carbon::parse($this->to)->format('H:i:s'),
            'status'  => $this->status == 1,
            'starts' => Carbon::parse($this->from)->diffForHumans(date('Y-m-d H:i:s')), 
            'ends' => $this->to > date('Y-m-d H:i:s') ? Carbon::parse($this->to)->diffForHumans(date('Y-m-d H:i:s')) : 'Ended', 
            'created_at' => $this->created_at
        ];
    }
}
