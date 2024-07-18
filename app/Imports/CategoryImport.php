<?php

namespace App\Imports;

use App\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CategoryImport implements ToModel, WithHeadingRow
{
	/**
	 * @param array $row
	 *
	 * @return \Illuminate\Database\Eloquent\Model|null
	 */
	public function model(array $row)
	{
		$parentId = $row['parent_slug']
			? optional(Category::where('slug', $row['parent_slug'])->first())->id
			: null;

		return new Category([
			'name'      => $row['name'],
			'slug'      => $row['slug'],
			'image'     => $row['image'],
			'parent_id' => $parentId,
			'parent'    => $row['parent'],
		]);
	}
}
