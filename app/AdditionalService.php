<?php

namespace App;

use App\Custom\Traits\Imageable;
use App\Custom\Traits\Routeable;
use Illuminate\Database\Eloquent\Model;
use App\Custom\Contracts\ImageableContract;

class AdditionalService extends Model implements ImageableContract
{
    use Imageable, Routeable;

    protected $guarded = ['id'];

    public $columnsWithTypes = [
        'name'      => 'string',
        'slug'      => 'string',
        'image'     => 'image',
        'subtitle'      => 'string',
        'order'      => 'string',
        'cashback'      => 'string',
        'enabled' => 'boolean',
        'enabled_promo' => 'boolean',
    ];

    public function getEnabledAttribute($value)
    {
        return $value == 1;
    }

    public function setEnabledAttribute($value)
    {
        $this->attributes['enabled'] = $value == true ? 1 : 0;
    }

    public function getEnabledPromoAttribute($value)
    {
        return $value == 1;
    }

    public function setEnabledPromoAttribute($value)
    {
        $this->attributes['enabled_Promo'] = $value == true ? 1 : 0;
    }
}
