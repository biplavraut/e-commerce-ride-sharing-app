<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class CommonController extends Controller
{
	protected $website = [];
	protected $paginationLimit = 25;

	public function __construct()
	{
		if (!request()->ajax()) {
			$this->website['company'] = Company::findOrFail(1);
		}
	}

	protected function forgetIndexCache()
	{
		Cache::forget("{$this->cacheKey}.index");
	}
}
