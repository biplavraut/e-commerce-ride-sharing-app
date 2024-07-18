<?php

namespace App\Exports;

use App\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;


class ProductExport implements FromView
{
    use Exportable;

    /**
     * @return View
     */
    public function view(): View
    {
        return view('admin.product.excel-export', [
            'products' => Product::query()
                ->where('vendor_id', 47)
                ->oldest()
                ->get(),
        ]);
    }
}
