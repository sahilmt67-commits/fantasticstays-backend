<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\JsonResponse;

class LocationApiController extends Controller
{
    public function index(): JsonResponse
    {
        $locations = Location::withCount(['villas' => function ($q) {
            $q->where('status', 'active');
        }])
        ->orderBy('display_order')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $locations,
        ]);
    }
}
