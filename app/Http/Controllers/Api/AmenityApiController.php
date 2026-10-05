<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\JsonResponse;

class AmenityApiController extends Controller
{
    public function index(): JsonResponse
    {
        $amenities = Amenity::orderBy('display_order')->get();
        $filters = Amenity::where('is_filter', true)->orderBy('display_order')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'all' => $amenities,
                'filters' => $filters,
            ]
        ]);
    }
}
