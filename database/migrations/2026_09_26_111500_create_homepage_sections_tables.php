<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_experiences', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        Schema::create('homepage_stats', function (Blueprint $table) {
            $table->id();
            $table->string('value')->nullable();
            $table->string('label');
            $table->string('source')->default('manual');
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        Schema::create('homepage_posts', function (Blueprint $table) {
            $table->id();
            $table->string('published_label')->nullable();
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        Schema::create('homepage_faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        $now = now();

        DB::table('homepage_experiences')->insert(array_map(
            fn (array $row, int $index) => $row + [
                'is_active' => true,
                'display_order' => $index + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                ['title' => 'Private Chef Experiences', 'description' => 'Bespoke menus crafted in your villa by skilled private chefs.'],
                ['title' => 'Airport & Local Transfers', 'description' => 'Comfortable, reliable transfers arranged on request.'],
                ['title' => 'Yacht & Sunset Cruises', 'description' => 'Sail along the Goan coastline for an unforgettable evening.'],
                ['title' => 'Beach Club Reservations', 'description' => 'Premium sunbeds and access at Goa\'s finest beach clubs.'],
                ['title' => 'Birthday & Anniversary Setups', 'description' => 'Thoughtful decor to celebrate your special moments.'],
                ['title' => 'Wedding & Event Assistance', 'description' => 'End-to-end planning support for villa celebrations.'],
                ['title' => 'Wellness & Spa Sessions', 'description' => 'In-villa spa, yoga and wellness experiences.'],
                ['title' => 'Local Sightseeing Experiences', 'description' => 'Curated days out with trusted local guides.'],
            ],
            array_keys([0, 1, 2, 3, 4, 5, 6, 7])
        ));

        DB::table('homepage_stats')->insert([
            ['value' => '10+', 'label' => 'Years of Local Experience', 'source' => 'manual', 'is_active' => true, 'display_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['value' => '', 'label' => 'Villas Curated', 'source' => 'villas', 'is_active' => true, 'display_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['value' => '5,000+', 'label' => 'Happy Guests', 'source' => 'manual', 'is_active' => true, 'display_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['value' => '', 'label' => 'Goa Locations Covered', 'source' => 'locations', 'is_active' => true, 'display_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['value' => '', 'label' => 'Average Guest Rating', 'source' => 'rating', 'is_active' => true, 'display_order' => 5, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('homepage_posts')->insert(array_map(
            fn (array $row, int $index) => $row + [
                'is_active' => true,
                'display_order' => $index + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                ['published_label' => '12 Jul 2025', 'title' => 'Best Areas to Rent a Luxury Villa in Goa', 'excerpt' => 'A neighbourhood-by-neighbourhood guide to choosing the right villa location for your trip.'],
                ['published_label' => '28 Jun 2025', 'title' => 'North Goa vs South Goa for a Villa Holiday', 'excerpt' => 'Comparing vibe, beaches and villa options to help you decide where to stay.'],
                ['published_label' => '15 Jun 2025', 'title' => 'How to Choose the Right Goa Villa for a Large Group', 'excerpt' => 'Bedrooms, privacy, amenities and layout — what matters most for group stays.'],
                ['published_label' => '02 Jun 2025', 'title' => 'Best Time to Visit Goa for a Villa Stay', 'excerpt' => 'Seasons, weather and pricing windows to plan the perfect villa holiday.'],
            ],
            array_keys([0, 1, 2, 3])
        ));

        $faqs = [
            ['How can I book a luxury villa in Goa?', 'Submit your dates, group size and preferred location via our enquiry form or WhatsApp. Our villa specialists will share curated options, confirm pricing and guide you through a simple booking process.'],
            ['Are the villas suitable for families and large groups?', 'Yes. Many of our villas are ideal for families and large groups, with multiple bedrooms, spacious living areas, private pools and outdoor spaces designed for shared time together.'],
            ['Do all villas have private swimming pools?', 'Most properties in our collection feature a private swimming pool. Pool size and style vary by villa — we will confirm the details for your shortlisted options.'],
            ['Is housekeeping included?', 'Daily housekeeping is included with every stay. Frequency and any additional services can be confirmed at the time of booking.'],
            ['Can meals or private chef services be arranged?', 'Yes. Private chef and catering services can be arranged on request for most villas, subject to availability.'],
            ['Are parties and events allowed at the villas?', 'Many villas permit small celebrations and gatherings. Larger parties or events may require prior approval and additional charges. Please share your plans when you enquire.'],
            ['Is a security deposit required?', 'A refundable security deposit is standard for most villas and is typically collected before check-in. The amount and refund timeline are confirmed at booking.'],
            ['Can airport transfers be organised?', 'Yes. Airport and local transfers can be arranged on request for most stays.'],
            ['What is the cancellation policy?', 'Policies vary by property. Many offer free cancellation up to a set number of days before check-in. Full details are shared before you confirm.'],
            ['How do I check villa availability and final pricing?', 'Send us your travel dates, guest count and preferred location. Our team will confirm availability and share final pricing for suitable villas.'],
        ];

        DB::table('homepage_faqs')->insert(array_map(
            fn (array $row, int $index) => [
                'question' => $row[0],
                'answer' => $row[1],
                'is_active' => true,
                'display_order' => $index + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            $faqs,
            array_keys($faqs)
        ));

        $copy = [
            'experiences_eyebrow' => 'Goa Experiences',
            'experiences_heading' => 'Make Your Goa Holiday Even More Memorable',
            'experiences_note' => 'Services are subject to availability and may involve additional charges.',
            'about_eyebrow' => 'About the Company',
            'about_heading' => 'Your Trusted Partner for Luxury Villa Rentals in Goa',
            'about_body' => 'fantastic stays helps guests discover private villas in Goa suited to their travel requirements. We focus on carefully selected properties, responsive communication, genuine local knowledge and personalised booking support — so every stay feels considered, comfortable and entirely your own.',
            'about_image' => '/images/demo/about.webp',
            'about_rating_caption' => 'Average guest rating across verified stays',
            'inspiration_eyebrow' => 'Goa Travel Inspiration',
            'inspiration_heading' => 'Plan a Better Villa Holiday in Goa',
            'faq_eyebrow' => 'Frequently Asked Questions',
            'faq_heading' => 'Everything You Should Know Before You Book',
        ];

        foreach ($copy as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'group' => 'homepage', 'created_at' => $now, 'updated_at' => $now]
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_faqs');
        Schema::dropIfExists('homepage_posts');
        Schema::dropIfExists('homepage_stats');
        Schema::dropIfExists('homepage_experiences');

        DB::table('settings')->where('group', 'homepage')->delete();
    }
};
