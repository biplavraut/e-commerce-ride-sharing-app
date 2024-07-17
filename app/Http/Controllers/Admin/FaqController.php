<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\FaqService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\FaqResource;
use App\Http\Requests\Admin\FaqRequest;

class FaqController extends CommonController
{
    /** @var FaqService */
    private $FaqService;

    public function __construct(FaqService $FaqService)
    {
        parent::__construct();
        $this->FaqService = $FaqService;
    }

    public function index()
    {
        $donations = $this->FaqService->getForIndex(
            $this->paginationLimit
        );

        return FaqResource::collection($donations);
    }

    public function search(Request $request)
    {
        return FaqResource::collection($this->FaqService->query()->where('faq_title', 'LIKE', $request->name . '%')->take(10)->get());
    }

    public function store(FaqRequest $request)
	{
		$faqs = $this->FaqService->store();
		return new FaqResource($faqs);
	}

    
	public function show($faqId)
	{
		$faqs = $this->FaqService->findOrFail($faqId, ['id', 'faq_title', 'faq_description', 'order']);

		return new FaqResource($faqs);
	}

	public function update(FaqRequest $request, $faqId)
	{
		$faqs = $this->FaqService->update($faqId);

		return new FaqResource($faqs);
	}

	public function destroy($faqId)
	{
		$faqs = $this->FaqService->delete($faqId);
		return response('success');
	}
}
