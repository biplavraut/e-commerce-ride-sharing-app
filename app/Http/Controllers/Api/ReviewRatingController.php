<?php

namespace App\Http\Controllers\Api;

use App\Vendor;
use App\VendorReviewRating;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\VendorReviewResource;

class ReviewRatingController extends CommonController
{

    /** @var ImageService */
    private $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function ratingReviewVendor(Request $request)
    {
        if (!$vendor = Vendor::find($request->vendorId)) {
            return response()->json(['status' => false, 'message' => 'Vendor Not Found.', 'statusCode' => 404], 404);
        }

        $validator = Validator::make($request->all(), [
            'vendorId' => 'required|integer',
            'review' => 'nullable|string',
            'rating' => 'required|min:0.5|max:5',
            'anonymously' => 'required',
            'images' => 'bail|nullable|array',
            'images.*' => 'bail|nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $review = VendorReviewRating::create([
            'vendor_id' => $vendor->id,
            'user_id' => $user->id,
            'review' => $request->review,
            'rating' => $request->rating,
            'anonymously' => $request->anonymously
        ]);

        foreach ($request->only('images')['images'] ?? [] as $image) {
            $this->imageService->store([
                'image' => $image,
                'model_type' => get_class($review),
                'model_id' => $review->id,
            ]);
        }

        return (new VendorReviewResource($review))->additional(['status' => true, 'message' => "Thank you for reviewing.", 'statusCode' => 201], 201);
    }

    public function updateRatingVendor(Request $request, $reviewId)
    {
        if (!$review = VendorReviewRating::find($reviewId)) {
            return response()->json(['status' => false, 'message' => 'Review Not Found.', 'statusCode' => 404], 404);
        }

        $validator = Validator::make($request->all(), [
            'reviewId' => 'nullable|string',
            'review' => 'nullable|string',
            'rating' => 'required|min:0.5|max:5',
            'anonymously' => 'required',
            'images' => 'bail|nullable|array',
            'images.*' => 'bail|nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $updatedReview = $review->update([
            'review' => $request->review,
            'rating' => $request->rating,
            'anonymously' => $request->anonymously,
            'created_at' => now()
        ]);

        foreach ($request->only('images')['images'] ?? [] as $image) {
            $this->imageService->store([
                'image' => $image,
                'model_type' => get_class($review),
                'model_id' => $review->id,
            ]);
        }

        return (new VendorReviewResource($review))->additional(['status' => true, 'message' => "Thank you for reviewing.", 'statusCode' => 204], 204);
    }
}
