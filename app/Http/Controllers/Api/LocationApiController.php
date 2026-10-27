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

        $data = $locations->map(function (Location $location) {
            return array_merge($location->toArray(), [
                'villas_count' => $location->villas_count,
                'pageType' => $location->page_type ?: 'listing',
                'href' => $location->publicHref(),
            ], $location->seoFields());
        })->values();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
