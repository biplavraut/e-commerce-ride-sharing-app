<?php

namespace App\Exports;

use App\Category;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class CategoryExport implements FromView
{
	use Exportable;

	/**
	 * @return View
	 */
	public function view(): View
	{
		return view('admin.category.excel-export', [
			'categories' => Category::query()
			                        ->orderBy('parent', 'desc')
			                        ->latest()
			                        ->get(),
		]);
	}
}
