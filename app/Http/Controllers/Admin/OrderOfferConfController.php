<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\OrderOfferConfResource;
use App\Services\OrderOfferConfService;
use DateTime;
use Illuminate\Http\Request;

class OrderOfferConfController extends CommonController
{
    /** @var OrderOfferConfService */
    private $orderOfferConfService;

    public function __construct(OrderOfferConfService $orderOfferConfService)
    {
        parent::__construct();
        $this->orderOfferConfService = $orderOfferConfService;
    }

    public function index()
    {
        $conf = $this->orderOfferConfService->latest();
        if ($conf) {
            return new OrderOfferConfResource($conf);
        }else{
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
            'no_of_orders' => 'required',
            'from' => 'required',
            'to' => 'required'
        ], ['to.required' => 'The To Date-Time is required and must be greater then From Date-Time']);

        $conf = $this->orderOfferConfService->latest();
        if ($conf) {
            $conf = $this->orderOfferConfService->update($conf->id, $request->except('_method'));
        } else {    
            $conf = $this->orderOfferConfService->store();
        }

        return new OrderOfferConfResource($conf);
    }

    public function update(Request $request, $confId)
    {
        $conf = $this->orderOfferConfService->update($confId, $request->validated());

        return new OrderOfferConfResource($conf);
    }
}
