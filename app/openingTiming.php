<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class openingTiming extends Model
{
    public $columnsWithTypes = [
        'vendor_id' => 'integer',
        'sun_opening' => 'string',
        'mon_opening' => 'string',
        'tue_opening' => 'string',
        'wed_opening' => 'string',
        'thu_opening' => 'string',
        'fri_opening' => 'string',
        'sat_opening' => 'string',
	];
}
