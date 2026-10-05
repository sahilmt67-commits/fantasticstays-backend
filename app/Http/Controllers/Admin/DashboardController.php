<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Enquiry;
use App\Models\Location;
use App\Models\Review;
use App\Models\Villa;

class DashboardController extends Controller
{
    public function index()
    {
        $totalVillas = Villa::count();
        $activeVillas = Villa::where('status', 'active')->count();
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $totalEnquiries = Enquiry::count();
        $newEnquiries = Enquiry::where('status', 'new')->count();
        $totalRevenue = Booking::whereIn('status', ['confirmed', 'completed'])->sum('total_amount');
        $pendingReviews = Review::where('is_approved', false)->count();

        $recentBookings = Booking::with('villa')->latest()->take(5)->get();
        $recentEnquiries = Enquiry::with('villa')->latest()->take(5)->get();
        $featuredVillas = Villa::where('is_featured', true)->take(4)->get();
        $locations = Location::withCount('villas')->get();

        return view('admin.dashboard.index', compact(
            'totalVillas',
            'activeVillas',
            'totalBookings',
            'pendingBookings',
            'totalEnquiries',
            'newEnquiries',
            'totalRevenue',
            'pendingReviews',
            'recentBookings',
            'recentEnquiries',
            'featuredVillas',
            'locations'
        ));
    }
}
