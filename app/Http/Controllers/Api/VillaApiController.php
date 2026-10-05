<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Location;
use App\Models\Villa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VillaApiController extends Controller
{
    /**
     * Get list of villas with search, filter, and sorting
     * Designed to seamlessly power Next.js frontend filters (Property Type, Bedrooms, Guests, Areas, Price, Amenities)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Villa::with(['location'])->where('status', 'active');

        // 1. Text Search: name, area, or description
        $searchTerm = $request->input('search') ?? $request->input('query') ?? $request->input('q');
        if (!empty($searchTerm)) {
            $term = trim($searchTerm);
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('slug', 'like', "%{$term}%")
                  ->orWhere('location_name', 'like', "%{$term}%")
                  ->orWhere('region', 'like', "%{$term}%")
                  ->orWhere('short_description', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%")
                  ->orWhere('address', 'like', "%{$term}%");
            });
        }

        // 2. Property Type: Apartments, Classic, Budgeted, Luxe
        $propertyType = $request->input('property_type') ?? $request->input('propertyType');
        if (!empty($propertyType)) {
            $type = strtolower(trim($propertyType));
            if ($type === 'apartments' || $type === 'apartment') {
                $query->where(function ($q) {
                    $q->where('property_type', 'apartments')
                      ->orWhere('bedrooms', '<=', 2)
                      ->orWhere('name', 'like', '%apartment%')
                      ->orWhere('name', 'like', '%flat%');
                });
            } elseif ($type === 'classic') {
                $query->where(function ($q) {
                    $q->where('property_type', 'classic')
                      ->orWhereBetween('price_per_night', [18000, 35000]);
                });
            } elseif ($type === 'budgeted' || $type === 'budget') {
                $query->where(function ($q) {
                    $q->where('property_type', 'budgeted')
                      ->orWhere('price_per_night', '<', 20000);
                });
            } elseif ($type === 'luxe' || $type === 'luxury') {
                $query->where(function ($q) {
                    $q->where('property_type', 'luxe')
                      ->orWhere('price_per_night', '>=', 30000)
                      ->orWhere('badge', 'like', '%FEATURED%')
                      ->orWhere('badge', 'like', '%EXCLUSIVE%')
                      ->orWhere('badge', 'like', '%BEST%')
                      ->orWhere('is_featured', true);
                });
            }
        }

        // 3. Location / Region / Area checkboxes
        // Can receive 'areas' array, comma-separated 'areas', 'location', or 'region'
        $areasInput = $request->input('areas') ?? $request->input('locations') ?? $request->input('location');
        if (!empty($areasInput)) {
            $areaList = is_array($areasInput) ? $areasInput : explode(',', $areasInput);
            $areaList = array_filter(array_map('trim', $areaList));

            if (!empty($areaList)) {
                $query->where(function ($q) use ($areaList) {
                    foreach ($areaList as $area) {
                        if ($area === 'North Goa' || $area === 'South Goa') {
                            $q->orWhere('region', $area);
                        } else {
                            $q->orWhere('location_name', 'like', "%{$area}%")
                              ->orWhereHas('location', function ($lq) use ($area) {
                                  $lq->where('name', 'like', "%{$area}%")
                                     ->orWhere('slug', $area);
                              });
                        }
                    }
                });
            }
        }

        // Direct Region filter (north/south)
        $regionInput = $request->input('region');
        if (!empty($regionInput) && $regionInput !== 'all') {
            if (strtolower($regionInput) === 'north' || $regionInput === 'North Goa') {
                $query->where('region', 'North Goa');
            } elseif (strtolower($regionInput) === 'south' || $regionInput === 'South Goa') {
                $query->where('region', 'South Goa');
            }
        }

        // 4. Bedrooms (BHK): 1, 2, 3, 4, 5, 6, 7, 8, 9, 10+
        $bedroomsInput = $request->input('bedrooms') ?? $request->input('bhk');
        if (!empty($bedroomsInput) && $bedroomsInput !== '0' && $bedroomsInput !== 0) {
            $bedsVal = str_replace('+', '', (string) $bedroomsInput);
            $bedsInt = (int) $bedsVal;
            if ($bedsInt >= 10 || str_contains((string) $bedroomsInput, '+')) {
                $query->where('bedrooms', '>=', 10);
            } elseif ($bedsInt > 0) {
                $query->where('bedrooms', $bedsInt);
            }
        }

        // 5. Guests: Any, 4+, 6+, 8+, 10+, 12+
        $guestsInput = $request->input('guests');
        if (!empty($guestsInput) && $guestsInput !== '0' && strtolower($guestsInput) !== 'any') {
            $guestsVal = (int) str_replace('+', '', (string) $guestsInput);
            if ($guestsVal > 0) {
                $query->where('guests', '>=', $guestsVal);
            }
        }

        // 6. Price Range (e.g. ₹15,000 to ₹90,000)
        $minPrice = $request->input('price_min') ?? $request->input('priceMin') ?? $request->input('min_price');
        $maxPrice = $request->input('price_max') ?? $request->input('priceMax') ?? $request->input('max_price');

        if (!empty($minPrice)) {
            $query->where('price_per_night', '>=', (float) $minPrice);
        }
        if (!empty($maxPrice)) {
            $query->where('price_per_night', '<=', (float) $maxPrice);
        }

        // 7. Amenities Checklist: pool, beach, pet, chef, hk, family, event, sea, instant
        $amenityInput = $request->input('amenities') ?? $request->input('amenity_keys') ?? $request->input('amenityKeys');
        if (!empty($amenityInput)) {
            $amenityList = is_array($amenityInput) ? $amenityInput : explode(',', $amenityInput);
            foreach ($amenityList as $key) {
                $key = trim($key);
                if (!empty($key)) {
                    $query->where(function ($q) use ($key) {
                        $q->whereJsonContains('amenity_keys', $key)
                          ->orWhere('amenities', 'like', "%{$key}%")
                          ->orWhere('tags', 'like', "%{$key}%");
                    });
                }
            }
        }

        // 8. Sorting
        $sort = $request->input('sort', 'recommended');
        switch ($sort) {
            case 'price-asc':
            case 'price_asc':
                $query->orderBy('price_per_night', 'asc');
                break;
            case 'price-desc':
            case 'price_desc':
                $query->orderBy('price_per_night', 'desc');
                break;
            case 'rating':
            case 'rating_desc':
                $query->orderBy('rating', 'desc');
                break;
            case 'popular':
            case 'reviews':
                $query->orderBy('reviews_count', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'recommended':
            case 'featured':
            default:
                $query->orderBy('is_featured', 'desc')->orderBy('rating', 'desc');
                break;
        }

        // Calculate dynamic counts for each amenity key matching the active base filter
        $baseVillas = Villa::where('status', 'active')->get();
        $amenityCounts = [
            'pool' => 0,
            'beach' => 0,
            'pet' => 0,
            'chef' => 0,
            'hk' => 0,
            'family' => 0,
            'event' => 0,
            'sea' => 0,
            'instant' => 0,
        ];

        foreach ($baseVillas as $v) {
            $keys = $v->amenity_keys ?? [];
            $amenities = is_array($v->amenities) ? implode(' ', $v->amenities) : '';
            $tags = is_array($v->tags) ? implode(' ', $v->tags) : '';
            $allText = strtolower($amenities . ' ' . $tags);

            if (in_array('pool', $keys) || str_contains($allText, 'pool')) $amenityCounts['pool']++;
            if (in_array('beach', $keys) || str_contains($allText, 'beach')) $amenityCounts['beach']++;
            if (in_array('pet', $keys) || str_contains($allText, 'pet')) $amenityCounts['pet']++;
            if (in_array('chef', $keys) || str_contains($allText, 'chef')) $amenityCounts['chef']++;
            if (in_array('hk', $keys) || str_contains($allText, 'housekeeping')) $amenityCounts['hk']++;
            if (in_array('family', $keys) || str_contains($allText, 'family')) $amenityCounts['family']++;
            if (in_array('event', $keys) || str_contains($allText, 'event')) $amenityCounts['event']++;
            if (in_array('sea', $keys) || str_contains($allText, 'sea')) $amenityCounts['sea']++;
            if ($v->instant_booking) $amenityCounts['instant']++;
        }

        $perPage = (int) $request->input('per_page', 20);
        $villas = $query->paginate($perPage);

        // Format each villa according to Next.js schema
        $data = collect($villas->items())->map(function ($villa) {
            return $villa->toFrontendFormat();
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'amenityCounts' => $amenityCounts,
            'meta' => [
                'current_page' => $villas->currentPage(),
                'last_page' => $villas->lastPage(),
                'per_page' => $villas->perPage(),
                'total' => $villas->total(),
            ]
        ]);
    }

    /**
     * Get single villa details by slug
     */
    public function show(string $slug): JsonResponse
    {
        $villa = Villa::with([
            'location',
            'rooms',
            'attractions',
            'reviews',
            'faqs'
        ])
        ->where('slug', $slug)
        ->orWhere('id', $slug)
        ->first();

        if (!$villa) {
            return response()->json([
                'success' => false,
                'message' => 'Villa not found',
            ], 404);
        }

        // Get similar villas in same region/location
        $similarVillas = Villa::where('id', '!=', $villa->id)
            ->where('status', 'active')
            ->where(function ($q) use ($villa) {
                $q->where('location_id', $villa->location_id)
                  ->orWhere('region', $villa->region);
            })
            ->take(3)
            ->get()
            ->map(fn($v) => $v->toFrontendFormat());

        $formatted = $villa->toFrontendFormat();
        $formatted['similarVillas'] = $similarVillas;

        return response()->json([
            'success' => true,
            'data' => $formatted,
        ]);
    }

    /**
     * Get featured villas for homepage
     */
    public function featured(): JsonResponse
    {
        $villas = Villa::with(['location'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->orderBy('rating', 'desc')
            ->take(8)
            ->get()
            ->map(fn($v) => $v->toFrontendFormat());

        return response()->json([
            'success' => true,
            'data' => $villas,
        ]);
    }

    /**
     * Get filter options for search form and modal
     */
    public function filterOptions(): JsonResponse
    {
        $locations = Location::select('id', 'name', 'slug', 'region')->orderBy('display_order')->get();
        $amenityFilters = Amenity::where('is_filter', true)->orderBy('display_order')->get();
        
        $priceMin = Villa::where('status', 'active')->min('price_per_night') ?? 15000;
        $priceMax = Villa::where('status', 'active')->max('price_per_night') ?? 90000;

        return response()->json([
            'success' => true,
            'data' => [
                'property_types' => [
                    ['key' => 'apartments', 'label' => 'Apartments', 'icon' => 'bi-building'],
                    ['key' => 'classic', 'label' => 'Classic', 'icon' => 'bi-house-door'],
                    ['key' => 'budgeted', 'label' => 'Budgeted', 'icon' => 'bi-clock'],
                    ['key' => 'luxe', 'label' => 'Luxe', 'icon' => 'bi-star'],
                ],
                'bedroom_options' => [1, 2, 3, 4, 5, 6, 7, 8, 9, '10+'],
                'guest_options' => ['Any', '4+', '6+', '8+', '10+', '12+'],
                'locations' => [
                    'North Goa',
                    'South Goa',
                    'Assagao',
                    'Anjuna',
                    'Vagator',
                    'Siolim',
                    'Candolim',
                    'Calangute',
                    'Morjim',
                ],
                'amenities' => [
                    ['key' => 'pool', 'label' => 'Private swimming pool'],
                    ['key' => 'beach', 'label' => 'Beachfront or near the beach'],
                    ['key' => 'pet', 'label' => 'Pet-friendly villas'],
                    ['key' => 'chef', 'label' => 'Chef service'],
                    ['key' => 'hk', 'label' => 'Housekeeping'],
                    ['key' => 'family', 'label' => 'Family-friendly stays'],
                    ['key' => 'event', 'label' => 'Event-friendly villas'],
                    ['key' => 'sea', 'label' => 'Sea view'],
                ],
                'price_range' => [
                    'min' => (float) $priceMin,
                    'max' => (float) $priceMax,
                ],
            ]
        ]);
    }
}
