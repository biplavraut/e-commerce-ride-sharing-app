<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\OrderFeedbackResource;
use App\Services\OrderFeedbackService;

class OrderFeedbackController extends CommonController
{
    /** @var OrderFeedbackService */
    private $orderFeedbackService;

    public function __construct(OrderFeedbackService $orderFeedbackService)
    {
        parent::__construct();
        $this->orderFeedbackService = $orderFeedbackService;
    }

    public function index()
    {
        $feedbacks = $this->orderFeedbackService->query()->whereNull('respond')->paginate($this->paginationLimit);

        return OrderFeedbackResource::collection($feedbacks);
    }

    public function updateRespond(Request $request)
    {
        $feedback =$this->orderFeedbackService->findOrFail($request->feedbackId);
        $feedback->update(['respond' => $request->respond, 'admin_id' => auth()->id()]);
        return response('success');
    }

    public function destroy($orderFeedbackId)
    {
        $feedback = $this->orderFeedbackService->delete($orderFeedbackId);

        return response('success');
    }
}
