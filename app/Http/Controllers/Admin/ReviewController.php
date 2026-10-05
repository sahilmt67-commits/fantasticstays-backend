<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Villa;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with('villa');

        if ($request->has('status') && $request->input('status') !== '') {
            $isApproved = $request->input('status') === 'approved';
            $query->where('is_approved', $isApproved);
        }

        if ($request->filled('villa_id')) {
            $query->where('villa_id', $request->input('villa_id'));
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();
        $villas = Villa::orderBy('name')->get();

        return view('admin.reviews.index', compact('reviews', 'villas'));
    }

    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'guest_location' => 'nullable|string|max:255',
            'villa_id' => 'required|exists:villas,id',
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'required|string|max:2000',
            'stay_date' => 'nullable|string|max:50',
        ]);

        $review->update($validated);

        return back()->with('success', 'Review updated.');
    }

    public function toggleApproval(Review $review)
    {
        $review->is_approved = !$review->is_approved;
        $review->save();

        return back()->with('success', 'Review approval status updated.');
    }

    public function toggleFeatured(Review $review)
    {
        $review->is_featured = !$review->is_featured;
        $review->save();

        return back()->with('success', 'Review featured status updated.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }
}
