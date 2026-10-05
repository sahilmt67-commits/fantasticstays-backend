<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WhyChoose;
use Illuminate\Http\JsonResponse;

class WhyChooseApiController extends Controller
{
    public function index(): JsonResponse
    {
        $points = WhyChoose::where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $points,
        ]);
    }
}
