<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\VendorReviewRatingService;
use App\Http\Resources\Admin\VendorRatingResource;
use App\VendorReviewRating;

class VendorRatingController extends CommonController
{
    /**
     * @var VendorReviewRatingService
     */
    private $reviewRatingService;

    public function __construct(VendorReviewRatingService $reviewRatingService)
    {
        parent::__construct();
        $this->reviewRatingService          = $reviewRatingService;
    }

    public function index()
    {
        return VendorRatingResource::collection($this->reviewRatingService->getForIndex($this->paginationLimit));
    }

    public function verify(Request $request)
    {
        $review = $this->reviewRatingService->findOrFail($request->reviewId);
        $review->update(['verified' => 1]);
        return response('success');
    }

    public function destroy($reviewRatingId)
    {
        $rewi = $this->reviewRatingService->delete($reviewRatingId);

        return response('success');
    }


    public function search(Request $request)
    {
        return VendorRatingResource::collection($this->reviewRatingService->query()
            ->where('review', 'LIKE', '%' . $request->name . '%')
            ->orwhere('rating', 'LIKE', '%' . $request->name . '%')
            ->paginate($this->paginationLimit));
    }
}
