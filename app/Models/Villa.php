<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Villa extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'location_id',
        'location_name',
        'region',
        'price_per_night',
        'bedrooms',
        'bathrooms',
        'guests',
        'beds',
        'sleep_heading',
        'sleep_intro',
        'amenity_eyebrow',
        'amenity_heading',
        'amenity_groups',
        'services_eyebrow',
        'services_heading',
        'services_intro',
        'services',
        'rating',
        'reviews_count',
        'badge',
        'property_type',
        'listing_badges',
        'amenity_keys',
        'instant_booking',
        'amenities',
        'tags',
        'image',
        'gallery',
        'videos',
        'short_description',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'house_rules',
        'check_in_time',
        'check_out_time',
        'address',
        'latitude',
        'longitude',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'price_per_night' => 'float',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'guests' => 'integer',
        'beds' => 'integer',
        'rating' => 'float',
        'reviews_count' => 'integer',
        'instant_booking' => 'boolean',
        'is_featured' => 'boolean',
        'listing_badges' => 'array',
        'amenity_keys' => 'array',
        'amenities' => 'array',
        'tags' => 'array',
        'gallery' => 'array',
        'videos' => 'array',
        'amenity_groups' => 'array',
        'services' => 'array',
        'house_rules' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function locationProfile(): ?array
    {
        if (! $this->relationLoaded('location') || ! $this->location) {
            return null;
        }

        $location = $this->location;

        return [
            'name' => $location->name,
            'region' => $location->region,
            'heading' => $location->detail_heading,
            'description' => $location->description,
            'image' => $location->image,
            'places' => $location->places ?? [],
        ];
    }

    public function rooms()
    {
        return $this->hasMany(VillaRoom::class)->orderBy('display_order');
    }

    public function attractions()
    {
        return $this->hasMany(VillaAttraction::class)->orderBy('display_order');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function enquiries()
    {
        return $this->hasMany(Enquiry::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true)->latest();
    }

    public function allReviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class)->where('is_active', true)->orderBy('display_order');
    }

    public function reviewsAsText(): string
    {
        return $this->allReviews()->orderBy('id')->get()->map(function (Review $review) {
            $rating = $this->scoreText($review->rating);
            $header = implode(' | ', [
                $review->guest_name,
                $review->guest_location ?? '',
                $review->stay_date ?? '',
                $rating,
            ]);
            $scores = implode(' | ', [
                $this->scoreText($review->cleanliness_rating ?? $review->rating),
                $this->scoreText($review->accuracy_rating ?? $review->rating),
                $this->scoreText($review->communication_rating ?? $review->rating),
                $this->scoreText($review->location_rating ?? $review->rating),
                $this->scoreText($review->value_rating ?? $review->rating),
            ]);

            $comment = trim((string) preg_replace("/\n{2,}/", "\n", (string) $review->comment));

            return $header."\n".$scores."\n".$comment;
        })->implode("\n\n");
    }

    public function faqRows(): array
    {
        $faqs = Faq::where('villa_id', $this->id)->orderBy('display_order')->orderBy('id')->get();
        if ($faqs->isEmpty()) {
            $faqs = Faq::whereNull('villa_id')->orderBy('display_order')->orderBy('id')->get();
        }

        return $faqs->map(fn (Faq $faq) => [
            'question' => $faq->question,
            'answer' => $faq->answer,
        ])->all();
    }

    private function scoreText(mixed $value): string
    {
        $number = (float) $value;

        return rtrim(rtrim(number_format($number, 1, '.', ''), '0'), '.');
    }

    /**
     * Transform to Next.js schema format for API responses
     */
    public function toFrontendFormat(): array
    {
        return [
            'id' => (string) $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'location' => $this->location_name ?? ($this->location->name ?? 'Assagao'),
            'region' => $this->region,
            'price' => (float) $this->price_per_night,
            'bedrooms' => (int) $this->bedrooms,
            'bathrooms' => (int) $this->bathrooms,
            'guests' => (int) $this->guests,
            'beds' => (int) $this->beds,
            'sleepHeading' => $this->sleep_heading,
            'sleepIntro' => $this->sleep_intro,
            'amenityEyebrow' => $this->amenity_eyebrow,
            'amenityHeading' => $this->amenity_heading,
            'amenityGroups' => $this->amenity_groups ?? [],
            'servicesEyebrow' => $this->services_eyebrow,
            'servicesHeading' => $this->services_heading,
            'servicesIntro' => $this->services_intro,
            'services' => $this->services ?? [],
            'rating' => (float) $this->rating,
            'reviews' => (int) $this->reviews_count,
            'badge' => $this->badge,
            'propertyType' => $this->property_type ?? 'luxe',
            'property_type' => $this->property_type ?? 'luxe',
            'listingBadges' => $this->listing_badges ?? [],
            'amenityKeys' => $this->amenity_keys ?? [],
            'instantBooking' => (bool) $this->instant_booking,
            'amenities' => $this->amenities ?? [],
            'tags' => $this->tags ?? [],
            'image' => $this->image,
            'gallery' => $this->gallery ?? [],
            'videos' => $this->videos ?? [],
            'shortDescription' => $this->short_description ?? '',
            'description' => $this->description ?? '',
            'metaTitle' => $this->meta_title ?? ($this->name . ' | Fantastic Stays Goa'),
            'metaDescription' => $this->meta_description ?? ($this->short_description ?? ''),
            'metaKeywords' => $this->meta_keywords ?? 'luxury villa goa, private pool villa, holiday home assagao',
            'meta_title' => $this->meta_title ?? ($this->name . ' | Fantastic Stays Goa'),
            'meta_description' => $this->meta_description ?? ($this->short_description ?? ''),
            'meta_keywords' => $this->meta_keywords ?? 'luxury villa goa, private pool villa, holiday home assagao',
            'featured' => (bool) $this->is_featured,
            'checkInTime' => $this->check_in_time,
            'checkOutTime' => $this->check_out_time,
            'houseRules' => $this->house_rules,
            'address' => $this->address,
            'locationProfile' => $this->locationProfile(),
            'rooms' => $this->relationLoaded('rooms') ? $this->rooms : [],
            'attractions' => $this->relationLoaded('attractions') ? $this->attractions : [],
            'guestReviews' => $this->relationLoaded('reviews') ? $this->reviews : [],
            'faqs' => $this->relationLoaded('faqs') ? $this->faqs : [],
        ];
    }
}
