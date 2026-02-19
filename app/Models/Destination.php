<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\DestinationImage;
use App\Models\User;
use App\Models\CustomTourRequest;

/**
 * Destination model
 *
 * Notes:
 * - sections_content (cast to array) stores ordered blocks per section. Each block:
 *   ['id' => 'blk-1', 'type' => 'text'|'heading'|'subheading'|'image', 'text' => '', 'caption' => '', 'media_id' => int, 'block_id' => '...']
 * - destinationImages holds persistent media (storage_path, thumbnail_path, block_id, etc.)
 *
 * This class provides safe, efficient rendering of sections into HTML while avoiding N+1 queries,
 * token-aware inline image rendering, and safe escaping to prevent XSS.
 */
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

        // use floats for lat/lng for convenience in calculations; if you prefer decimals keep 'decimal:8'
        'latitude' => 'float',
        'longitude' => 'float',

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
     * Boot method to auto-generate slug and cleanup on delete.
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

        // Ensure destination images are removed (and their files) when destination is deleted.
        // DestinationImage model should handle file deletion in its deleting() hook.
        static::deleting(function ($destination) {
            try {
                foreach ($destination->destinationImages()->get() as $img) {
                    $img->delete();
                }
            } catch (\Throwable $e) {
                // Don't fail deletion of destination if image cleanup has an issue,
                // but log (controller or app logging should capture this).
            }
        });
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
     * Destination images managed via the block editor or gallery
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
            return asset('storage/' . ltrim($this->featured_image, '/'));
        }
        if ($this->image) {
            return asset('storage/' . ltrim($this->image, '/'));
        }
        return asset('images/default-destination.jpg');
    }

    /**
     * Get the main image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . ltrim($this->image, '/'));
        }
        return asset('images/default-destination.jpg');
    }

    /**
     * Render a section using sections_content block data (preferred) or fall back to legacy fields.
     * Returns HTML string ready to echo in Blade (the method ensures internal escaping).
     *
     * @param string $sectionKey
     * @return string
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
                return nl2br(e($this->geography_landscape ?? ''));
            case 'practical':
                return nl2br(e($this->practical_information ?? ''));
            case 'accommodation':
                return nl2br(e($this->accommodation_options ?? ''));
            case 'extras':
                return nl2br(e($this->interesting_facts ?? ''));
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
// ADD THIS
public function multiActivities()
{
    return $this->belongsToMany(Activity::class, 'activity_destination')
                ->withTimestamps();
}
    public function getWildlifeWithImagesAttribute()
    {
        return $this->renderSection('wildlife');
    }

    /**
     * Render blocks array (from sections_content) to HTML.
     * Optimized to avoid N+1 queries and token-aware for inline image tokens inside text blocks.
     *
     * @param array $blocks
     * @param string|null $sectionKey
     * @return string
     */
                protected function renderBlocksToHtml(array $blocks, string $sectionKey = null): string
{
    $html = '';

    // Prepare image indexes once to avoid N+1 queries
    $images = $this->relationLoaded('destinationImages')
        ? $this->destinationImages
        : DestinationImage::where('destination_id', $this->id)->get();

    $imagesById = $images->keyBy('id');
    $imagesByBlockId = $images->filter(function ($i) {
        return !empty($i->block_id);
    })->keyBy('block_id');

    // Helper: create figure HTML for a given storage path + caption
    $renderFigureHtml = function ($storagePath, $caption = '') {
        $storagePath = ltrim($storagePath, '/');
        $url = asset('storage/' . $storagePath);
        $alt = $caption ?: '';
        $fig = '<figure class="my-6">';
        $fig .= '<img src="' . e($url) . '" alt="' . e($alt) . '" loading="lazy" class="w-full h-auto rounded-lg shadow-md object-cover">';
        if ($caption) {
            $fig .= '<figcaption class="text-sm text-gray-600 italic mt-2">' . e($caption) . '</figcaption>';
        }
        $fig .= '</figure>';
        return $fig;
    };

    // Token regex used in text blocks: [[image:IDENTIFIER|optional caption]]
    $tokenPattern = '/\[\[image:([^\|\]]+)(?:\|([^\]]*))?\]\]/i';

    // Iterate blocks in order and render immediately
    foreach ($blocks as $block) {
        $type = $block['type'] ?? 'text';

        switch ($type) {
            case 'heading':
                $text = $block['text'] ?? '';

                // Handle icons in headings: [[icon:fas fa-check-circle]]
                $text = preg_replace_callback('/\[\[icon:([^\]]+)\]\]/', function ($matches) {
                    $iconClass = trim($matches[1]);
                    // Only allow FA patterns like "fas fa-xxx"
                    if (preg_match('/^(fas|far|fab|fal|fad)\s+fa-[\w-]+$/i', $iconClass)) {
                        return '<i class="' . e($iconClass) . ' mr-2 text-green-600"></i>';
                    }
                    return '';
                }, $text);

                // We assume headings don't contain arbitrary HTML, so we don't double-escape here.
                $html .= "<h2 class=\"text-2xl font-bold mt-6 mb-3 text-green-800\">{$text}</h2>";
                break;

            case 'subheading':
                $text = $block['text'] ?? '';

                // Handle icons in subheadings
                $text = preg_replace_callback('/\[\[icon:([^\]]+)\]\]/', function ($matches) {
                    $iconClass = trim($matches[1]);
                    if (preg_match('/^(fas|far|fab|fal|fad)\s+fa-[\w-]+$/i', $iconClass)) {
                        return '<i class="' . e($iconClass) . ' mr-2 text-green-600"></i>';
                    }
                    return '';
                }, $text);

                $html .= "<h3 class=\"text-xl font-semibold mt-4 mb-2 text-green-700\">{$text}</h3>";
                break;

            case 'text':
                $text = $block['text'] ?? '';

                // STEP 1: Convert [[icon:...]] into safe placeholders we can re-inject after escaping
                $iconMarker = '###ICON_PLACEHOLDER###';
                $iconReplacements = [];

                $textWithPlaceholders = preg_replace_callback(
                    '/\[\[icon:([^\]]+)\]\]/',
                    function ($matches) use (&$iconReplacements, $iconMarker) {
                        $iconClass = trim($matches[1]);

                        // Only allow Font Awesome-like classes e.g. "fas fa-check-circle"
                        if (preg_match('/^(fas|far|fab|fal|fad)\s+fa-[\w-]+$/i', $iconClass)) {
                            $iconHtml = '<i class="' . e($iconClass) . ' mr-2 text-green-600"></i>';
                            $placeholder = $iconMarker . count($iconReplacements) . $iconMarker;
                            $iconReplacements[$placeholder] = $iconHtml;
                            return $placeholder;
                        }

                        // Invalid icon -> nothing
                        return '';
                    },
                    $text
                );

                // STEP 2: Split text by image tokens (using textWithPlaceholders)
                $parts = preg_split($tokenPattern, $textWithPlaceholders, -1, PREG_SPLIT_DELIM_CAPTURE);

                $blockHtml = '';
                for ($i = 0; $i < count($parts); $i += 3) {
                    // Plain text segment (with icon placeholders still inside)
                    $plain = $parts[$i] ?? '';
                    if ($plain !== '') {
                        // Escape whole segment
                        $escaped = e($plain);

                        // STEP 3: Replace escaped placeholders with real icon HTML
                        foreach ($iconReplacements as $placeholder => $iconHtml) {
                            $escapedPlaceholder = e($placeholder);
                            $escaped = str_replace($escapedPlaceholder, $iconHtml, $escaped);
                        }

                        $blockHtml .= '<div>' . nl2br($escaped) . '</div>';
                    }

                    // Image token segment (unchanged from your code)
                    if (isset($parts[$i + 1])) {
                        $identifier = $parts[$i + 1];
                        $caption = trim($parts[$i + 2] ?? '');

                        // Try media-<id>
                        if (preg_match('/^media-(\d+)$/i', $identifier, $m)) {
                            $mid = (int)$m[1];
                            if (isset($imagesById[$mid])) {
                                $img = $imagesById[$mid];
                                $path = $img->thumbnail_path ?: $img->storage_path;
                                if ($path) {
                                    $blockHtml .= $renderFigureHtml($path, $caption);
                                    continue;
                                }
                            }
                            continue;
                        }

                        // Try block-<id> or tmp-<id>
                        $blockId = preg_replace('/^(?:block-|tmp-)/i', '', $identifier);
                        if (!empty($blockId) && isset($imagesByBlockId[$blockId])) {
                            $img = $imagesByBlockId[$blockId];
                            $path = $img->thumbnail_path ?: $img->storage_path;
                            if ($path) {
                                $blockHtml .= $renderFigureHtml($path, $caption);
                                continue;
                            }
                        }

                        // Numeric id fallback
                        if (is_numeric($identifier)) {
                            $mid = (int)$identifier;
                            if (isset($imagesById[$mid])) {
                                $img = $imagesById[$mid];
                                $path = $img->thumbnail_path ?: $img->storage_path;
                                if ($path) {
                                    $blockHtml .= $renderFigureHtml($path, $caption);
                                    continue;
                                }
                            }
                        }
                    }
                }

                $html .= '<div class="prose max-w-none text-gray-700 mb-4">' . $blockHtml . '</div>';
                break;

            case 'image':
                $caption = trim($block['caption'] ?? '');

                // 1) media_id lookup
                if (!empty($block['media_id']) && isset($imagesById[$block['media_id']])) {
                    $img = $imagesById[$block['media_id']];
                    $path = $img->thumbnail_path ?: $img->storage_path;
                    if ($path) {
                        $html .= $renderFigureHtml($path, $caption);
                    }
                    break;
                }

                // 2) block_id lookup
                if (!empty($block['block_id']) && isset($imagesByBlockId[$block['block_id']])) {
                    $img = $imagesByBlockId[$block['block_id']];
                    $path = $img->thumbnail_path ?: $img->storage_path;
                    if ($path) {
                        $html .= $renderFigureHtml($path, $caption);
                    }
                    break;
                }

                // 3) direct storage_path on block
                if (!empty($block['storage_path'])) {
                    $html .= $renderFigureHtml($block['storage_path'], $caption);
                    break;
                }
                break;

            default:
                $text = $block['text'] ?? '';
                $html .= '<div class="prose max-w-none text-gray-700 mb-4">' . nl2br(e($text)) . '</div>';
                break;
        }
    }

    return $html;
}

    
    /**
     * Legacy helper: Parse content and inject images based on legacy arrays.
     * This is safer than previous version: escapes all inputs and attempts to insert images
     * after a matching heading/marker; otherwise appends at the end.
     *
     * @param string|null $content
     * @param array|null $images
     * @return string
     */
    private function parseContentWithImages($content, $images)
    {
        $content = (string) ($content ?? '');
        if (empty($content) || empty($images) || !is_array($images)) {
            return nl2br(e($content));
        }

        // Build safe HTML for images grouped by section marker (if provided)
        $grouped = [];
        foreach ($images as $imageData) {
            $section = trim($imageData['section'] ?? '');
            $imagePath = trim($imageData['image'] ?? '');
            $caption = trim($imageData['caption'] ?? '');

            if (!$imagePath) continue;

            $imgHtml = '<div class="inline-image my-4">';
            $imgHtml .= '<img src="' . e(asset('storage/' . ltrim($imagePath, '/'))) . '" alt="' . e($caption) . '" class="rounded-lg shadow-md max-w-full">';
            if ($caption) {
                $imgHtml .= '<p class="text-sm text-gray-600 italic mt-2">' . e($caption) . '</p>';
            }
            $imgHtml .= '</div>';

            $grouped[$section][] = $imgHtml;
        }

        // Attempt to insert grouped images after the first occurrence of the matching heading or marker.
        $result = e($content); // escaped content
        foreach ($grouped as $section => $imgs) {
            $inject = implode('', $imgs);

            if ($section !== '') {
                // Search for a heading or plain "SectionName" followed by newline or colon.
                // Work on the original (unescaped) content to find positions.
                $needle = $section;
                $pos = stripos($content, $needle);
                if ($pos !== false) {
                    // Find insertion point: at end of the line containing the needle
                    $lineEnd = strpos($content, PHP_EOL, $pos);
                    if ($lineEnd === false) $lineEnd = strlen($content);
                    // Build escaped parts around insertion index
                    $before = e(substr($content, 0, $lineEnd + 1));
                    $after  = e(substr($content, $lineEnd + 1));
                    // Replace the corresponding part in $result (which is escaped)
                    // Use first occurrence replacement
                    $result = preg_replace('/' . preg_quote(e(substr($content, 0, $lineEnd + 1)), '/') . '/', $before . $inject, $result, 1);
                    continue;
                }
            }

            // No section match -> append at end
            $result .= $inject;
        }

        // Convert newlines to <br> while keeping injected HTML as-is (injection is already HTML)
        // Because $result contains escaped original content and raw injected HTML, we can safely convert newlines to <br>
        $result = nl2br($result);

        return $result;
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
        if ($this->latitude !== null && $this->longitude !== null) {
            return [
                'lat' => (float) $this->latitude,
                'lng' => (float) $this->longitude
            ];
        }
        return null;
    }

    public function getAltitudeRangeAttribute()
    {
        if ($this->altitude_min !== null && $this->altitude_max !== null) {
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