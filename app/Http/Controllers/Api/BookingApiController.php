<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Villa;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookingApiController extends Controller
{
    /**
     * Create a new booking request
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'villa_id' => 'required|exists:villas,id',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:50',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $villa = Villa::findOrFail($request->input('villa_id'));
        $checkIn = Carbon::parse($request->input('check_in'));
        $checkOut = Carbon::parse($request->input('check_out'));
        $nights = max(1, $checkIn->diffInDays($checkOut));

        $adults = (int) $request->input('adults', 1);
        $children = (int) $request->input('children', 0);
        $totalGuests = $adults + $children;

        // Check if exceeds capacity
        if ($totalGuests > $villa->guests + 2) {
            return response()->json([
                'success' => false,
                'message' => "Maximum guests allowed for this villa is {$villa->guests}.",
            ], 422);
        }

        // Check for conflicting confirmed bookings
        $conflict = Booking::where('villa_id', $villa->id)
            ->whereIn('status', ['confirmed', 'completed'])
            ->where(function ($q) use ($checkIn, $checkOut) {
                $q->whereBetween('check_in', [$checkIn, $checkOut])
                  ->orWhereBetween('check_out', [$checkIn, $checkOut])
                  ->orWhere(function ($sub) use ($checkIn, $checkOut) {
                      $sub->where('check_in', '<=', $checkIn)
                          ->where('check_out', '>=', $checkOut);
                  });
            })
            ->exists();

        if ($conflict) {
            return response()->json([
                'success' => false,
                'message' => 'The selected dates are already booked for this villa. Please choose alternate dates.',
            ], 409);
        }

        $pricePerNight = (float) $villa->price_per_night;
        $subtotal = $pricePerNight * $nights;
        $taxRate = (float) Setting::get('tax_rate_percent', 18);
        $taxes = round($subtotal * ($taxRate / 100), 2);
        $totalAmount = $subtotal + $taxes;

        $bookingNumber = 'FS-' . date('Y') . '-' . strtoupper(Str::random(5));

        $booking = Booking::create([
            'booking_number' => $bookingNumber,
            'villa_id' => $villa->id,
            'guest_name' => $request->input('guest_name'),
            'guest_email' => $request->input('guest_email'),
            'guest_phone' => $request->input('guest_phone'),
            'check_in' => $checkIn->format('Y-m-d'),
            'check_out' => $checkOut->format('Y-m-d'),
            'adults' => $adults,
            'children' => $children,
            'total_guests' => $totalGuests,
            'nights' => $nights,
            'price_per_night' => $pricePerNight,
            'subtotal' => $subtotal,
            'taxes' => $taxes,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'special_requests' => $request->input('special_requests'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking request submitted successfully! Our luxury concierge will contact you shortly.',
            'data' => [
                'booking_number' => $booking->booking_number,
                'villa' => $villa->name,
                'check_in' => $booking->check_in->format('d M Y'),
                'check_out' => $booking->check_out->format('d M Y'),
                'nights' => $nights,
                'total_amount' => $totalAmount,
                'status' => $booking->status,
            ]
        ], 201);
    }

    /**
     * Check if dates are available for booking
     */
    public function checkAvailability(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'villa_id' => 'required|exists:villas,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $checkIn = Carbon::parse($request->input('check_in'));
        $checkOut = Carbon::parse($request->input('check_out'));

        $isBooked = Booking::where('villa_id', $request->input('villa_id'))
            ->whereIn('status', ['confirmed', 'completed'])
            ->where(function ($q) use ($checkIn, $checkOut) {
                $q->whereBetween('check_in', [$checkIn, $checkOut])
                  ->orWhereBetween('check_out', [$checkIn, $checkOut])
                  ->orWhere(function ($sub) use ($checkIn, $checkOut) {
                      $sub->where('check_in', '<=', $checkIn)
                          ->where('check_out', '>=', $checkOut);
                  });
            })
            ->exists();

        return response()->json([
            'success' => true,
            'available' => !$isBooked,
            'message' => $isBooked ? 'Villa is reserved on these dates' : 'Villa is available for booking',
        ]);
    }
}
