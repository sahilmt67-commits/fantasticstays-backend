<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Occasion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OccasionController extends Controller
{
    public function index()
    {
        $occasions = Occasion::orderBy('display_order')->get();
        return view('admin.occasions.index', compact('occasions'));
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
        ]);

        $path = $request->file('image')->store('occasions', 'public');

        return response()->json([
            'url' => '/storage/'.$path,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'badge' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ]);

        $slug = Str::slug($validated['title']);
        if (Occasion::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::random(4);
        }

        Occasion::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'image' => $this->storagePath($validated['image'] ?? null) ?? '/images/demo/occ-group.webp',
            'badge' => $validated['badge'],
            'is_active' => $request->boolean('is_active'),
            'display_order' => $validated['display_order'] ?? 0,
        ]);

        return back()->with('success', 'Occasion created successfully.');
    }

    public function update(Request $request, Occasion $occasion)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'badge' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ]);

        $occasion->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image' => $this->storagePath($validated['image'] ?? null) ?? $occasion->image,
            'badge' => $validated['badge'],
            'is_active' => $request->boolean('is_active'),
            'display_order' => $validated['display_order'] ?? $occasion->display_order,
        ]);

        return back()->with('success', 'Occasion updated.');
    }

    public function destroy(Occasion $occasion)
    {
        $occasion->delete();
        return back()->with('success', 'Occasion deleted.');
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
