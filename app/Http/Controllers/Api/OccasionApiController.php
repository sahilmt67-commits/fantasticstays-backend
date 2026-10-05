<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Occasion;
use Illuminate\Http\JsonResponse;

class OccasionApiController extends Controller
{
    public function index(): JsonResponse
    {
        $occasions = Occasion::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $occasions,
        ]);
    }
}
