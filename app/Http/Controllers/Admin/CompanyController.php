<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CompanyRequest;
use App\Http\Resources\Admin\CompanyResource;
use App\Services\CompanyService;

class CompanyController extends CommonController
{
	/**
	 * @var CompanyService
	 */
	private $companyService;

	public function __construct(CompanyService $service)
	{
		parent::__construct();
		$this->companyService = $service;
	}

	public function update(CompanyRequest $request, $id)
	{
		$company = $this->companyService->updateByModel(
			$this->website['company'] ?? $this->companyService->findOrFail(1)
		);

		return new CompanyResource($company);
	}
}
