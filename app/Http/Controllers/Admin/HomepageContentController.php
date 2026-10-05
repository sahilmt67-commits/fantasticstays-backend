<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageExperience;
use App\Models\HomepageFaq;
use App\Models\HomepagePost;
use App\Models\HomepageStat;
use App\Models\Setting;
use Illuminate\Http\Request;

class HomepageContentController extends Controller
{
    public function updateCopy(Request $request)
    {
        $tab = $request->input('tab', 'experiences');
        $fields = [
            'experiences' => ['experiences_eyebrow', 'experiences_heading', 'experiences_note'],
            'about' => ['about_eyebrow', 'about_heading', 'about_body', 'about_rating_caption', 'about_image'],
            'inspiration' => ['inspiration_eyebrow', 'inspiration_heading'],
            'faqs' => ['faq_eyebrow', 'faq_heading'],
            'listing' => [
                'listing_eyebrow',
                'listing_heading',
                'listing_paragraph_1',
                'listing_paragraph_2',
                'listing_stat_villas_label',
                'listing_stat_rating_label',
                'listing_response',
                'listing_response_label',
                'listing_badge',
                'listing_image',
                'listing_image_small',
            ],
            'services' => [
                'sale_eyebrow',
                'sale_heading',
                'sale_intro',
                'sale_point_1',
                'sale_point_2',
                'sale_point_3',
                'sale_form_heading',
                'sale_meta_title',
                'sale_meta_description',
                'sale_meta_keywords',
                'list_eyebrow',
                'list_heading',
                'list_intro',
                'list_stat_1_value',
                'list_stat_1_label',
                'list_stat_2_value',
                'list_stat_2_label',
                'list_stat_3_value',
                'list_stat_3_label',
                'list_stat_4_value',
                'list_stat_4_label',
                'list_benefit_1_title',
                'list_benefit_1_text',
                'list_benefit_2_title',
                'list_benefit_2_text',
                'list_benefit_3_title',
                'list_benefit_3_text',
                'list_benefit_4_title',
                'list_benefit_4_text',
                'list_form_heading',
                'list_meta_title',
                'list_meta_description',
                'list_meta_keywords',
            ],
        ];

        $imageFallbacks = [
            'about_image' => '/images/demo/about.webp',
            'listing_image' => '/images/demo/home-villa-specialist-welcoming-guests-at-a-luxury-goa-.webp',
            'listing_image_small' => '/images/demo/loc-assagao.webp',
        ];

        foreach ($fields[$tab] ?? [] as $key) {
            $value = $request->input($key);
            if (isset($imageFallbacks[$key])) {
                $value = $this->storagePath($value) ?? $imageFallbacks[$key];
            }
            Setting::set($key, $value ?? '', 'homepage');
        }

        return redirect()
            ->route('admin.settings.index', ['tab' => $tab])
            ->with('success', 'Section updated.');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
        ]);

        $path = $request->file('image')->store('homepage', 'public');

        return response()->json([
            'url' => '/storage/'.$path,
        ]);
    }

    public function storeExperience(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'display_order' => 'nullable|integer',
        ]);

        HomepageExperience::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'display_order' => $validated['display_order'] ?? 0,
        ]);

        return $this->back('experiences', 'Experience added.');
    }

    public function updateExperience(Request $request, HomepageExperience $experience)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'display_order' => 'nullable|integer',
        ]);

        $experience->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'display_order' => $validated['display_order'] ?? $experience->display_order,
        ]);

        return $this->back('experiences', 'Experience updated.');
    }

    public function destroyExperience(HomepageExperience $experience)
    {
        $experience->delete();

        return $this->back('experiences', 'Experience deleted.');
    }

    public function storeStat(Request $request)
    {
        HomepageStat::create($this->statPayload($request));

        return $this->back('about', 'Stat added.');
    }

    public function updateStat(Request $request, HomepageStat $stat)
    {
        $stat->update($this->statPayload($request, $stat->display_order));

        return $this->back('about', 'Stat updated.');
    }

    public function destroyStat(HomepageStat $stat)
    {
        $stat->delete();

        return $this->back('about', 'Stat deleted.');
    }

    public function storePost(Request $request)
    {
        HomepagePost::create($this->postPayload($request));

        return $this->back('inspiration', 'Article added.');
    }

    public function updatePost(Request $request, HomepagePost $post)
    {
        $post->update($this->postPayload($request, $post->display_order));

        return $this->back('inspiration', 'Article updated.');
    }

    public function destroyPost(HomepagePost $post)
    {
        $post->delete();

        return $this->back('inspiration', 'Article deleted.');
    }

    public function storeFaq(Request $request)
    {
        HomepageFaq::create($this->faqPayload($request));

        return $this->back('faqs', 'FAQ added.');
    }

    public function updateFaq(Request $request, HomepageFaq $homepageFaq)
    {
        $homepageFaq->update($this->faqPayload($request, $homepageFaq->display_order));

        return $this->back('faqs', 'FAQ updated.');
    }

    public function destroyFaq(HomepageFaq $homepageFaq)
    {
        $homepageFaq->delete();

        return $this->back('faqs', 'FAQ deleted.');
    }

    private function statPayload(Request $request, ?int $currentOrder = 0): array
    {
        $validated = $request->validate([
            'value' => 'nullable|string|max:50',
            'label' => 'required|string|max:255',
            'source' => 'required|in:manual,villas,locations,rating',
            'display_order' => 'nullable|integer',
        ]);

        return [
            'value' => $validated['value'] ?? '',
            'label' => $validated['label'],
            'source' => $validated['source'],
            'is_active' => $request->boolean('is_active'),
            'display_order' => $validated['display_order'] ?? $currentOrder,
        ];
    }

    private function postPayload(Request $request, ?int $currentOrder = 0): array
    {
        $validated = $request->validate([
            'published_label' => 'nullable|string|max:50',
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'display_order' => 'nullable|integer',
        ]);

        return [
            'published_label' => $validated['published_label'] ?? null,
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'display_order' => $validated['display_order'] ?? $currentOrder,
        ];
    }

    private function faqPayload(Request $request, ?int $currentOrder = 0): array
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'display_order' => 'nullable|integer',
        ]);

        return [
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'is_active' => $request->boolean('is_active'),
            'display_order' => $validated['display_order'] ?? $currentOrder,
        ];
    }

    private function storagePath(?string $value): ?string
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

    private function back(string $tab, string $message)
    {
        return redirect()
            ->route('admin.settings.index', ['tab' => $tab])
            ->with('success', $message);
    }
}
