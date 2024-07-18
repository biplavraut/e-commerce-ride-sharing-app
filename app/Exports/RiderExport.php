<?php

namespace App\Exports;

use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class RiderExport implements FromView
{
	use Exportable;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

	/**
	 * @return View
	 */
	public function view(): View
	{
		return view('admin.rider.excel-export', [
			'riders' => $this->data,
		]);
	}
}
