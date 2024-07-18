<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CommonController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ProductReviewResource;
use App\Http\Resources\Api\UserQAResource;
use App\Product;
use App\ProductQa;
use App\ProductReviewRating;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductQAController extends CommonController
{

    /** @var ImageService */
    private $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function store(Request $request)
    {
        if (!$product = Product::find($request->productId)) {
            return response()->json(['status' => false, 'message' => 'Product Not Found.', 'statusCode' => 404], 404);
        }

        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'productId' => 'required|integer',
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

        $qa = ProductQa::create(['vendor_id' => $product->vendor->id, 'product_id' => $request->productId, 'user_id' => $user->id, 'question' => $request->question]);
        return (new UserQAResource($qa))->additional(['status' => true, 'message' => "Thank you for questioning.", 'statusCode' => 200], 200);
    }

    public function ratingReview(Request $request)
    {
        if (!$product = Product::find($request->productId)) {
            return response()->json(['status' => false, 'message' => 'Product Not Found.', 'statusCode' => 404], 404);
        }

        $validator = Validator::make($request->all(), [
            'productId' => 'required|integer',
            'review' => 'nullable|string',
            'rating' => 'required|integer|max:5',
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

        $review = ProductReviewRating::create([
            'vendor_id' => $product->vendor->id,
            'product_id' => $request->productId,
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

        return (new ProductReviewResource($review))->additional(['status' => true, 'message' => "Thank you for reviewing.", 'statusCode' => 200], 200);
    }

    public function updateRating(Request $request, $reviewId)
    {
        if (!$review = ProductReviewRating::find($reviewId)) {
            return response()->json(['status' => false, 'message' => 'Review Not Found.', 'statusCode' => 404], 404);
        }

        $validator = Validator::make($request->all(), [
            'review' => 'nullable|string',
            'rating' => 'required|integer|max:5',
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

        $review = $review->update([
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

        return (new ProductReviewResource($review))->additional(['status' => true, 'message' => "Thank you for reviewing.", 'statusCode' => 200], 200);
    }
}
