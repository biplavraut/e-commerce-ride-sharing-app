<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\RideOfferConfResource;
use App\Services\RideOfferConfService;
use DateTime;
use Illuminate\Http\Request;

class RideOfferConfController extends CommonController
{
    //
    /** @var RideOfferConfService */
    private $rideOfferConfService;

    public function __construct(RideOfferConfService $rideOfferConfService)
    {
        parent::__construct();
        $this->rideOfferConfService = $rideOfferConfService;
    }

    public function index()
    {
        $conf = $this->rideOfferConfService->latest();
        if ($conf) {
            return new RideOfferConfResource($conf);
        } else {
            return $data = [];
        }
    }

    public function store(Request $request)
    {
        $from = new DateTime($request->from);
        $to = new DateTime($request->to);

        $from = strtotime($from->format("Y-m-d H:i:s"));
        $to = strtotime($to->format("Y-m-d H:i:s"));

        $date_diff = $to - $from;
        $strDeltaTime = "" . $date_diff / 60 / 60; // sec -> hour
        if ($strDeltaTime <= 0) {
            $request->merge(['to' => '']);
        }
        $this->validate($request, [
            'discount' => 'required',
            'no_of_rides' => 'required',
            'from' => 'required',
            'to' => 'required'
        ], ['to.required' => 'The To Date-Time is required and must be greater then From Date-Time']);

        $conf = $this->rideOfferConfService->latest();
        if ($conf) {
            $conf = $this->rideOfferConfService->update($conf->id, $request->except('_method'));
        } else {
            $conf = $this->rideOfferConfService->store();
        }

        return new RideOfferConfResource($conf);
    }

    public function update(Request $request, $confId)
    {
        $conf = $this->rideOfferConfService->update($confId, $request->validated());

        return new RideOfferConfResource($conf);
    }
}
