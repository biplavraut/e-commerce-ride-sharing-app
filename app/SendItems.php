<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SendItems extends Model
{
      //Status: 0->inactive,1=>active
      protected $fillable =   [
            'name',
            'flat_price',
            'added_per_km_price',
            'added_weightprice_per_kg',
            'status'
        ];

      public $columnsWithTypes = [
      'name' => 'string',
      'flat_price' => 'string',
      'added_per_km_price' => 'string',
      'added_weightprice_per_kg' => 'string',
      'status' => 'boolean'
      ];
}
