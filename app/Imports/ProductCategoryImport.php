<?php

namespace App\Imports;

use App\Category;
use App\ProductCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductCategoryImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $subCats = $row['has_sub_cat'] == 1 ? explode("|", $row['names']) : [];
      
        $serviceId = $row['service_slug']
        ? optional(Category::where('slug', $row['service_slug'])->first())->id
        : null;
        
        $category =  ProductCategory::create([
            'name' => $row['name'],
            'slug' => Str::slug($row['name']),
            'category_id' => $serviceId,
            'parent' => 1
        ]);

        if (count($subCats) > 0) {
            for ($i = 0; $i < count($subCats); $i++) {
                $category->children()->create([
                    'name' => $subCats[$i],
                    'slug' => Str::slug($subCats[$i]),
                ]);
            }
        }
        return;
    }
}
