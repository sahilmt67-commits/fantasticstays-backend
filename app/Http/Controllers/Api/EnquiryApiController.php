<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Villa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnquiryApiController extends Controller
{
    /**
     * Submit an inquiry or WhatsApp booking lead
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'villa_id' => 'nullable|exists:villas,id',
            'villa_slug' => 'nullable|string',
            'check_in' => 'nullable|date',
            'check_out' => 'nullable|date',
            'guests' => 'nullable|integer|min:0',
            'adults' => 'nullable|integer|min:0|max:50',
            'children' => 'nullable|integer|min:0|max:50',
            'infants' => 'nullable|integer|min:0|max:20',
            'pets' => 'nullable|integer|min:0|max:10',
            'preferred_location' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:1000',
            'source' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $villaId = $request->input('villa_id');
        $villaName = null;

        if (!$villaId && $request->filled('villa_slug')) {
            $villa = Villa::where('slug', $request->input('villa_slug'))->first();
            if ($villa) {
                $villaId = $villa->id;
                $villaName = $villa->name;
            }
        } elseif ($villaId) {
            $villa = Villa::find($villaId);
            $villaName = $villa?->name;
        }

        $adults = $request->filled('adults') ? (int) $request->input('adults') : null;
        $children = $request->filled('children') ? (int) $request->input('children') : null;
        $infants = $request->filled('infants') ? (int) $request->input('infants') : null;
        $pets = $request->filled('pets') ? (int) $request->input('pets') : null;
        $guests = $request->input('guests');
        if ($adults !== null || $children !== null) {
            $guests = (int) $adults + (int) $children;
        }

        $enquiry = Enquiry::create([
            'villa_id' => $villaId,
            'villa_name' => $villaName,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'check_in' => $request->input('check_in'),
            'check_out' => $request->input('check_out'),
            'guests' => $guests,
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
            'pets' => $pets,
            'preferred_location' => $request->input('preferred_location'),
            'message' => $request->input('message'),
            'source' => $request->input('source', 'website'),
            'status' => 'new',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your inquiry has been received. Our luxury villa expert will connect with you via WhatsApp or phone.',
            'data' => [
                'enquiry_id' => $enquiry->id,
            ]
        ], 201);
    }
}
