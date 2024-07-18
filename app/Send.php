<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SendItems;
use App\User;

class Send extends Model
{
      //Status: 0->inactive,1=>processing,2->pickedup,3->ontheway,4->completed,5->cancel,6->rejected
      protected $fillable =   [
            'user_id',
            'pickup_location_name',
            'delivery_location_name',
            'pickup_location_lat',
            'pickup_location_long',
            'delivery_destination_lat',
            'delivery_destination_long',
            'distance_in_km',
            'delivery_item_type',
            'delivery_item_weight',
            'moneytery_value_of_item',
            'generated_total_price',
            'discount_price',
            'discount_method',
            'net_total_price',
            'pickup_person_name',
            'pickup_point_address',
            'pickup_person_number',
            'pickup_date',
            'pickup_time',
            'pickup_comment',
            'contact_person_name',
            'contact_person_address',
            'contact_person_number',
            'delivery_date',
            'delivery_time',
            'delivery_comment',
            'notify_receipents_by_sms',
            'status',
            'extra_column',
            'pickup_driver_id',
            'delivery_driver_id',
            'pickup_otp',
            'delivery_otp'
      ];

      public function getStatusAttribute($value)
      {

            return $value == 1 ? 'Requested' : ($value == 2 ? 'Picking up by Rider' : 'Pending');
      }

      public function item()
      {
            return $this->belongsTo(SendItems::class, 'delivery_item_type');
      }

      public function user()
      {
            return $this->belongsTo(User::class, 'user_id');
      }
}
