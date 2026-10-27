<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Services\WebpImage;
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
        WebpImage::validate($request);

        $directory = public_path('storage/locations');

        try {
            $filename = WebpImage::store($request->file('image'), $directory);
        } catch (\Throwable $e) {
            report($e);

            return WebpImage::failed();
        }

        return WebpImage::response('/storage/locations/'.$filename, $request->file('image'), $directory, $filename);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'image' => 'nullable|string',
            'description' => 'nullable|string',
            'detail_heading' => 'nullable|string|max:255',
            'places_raw' => 'nullable|string',
            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:200',
            'meta_keywords' => 'nullable|string|max:1000',
            'is_featured' => 'nullable|boolean',
            'page_type' => 'nullable|in:listing,luxury_rental',
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
            'description' => $this->editorHtml($validated['description'] ?? null),
            'detail_heading' => $this->blankToNull($validated['detail_heading'] ?? null),
            'places' => $this->placesFromRaw($validated['places_raw'] ?? null),
            'is_featured' => $request->boolean('is_featured'),
            'page_type' => $validated['page_type'] ?? 'listing',
            'display_order' => $validated['display_order'] ?? 0,
        ] + $this->seoAttributes($validated));

        return back()->with('success', 'Location created successfully.');
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'image' => 'nullable|string',
            'description' => 'nullable|string',
            'detail_heading' => 'nullable|string|max:255',
            'places_raw' => 'nullable|string',
            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:200',
            'meta_keywords' => 'nullable|string|max:1000',
            'is_featured' => 'nullable|boolean',
            'page_type' => 'nullable|in:listing,luxury_rental',
            'display_order' => 'nullable|integer',
        ]);

        $location->update([
            'name' => $validated['name'],
            'region' => $validated['region'],
            'image' => $this->storagePath($validated['image'] ?? null) ?? $location->image,
            'description' => $this->editorHtml($validated['description'] ?? null),
            'detail_heading' => $this->blankToNull($validated['detail_heading'] ?? null),
            'places' => $this->placesFromRaw($validated['places_raw'] ?? null),
            'is_featured' => $request->boolean('is_featured'),
            'page_type' => $validated['page_type'] ?? 'listing',
            'display_order' => $validated['display_order'] ?? $location->display_order,
        ] + $this->seoAttributes($validated));

        return back()->with('success', 'Location updated.');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return back()->with('success', 'Location deleted.');
    }

    private function seoAttributes(array $validated): array
    {
        $name = $validated['name'];
        $region = $validated['region'];

        return [
            'meta_title' => $this->blankToNull($validated['meta_title'] ?? null) ?? ($name.' | Fantastic Stays'),
            'meta_description' => $this->blankToNull($validated['meta_description'] ?? null) ?? $this->plainExcerpt($validated['description'] ?? null),
            'meta_keywords' => $this->blankToNull($validated['meta_keywords'] ?? null) ?? $this->defaultKeywords($name, $region),
        ];
    }

    private function plainExcerpt(?string $html): ?string
    {
        $text = trim(html_entity_decode(strip_tags(str_replace('&nbsp;', ' ', (string) $html))));
        $text = trim((string) preg_replace('/\s+/', ' ', $text));

        return $text === '' ? null : Str::limit($text, 160, '');
    }

    private function defaultKeywords(string $name, string $region): string
    {
        return implode(', ', array_filter([
            $name.' villas',
            'luxury villas in '.$name,
            $region.' villas',
            trim($name.' '.$region),
            'Fantastic Stays',
        ]));
    }

    private function editorHtml(mixed $html): ?string
    {
        $html = trim((string) $html);
        $text = trim(html_entity_decode(strip_tags(str_replace('&nbsp;', ' ', $html))));

        return $text === '' ? null : $html;
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
