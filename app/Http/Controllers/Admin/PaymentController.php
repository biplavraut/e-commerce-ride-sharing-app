<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\DriverService;
use App\Services\DriverPaymentSettlementService;
use App\Http\Resources\Admin\DriverPaymentResource;

class PaymentController extends CommonController
{
    /** @var DriverPaymentSettlementService */
    private $paymentService;

    /** @var DriverService */
    private $driverService;

    public function __construct(DriverPaymentSettlementService $paymentService, DriverService $driverService)
    {
        parent::__construct();
        $this->paymentService = $paymentService;
        $this->driverService = $driverService;
    }

    public function index()
    {
        $payments = $this->paymentService->getForIndex(
            $this->paginationLimit
        );

        return DriverPaymentResource::collection($payments);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $payment = $this->paymentService->findOrFail($id);

        $payment->update(['payable_amount' => ($payment->payable_amount - $request->payable), 'receivable_amount' => ($payment->receivable_amount - $request->receivable)]);

        return new DriverPaymentResource($payment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function search(Request $request)
    {

        return DriverPaymentResource::collection($this->paymentService->query()->whereHas('driver', function ($query) use ($request) {
            $query->where('phone', 'LIKE', $request->name . '%');

            $splitter = explode(" ", $request->name);

            if (count($splitter) > 1) {
                $query->orWhere('last_name', 'LIKE', $splitter[1]);
            }

            if ($splitter[0]) {
                $query->orWhere('first_name', 'LIKE', $splitter[0]);
            }

            
        })->paginate($this->paginationLimit));
    }
}
