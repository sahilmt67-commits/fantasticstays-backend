<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Review::with('villa:id,name,slug')->where('is_approved', true);

        if ($request->filled('villa_id')) {
            $query->where('villa_id', $request->input('villa_id'));
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $reviews = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $reviews,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'villa_id' => 'required|exists:villas,id',
            'guest_name' => 'required|string|max:255',
            'guest_location' => 'nullable|string|max:255',
            'rating' => 'required|numeric|min:1|max:5',
            'cleanliness_rating' => 'nullable|numeric|min:1|max:5',
            'accuracy_rating' => 'nullable|numeric|min:1|max:5',
            'communication_rating' => 'nullable|numeric|min:1|max:5',
            'location_rating' => 'nullable|numeric|min:1|max:5',
            'value_rating' => 'nullable|numeric|min:1|max:5',
            'comment' => 'required|string|min:10|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $review = Review::create([
            'villa_id' => $request->input('villa_id'),
            'guest_name' => $request->input('guest_name'),
            'guest_location' => $request->input('guest_location'),
            'rating' => $request->input('rating'),
            'cleanliness_rating' => $request->input('cleanliness_rating', $request->input('rating')),
            'accuracy_rating' => $request->input('accuracy_rating', $request->input('rating')),
            'communication_rating' => $request->input('communication_rating', $request->input('rating')),
            'location_rating' => $request->input('location_rating', $request->input('rating')),
            'value_rating' => $request->input('value_rating', $request->input('rating')),
            'comment' => $request->input('comment'),
            'stay_date' => date('F Y'),
            'is_approved' => false, // Requires admin moderation
            'is_featured' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your review! It will be visible after verification by our team.',
            'data' => $review,
        ], 201);
    }
}
