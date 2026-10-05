<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HomepageExperience;
use App\Models\HomepageFaq;
use App\Models\HomepagePost;
use App\Models\HomepageStat;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class HomepageSectionApiController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = Setting::where('group', 'homepage')->pluck('value', 'key');

        $copy = function (string $key, string $fallback) use ($settings) {
            $value = $settings[$key] ?? null;

            return is_string($value) && trim($value) !== '' ? $value : $fallback;
        };

        return response()->json([
            'success' => true,
            'data' => [
                'experiences' => [
                    'eyebrow' => $copy('experiences_eyebrow', 'Goa Experiences'),
                    'heading' => $copy('experiences_heading', 'Make Your Goa Holiday Even More Memorable'),
                    'note' => $copy('experiences_note', 'Services are subject to availability and may involve additional charges.'),
                    'items' => HomepageExperience::where('is_active', true)
                        ->orderBy('display_order')
                        ->orderBy('id')
                        ->get(['title', 'description']),
                ],
                'about' => [
                    'eyebrow' => $copy('about_eyebrow', 'About the Company'),
                    'heading' => $copy('about_heading', 'Your Trusted Partner for Luxury Villa Rentals in Goa'),
                    'body' => $copy('about_body', ''),
                    'image' => $copy('about_image', '/images/demo/about.webp'),
                    'rating_caption' => $copy('about_rating_caption', 'Average guest rating across verified stays'),
                    'stats' => HomepageStat::where('is_active', true)
                        ->orderBy('display_order')
                        ->orderBy('id')
                        ->get(['value', 'label', 'source']),
                ],
                'inspiration' => [
                    'eyebrow' => $copy('inspiration_eyebrow', 'Goa Travel Inspiration'),
                    'heading' => $copy('inspiration_heading', 'Plan a Better Villa Holiday in Goa'),
                    'items' => HomepagePost::where('is_active', true)
                        ->orderBy('display_order')
                        ->orderBy('id')
                        ->get(['published_label', 'title', 'excerpt']),
                ],
                'faqs' => [
                    'eyebrow' => $copy('faq_eyebrow', 'Frequently Asked Questions'),
                    'heading' => $copy('faq_heading', 'Everything You Should Know Before You Book'),
                    'items' => HomepageFaq::where('is_active', true)
                        ->orderBy('display_order')
                        ->orderBy('id')
                        ->get(['question', 'answer']),
                ],
            ],
        ]);
    }
}
