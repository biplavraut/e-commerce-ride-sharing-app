<?php

namespace App\Imports;

use App\RentalPackage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RentalPackageImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $vehicle_names = explode("|", $row['vehicle_names']);
        $vehicle_prices = explode("|", $row['vehicle_prices']);
        $vehicle_descriptions = explode("|", $row['vehicle_descriptions']);

       
        if (count($vehicle_names) > 0) {
            $vehicles = '[';

            for ($i = 0; $i < count($vehicle_names); $i++) {
                $comma = count($vehicle_names) == $i+1 ? '': ',';
                $vehicles.='{ "id": "'.$i.'","name": "'.$vehicle_names[$i].'","price": "'.$vehicle_prices[$i].'","description": "'.$vehicle_descriptions[$i].'"}'. $comma;
            }
            $vehicles.= ']';
        }
        $package =  RentalPackage::create([
            'name' => $row['package_name'],
            'duration' => $row['duration'],
            'distance' => $row['distance'],
            'vehicles' => $vehicles ?? null,
            'description' => $row['description'],
        ]);
        
        return;
    }
}
