<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ProductQa;
use App\ProductReviewRating;
use Illuminate\Http\Request;

class ProductQAReviewController extends Controller
{
    public function listReviews()
    {
        
    }

    public function listQAs()
    {
    }

    public function verifyReview(Request $request)
    {
        $review = ProductReviewRating::find($request->id);
        $review->verify();
        return response('success');
    }

    public function answeredQA(Request $request)
    {
        $qa = ProductQa::find($request->id);
        $qa->answer = $request->answer;
        $qa->updated_at = now();
        $qa->save();
        return response('success');
    }
}
