<?php

namespace App\Imports;

use App\Category;
use App\ProductCategory;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductSubCategoryImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $subCats = $row['has_sub_cat'] == 1 ? explode("|", $row['names']) : [];
      
        $categoryId = $row['parent_slug']
        ? optional(ProductCategory::where('slug', $row['parent_slug'])->first())->id
        : null;
        
        $category =  ProductCategory::create([
            'name' => $row['name'],
            'slug' => Str::slug($row['name']),
            'parent_id' => $categoryId,
            'parent' => 0
        ]);

        if ($row['names']) {
            if (count($subCats) > 0) {
                for ($i = 0; $i < count($subCats); $i++) {
                    $category->children()->create([
                        'name' => $subCats[$i],
                        'slug' => Str::slug($subCats[$i]),
                    ]);
                }
            }
        }
        return;
    }
}
