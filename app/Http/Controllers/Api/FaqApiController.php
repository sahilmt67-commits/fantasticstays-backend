<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaqApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Faq::where('is_active', true);

        if ($request->filled('villa_id')) {
            $villaId = $request->input('villa_id');
            $query->where(function ($q) use ($villaId) {
                $q->where('villa_id', $villaId)->orWhereNull('villa_id');
            });
        }

        $faqs = $query->orderBy('display_order')->get();

        return response()->json([
            'success' => true,
            'data' => $faqs,
        ]);
    }
}
