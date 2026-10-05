<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'display_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'display_order' => 'integer',
        'places' => 'array',
    ];

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
