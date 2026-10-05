<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AmenityController extends Controller
{
    public function index()
    {
        $amenities = Amenity::orderBy('display_order')->get();
        return view('admin.amenities.index', compact('amenities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'nullable|string|max:50|unique:amenities,key',
            'category' => 'required|string|max:100',
            'icon' => 'nullable|string|max:50',
            'is_filter' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ]);

        $key = !empty($validated['key']) ? Str::slug($validated['key']) : Str::slug($validated['name']);
        if (Amenity::where('key', $key)->exists()) {
            $key .= '-' . Str::random(3);
        }

        Amenity::create([
            'name' => $validated['name'],
            'key' => $key,
            'category' => $validated['category'],
            'icon' => $validated['icon'] ?? 'bi-stars',
            'is_filter' => $request->boolean('is_filter'),
            'display_order' => $validated['display_order'] ?? 0,
        ]);

        return back()->with('success', 'Amenity created successfully.');
    }

    public function update(Request $request, Amenity $amenity)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:50|unique:amenities,key,' . $amenity->id,
            'category' => 'required|string|max:100',
            'icon' => 'nullable|string|max:50',
            'is_filter' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ]);

        $amenity->update([
            'name' => $validated['name'],
            'key' => Str::slug($validated['key']),
            'category' => $validated['category'],
            'icon' => $validated['icon'] ?? $amenity->icon,
            'is_filter' => $request->boolean('is_filter'),
            'display_order' => $validated['display_order'] ?? $amenity->display_order,
        ]);

        return back()->with('success', 'Amenity updated.');
    }

    public function destroy(Amenity $amenity)
    {
        $amenity->delete();
        return back()->with('success', 'Amenity deleted.');
    }
}
