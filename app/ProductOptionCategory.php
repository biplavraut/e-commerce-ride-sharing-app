<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductOptionCategory extends Model
{
    public $columnsWithTypes = [
        'title'        => 'string',
        'slug'        => 'string',
        'order'        => 'string',
        'layout' => 'string',
    ];

    public function productOptions()
    {
        return $this->hasMany(ProductOption::class);
    }
}
