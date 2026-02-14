<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\DestinationImage;
use App\Models\User;
use App\Models\CustomTourRequest;
use Carbon\Carbon;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'country_id',
        'region',
        'type',
        'description',
        'detailed_overview',
        'what_to_see_do',
        'wildlife_highlights',
        'geography_landscape',
        'best_time_visit',
        'how_to_get_there',
        'accommodation_options',
        'practical_information',
        'cultural_significance',
        'photography_tips',
        'nearby_attractions',
        'interesting_facts',
        // legacy image arrays (kept for backward compatibility)
        'overview_images',
        'activities_images',
        'wildlife_images',
        'landscape_images',
        'accommodation_images',
        'gallery_images',
        // top-level images
        'featured_image',
        'image',
        // geography / metrics
        'latitude',
        'longitude',
        'area_size',
        'area_unit',
        'altitude_min',
        'altitude_max',
        'entry_fee_foreign',
        'entry_fee_resident',
        'entry_fee_local',
        'currency',
        'established_year',
        'annual_visitors',
        'phone',
        'email',
        'website',
        'opening_hours',
        'best_season',
        'climate',
        'avg_temp_high',
        'avg_temp_low',
        'rainfall_annual',
        'is_popular',
        'is_active',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',

        // New fields for block editor & drafts
        'sections_content', // json per-section blocks
        'is_draft',
        'draft_user_id',
        'published_at',
    ];

    protected $casts = [
        'overview_images' => 'array',
        'activities_images' => 'array',
        'wildlife_images' => 'array',
        'landscape_images' => 'array',
        'accommodation_images' => 'array',
        'gallery_images' => 'array',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'entry_fee_foreign' => 'decimal:2',
        'entry_fee_resident' => 'decimal:2',
        'entry_fee_local' => 'decimal:2',
        'area_size' => 'integer',
        'altitude_min' => 'integer',
        'altitude_max' => 'integer',
        'annual_visitors' => 'integer',
        'avg_temp_high' => 'integer',
        'avg_temp_low' => 'integer',
        'rainfall_annual' => 'integer',

        // New casts
        'sections_content' => 'array',
        'is_draft' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($destination) {
            if (empty($destination->slug) && !empty($destination->name)) {
                $destination->slug = Str::slug($destination->name);
            }
        });

        static::updating(function ($destination) {
            if ($destination->isDirty('name') && empty($destination->slug)) {
                $destination->slug = Str::slug($destination->name);
            }
        });

        // When a destination is deleted, its DestinationImage rows will cascade via FK,
        // and the DestinationImage model will remove files from disk on deleting().
    }

    /**
     * Relationships
     */

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class)->orderBy('sort_order');
    }

    public function tours()
    {
        return $this->belongsToMany(Tour::class, 'tour_destinations')
                    ->withPivot('day_number', 'notes')
                    ->withTimestamps();
    }

    /**
     * New relationship: destination images (all images stored via block editor or gallery)
     */
    public function destinationImages()
    {
        return $this->hasMany(DestinationImage::class, 'destination_id')->orderBy('order');
    }

    /**
     * Draft user relation
     */
    public function draftUser()
    {
        return $this->belongsTo(User::class, 'draft_user_id');
    }

    /**
     * Get the featured image URL
     */
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-destination.jpg');
    }

    /**
     * Get the main image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-destination.jpg');
    }

    /**
     * Render a section using sections_content block data (preferred) or fall back to legacy fields.
     * Returns HTML string ready to echo in Blade (not escaped).
     */
    public function renderSection(string $sectionKey)
    {
        // Prefer structured sections_content if present
        $sections = $this->sections_content ?? [];

        if (!empty($sections[$sectionKey]) && is_array($sections[$sectionKey])) {
            $blocks = $sections[$sectionKey];
            return $this->renderBlocksToHtml($blocks, $sectionKey);
        }

        // Fallback: legacy simple fields + arrays (for backward compatibility)
        switch ($sectionKey) {
            case 'overview':
                return $this->parseContentWithImages($this->detailed_overview, $this->overview_images);
            case 'activities':
                return $this->parseContentWithImages($this->what_to_see_do, $this->activities_images);
            case 'wildlife':
                return $this->parseContentWithImages($this->wildlife_highlights, $this->wildlife_images);
            case 'geography':
                return $this->geography_landscape;
            case 'practical':
                return $this->practical_information;
            case 'accommodation':
                return $this->accommodation_options;
            case 'extras':
                return $this->interesting_facts;
            default:
                return '';
        }
    }

    /**
     * Convenience getters for templates
     */
    public function getOverviewWithImagesAttribute()
    {
        return $this->renderSection('overview');
    }

    public function getActivitiesWithImagesAttribute()
    {
        return $this->renderSection('activities');
    }

    public function getWildlifeWithImagesAttribute()
    {
        return $this->renderSection('wildlife');
    }

    /**
     * Render blocks array (from sections_content) to HTML.
     * Supports block types: heading, subheading, text, image (media_id or temp_media_id).
     */
    protected function renderBlocksToHtml(array $blocks, string $sectionKey = null): string
    {
        $html = '';

        foreach ($blocks as $block) {
            $type = $block['type'] ?? 'text';

            switch ($type) {
                case 'heading':
                    $text = htmlspecialchars($block['text'] ?? '', ENT_QUOTES, 'UTF-8');
                    $html .= "<h2 class=\"text-2xl font-bold mt-6 mb-3\">{$text}</h2>";
                    break;

                case 'subheading':
                    $text = htmlspecialchars($block['text'] ?? '', ENT_QUOTES, 'UTF-8');
                    $html .= "<h3 class=\"text-xl font-semibold mt-4 mb-2\">{$text}</h3>";
                    break;

                case 'text':
                    $text = $block['text'] ?? '';
                    // Allow basic line breaks; content may contain markdown later
                    $escaped = nl2br(e($text));
                    $html .= "<div class=\"prose max-w-none text-gray-700 mb-4\">{$escaped}</div>";
                    break;

                case 'image':
                    // prefer persistent media_id, otherwise try to resolve by block_id or temp_media_id fallback
                    $caption = isset($block['caption']) ? e($block['caption']) : '';
                    $imgHtml = '';
                    $imgUrl = null;

                    if (!empty($block['media_id'])) {
                        $img = DestinationImage::find($block['media_id']);
                        if ($img) $imgUrl = $img->thumbnail_path ? asset('storage/' . $img->thumbnail_path) : asset('storage/' . $img->storage_path);
                    } elseif (!empty($block['block_id'])) {
                        // try to find by block_id (useful if images were created and block_id stored)
                        $img = DestinationImage::where('destination_id', $this->id)->where('block_id', $block['block_id'])->first();
                        if ($img) $imgUrl = $img->thumbnail_path ? asset('storage/' . $img->thumbnail_path) : asset('storage/' . $img->storage_path);
                    }

                    // If still no URL, skip rendering (could be temp_media_id before upload)
                    if ($imgUrl) {
                        $imgHtml = '<div class="my-4">';
                        $imgHtml .= '<img src="' . e($imgUrl) . '" alt="' . $caption . '" class="rounded-lg shadow-md max-w-full">';
                        if ($caption) {
                            $imgHtml .= '<p class="text-sm text-gray-600 italic mt-2">' . $caption . '</p>';
                        }
                        $imgHtml .= '</div>';
                        $html .= $imgHtml;
                    }
                    break;

                default:
                    // unknown block -> treat as text
                    $text = $block['text'] ?? '';
                    $html .= "<div class=\"prose max-w-none text-gray-700 mb-4\">" . nl2br(e($text)) . "</div>";
                    break;
            }
        }

        return $html;
    }

    /**
     * Legacy helper: Parse content and inject images based on legacy arrays.
     * Kept for backward compatibility with pre-existing data/schema.
     */
    private function parseContentWithImages($content, $images)
    {
        if (empty($content) || empty($images) || !is_array($images)) {
            return $content;
        }

        foreach ($images as $imageData) {
            $section = $imageData['section'] ?? '';
            $imagePath = $imageData['image'] ?? '';
            $caption = $imageData['caption'] ?? '';

            if ($section && $imagePath) {
                $imageHtml = '<div class="inline-image my-4"><img src="' . asset('storage/' . $imagePath) . '" alt="' . e($caption) . '" class="rounded-lg shadow-md max-w-full">';
                if ($caption) {
                    $imageHtml .= '<p class="text-sm text-gray-600 italic mt-2">' . e($caption) . '</p>';
                }
                $imageHtml .= '</div>';

                // Insert image after the section heading marker if present or append otherwise
                if (strpos($content, $section . ':**') !== false) {
                    $content = str_replace($section . ':**', $section . ':**' . $imageHtml, $content);
                } else {
                    // append near end of content
                    $content .= $imageHtml;
                }
            }
        }

        return $content;
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeByCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Derived attributes & helpers
     */
    public function getActiveActivitiesCountAttribute()
    {
        return $this->activities()->where('is_active', true)->count();
    }

    public function getCoordinatesAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return [
                'lat' => (float) $this->latitude,
                'lng' => (float) $this->longitude
            ];
        }
        return null;
    }

    public function getAltitudeRangeAttribute()
    {
        if ($this->altitude_min && $this->altitude_max) {
            return $this->altitude_min . 'm - ' . $this->altitude_max . 'm';
        }
        return null;
    }

    public function getCustomTourRequestsCountAttribute()
    {
        return CustomTourRequest::whereJsonContains('destinations', $this->id)->count();
    }

    public function getCustomTourRequestsAttribute()
    {
        return CustomTourRequest::whereJsonContains('destinations', $this->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
    }

    public function getAreaFormattedAttribute()
    {
        if ($this->area_size) {
            return number_format($this->area_size) . ' ' . ($this->area_unit ?: 'km²');
        }
        return null;
    }
}