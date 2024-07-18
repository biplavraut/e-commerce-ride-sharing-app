<?php

namespace App;

use App\Custom\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospital extends Model
{
    //
    use SoftDeletes;
    use CreatedUpdatedBy;
    protected $guarded = ['id'];

    /**
     * Columns of the table with data type e.g. 'name' => 'string'
     */
    public $columnsWithTypes = [
        'title' => 'string',
        'lat' => 'string',
        'long' => 'string',
        'vendors' => 'json'
    ];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucfirst($value);
    }
}
