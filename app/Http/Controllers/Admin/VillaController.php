<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Faq;
use App\Models\Location;
use App\Models\Review;
use App\Models\Villa;
use App\Models\VillaRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VillaController extends Controller
{
    public function index(Request $request)
    {
        $query = Villa::with('location');

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('location_name', 'like', "%{$term}%");
            });
        }

        if ($request->filled('region')) {
            $query->where('region', $request->input('region'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->boolean('featured'));
        }

        $villas = $query->latest()->paginate(10)->withQueryString();
        $locations = Location::all();

        return view('admin.villas.index', compact('villas', 'locations'));
    }

    public function create()
    {
        $locations = Location::orderBy('name')->get();
        $amenities = Amenity::orderBy('display_order')->get();
        return view('admin.villas.create', compact('locations', 'amenities'));
    }

    /**
     * AJAX: Upload a single villa image to storage, return its URL.
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
        ]);

        $path = $request->file('image')->store('villas', 'public');
        return response()->json([
            'url' => Storage::url($path),
            'path' => $path,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:villas,slug',
            'location_id' => 'nullable|exists:locations,id',
            'location_name' => 'nullable|string|max:255',
            'region' => 'required|in:North Goa,South Goa',
            'price_per_night' => 'required|numeric|min:0',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'guests' => 'required|integer|min:1',
            'beds' => 'required|integer|min:1',
            'rating' => 'nullable|numeric|min:1|max:5',
            'badge' => 'nullable|string|max:50',
            'property_type' => 'required|in:apartments,classic,budgeted,luxe',
            'image' => 'required|string',
            'gallery_raw' => 'nullable|string',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'sleep_heading' => 'nullable|string|max:255',
            'sleep_intro' => 'nullable|string',
            'amenity_eyebrow' => 'nullable|string|max:255',
            'amenity_heading' => 'nullable|string|max:255',
            'amenity_groups_raw' => 'nullable|string',
            'services_eyebrow' => 'nullable|string|max:255',
            'services_heading' => 'nullable|string|max:255',
            'services_intro' => 'nullable|string',
            'services_raw' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'meta_keywords' => 'nullable|string|max:1000',
            'amenities_list' => 'nullable|array',
            'tags_raw' => 'nullable|string',
            'check_in_time' => 'nullable|string',
            'check_out_time' => 'nullable|string',
            'address' => 'nullable|string',
            'rule_cancellation' => 'nullable|string',
            'rule_deposit' => 'nullable|string',
            'rule_pets' => 'nullable|string',
            'rule_parties' => 'nullable|string',
            'rule_smoking' => 'nullable|string',
            'rule_quiet_hours' => 'nullable|string',
            'rule_child_policy' => 'nullable|string',
            'rule_identification' => 'nullable|string',
            'reviews_count' => 'nullable|integer|min:0',
            'reviews_raw' => 'nullable|string',
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'nullable|string|max:500',
            'faqs.*.answer' => 'nullable|string',
            'video_source' => 'nullable|in:youtube,instagram',
            'video_url' => 'nullable|string|max:500',
            'video_thumb' => 'nullable|string|max:500',
            'is_featured' => 'nullable|boolean',
            'instant_booking' => 'nullable|boolean',
            'status' => 'required|in:active,inactive,maintenance',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        // Check uniqueness
        if (Villa::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::random(4);
        }

        // Parse gallery
        $gallery = [];
        if (!empty($request->input('gallery_raw'))) {
            $lines = preg_split('/\r\n|\r|\n/', $request->input('gallery_raw'));
            foreach ($lines as $line) {
                $trimmed = trim($line);
                if (!empty($trimmed)) {
                    $gallery[] = $trimmed;
                }
            }
        }
        if (empty($gallery)) {
            $gallery = [$validated['image']];
        }

        // Parse tags
        $tags = [];
        if (!empty($request->input('tags_raw'))) {
            $tags = array_map('trim', explode(',', $request->input('tags_raw')));
        }

        // Amenities selected
        $amenityKeys = $request->input('amenities_list', []);
        $amenityNames = Amenity::whereIn('key', $amenityKeys)->pluck('name')->toArray();

        // Location name
        $locationName = $validated['location_name'];
        if (!empty($validated['location_id'])) {
            $loc = Location::find($validated['location_id']);
            if ($loc) $locationName = $loc->name;
        }

        $villa = Villa::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'location_id' => $validated['location_id'],
            'location_name' => $locationName,
            'region' => $validated['region'],
            'price_per_night' => $validated['price_per_night'],
            'bedrooms' => $validated['bedrooms'],
            'bathrooms' => $validated['bathrooms'],
            'guests' => $validated['guests'],
            'beds' => $validated['beds'],
            'rating' => $validated['rating'] ?? 5.0,
            'badge' => $validated['badge'],
            'property_type' => $validated['property_type'],
            'image' => $validated['image'],
            'gallery' => $gallery,
            'videos' => $this->videosFromRequest($request),
            'amenity_keys' => $amenityKeys,
            'amenities' => $amenityNames,
            'tags' => $tags,
            'short_description' => $validated['short_description'],
            'description' => $validated['description'],
            'sleep_heading' => $validated['sleep_heading'] ?? null,
            'sleep_intro' => $validated['sleep_intro'] ?? null,
            'amenity_eyebrow' => $validated['amenity_eyebrow'] ?? null,
            'amenity_heading' => $validated['amenity_heading'] ?? null,
            'amenity_groups' => $this->amenityGroupsFromRaw($validated['amenity_groups_raw'] ?? null),
            'services_eyebrow' => $validated['services_eyebrow'] ?? null,
            'services_heading' => $validated['services_heading'] ?? null,
            'services_intro' => $validated['services_intro'] ?? null,
            'services' => $this->servicesFromRaw($validated['services_raw'] ?? null),
            'meta_title' => $validated['meta_title'] ?? ($validated['name'] . ' | Fantastic Stays'),
            'meta_description' => $validated['meta_description'] ?? ($validated['short_description'] ?? ''),
            'meta_keywords' => $validated['meta_keywords'] ?? 'luxury villa goa, private pool villa, vacation rental',
            'check_in_time' => $validated['check_in_time'] ?? '02:00 PM',
            'check_out_time' => $validated['check_out_time'] ?? '11:00 AM',
            'address' => $validated['address'],
            'house_rules' => $this->houseRulesFromRequest($request),
            'reviews_count' => $validated['reviews_count'] ?? 0,
            'is_featured' => $request->boolean('is_featured'),
            'instant_booking' => $request->boolean('instant_booking'),
            'status' => $validated['status'],
        ]);

        $this->syncReviews($villa, $validated['reviews_raw'] ?? null);
        $this->syncFaqs($villa, $request->input('faqs', []));

        return redirect()->route('admin.villas.rooms', $villa->id)->with('success', 'Villa created successfully! Now you can configure its bedroom layouts.');
    }

    public function edit(Villa $villa)
    {
        $locations = Location::orderBy('name')->get();
        $amenities = Amenity::orderBy('display_order')->get();
        return view('admin.villas.edit', compact('villa', 'locations', 'amenities'));
    }

    public function update(Request $request, Villa $villa)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:villas,slug,' . $villa->id,
            'location_id' => 'nullable|exists:locations,id',
            'location_name' => 'nullable|string|max:255',
            'region' => 'required|in:North Goa,South Goa',
            'price_per_night' => 'required|numeric|min:0',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'guests' => 'required|integer|min:1',
            'beds' => 'required|integer|min:1',
            'rating' => 'nullable|numeric|min:1|max:5',
            'badge' => 'nullable|string|max:50',
            'property_type' => 'required|in:apartments,classic,budgeted,luxe',
            'image' => 'required|string',
            'gallery_raw' => 'nullable|string',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'sleep_heading' => 'nullable|string|max:255',
            'sleep_intro' => 'nullable|string',
            'amenity_eyebrow' => 'nullable|string|max:255',
            'amenity_heading' => 'nullable|string|max:255',
            'amenity_groups_raw' => 'nullable|string',
            'services_eyebrow' => 'nullable|string|max:255',
            'services_heading' => 'nullable|string|max:255',
            'services_intro' => 'nullable|string',
            'services_raw' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'meta_keywords' => 'nullable|string|max:1000',
            'amenities_list' => 'nullable|array',
            'tags_raw' => 'nullable|string',
            'check_in_time' => 'nullable|string',
            'check_out_time' => 'nullable|string',
            'address' => 'nullable|string',
            'rule_cancellation' => 'nullable|string',
            'rule_deposit' => 'nullable|string',
            'rule_pets' => 'nullable|string',
            'rule_parties' => 'nullable|string',
            'rule_smoking' => 'nullable|string',
            'rule_quiet_hours' => 'nullable|string',
            'rule_child_policy' => 'nullable|string',
            'rule_identification' => 'nullable|string',
            'reviews_count' => 'nullable|integer|min:0',
            'reviews_raw' => 'nullable|string',
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'nullable|string|max:500',
            'faqs.*.answer' => 'nullable|string',
            'video_source' => 'nullable|in:youtube,instagram',
            'video_url' => 'nullable|string|max:500',
            'video_thumb' => 'nullable|string|max:500',
            'is_featured' => 'nullable|boolean',
            'instant_booking' => 'nullable|boolean',
            'status' => 'required|in:active,inactive,maintenance',
        ]);

        // Parse gallery
        $gallery = [];
        if (!empty($request->input('gallery_raw'))) {
            $lines = preg_split('/\r\n|\r|\n/', $request->input('gallery_raw'));
            foreach ($lines as $line) {
                $trimmed = trim($line);
                if (!empty($trimmed)) {
                    $gallery[] = $trimmed;
                }
            }
        }
        if (empty($gallery)) {
            $gallery = $villa->gallery ?? [$validated['image']];
        }

        // Parse tags
        $tags = [];
        if (!empty($request->input('tags_raw'))) {
            $tags = array_map('trim', explode(',', $request->input('tags_raw')));
        }

        // Amenities selected
        $amenityKeys = $request->input('amenities_list', []);
        $amenityNames = Amenity::whereIn('key', $amenityKeys)->pluck('name')->toArray();

        // Location name
        $locationName = $validated['location_name'];
        if (!empty($validated['location_id'])) {
            $loc = Location::find($validated['location_id']);
            if ($loc) $locationName = $loc->name;
        }

        $villa->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['slug']),
            'location_id' => $validated['location_id'],
            'location_name' => $locationName,
            'region' => $validated['region'],
            'price_per_night' => $validated['price_per_night'],
            'bedrooms' => $validated['bedrooms'],
            'bathrooms' => $validated['bathrooms'],
            'guests' => $validated['guests'],
            'beds' => $validated['beds'],
            'rating' => $validated['rating'] ?? 5.0,
            'badge' => $validated['badge'],
            'property_type' => $validated['property_type'],
            'image' => $validated['image'],
            'gallery' => $gallery,
            'videos' => $this->videosFromRequest($request),
            'amenity_keys' => $amenityKeys,
            'amenities' => $amenityNames,
            'tags' => $tags,
            'short_description' => $validated['short_description'],
            'description' => $validated['description'],
            'sleep_heading' => $validated['sleep_heading'] ?? null,
            'sleep_intro' => $validated['sleep_intro'] ?? null,
            'amenity_eyebrow' => $validated['amenity_eyebrow'] ?? null,
            'amenity_heading' => $validated['amenity_heading'] ?? null,
            'amenity_groups' => $this->amenityGroupsFromRaw($validated['amenity_groups_raw'] ?? null),
            'services_eyebrow' => $validated['services_eyebrow'] ?? null,
            'services_heading' => $validated['services_heading'] ?? null,
            'services_intro' => $validated['services_intro'] ?? null,
            'services' => $this->servicesFromRaw($validated['services_raw'] ?? null),
            'meta_title' => $validated['meta_title'] ?? ($validated['name'] . ' | Fantastic Stays'),
            'meta_description' => $validated['meta_description'] ?? ($validated['short_description'] ?? ''),
            'meta_keywords' => $validated['meta_keywords'] ?? 'luxury villa goa, private pool villa, vacation rental',
            'check_in_time' => $validated['check_in_time'] ?? '02:00 PM',
            'check_out_time' => $validated['check_out_time'] ?? '11:00 AM',
            'address' => $validated['address'],
            'house_rules' => $this->houseRulesFromRequest($request),
            'reviews_count' => $validated['reviews_count'] ?? $villa->reviews_count,
            'is_featured' => $request->boolean('is_featured'),
            'instant_booking' => $request->boolean('instant_booking'),
            'status' => $validated['status'],
        ]);

        $this->syncReviews($villa, $validated['reviews_raw'] ?? null);
        $this->syncFaqs($villa, $request->input('faqs', []));

        return redirect()->route('admin.villas.index')->with('success', "Villa '{$villa->name}' updated successfully.");
    }

    public function destroy(Villa $villa)
    {
        $name = $villa->name;
        $villa->delete();
        return redirect()->route('admin.villas.index')->with('success', "Villa '{$name}' deleted successfully.");
    }

    /**
     * Bedroom Layouts manager
     */
    public function rooms(Villa $villa)
    {
        $villa->load('rooms');
        return view('admin.villas.rooms', compact('villa'));
    }

    public function storeRoom(Request $request, Villa $villa)
    {
        $validated = $request->validate([
            'room_name' => 'required|string|max:255',
            'bed_type' => 'required|string|max:100',
            'image' => 'nullable|string',
            'ensuite_bath' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        $villa->rooms()->create([
            'room_name' => $validated['room_name'],
            'bed_type' => $validated['bed_type'],
            'image' => $this->roomImagePath($validated['image'] ?? null) ?? $villa->image,
            'ensuite_bath' => $request->boolean('ensuite_bath'),
            'description' => $validated['description'],
            'display_order' => $villa->rooms()->count() + 1,
        ]);

        return back()->with('success', 'Room added successfully.');
    }

    public function updateRoom(Request $request, VillaRoom $room)
    {
        $validated = $request->validate([
            'room_name' => 'required|string|max:255',
            'bed_type' => 'required|string|max:100',
            'image' => 'nullable|string',
            'ensuite_bath' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        $room->update([
            'room_name' => $validated['room_name'],
            'bed_type' => $validated['bed_type'],
            'image' => $this->roomImagePath($validated['image'] ?? null) ?? $room->image,
            'ensuite_bath' => $request->boolean('ensuite_bath'),
            'description' => $validated['description'],
        ]);

        return back()->with('success', 'Room updated.');
    }

    public function deleteRoom(VillaRoom $room)
    {
        $room->delete();
        return back()->with('success', 'Room deleted.');
    }

    public function toggleFeatured(Villa $villa)
    {
        $villa->is_featured = !$villa->is_featured;
        $villa->save();
        return back()->with('success', 'Featured status updated.');
    }

    private function amenityGroupsFromRaw(?string $raw): array
    {
        $groups = [];
        $current = null;
        foreach (preg_split('/\r\n|\r|\n/', (string) $raw) as $line) {
            if (trim($line) === '') {
                $current = null;
                continue;
            }
            if ($current === null) {
                $groups[] = ['title' => trim($line), 'items' => []];
                $current = array_key_last($groups);
            } else {
                $groups[$current]['items'][] = trim($line);
            }
        }

        return array_values(array_filter($groups, fn (array $group) => $group['title'] !== ''));
    }

    private function houseRulesFromRequest(Request $request): array
    {
        $rules = [];
        foreach ([
            'cancellation' => 'rule_cancellation',
            'deposit' => 'rule_deposit',
            'pets' => 'rule_pets',
            'parties' => 'rule_parties',
            'smoking' => 'rule_smoking',
            'quiet_hours' => 'rule_quiet_hours',
            'child_policy' => 'rule_child_policy',
            'identification' => 'rule_identification',
        ] as $key => $input) {
            $value = trim((string) $request->input($input, ''));
            if ($value !== '') {
                $rules[$key] = $value;
            }
        }

        return $rules;
    }

    private function syncReviews(Villa $villa, ?string $raw): void
    {
        $existing = $villa->allReviews()->get();
        $parsed = $this->reviewsFromRaw($raw);
        $villa->allReviews()->delete();

        foreach ($parsed as $review) {
            $match = $existing->first(function (Review $row) use ($review) {
                return $row->guest_name === $review['guest_name'] && $row->comment === $review['comment'];
            });
            $review['is_approved'] = $match->is_approved ?? true;
            $review['is_featured'] = $match->is_featured ?? false;
            $villa->allReviews()->create($review);
        }
    }

    private function reviewsFromRaw(?string $raw): array
    {
        $reviews = [];
        $blocks = preg_split("/\n\s*\n/", trim((string) $raw)) ?: [];
        foreach ($blocks as $block) {
            $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $block) ?: []), fn ($line) => $line !== ''));
            if (count($lines) < 2) {
                continue;
            }
            $parts = array_map('trim', explode('|', $lines[0]));
            $name = $parts[0] ?? '';
            $commentLines = array_slice($lines, 1);
            if ($name === '' || $commentLines === []) {
                continue;
            }
            $rating = $this->scoreValue($parts[3] ?? null, 5);
            $scores = [$rating, $rating, $rating, $rating, $rating];
            if (isset($commentLines[0]) && preg_match('/^\d+(?:\.\d+)?(?:\s*\|\s*\d+(?:\.\d+)?){4}$/', $commentLines[0])) {
                $scoreParts = array_map('trim', explode('|', array_shift($commentLines)));
                foreach ($scoreParts as $index => $score) {
                    $scores[$index] = $this->scoreValue($score, $rating);
                }
            }
            $comment = trim(implode("\n", $commentLines));
            if ($comment === '') {
                continue;
            }
            $reviews[] = [
                'guest_name' => $name,
                'guest_location' => ($parts[1] ?? '') !== '' ? $parts[1] : null,
                'stay_date' => ($parts[2] ?? '') !== '' ? $parts[2] : null,
                'rating' => $rating,
                'cleanliness_rating' => $scores[0],
                'accuracy_rating' => $scores[1],
                'communication_rating' => $scores[2],
                'location_rating' => $scores[3],
                'value_rating' => $scores[4],
                'comment' => $comment,
            ];
        }

        return $reviews;
    }

    private function syncFaqs(Villa $villa, array $rows): void
    {
        Faq::where('villa_id', $villa->id)->delete();
        $order = 0;
        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }
            $question = trim((string) ($row['question'] ?? ''));
            $answer = trim((string) ($row['answer'] ?? ''));
            if ($question === '' || $answer === '') {
                continue;
            }
            $villa->faqs()->create([
                'question' => $question,
                'answer' => $answer,
                'category' => 'General',
                'display_order' => $order,
                'is_active' => true,
            ]);
            $order++;
        }
    }

    private function scoreValue(mixed $value, float $fallback): float
    {
        if ($value === null || trim((string) $value) === '' || ! is_numeric($value)) {
            return min(5, max(1, $fallback));
        }

        return min(5, max(1, (float) $value));
    }

    private function servicesFromRaw(?string $raw): array
    {
        $services = [];
        foreach (preg_split('/\r\n|\r|\n/', (string) $raw) as $line) {
            $line = trim($line);
            if ($line === '' || !str_contains($line, '|')) {
                continue;
            }
            [$status, $name] = array_map('trim', explode('|', $line, 2));
            if ($name === '') {
                continue;
            }
            $services[] = [
                'name' => $name,
                'included' => str_starts_with(strtolower($status), 'inc'),
            ];
        }

        return $services;
    }

    private function videosFromRequest(Request $request): array
    {
        $source = $request->input('video_source');
        $url = trim((string) $request->input('video_url', ''));
        $thumb = trim((string) $request->input('video_thumb', ''));

        if (! in_array($source, ['youtube', 'instagram'], true) || $url === '') {
            return [];
        }

        if ($source === 'youtube') {
            $embed = $this->youtubeEmbed($url);
            if ($embed === null) {
                return [];
            }

            return [[
                'id' => 'tour',
                'title' => 'Video Tour',
                'thumb' => '',
                'embed' => $embed,
                'source' => 'youtube',
            ]];
        }

        return [[
            'id' => 'tour',
            'title' => 'Video Tour',
            'thumb' => $thumb,
            'embed' => $url,
            'source' => 'instagram',
        ]];
    }

    private function youtubeEmbed(string $url): ?string
    {
        if (preg_match('~(?:youtube\.com/(?:embed|shorts)/|youtu\.be/)([A-Za-z0-9_-]{6,})~i', $url, $matches)) {
            return 'https://www.youtube.com/embed/'.$matches[1];
        }
        if (preg_match('~[?&]v=([A-Za-z0-9_-]{6,})~', $url, $matches)) {
            return 'https://www.youtube.com/embed/'.$matches[1];
        }

        return null;
    }

    private function roomImagePath(?string $value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }
        if (str_starts_with($value, '/storage/')) {
            return $value;
        }
        $path = parse_url($value, PHP_URL_PATH);
        if (is_string($path) && str_starts_with($path, '/storage/')) {
            return $path;
        }

        return $value;
    }
}
