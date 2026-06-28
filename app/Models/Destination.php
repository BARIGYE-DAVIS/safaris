<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\DestinationImage;
use App\Models\User;
use App\Models\CustomTourRequest;

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
        
        // NEW: content_blocks for block-based content
        'content_blocks',
        
        // NEW: focus_keyword for SEO
        'focus_keyword',

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

        // Draft fields
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
        
        // NEW: cast content_blocks to array
        'content_blocks' => 'array',
        
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',

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

        static::deleting(function ($destination) {
            try {
                foreach ($destination->destinationImages()->get() as $img) {
                    $img->delete();
                }
            } catch (\Throwable $e) {
                // Don't fail deletion if image cleanup fails
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

    public function destinationImages()
    {
        return $this->hasMany(DestinationImage::class, 'destination_id')->orderBy('order');
    }

    public function draftUser()
    {
        return $this->belongsTo(User::class, 'draft_user_id');
    }

    /**
     * Get featured image URL
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
     * Get main image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . ltrim($this->image, '/'));
        }
        return asset('images/default-destination.jpg');
    }

    /**
     * Render content_blocks to HTML
     * Supports: heading (h1-h6), text (with inline links), image (with alt), list (ul/ol)
     * 
     * @return string
     */
   /**
 * Render content_blocks to HTML
 * Supports: heading (h1-h6), text (with inline links), image (with alt), list (ul/ol)
 * 
 * @return string
 */
public function renderContentBlocks()
{
    $blocks = $this->content_blocks ?? [];
    
    if (empty($blocks)) {
        return '';
    }

    $html = '';

    // Get all images for this destination
    $images = $this->relationLoaded('destinationImages')
        ? $this->destinationImages
        : DestinationImage::where('destination_id', $this->id)->get();

    $imagesById = $images->keyBy('id');
    $imagesByBlockId = $images->filter(function ($i) {
        return !empty($i->block_id);
    })->keyBy('block_id');

    // Helper: render figure HTML
    $renderFigureHtml = function ($storagePath, $caption = '', $alt = '') {
        $storagePath = ltrim($storagePath, '/');
        $url = asset('storage/' . $storagePath);
        $altText = $alt ?: $caption ?: '';
        $fig = '<figure class="my-6">';
        $fig .= '<img src="' . e($url) . '" alt="' . e($altText) . '" loading="lazy" class="w-full h-auto rounded-lg shadow-md object-cover">';
        if ($caption) {
            $fig .= '<figcaption class="text-sm text-gray-600 italic mt-2">' . e($caption) . '</figcaption>';
        }
        $fig .= '</figure>';
        return $fig;
    };

    // Token regex for image placeholders: [[image:IDENTIFIER|optional caption]]
    $tokenPattern = '/\[\[image:([^\|\]]+)(?:\|([^\]]*))?\]\]/i';

    // Heading level styles
    $headingStyles = [
        'h1' => 'text-3xl font-bold mt-8 mb-4 text-gray-900',
        'h2' => 'text-2xl font-bold mt-6 mb-3 text-gray-800',
        'h3' => 'text-xl font-semibold mt-5 mb-3 text-gray-800',
        'h4' => 'text-lg font-semibold mt-4 mb-2 text-gray-700',
        'h5' => 'text-base font-semibold mt-3 mb-2 text-gray-700',
        'h6' => 'text-sm font-semibold mt-3 mb-2 text-gray-600',
    ];

    // Allowed HTML tags for sanitization
    $allowedTags = '<a><strong><b><em><i><u><br><p><ul><ol><li><span><div><blockquote><code><pre>';

    foreach ($blocks as $block) {
        $type = $block['type'] ?? 'text';

        switch ($type) {
            case 'heading':
                $level = $block['heading_level'] ?? 'h2';
                $content = $block['content'] ?? '';
                $styleClass = $headingStyles[$level] ?? $headingStyles['h2'];
                $html .= "<{$level} class=\"{$styleClass}\">" . e($content) . "</{$level}>";
                break;

            case 'text':
                $content = $block['content'] ?? '';
                
                // Process image tokens inside text blocks
                $parts = preg_split($tokenPattern, $content, -1, PREG_SPLIT_DELIM_CAPTURE);
                $blockHtml = '';
                
                for ($i = 0; $i < count($parts); $i += 3) {
                    $plain = $parts[$i] ?? '';
                    if ($plain !== '') {
                        // ✅ SAFE: Strip tags but allow safe inline HTML (links, bold, etc.)
                        $blockHtml .= '<div>' . strip_tags($plain, $allowedTags) . '</div>';
                    }

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
                                    $blockHtml .= $renderFigureHtml($path, $caption, $img->alt_text);
                                    continue;
                                }
                            }
                        }

                        // Try block-<id> or tmp-<id>
                        $blockId = preg_replace('/^(?:block-|tmp-)/i', '', $identifier);
                        if (!empty($blockId) && isset($imagesByBlockId[$blockId])) {
                            $img = $imagesByBlockId[$blockId];
                            $path = $img->thumbnail_path ?: $img->storage_path;
                            if ($path) {
                                $blockHtml .= $renderFigureHtml($path, $caption, $img->alt_text);
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
                                    $blockHtml .= $renderFigureHtml($path, $caption, $img->alt_text);
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
                $alt = trim($block['alt'] ?? '');

                if (!empty($block['media_id']) && isset($imagesById[$block['media_id']])) {
                    $img = $imagesById[$block['media_id']];
                    $path = $img->thumbnail_path ?: $img->storage_path;
                    if ($path) {
                        $html .= $renderFigureHtml($path, $caption, $alt ?: $img->alt_text);
                    }
                    break;
                }

                if (!empty($block['block_id']) && isset($imagesByBlockId[$block['block_id']])) {
                    $img = $imagesByBlockId[$block['block_id']];
                    $path = $img->thumbnail_path ?: $img->storage_path;
                    if ($path) {
                        $html .= $renderFigureHtml($path, $caption, $alt ?: $img->alt_text);
                    }
                    break;
                }

                if (!empty($block['storage_path'])) {
                    $html .= $renderFigureHtml($block['storage_path'], $caption, $alt);
                    break;
                }
                break;

            case 'list':
                $listType = $block['list_type'] ?? 'ul';
                $content = $block['content'] ?? '';
                
                // ✅ SAFE: Strip tags but allow safe HTML in list items
                $content = strip_tags($content, $allowedTags);
                
                // Process image tokens inside list items
                $parts = preg_split($tokenPattern, $content, -1, PREG_SPLIT_DELIM_CAPTURE);
                $listHtml = '';
                
                for ($i = 0; $i < count($parts); $i += 3) {
                    $plain = $parts[$i] ?? '';
                    if ($plain !== '') {
                        $listHtml .= strip_tags($plain, $allowedTags);
                    }

                    if (isset($parts[$i + 1])) {
                        $identifier = $parts[$i + 1];
                        $caption = trim($parts[$i + 2] ?? '');

                        if (preg_match('/^media-(\d+)$/i', $identifier, $m)) {
                            $mid = (int)$m[1];
                            if (isset($imagesById[$mid])) {
                                $img = $imagesById[$mid];
                                $path = $img->thumbnail_path ?: $img->storage_path;
                                if ($path) {
                                    $listHtml .= $renderFigureHtml($path, $caption, $img->alt_text);
                                    continue;
                                }
                            }
                        }

                        $blockId = preg_replace('/^(?:block-|tmp-)/i', '', $identifier);
                        if (!empty($blockId) && isset($imagesByBlockId[$blockId])) {
                            $img = $imagesByBlockId[$blockId];
                            $path = $img->thumbnail_path ?: $img->storage_path;
                            if ($path) {
                                $listHtml .= $renderFigureHtml($path, $caption, $img->alt_text);
                                continue;
                            }
                        }

                        if (is_numeric($identifier)) {
                            $mid = (int)$identifier;
                            if (isset($imagesById[$mid])) {
                                $img = $imagesById[$mid];
                                $path = $img->thumbnail_path ?: $img->storage_path;
                                if ($path) {
                                    $listHtml .= $renderFigureHtml($path, $caption, $img->alt_text);
                                    continue;
                                }
                            }
                        }
                    }
                }

                $html .= "<{$listType} class=\"list-disc pl-6 mb-4 text-gray-700\">";
                $html .= $listHtml;
                $html .= "</{$listType}>";
                break;

            default:
                $content = $block['content'] ?? '';
                $html .= '<div class="prose max-w-none text-gray-700 mb-4">' . nl2br(e($content)) . '</div>';
                break;
        }
    }

    return $html;
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