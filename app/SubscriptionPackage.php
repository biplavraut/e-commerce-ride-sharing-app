<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPackage extends Model
{
    protected $guarded = [];
    /**
     * Columns of the table with data type e.g. 'name' => 'string'
     */
    public $columnsWithTypes = [
        'name' => 'string',
        'type' => 'string',
        'two_wheel_value' => 'string',
        'four_wheel_value' => 'string',
        'duration' => 'string',
        'hide' => 'boolean'
    ];

    public function drivers()
    {
        return $this->belongsToMany(Driver::class);
    }
}
