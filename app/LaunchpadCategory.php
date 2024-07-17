<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LaunchpadCategory extends Model
{
    /**
     * Columns of the table with data type e.g. 'name' => 'string'
     */
    public $columnsWithTypes = [
        'name' => 'string',
        'description' => 'string',
        'order' => 'string'
    ];

    public function launchpads()
    {
        return $this->hasMany('App\Launchpad', 'launchpad_category_id');
    }
}
