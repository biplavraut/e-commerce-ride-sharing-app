<?php

namespace App;

use App\Custom\Contracts\ImageableContract;
use App\Custom\Traits\Imageable;
use Illuminate\Database\Eloquent\Model;

class Image extends Model implements ImageableContract
{
    use Imageable;

    public $timestamps = false;

    public $columnsWithTypes = [
        'image' => 'image',
        'model_id' => 'string',
        'model_type' => 'string',
    ];

    public function model()
    {
        return $this->morphTo('model');
    }

    public function getMe($id)
    {
        return $this->where('model_id', $id)->limit(1)->get();
    }
}