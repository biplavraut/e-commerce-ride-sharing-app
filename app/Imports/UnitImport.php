<?php

namespace App\Imports;

use App\ProductCategory;
use App\Unit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UnitImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $categoryId = $row['product_category_slug']
        ? optional(ProductCategory::where('slug', $row['product_category_slug'])->first())->id
        : null;
        
        $unit =  Unit::create([
            'name' => $row['name'],
            'description' => $row['description'],
            'product_category_id' => $categoryId,
        ]);
        
        return;
    }
}
