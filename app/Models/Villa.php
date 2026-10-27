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
        'discount_percent',
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
        'show_on_sale',
        'show_on_list',
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
        'show_on_sale' => 'boolean',
        'show_on_list' => 'boolean',
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
            'slug' => $location->slug,
            'region' => $location->region,
            'heading' => $location->detail_heading,
            'description' => $location->description,
            'image' => $location->image,
            'places' => $location->places ?? [],
        ] + $location->seoFields();
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

    public static function starterContent(): array
    {
        return [
            'house_rules_content' => '<p><strong>Check-in &amp; check-out</strong><br>Check-in is from 02:00 PM and check-out is until 11:00 AM. Early check-in or late check-out is subject to availability.</p>'
                .'<p><strong>Security deposit</strong><br>A refundable security deposit of ₹15,000 is collected at check-in.</p>'
                .'<p><strong>Pet policy</strong><br>Well-trained pets are welcome with advance notice. A small refundable cleaning deposit may apply.</p>'
                .'<p><strong>Parties &amp; events</strong><br>No loud music outdoors after 10:00 PM. Celebrations need prior approval.</p>'
                .'<p><strong>Smoking</strong><br>Smoking is not allowed inside the villa.</p>'
                .'<p><strong>Quiet hours</strong><br>Quiet hours are from 10:00 PM to 8:00 AM.</p>'
                .'<p><strong>Child policy</strong><br>Children are welcome. Please share ages in advance so the villa can be prepared.</p>'
                .'<p><strong>Identification</strong><br>A valid photo ID is required for all adult guests at check-in.</p>',
            'cancellation_policy' => '<ul>'
                .'<li><strong>45 Days or More Before Check-in:</strong> 90% refund of the booking amount.</li>'
                .'<li><strong>44–30 Days Before Check-in:</strong> 75% refund of the booking amount.</li>'
                .'<li><strong>29–15 Days Before Check-in:</strong> 50% refund of the booking amount.</li>'
                .'<li><strong>Less Than 15 Days Before Check-in:</strong> No refund applicable.</li>'
                .'<li><strong>Peak Dates and Long Weekends:</strong> All bookings are non-refundable and non-amendable.</li>'
                .'<li><strong>Peak Period (23rd December – 5th January):</strong> All bookings are non-refundable and non-amendable.</li>'
                .'<li><strong>Lockdown Policy:</strong> In case of a lockdown in Goa or the guest’s city of origin, a 100% refund of the total booking value will be provided.</li>'
                .'<li><strong>Refund Processing:</strong> Refunds will be initiated within 5–7 working days from the date of cancellation.</li>'
                .'</ul>',
            'amenity_eyebrow' => 'Amenities',
            'amenity_heading' => 'Thoughtfully appointed throughout',
            'amenity_groups_raw' => implode("\n", [
                'Popular Amenities',
                'Private Swimming Pool',
                'Air Conditioning',
                'High-Speed Wi-Fi',
                'Daily Housekeeping',
                'Fully Staffed',
                'Power Backup',
                '',
                'Kitchen & Dining',
                'Fully Equipped Kitchen',
                'Wood-Fired Pizza Oven',
                'Barbecue',
                'Dining for 10',
                'Tea/Coffee Station',
                'Refrigerator',
                '',
                'Bedroom & Bathroom',
                'Premium Linen',
                'En-suite Bathrooms',
                'Rain Showers',
                'Bath Toiletries',
                'Wardrobes',
                'Safe in Master Suite',
                '',
                'Entertainment',
                'Smart TV in Living Room',
                'Bluetooth Sound System',
                'Board Games',
                'Book Library',
                'Yoga Deck',
            ]),
            'services_eyebrow' => 'Private Dining & Services',
            'services_heading' => 'Curated services, included or on request',
            'services_intro' => 'A dedicated team ensures your stay is effortless. Some services are part of your stay, and others can be arranged on request.',
            'services_raw' => implode("\n", [
                'included | Local Concierge Support',
                'request | Personal Chef',
                'request | Grocery Shopping Assistance',
                'request | Airport Transfer',
                'request | Car & Scooter Rental',
                'request | Spa & Wellness Treatments',
                'request | Event or Celebration Arrangements',
                'request | Butler Service',
            ]),
            'faqs' => [
                [
                    'question' => 'What are the standard check-in and check-out timings?',
                    'answer' => 'Check-in is from 02:00 PM and check-out is until 11:00 AM. Early check-in or late check-out is subject to availability and prior confirmation with our concierge.',
                ],
                [
                    'question' => 'Is a private chef included with the villa reservation?',
                    'answer' => 'A private chef can be arranged on request. Ingredients and groceries are billed at actuals based on the menu.',
                ],
                [
                    'question' => 'Are pets allowed at the villa?',
                    'answer' => 'Well-trained pets are welcome with advance notice. A small refundable cleaning deposit may apply.',
                ],
                [
                    'question' => 'What is the cancellation and refund policy?',
                    'answer' => 'Bookings cancelled 45 days or more before check-in receive a 90% refund. Cancellations between 29 and 15 days receive a 50% refund. Less than 15 days is non-refundable.',
                ],
                [
                    'question' => 'Is there high-speed internet and power backup available?',
                    'answer' => 'The villa has high-speed Wi-Fi and automatic power backup.',
                ],
            ],
        ];
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
            'location' => $this->location_name ?: ($this->location->name ?? 'Assagao'),
            'locationSlug' => $this->location?->slug,
            'region' => $this->region,
            'price' => (float) $this->price_per_night,
            'discountPercent' => trim((string) $this->discount_percent) !== '' ? trim((string) $this->discount_percent) : null,
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
