<?php

namespace App\Http\Controllers\Frontend;

use App\Company;
use App\Http\Controllers\Controller;

class CommonController extends Controller {
	protected $website = [];
	protected $viewPath = 'frontend';

	public function __construct() {
		$this->website['company'] = Company::findOrFail(1);
	}
}
