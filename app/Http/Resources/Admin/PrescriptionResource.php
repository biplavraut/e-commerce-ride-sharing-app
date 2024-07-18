<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionResource extends JsonResource
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
            'uniqueNo' => $this->prescriptionNo(),
            'user' => new UserResource($this->user),
            'admin' => new AdminResource($this->admin),
            'vendor' => new VendorResource($this->vendor),
            'driver' => new DriverResource($this->driver),
            'hospital' => new HospitalResource($this->hospital),
            'status' => strtoupper($this->status),
            'patientName' => $this->patient_name,
            'patientAge' => $this->patient_age,
            'doctorName' => $this->doctor_name,
            'doctorNmc' => $this->doctor_nmc,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'deliveryArea' => $this->delivery_area,
            'nearestLandmark' => $this->nearest_landmark,
            'additionalDetail' => $this->additional_detail,
            'remarks' => $this->remarks,
            'preferredDate' => $this->preferred_date,
            'preferredTime' => $this->preferred_time,
            'alternateName' => $this->alternate_name,
            'alternatePhone' => $this->alternate_phone,
            'image' => $this->getFirstImage(),
            'images' => $this->images->map(function ($image) {
                return ['image' => $image->imageUrl(), 'id' => $image->id];
            }),
            'subTotal' => round($this->sub_total),
            'vendorTotal' => round($this->vendor_total),
            'outsideTotal' => round($this->outside_total),
            'shippingFee' => $this->shipping_fee,
            'total' => round($this->total),
            'payingTotal' => $this->paying_total,
            'otp'    =>  $this->otp ?? '',
            'createdAt' => $this->created_at->diffForHumans(),
            'dynamicColor' => substr($this->dynaicColor($this->created_at), -7),
            'bills' => PrescriptionBillResource::collection($this->billDetail)
            // 'dynamicColor' => substr($this->dynaicColor($this->created_at), -7) . substr($this->dynaicAlphaColor($this->created_at), -2),

        ];
    }

    private function dynaicColor($carbonObject)
    {
        return str_ireplace(
            [' seconds ago', ' second ago', ' minutes ago', ' minute ago', ' hours ago', ' hour ago', ' days ago', ' day ago', ' weeks ago', ' week ago'],
            ['#32b332', '#27c427', '#97d93b', '#99ad32', '#c4a829', '#ebc413', '#bd923c', '#eba721', '#c25d32', '#eba721'],
            $carbonObject->diffForHumans()
        );
    }
    private function dynaicAlphaColor($carbonObject)
    {
        return str_ireplace(
            [' seconds ago', ' second ago', ' minutes ago', ' minute ago', ' hours ago', ' hour ago', ' days ago', ' day ago', ' weeks ago', ' week ago'],
            ['', '', '', '', '95', '95', '90', '90', '85', '85'],
            $carbonObject->diffForHumans()
        );
    }
}
