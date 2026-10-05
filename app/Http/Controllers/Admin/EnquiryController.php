<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = Enquiry::with('villa');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('phone', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%")
                  ->orWhere('villa_name', 'like', "%{$term}%")
                  ->orWhere('preferred_location', 'like', "%{$term}%")
                  ->orWhere('message', 'like', "%{$term}%");
            });
        }

        $enquiries = $query->latest()->paginate(15)->withQueryString();

        return view('admin.enquiries.index', compact('enquiries'));
    }

    public function updateStatus(Request $request, Enquiry $enquiry)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,qualified,booked,closed',
            'admin_notes' => 'nullable|string',
        ]);

        $enquiry->update($validated);

        return back()->with('success', 'Enquiry updated successfully.');
    }

    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();
        return back()->with('success', 'Enquiry deleted.');
    }
}
