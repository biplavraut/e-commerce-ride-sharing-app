<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\PaymentLogService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PaymentLogResource;

class PaymentLogController extends CommonController
{
    /** @var PaymentLogService */
    private $paymentLogService;

    public function __construct(PaymentLogService $paymentLogService)
    {
        parent::__construct();
        $this->paymentLogService = $paymentLogService;
    }

    public function index()
    {
        $logs = $this->paymentLogService->getForIndex(
            $this->paginationLimit
        );

        return PaymentLogResource::collection($logs);
    }

    public function search(Request $request)
    {
        return PaymentLogResource::collection($this->paymentLogService->query()->where('action', 'LIKE', $request->name . '%')
            ->orWhere('verified', 'LIKE', $request->name . '%')
            ->orWhere('payment_mode', 'LIKE', $request->name . '%')
            ->orWhere('agent', 'LIKE', $request->name . '%')
            ->take(10)->get());
    }

    public function store(Request $request)
    {
        $logs = $this->paymentLogService->store();
        return new PaymentLogResource($logs);
    }


    public function show($paymentLogId)
    {
        $logs = $this->paymentLogService->findOrFail($paymentLogId, ['id', 'faq_title', 'faq_description', 'order']);

        return new PaymentLogResource($logs);
    }

    public function update(Request $request, $paymentLogId)
    {
        $logs = $this->paymentLogService->update($paymentLogId);

        return new PaymentLogResource($logs);
    }

    public function destroy($paymentLogId)
    {
        $logs = $this->paymentLogService->delete($paymentLogId);
        return response('success');
    }

    public function range(Request $request)
    {
        if ($request->from == $request->to) {
            $logs = $this->paymentLogService->query()->whereDate('created_at', $request->from)->orderBy('created_at')->paginate($this->paginationLimit);
        } else if ($request->from == null || $request->to == null) {
            $logs = $this->paymentLogService->getForIndex(
                $this->paginationLimit
            );
        } else {
            $logs = $this->paymentLogService->query()->whereBetween('created_at', [$request->from, $request->to])->orderBy('created_at')->paginate($this->paginationLimit);
        }
        return PaymentLogResource::collection($logs);
    }
}
