<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\DonationService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\DonationResource;

class DonationController extends CommonController
{
    /** @var DonationService */
    private $donationService;

    public function __construct(DonationService $donationService)
    {
        parent::__construct();
        $this->donationService = $donationService;
    }

    public function index()
    {
        $donations = $this->donationService->getForIndex(
            $this->paginationLimit
        );

        return DonationResource::collection($donations);
    }
    public function search(Request $request)
    {
        return DonationResource::collection($this->donationService->query()->where('trust', 'LIKE', $request->name . '%')->take(10)->get());
    }
}
