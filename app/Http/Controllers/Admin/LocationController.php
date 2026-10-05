<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::withCount('villas')->orderBy('display_order')->get();
        return view('admin.locations.index', compact('locations'));
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
        ]);

        $path = $request->file('image')->store('locations', 'public');

        return response()->json([
            'url' => '/storage/'.$path,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'region' => 'required|in:North Goa,South Goa',
            'image' => 'nullable|string',
            'description' => 'nullable|string',
            'detail_heading' => 'nullable|string|max:255',
            'places_raw' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ]);

        $slug = Str::slug($validated['name']);
        if (Location::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::random(4);
        }

        Location::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'region' => $validated['region'],
            'image' => $this->storagePath($validated['image'] ?? null) ?? '/images/demo/loc-assagao.webp',
            'description' => $validated['description'],
            'detail_heading' => $this->blankToNull($validated['detail_heading'] ?? null),
            'places' => $this->placesFromRaw($validated['places_raw'] ?? null),
            'is_featured' => $request->boolean('is_featured'),
            'display_order' => $validated['display_order'] ?? 0,
        ]);

        return back()->with('success', 'Location created successfully.');
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'region' => 'required|in:North Goa,South Goa',
            'image' => 'nullable|string',
            'description' => 'nullable|string',
            'detail_heading' => 'nullable|string|max:255',
            'places_raw' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ]);

        $location->update([
            'name' => $validated['name'],
            'region' => $validated['region'],
            'image' => $this->storagePath($validated['image'] ?? null) ?? $location->image,
            'description' => $validated['description'],
            'detail_heading' => $this->blankToNull($validated['detail_heading'] ?? null),
            'places' => $this->placesFromRaw($validated['places_raw'] ?? null),
            'is_featured' => $request->boolean('is_featured'),
            'display_order' => $validated['display_order'] ?? $location->display_order,
        ]);

        return back()->with('success', 'Location updated.');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return back()->with('success', 'Location deleted.');
    }

    private function blankToNull(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function placesFromRaw(?string $raw): array
    {
        $places = [];
        foreach (preg_split('/\r\n|\r|\n/', (string) $raw) as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            $parts = array_map('trim', explode('|', $line));
            $name = $parts[0] ?? '';
            if ($name === '') {
                continue;
            }
            $places[] = [
                'name' => $name,
                'distance' => $parts[1] ?? '',
                'category' => $parts[2] ?? '',
            ];
        }

        return $places;
    }

    private function storagePath(?string $image): ?string
    {
        if ($image === null || $image === '') {
            return null;
        }

        $path = parse_url($image, PHP_URL_PATH) ?: $image;

        return str_starts_with($path, '/storage/') ? $path : $image;
    }
}
