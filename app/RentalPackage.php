<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RentalPackage extends Model
{
    protected $guarded = [];
    /**
     * Columns of the table with data type e.g. 'name' => 'string'
     */
    public $columnsWithTypes = [
        'name' => 'string',
        'duration' => 'string',
        'distance' => 'string',
        'vehicles' => 'json',
        'description' => 'string'
    ];

    public function getVehiclesAttribute($value)
    {
        return json_decode($value, true);
    }

    public function vehiclesFormat()
    {
        $vehicles = [];

        foreach ($this->vehicles as $key => $vehicle) {
            $vehicles[$key] = ["name" => $vehicle['name'], 'price' => $vehicle['price'], 'description' => $vehicle['description'] ?? ''];
        }
        return $vehicles;
    }
}
