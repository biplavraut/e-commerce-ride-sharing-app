<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $guarded = ['id'];

    public $columnsWithTypes = [
        'name'      => 'string',
        'description'      => 'string',
        'held_on'      => 'string',
        'winners'      => 'json',
        'prizes'     => 'json',
        'types'     => 'json',
        'user_type'     => 'string',
    ];

    public function getWinnersAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    public function getPrizesAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    public function getTypesAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }
}
