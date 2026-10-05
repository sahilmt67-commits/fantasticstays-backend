<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceLead;
use Illuminate\Http\Request;

class ServiceLeadController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceLead::query();

        if ($request->filled('kind')) {
            $query->where('kind', $request->input('kind'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('message', 'like', "%{$term}%");
            });
        }

        $leads = $query->latest()->paginate(15)->withQueryString();

        return view('admin.service-leads.index', compact('leads'));
    }

    public function updateStatus(Request $request, ServiceLead $serviceLead)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,closed',
        ]);

        $serviceLead->update($validated);

        return back()->with('success', 'Lead updated.');
    }

    public function destroy(ServiceLead $serviceLead)
    {
        $serviceLead->delete();

        return back()->with('success', 'Lead deleted.');
    }
}
