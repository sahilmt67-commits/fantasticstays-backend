<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'region',
        'image',
        'description',
        'detail_heading',
        'places',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_featured',
        'page_type',
        'display_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'display_order' => 'integer',
        'places' => 'array',
    ];

    public function publicHref(): string
    {
        if (($this->page_type ?: 'listing') === 'luxury_rental') {
            return '/luxury-rental/villas-in-'.$this->slug;
        }

        return '/villas?location='.rawurlencode((string) $this->name);
    }

    public function seoFields(): array
    {
        $title = trim((string) $this->meta_title);
        if ($title === '') {
            $title = $this->name.' | Fantastic Stays';
        }

        $description = trim((string) $this->meta_description);
        if ($description === '') {
            $description = trim(html_entity_decode(strip_tags(str_replace('&nbsp;', ' ', (string) $this->description))));
            $description = trim((string) preg_replace('/\s+/', ' ', $description));
            $description = $description === '' ? '' : Str::limit($description, 160, '');
        }

        $keywords = trim((string) $this->meta_keywords);
        if ($keywords === '') {
            $keywords = implode(', ', array_filter([
                $this->name.' villas',
                'luxury villas in '.$this->name,
                $this->region.' villas',
                trim($this->name.' '.$this->region),
                'Fantastic Stays',
            ]));
        }

        return [
            'meta_title' => $title,
            'meta_description' => $description,
            'meta_keywords' => $keywords,
            'metaTitle' => $title,
            'metaDescription' => $description,
            'metaKeywords' => $keywords,
        ];
    }

    public function placesAsText(): string
    {
        return collect($this->places ?? [])
            ->map(function ($place) {
                $name = trim((string) ($place['name'] ?? ''));
                if ($name === '') {
                    return null;
                }
                $distance = trim((string) ($place['distance'] ?? ''));
                $category = trim((string) ($place['category'] ?? ''));
                $line = $name;
                if ($distance !== '' || $category !== '') {
                    $line .= ' | '.$distance;
                }
                if ($category !== '') {
                    $line .= ' | '.$category;
                }

                return $line;
            })
            ->filter()
            ->implode("\n");
    }

    public function villas()
    {
        return $this->hasMany(Villa::class);
    }
}
