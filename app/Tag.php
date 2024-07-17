<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = ['id'];
    /**
     * Columns of the table with data type e.g. 'name' => 'string'
     */
    public $columnsWithTypes = [
        'name' => 'string',
        'slug' => 'string',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
