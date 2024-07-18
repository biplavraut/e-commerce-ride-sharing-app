<?php

namespace App;

use App\Custom\Contracts\ImageableContract;
use App\Custom\Traits\Imageable;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model implements ImageableContract
{
    //
    use Imageable;

    protected $guarded = [];

    public $columnsWithTypes = [
        'title' => 'string',
        'sub_title' => 'string',
        'image' => 'image',
        'category_id' => 'string',
        'bg_color' => 'string',
        'text_color' => 'string',
        'from' => 'string',
        'to' => 'string',
        'status' => 'boolean'
    ];

    public function dealproducts()
    {
        return $this->hasMany(DealProduct::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
