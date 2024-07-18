<?php

namespace App\Imports;

use App\PremiumPlace;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PremiumPlaceImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $place =  PremiumPlace::create([
            'location' => $row['location'],
            'lat' => $row['lat'],
            'long' => $row['long'],
            'radius' => $row['radius'],
            'price' => $row['price'],
            'popular' => $row['popular'] == 1 ? 1 : 0,
        ]);
        
        return;
    }
}
