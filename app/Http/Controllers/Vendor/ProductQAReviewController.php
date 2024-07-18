<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ProductQAResource;
use App\Http\Resources\Admin\ProductReviewResource;
use App\ProductQa;
use App\ProductReviewRating;
use Illuminate\Http\Request;

class ProductQAReviewController extends Controller
{
    public function listReviews()
    {
        return ProductReviewResource::collection(auth()->user()->reviews()->paginate(15));
    }

    public function listQAs()
    {
        return ProductQAResource::collection(auth()->user()->qas()->paginate(15));
    }

    public function verifyReview(Request $request)
    {
        $review = ProductReviewRating::find($request->id);
        $review->verify();
        return response('success');
    }

    public function answerQA(Request $request)
    {
        $qa = ProductQa::find($request->id);
        $qa->answer = $request->answer;
        $qa->updated_at = now();
        $qa->save();
        return response('success');
    }

    public function destroyReview($reviewId)
    {
        $review = ProductReviewRating::find($reviewId);
        $review->delete();
        return response('success');
    }

    public function searchReview(Request $request)
    {
        return ProductReviewResource::collection(ProductReviewRating::where('vendor_id', auth()->id())->where('review', 'LIKE', '%'.$request->name.'%')->take(10)->get());
    }

    public function searchQas(Request $request)
    {
        return ProductQAResource::collection(ProductQa::where('vendor_id', auth()->id())->where('question', 'LIKE', '%'.$request->name.'%')->take(10)->get());
    }
}
