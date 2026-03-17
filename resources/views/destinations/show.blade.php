@extends('layouts.app')

@section('title', $destination->meta_title ?? $destination->name)
@section('meta_description', $destination->meta_description ?? \Illuminate\Support\Str::limit($destination->description ?? $destination->detailed_overview, 160))
@section('meta_keywords', $destination->meta_keywords ?? implode(', ', array_filter([$destination->name, $destination->type, $destination->region])))

@php
/**
 * Inline block renderer with proper image positioning.
 * - Uses $destination->sections_content (ordered blocks).
 * - Resolves images by block_id from destination_images table
 * - Images appear exactly where they're placed in the content
 * - Multiple CONSECUTIVE image blocks are automatically grouped into a slider
 */

// Load images and index them properly
$imagesIndex = collect();
$imagesByBlockId = collect();

if (isset($destination) && $destination) {
    if ($destination->relationLoaded('destinationImages')) {
        $imagesIndex = $destination->destinationImages;
    } else {
        try {
            $imagesIndex = \App\Models\DestinationImage::where('destination_id', $destination->id)->get();
        } catch (\Throwable $e) {
            $imagesIndex = collect();
        }
    }
    
    // Create index by block_id for fast lookup
    $imagesByBlockId = $imagesIndex->keyBy('block_id');
}

/**
 * Resolve image path from a block
 * Priority: block_id match > media_id > storage_path
 */
$resolveImagePath = function(array $block) use ($imagesIndex, $imagesByBlockId) {
    if (!empty($block['id'])) {
        $img = $imagesByBlockId->get($block['id']);
        if ($img) return $img->thumbnail_path ?: $img->storage_path;
    }
    if (!empty($block['block_id'])) {
        $img = $imagesByBlockId->get($block['block_id']);
        if ($img) return $img->thumbnail_path ?: $img->storage_path;
    }
    if (!empty($block['media_id'])) {
        $img = $imagesIndex->firstWhere('id', $block['media_id']);
        if ($img) return $img->thumbnail_path ?: $img->storage_path;
    }
    if (!empty($block['storage_path'])) {
        return $block['storage_path'];
    }
    return null;
};

/**
 * Get caption for an image block
 */
$getImageCaption = function(array $block) use ($imagesIndex, $imagesByBlockId) {
    if (!empty($block['caption'])) return $block['caption'];
    if (!empty($block['id'])) {
        $img = $imagesByBlockId->get($block['id']);
        if ($img && $img->caption) return $img->caption;
    }
    if (!empty($block['block_id'])) {
        $img = $imagesByBlockId->get($block['block_id']);
        if ($img && $img->caption) return $img->caption;
    }
    return '';
};

/**
 * Render a slider for a group of image blocks.
 * Called automatically when 2+ consecutive image blocks appear in a section.
 */
$renderImageSlider = function(array $imageBlocks, int &$sliderCounter) use ($resolveImagePath, $getImageCaption, $destination): string {
    $sliderId = 'img-slider-' . $sliderCounter++;
    $slides   = [];

    foreach ($imageBlocks as $block) {
        $path = $resolveImagePath($block);
        if (!$path) continue;
        $caption  = $getImageCaption($block);
        $slides[] = [
            'url'     => asset('storage/' . ltrim($path, '/')),
            'caption' => $caption,
            'alt'     => $caption ?: ($destination->name ?? 'Image'),
        ];
    }

    if (empty($slides)) return '';

    $count = count($slides);
    $html  = '<div id="' . e($sliderId) . '" class="section-img-slider relative my-6 rounded-xl overflow-hidden shadow-md bg-black" data-count="' . $count . '" data-current="0">';

    // ── Track ──
    $html .= '<div class="slider-track flex transition-transform duration-500 ease-in-out">';
    foreach ($slides as $slide) {
        $html .= '<figure class="slider-slide min-w-full relative select-none">';
        // Aspect-ratio wrapper ensures uniform height regardless of source image dimensions.
        // object-cover fills the frame with no black bars or distortion.
        $html .= '<div class="relative w-full" style="aspect-ratio:16/9;overflow:hidden;">';
        $html .= '<img src="' . e($slide['url']) . '" alt="' . e($slide['alt']) . '" loading="lazy" class="absolute inset-0 w-full h-full object-cover pointer-events-none">';
        if ($slide['caption']) {
            $html .= '<figcaption class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent text-white text-sm italic px-4 py-3">' . e($slide['caption']) . '</figcaption>';
        }
        $html .= '</div>';
        $html .= '</figure>';
    }
    $html .= '</div>';

    // ── Controls (only if more than 1 slide) ──
    if ($count > 1) {
        // Prev
        $html .= '<button type="button" onclick="sliderMove(\'' . e($sliderId) . '\',-1)" aria-label="Previous image"
            class="absolute left-3 top-1/2 -translate-y-1/2 z-10 bg-gray/80 hover:bg-gray text-gray-800 rounded-full w-10 h-10 flex items-center justify-center shadow-lg transition-all duration-200 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-green-500">';
        $html .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>';
        $html .= '</button>';

        // Next
        $html .= '<button type="button" onclick="sliderMove(\'' . e($sliderId) . '\',1)" aria-label="Next image"
            class="absolute right-3 top-1/2 -translate-y-1/2 z-10 bg-gray/80 hover:bg-gray text-gray-800 rounded-full w-10 h-10 flex items-center justify-center shadow-lg transition-all duration-200 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-green-500">';
        $html .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>';
        $html .= '</button>';

        // Dot indicators
        $html .= '<div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5 z-10">';
        for ($d = 0; $d < $count; $d++) {
            $active = $d === 0 ? 'bg-gray w-5' : 'bg-gray/50 w-2.5';
            $html .= '<button type="button" onclick="sliderGoTo(\'' . e($sliderId) . '\',' . $d . ')" aria-label="Go to slide ' . ($d+1) . '"
                class="slider-dot h-2.5 rounded-full transition-all duration-300 ' . $active . ' hover:bg-gray focus:outline-none"></button>';
        }
        $html .= '</div>';

        // Counter
        $html .= '<div class="slider-counter absolute top-3 right-3 bg-black/50 text-white text-xs px-2.5 py-1 rounded-full z-10 font-medium tabular-nums">1 / ' . $count . '</div>';
    }

    $html .= '</div>';
    return $html;
};

/** 
 * Renders blocks array into HTML string (safe to echo with {!! !!}).
 * Consecutive image blocks are automatically grouped into a slider.
 */
$sliderCounter = 0;
$renderBlocksHtml = function($blocks) use ($resolveImagePath, $getImageCaption, $renderImageSlider, $destination, &$sliderCounter) {
    $html   = '';
    if (!is_array($blocks) || empty($blocks)) return $html;

    $blocks = array_values($blocks);
    $total  = count($blocks);
    $i      = 0;

    while ($i < $total) {
        $block = $blocks[$i];
        $type  = $block['type'] ?? 'text';

        // ── Image: collect consecutive image blocks ──
        if ($type === 'image') {
            $group = [];
            while ($i < $total && ($blocks[$i]['type'] ?? '') === 'image') {
                $group[] = $blocks[$i];
                $i++;
            }

            if (count($group) === 1) {
                // Single image — plain render
                $path    = $resolveImagePath($group[0]);
                $caption = $getImageCaption($group[0]);

                if ($path) {
                    $url = asset('storage/' . ltrim($path, '/'));
                    $alt = $caption ?: ($destination->name ?? 'Image');
                    $html .= '<figure class="my-6">';
                    $html .= '<img src="' . e($url) . '" alt="' . e($alt) . '" loading="lazy" class="w-full h-auto rounded-lg shadow-md object-cover">';
                    if ($caption) {
                        $html .= '<figcaption class="text-sm text-gray-600 italic mt-2 text-center">' . e($caption) . '</figcaption>';
                    }
                    $html .= '</figure>';
                } else {
                    $blockId = $group[0]['id'] ?? $group[0]['block_id'] ?? 'unknown';
                    $html .= '<div class="my-6 bg-gray-100 rounded-lg p-6 text-center text-gray-500 border border-dashed border-gray-300">'
                           . '<p>Image not found (Block ID: ' . e($blockId) . ')</p></div>';
                }
            } else {
                // Multiple consecutive images → slider
                $html .= $renderImageSlider($group, $sliderCounter);
            }

            continue; // $i was already advanced inside the while loop
        }

        // ── Non-image blocks ──
        switch ($type) {
            case 'heading':
                $raw = $block['text'] ?? '';
                $iconMarker = '###ICON_PLACEHOLDER###';
                $iconReplacements = [];
                $withPlaceholders = preg_replace_callback(
                    '/\[\[icon:([^\]]+)\]\]/',
                    function ($m) use (&$iconReplacements, $iconMarker) {
                        $cls = trim($m[1]);
                        if (preg_match('/^(fas|far|fab|fal|fad)\s+fa-[\w-]+$/i', $cls)) {
                            $ih  = '<i class="' . e($cls) . ' mr-2 text-green-600"></i>';
                            $ph  = $iconMarker . count($iconReplacements) . $iconMarker;
                            $iconReplacements[$ph] = $ih;
                            return $ph;
                        }
                        return '';
                    }, $raw
                );
                $esc = e($withPlaceholders);
                foreach ($iconReplacements as $ph => $ih) $esc = str_replace(e($ph), $ih, $esc);
                $html .= "<h2 class=\"text-2xl font-bold mt-6 mb-3 text-green-800\">{$esc}</h2>";
                break;

            case 'subheading':
                $raw = $block['text'] ?? '';
                $iconMarker = '###ICON_PLACEHOLDER###';
                $iconReplacements = [];
                $withPlaceholders = preg_replace_callback(
                    '/\[\[icon:([^\]]+)\]\]/',
                    function ($m) use (&$iconReplacements, $iconMarker) {
                        $cls = trim($m[1]);
                        if (preg_match('/^(fas|far|fab|fal|fad)\s+fa-[\w-]+$/i', $cls)) {
                            $ih  = '<i class="' . e($cls) . ' mr-2 text-indigo-600"></i>';
                            $ph  = $iconMarker . count($iconReplacements) . $iconMarker;
                            $iconReplacements[$ph] = $ih;
                            return $ph;
                        }
                        return '';
                    }, $raw
                );
                $esc = e($withPlaceholders);
                foreach ($iconReplacements as $ph => $ih) $esc = str_replace(e($ph), $ih, $esc);
                $html .= "<h3 class=\"text-xl font-semibold mt-4 mb-2 text-green-700\">{$esc}</h3>";
                break;

            case 'text':
                $text = $block['text'] ?? '';
                $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
                $text = preg_replace('/\*(.*?)\*/',     '<em>$1</em>',         $text);
                $iconMarker = '###ICON_PLACEHOLDER###';
                $iconReplacements = [];
                $textWithPlaceholders = preg_replace_callback(
                    '/\[\[icon:([^\]]+)\]\]/',
                    function ($m) use (&$iconReplacements, $iconMarker) {
                        $cls = trim($m[1]);
                        if (preg_match('/^(fas|far|fab|fal|fad)\s+fa-[\w-]+$/i', $cls)) {
                            $ih  = '<i class="' . e($cls) . ' mr-2 text-green-600"></i>';
                            $ph  = $iconMarker . count($iconReplacements) . $iconMarker;
                            $iconReplacements[$ph] = $ih;
                            return $ph;
                        }
                        return '';
                    }, $text
                );
                $esc = e($textWithPlaceholders);
                foreach ($iconReplacements as $ph => $ih) $esc = str_replace(e($ph), $ih, $esc);
                $esc  = nl2br($esc, false);
                $html .= "<div class=\"prose max-w-none text-gray-700 mb-4\">{$esc}</div>";
                break;

            default:
                $text = $block['text'] ?? '';
                $html .= "<div class=\"prose max-w-none text-gray-700 mb-4\">" . nl2br(e($text)) . "</div>";
                break;
        }

        $i++;
    }

    return $html;
};

/** 
 * Utility: get blocks for a named section or fallback to legacy field 
 */
$getSectionBlocks = function(string $key) use ($destination) {
    $sections = $destination->sections_content ?? [];
    if (!empty($sections[$key]) && is_array($sections[$key])) {
        return $sections[$key];
    }
    $legacyMap = [
        'overview'      => 'detailed_overview',
        'activities'    => 'what_to_see_do',
        'wildlife'      => 'wildlife_highlights',
        'geography'     => 'geography_landscape',
        'practical'     => 'practical_information',
        'accommodation' => 'accommodation_options',
        'extras'        => 'interesting_facts',
    ];
    if (isset($legacyMap[$key]) && !empty($destination->{$legacyMap[$key]})) {
        return [['id' => 'blk-legacy-' . $key, 'type' => 'text', 'text' => $destination->{$legacyMap[$key]}]];
    }
    return [];
};

$hasSectionContent = function(string $key) use ($getSectionBlocks) {
    return !empty($getSectionBlocks($key));
};
@endphp

@section('page-header')
<header class="relative h-96 lg:h-[500px] overflow-hidden">
    @if($destination->featured_image)
        <img src="{{ asset('storage/' . $destination->featured_image) }}" alt="{{ $destination->name }}" class="w-full h-full object-cover">
    @elseif($destination->image)
        <img src="{{ asset('storage/' . $destination->image) }}" alt="{{ $destination->name }}" class="w-full h-full object-cover">
    @else
        <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-500"></div>
    @endif
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>

    <div class="absolute bottom-0 left-0 right-0 p-8">
        <div class="max-w-7xl mx-auto text-white">
            <div class="flex flex-wrap gap-3 mb-4">
                @if($destination->type)
                    <span class="bg-green-600 text-white px-4 py-2 rounded-full text-sm font-semibold">{{ $destination->type }}</span>
                @endif
                @if($destination->region)
                    <span class="bg-blue-600 text-white px-4 py-2 rounded-full text-sm font-semibold">{{ $destination->region }}</span>
                @endif
            </div>
            <h1 class="text-3xl lg:text-5xl font-bold text-white mb-4">{{ $destination->name }}</h1>
            @if($destination->description)
                <p class="mt-2 max-w-3xl text-sm lg:text-base text-white/90">{{ \Illuminate\Support\Str::limit(strip_tags($destination->description), 220) }}</p>
            @endif
        </div>
    </div>
</header>
@endsection

@section('content')
<nav class="bg-gray border-b py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-2 text-sm">
            <a href="{{ route('index') }}" class="text-gray-500 hover:text-green-600">Home</a>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <a href="{{ route('destinations.index') }}" class="text-gray-500 hover:text-green-600">Destinations</a>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-900 font-medium">{{ $destination->name }}</span>
        </div>
    </div>
</nav>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <article class="lg:col-span-2 space-y-8">
            <div class="bg-gray rounded-2xl p-6 shadow-sm border">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">About {{ $destination->name }}</h2>
                        @if($destination->best_season)
                            <p class="text-sm text-gray-500 mt-1">Best time to visit: <strong class="text-green-600">{{ $destination->best_season }}</strong></p>
                        @endif
                    </div>
                    <div class="flex-shrink-0 flex gap-2">
                        <a href="{{ route('contact.index') }}" class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">Enquire</a>
                    </div>
                </div>
            </div>

            {{-- Overview Section --}}
            @if($hasSectionContent('overview'))
                <section id="overview" class="bg-gray rounded-2xl p-6 shadow-sm border">
                    <h2 class="text-2xl font-bold text-green-700 mb-4">Overview</h2>
                    <div class="prose max-w-none">
                        {!! $renderBlocksHtml($getSectionBlocks('overview')) !!}
                    </div>
                </section>
            @endif

            {{-- Activities Section --}}
            @if($hasSectionContent('activities'))
                <section id="activities" class="bg-gray rounded-2xl p-6 shadow-sm border">
                    <h2 class="text-2xl font-bold text-green-700 mb-4">Things to Do</h2>
                    <div class="prose max-w-none">
                        {!! $renderBlocksHtml($getSectionBlocks('activities')) !!}
                    </div>
                </section>
            @endif

            {{-- Wildlife Section --}}
            @if($hasSectionContent('wildlife'))
                <section id="wildlife" class="bg-gray rounded-2xl p-6 shadow-sm border">
                    <h2 class="text-2xl font-bold text-green-700 mb-4">Wildlife & Highlights</h2>
                    <div class="prose max-w-none">
                        {!! $renderBlocksHtml($getSectionBlocks('wildlife')) !!}
                    </div>
                </section>
            @endif

            {{-- Geography Section --}}
            @if($hasSectionContent('geography'))
                <section id="geography" class="bg-gray rounded-2xl p-6 shadow-sm border">
                    <h2 class="text-2xl font-bold text-green-700 mb-4">Geography & Landscape</h2>
                    <div class="prose max-w-none">
                        {!! $renderBlocksHtml($getSectionBlocks('geography')) !!}
                    </div>
                </section>
            @endif

            {{-- Practical Information Section --}}
            @if($hasSectionContent('practical'))
                <section id="practical" class=" rounded-2xl p-6 shadow-sm border">
                    <h2 class="text-2xl font-bold text-green-700 mb-4">Practical Information</h2>
                    <div class="prose max-w-none">
                        {!! $renderBlocksHtml($getSectionBlocks('practical')) !!}
                    </div>
                </section>
            @endif

            {{-- Accommodation Section --}}
            @if($hasSectionContent('accommodation'))
                <section id="accommodation" class=" rounded-2xl p-6 shadow-sm border">
                    <h2 class="text-2xl font-bold text-green-700 mb-4">Accommodation</h2>
                    <div class="prose max-w-none">
                        {!! $renderBlocksHtml($getSectionBlocks('accommodation')) !!}
                    </div>
                </section>
            @endif

            {{-- Extras/Interesting Facts Section --}}
            @if($hasSectionContent('extras'))
                <section id="extras" class=" rounded-2xl p-6 shadow-sm border">
                    <h2 class="text-2xl font-bold text-green-700 mb-4">Interesting Facts</h2>
                    <div class="prose max-w-none">
                        {!! $renderBlocksHtml($getSectionBlocks('extras')) !!}
                    </div>
                </section>
            @endif

            {{-- Photo gallery thumbnails (from gallery_images field) --}}
            @php
                $galleryImages = collect();
                if (is_array($destination->gallery_images) && !empty($destination->gallery_images)) {
                    $galleryImages = collect($destination->gallery_images)->pluck('image')->filter()->values();
                }
            @endphp

            @if($galleryImages->isNotEmpty())
                <section id="gallery" class=" rounded-2xl p-6 shadow-sm border">
                    <h2 class="text-2xl font-bold text-green-700 mb-4">Photo Gallery</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($galleryImages as $i => $imgPath)
                            <button type="button" onclick="openGallery({{ $i }})" class="block rounded-lg overflow-hidden focus:outline-none focus:ring-2 focus:ring-green-500">
                                <img src="{{ asset('storage/' . $imgPath) }}" alt="{{ $destination->name }} photo" loading="lazy" class="w-full h-44 object-cover transition-transform duration-300 hover:scale-105">
                            </button>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Related destinations --}}
            @if(!empty($relatedDestinations) && $relatedDestinations->count())
                <section class="bg-gray-50 rounded-2xl p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">You might also like</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($relatedDestinations as $rel)
                            <a href="{{ route('destinations.show', $rel->slug) }}" class="block bg-gray rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition">
                                <div class="h-40 bg-gray-100 overflow-hidden">
                                    @if($rel->featured_image || $rel->image)
                                        <img src="{{ asset('storage/' . ($rel->featured_image ?? $rel->image)) }}" alt="{{ $rel->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-green-300 to-blue-400 flex items-center justify-center text-white font-bold">
                                            {{ \Illuminate\Support\Str::limit($rel->name, 18) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h4 class="font-semibold text-gray-900">{{ $rel->name }}</h4>
                                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ \Illuminate\Support\Str::limit($rel->description ?? '', 100) }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </article>

        {{-- Sidebar --}}
        <aside class="lg:col-span-1">
            <div class="sticky top-24 space-y-6">
                <div class="bg-gray rounded-xl shadow p-6 border">
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Quick Info</h3>
                    <div class="text-sm text-gray-700 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Location</span>
                            <span>{{ $destination->region ?? ($destination->country->name ?? '—') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Type</span>
                            <span>{{ $destination->type ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Area</span>
                            <span>{{ $destination->area_size ? number_format($destination->area_size) . ' km²' : '—' }}</span>
                        </div>
                        @if($destination->latitude && $destination->longitude)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Coordinates</span>
                                <span class="text-xs">{{ number_format($destination->latitude,4) }}, {{ number_format($destination->longitude,4) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="mt-6">
                        <a href="{{ route ('custom-tour-requests.create') }}" class="block text-center bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg transition">Plan Your Trip</a>
                    </div>
                </div>

                @if($destination->phone || $destination->email || $destination->website)
                    <div class="bg-gray rounded-xl shadow p-6 border">
                        <h4 class="text-lg font-semibold mb-3">Contact</h4>
                        <div class="text-sm text-gray-700 space-y-2">
                            @if($destination->phone)
                                <div><strong>Phone:</strong> <a href="tel:{{ $destination->phone }}" class="text-green-600 hover:underline">{{ $destination->phone }}</a></div>
                            @endif
                            @if($destination->email)
                                <div><strong>Email:</strong> <a href="mailto:{{ $destination->email }}" class="text-green-600 hover:underline break-all">{{ $destination->email }}</a></div>
                            @endif
                            @if($destination->website)
                                <div><strong>Website:</strong> <a href="{{ $destination->website }}" target="_blank" rel="noopener" class="text-green-600 hover:underline">{{ parse_url($destination->website, PHP_URL_HOST) }}</a></div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </aside>
    </div>
</main>

{{-- Gallery modal --}}
@if(isset($galleryImages) && $galleryImages->isNotEmpty())
<div id="galleryModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center p-4">
    <div class="relative max-w-5xl w-full max-h-full">
        <button onclick="closeGallery()" class="absolute -top-2 -right-2 bg-gray rounded-full p-2 shadow hover:bg-gray-100 z-10">
            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div class="bg-gray rounded-lg overflow-hidden">
            <img id="galleryImage" src="" alt="Gallery image" class="w-full h-auto max-h-[80vh] object-contain bg-black">
            <div class="p-3 text-center bg-gray-50">
                <button id="galleryPrev" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded mr-2 transition">← Prev</button>
                <button id="galleryNext" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded transition">Next →</button>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
/* ═══════════════════════════════════════════
   Inline Section Image Slider
   ═══════════════════════════════════════════ */
function sliderApply(id, index) {
    const el = document.getElementById(id);
    if (!el) return;

    const track   = el.querySelector('.slider-track');
    const dots    = el.querySelectorAll('.slider-dot');
    const counter = el.querySelector('.slider-counter');
    const count   = parseInt(el.dataset.count || 1);

    index = ((index % count) + count) % count; // wrap around
    el.dataset.current = index;

    // Move track
    track.style.transform = `translateX(-${index * 100}%)`;

    // Update dots: active dot gets wider pill style
    dots.forEach((dot, i) => {
        if (i === index) {
            dot.classList.remove('bg-gray/50', 'w-2.5');
            dot.classList.add('bg-gray', 'w-5');
        } else {
            dot.classList.remove('bg-gray', 'w-5');
            dot.classList.add('bg-gray/50', 'w-2.5');
        }
    });

    // Update counter badge
    if (counter) counter.textContent = `${index + 1} / ${count}`;
}

function sliderMove(id, direction) {
    const el = document.getElementById(id);
    if (!el) return;
    sliderApply(id, parseInt(el.dataset.current || 0) + direction);
}

function sliderGoTo(id, index) {
    sliderApply(id, index);
}

/* Touch / swipe support */
(function () {
    let startX = 0, startId = null;

    document.addEventListener('touchstart', function (e) {
        const slider = e.target.closest('.section-img-slider');
        if (slider) { startX = e.touches[0].clientX; startId = slider.id; }
    }, { passive: true });

    document.addEventListener('touchend', function (e) {
        if (!startId) return;
        const diff = startX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 40) sliderMove(startId, diff > 0 ? 1 : -1);
        startId = null;
    }, { passive: true });
})();

/* ═══════════════════════════════════════════
   Gallery Modal
   ═══════════════════════════════════════════ */
const galleryImgs = @json($galleryImages->all() ?? []);

function openGallery(index) {
    if (!galleryImgs.length) return;
    const modal = document.getElementById('galleryModal');
    const img   = document.getElementById('galleryImage');
    index = Math.max(0, Math.min(parseInt(index) || 0, galleryImgs.length - 1));
    img.dataset.index = index;
    img.src = '{{ asset("storage/") }}/' + galleryImgs[index];
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeGallery() {
    const modal = document.getElementById('galleryModal');
    const img   = document.getElementById('galleryImage');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    if (img) img.src = '';
}

document.addEventListener('click', function (e) {
    if (e.target.matches('#galleryPrev') || e.target.closest('#galleryPrev')) {
        const img = document.getElementById('galleryImage');
        if (!img) return;
        let idx = parseInt(img.dataset.index || 0) - 1;
        if (idx < 0) idx = galleryImgs.length - 1;
        img.dataset.index = idx;
        img.src = '{{ asset("storage/") }}/' + galleryImgs[idx];
    } else if (e.target.matches('#galleryNext') || e.target.closest('#galleryNext')) {
        const img = document.getElementById('galleryImage');
        if (!img) return;
        let idx = parseInt(img.dataset.index || 0) + 1;
        if (idx >= galleryImgs.length) idx = 0;
        img.dataset.index = idx;
        img.src = '{{ asset("storage/") }}/' + galleryImgs[idx];
    }
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape')     closeGallery();
    if (e.key === 'ArrowLeft')  document.getElementById('galleryPrev')?.click();
    if (e.key === 'ArrowRight') document.getElementById('galleryNext')?.click();
});
</script>
@endpush
@endsection