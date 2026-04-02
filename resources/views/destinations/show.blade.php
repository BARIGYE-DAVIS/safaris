@extends('layouts.app')

@section('title', $destination->meta_title ?? $destination->name)
@section('meta_description', $destination->meta_description ?? \Illuminate\Support\Str::limit($destination->description ?? $destination->detailed_overview, 160))
@section('meta_keywords', $destination->meta_keywords ?? implode(', ', array_filter([$destination->name, $destination->type, $destination->region])))

@php
$imagesIndex   = collect();
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
    $imagesByBlockId = $imagesIndex->keyBy('block_id');
}

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
    if (!empty($block['storage_path'])) return $block['storage_path'];
    return null;
};

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

$sliderCounter = 0;
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
    $html  = '<div id="' . e($sliderId) . '" class="section-img-slider relative my-8 rounded-2xl overflow-hidden shadow-xl bg-black" data-count="' . $count . '" data-current="0">';
    $html .= '<div class="slider-track flex transition-transform duration-700 ease-[cubic-bezier(0.25,0.46,0.45,0.94)]">';
    foreach ($slides as $slide) {
        $html .= '<figure class="slider-slide min-w-full relative select-none">';
        $html .= '<div class="relative w-full" style="aspect-ratio:16/9;overflow:hidden;">';
        $html .= '<img src="' . e($slide['url']) . '" alt="' . e($slide['alt']) . '" loading="lazy" class="absolute inset-0 w-full h-full object-cover pointer-events-none transition-transform duration-700">';
        if ($slide['caption']) {
            $html .= '<figcaption class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent text-white text-sm italic px-6 py-5 font-light tracking-wide">' . e($slide['caption']) . '</figcaption>';
        }
        $html .= '</div></figure>';
    }
    $html .= '</div>';

    if ($count > 1) {
        $html .= '<button type="button" onclick="sliderMove(\'' . e($sliderId) . '\',-1)" aria-label="Previous image"
            class="absolute left-4 top-1/2 -translate-y-1/2 z-10 bg-white/15 backdrop-blur-sm hover:bg-white/30 text-white border border-white/25 rounded-full w-11 h-11 flex items-center justify-center shadow-lg transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-white/50">';
        $html .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>';

        $html .= '<button type="button" onclick="sliderMove(\'' . e($sliderId) . '\',1)" aria-label="Next image"
            class="absolute right-4 top-1/2 -translate-y-1/2 z-10 bg-white/15 backdrop-blur-sm hover:bg-white/30 text-white border border-white/25 rounded-full w-11 h-11 flex items-center justify-center shadow-lg transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-white/50">';
        $html .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>';

        $html .= '<div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">';
        for ($d = 0; $d < $count; $d++) {
            $active = $d === 0 ? 'bg-white w-6' : 'bg-white/40 w-2';
            $html .= '<button type="button" onclick="sliderGoTo(\'' . e($sliderId) . '\',' . $d . ')" aria-label="Go to slide ' . ($d+1) . '"
                class="slider-dot h-2 rounded-full transition-all duration-400 ' . $active . ' hover:bg-white/80 focus:outline-none"></button>';
        }
        $html .= '</div>';
        $html .= '<div class="slider-counter absolute top-4 right-4 bg-black/40 backdrop-blur-sm text-white text-xs px-3 py-1.5 rounded-full z-10 font-medium tabular-nums tracking-wider">1 / ' . $count . '</div>';
    }

    $html .= '</div>';
    return $html;
};

$renderBlocksHtml = function($blocks) use ($resolveImagePath, $getImageCaption, $renderImageSlider, $destination, &$sliderCounter) {
    $html  = '';
    if (!is_array($blocks) || empty($blocks)) return $html;

    $blocks = array_values($blocks);
    $total  = count($blocks);
    $i      = 0;

    while ($i < $total) {
        $block = $blocks[$i];
        $type  = $block['type'] ?? 'text';

        if ($type === 'image') {
            $group = [];
            while ($i < $total && ($blocks[$i]['type'] ?? '') === 'image') {
                $group[] = $blocks[$i];
                $i++;
            }

            if (count($group) === 1) {
                $path    = $resolveImagePath($group[0]);
                $caption = $getImageCaption($group[0]);
                if ($path) {
                    $url = asset('storage/' . ltrim($path, '/'));
                    $alt = $caption ?: ($destination->name ?? 'Image');
                    $html .= '<figure class="my-8 reveal-block">';
                    $html .= '<div class="overflow-hidden rounded-2xl shadow-lg"><img src="' . e($url) . '" alt="' . e($alt) . '" loading="lazy" class="w-full h-auto object-cover transition-transform duration-700 hover:scale-105"></div>';
                    if ($caption) {
                        $html .= '<figcaption class="text-sm text-gray-500 italic mt-3 text-center font-light tracking-wide">' . e($caption) . '</figcaption>';
                    }
                    $html .= '</figure>';
                }
            } else {
                $html .= $renderImageSlider($group, $sliderCounter);
            }
            continue;
        }

        switch ($type) {
            case 'heading':
                $raw = $block['text'] ?? '';
                $iconMarker = '###ICON###';
                $iconRep = [];
                $t = preg_replace_callback('/\[\[icon:([^\]]+)\]\]/', function($m) use (&$iconRep, $iconMarker) {
                    $cls = trim($m[1]);
                    if (preg_match('/^(fas|far|fab|fal|fad)\s+fa-[\w-]+$/i', $cls)) {
                        $ih = '<i class="' . e($cls) . ' mr-2 text-emerald-600"></i>';
                        $ph = $iconMarker . count($iconRep) . $iconMarker;
                        $iconRep[$ph] = $ih;
                        return $ph;
                    }
                    return '';
                }, $raw);
                $esc = e($t);
                foreach ($iconRep as $ph => $ih) $esc = str_replace(e($ph), $ih, $esc);
                $html .= '<h2 class="text-2xl font-bold mt-8 mb-3 text-emerald-800 tracking-tight reveal-block">' . $esc . '</h2>';
                break;

            case 'subheading':
                $raw = $block['text'] ?? '';
                $iconMarker = '###ICON###';
                $iconRep = [];
                $t = preg_replace_callback('/\[\[icon:([^\]]+)\]\]/', function($m) use (&$iconRep, $iconMarker) {
                    $cls = trim($m[1]);
                    if (preg_match('/^(fas|far|fab|fal|fad)\s+fa-[\w-]+$/i', $cls)) {
                        $ih = '<i class="' . e($cls) . ' mr-2 text-emerald-500"></i>';
                        $ph = $iconMarker . count($iconRep) . $iconMarker;
                        $iconRep[$ph] = $ih;
                        return $ph;
                    }
                    return '';
                }, $raw);
                $esc = e($t);
                foreach ($iconRep as $ph => $ih) $esc = str_replace(e($ph), $ih, $esc);
                $html .= '<h3 class="text-xl font-semibold mt-6 mb-2 text-emerald-700 tracking-tight reveal-block">' . $esc . '</h3>';
                break;

            case 'text':
                $text = $block['text'] ?? '';
                $text = preg_replace('/\*\*(.*?)\*\*/', '<strong class="font-semibold text-gray-800">$1</strong>', $text);
                $text = preg_replace('/\*(.*?)\*/',     '<em>$1</em>', $text);
                $iconMarker = '###ICON###';
                $iconRep = [];
                $text = preg_replace_callback('/\[\[icon:([^\]]+)\]\]/', function($m) use (&$iconRep, $iconMarker) {
                    $cls = trim($m[1]);
                    if (preg_match('/^(fas|far|fab|fal|fad)\s+fa-[\w-]+$/i', $cls)) {
                        $ih = '<i class="' . e($cls) . ' mr-2 text-emerald-600"></i>';
                        $ph = $iconMarker . count($iconRep) . $iconMarker;
                        $iconRep[$ph] = $ih;
                        return $ph;
                    }
                    return '';
                }, $text);
                $esc = e($text);
                foreach ($iconRep as $ph => $ih) $esc = str_replace(e($ph), $ih, $esc);
                $esc = nl2br($esc, false);
                $html .= '<div class="prose-custom text-gray-600 mb-5 leading-relaxed reveal-block">' . $esc . '</div>';
                break;

            default:
                $text = $block['text'] ?? '';
                $html .= '<div class="prose-custom text-gray-600 mb-5 leading-relaxed reveal-block">' . nl2br(e($text)) . '</div>';
                break;
        }

        $i++;
    }
    return $html;
};

$getSectionBlocks = function(string $key) use ($destination) {
    $sections = $destination->sections_content ?? [];
    if (!empty($sections[$key]) && is_array($sections[$key])) return $sections[$key];
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

@push('styles')
<style>
/* ─── Parallax Header ─────────────────────────────── */
.parallax-header {
    position: relative;
    height: 100vh;
    min-height: 560px;
    max-height: 800px;
    overflow: hidden;
}

.parallax-bg {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 130%;
    top: -15%;
    object-fit: cover;
    object-position: center;
    will-change: transform;
    transform: translateY(0px) scale(1.0);
    transition: transform 0s linear;
}

.parallax-gradient {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to bottom,
        rgba(0,0,0,0.10) 0%,
        rgba(0,0,0,0.10) 40%,
        rgba(0,0,0,0.65) 80%,
        rgba(0,0,0,0.80) 100%
    );
    z-index: 1;
}

.parallax-content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 2;
    padding: 2.5rem 2rem 3.5rem;
}

/* ─── Scroll-down indicator ───────────────────────── */
.scroll-indicator {
    position: absolute;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 3;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    opacity: 1;
    transition: opacity 0.4s ease;
    cursor: pointer;
}
.scroll-indicator span {
    color: rgba(255,255,255,0.7);
    font-size: 0.65rem;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    font-weight: 500;
}
.scroll-indicator-arrow {
    width: 30px;
    height: 30px;
    border-right: 2px solid rgba(255,255,255,0.6);
    border-bottom: 2px solid rgba(255,255,255,0.6);
    transform: rotate(45deg);
    animation: scrollBounce 1.8s ease-in-out infinite;
    margin-top: -6px;
}
@keyframes scrollBounce {
    0%, 100% { transform: rotate(45deg) translateY(0); opacity: 0.6; }
    50% { transform: rotate(45deg) translateY(6px); opacity: 1; }
}

/* ─── Hero title animation ───────────────────────── */
.hero-title {
    opacity: 0;
    transform: translateY(30px);
    animation: heroFadeUp 0.9s cubic-bezier(0.22, 1, 0.36, 1) 0.2s forwards;
}
.hero-subtitle {
    opacity: 0;
    transform: translateY(20px);
    animation: heroFadeUp 0.9s cubic-bezier(0.22, 1, 0.36, 1) 0.45s forwards;
}
.hero-badges {
    opacity: 0;
    transform: translateY(15px);
    animation: heroFadeUp 0.9s cubic-bezier(0.22, 1, 0.36, 1) 0.1s forwards;
}
@keyframes heroFadeUp {
    to { opacity: 1; transform: translateY(0); }
}

/* ─── Sticky nav progress bar ────────────────────── */
#reading-progress {
    position: fixed;
    top: 0;
    left: 0;
    height: 3px;
    background: linear-gradient(90deg, #059669, #34d399);
    z-index: 9999;
    width: 0%;
    transition: width 0.1s linear;
    border-radius: 0 2px 2px 0;
}

/* ─── Breadcrumb bar ─────────────────────────────── */
.breadcrumb-bar {
    background: rgba(255,255,255,0.97);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border-bottom: 1px solid rgba(0,0,0,0.07);
    position: sticky;
    top: 0;
    z-index: 40;
    transition: box-shadow 0.3s ease;
}
.breadcrumb-bar.scrolled {
    box-shadow: 0 2px 20px rgba(0,0,0,0.08);
}

/* ─── Section cards ──────────────────────────────── */
.content-section {
    background: #ffffff;
    border-radius: 20px;
    padding: 2.25rem;
    border: 1px solid rgba(0,0,0,0.06);
    box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 8px 32px rgba(0,0,0,0.03);
    transition: box-shadow 0.4s ease, transform 0.4s ease;
}
.content-section:hover {
    box-shadow: 0 4px 6px rgba(0,0,0,0.04), 0 16px 48px rgba(0,0,0,0.07);
    transform: translateY(-2px);
}
.section-title {
    font-size: 1.35rem;
    font-weight: 700;
    color: #065f46;
    margin-bottom: 1.25rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #d1fae5;
    display: flex;
    align-items: center;
    gap: 0.625rem;
    letter-spacing: -0.01em;
}
.section-title-icon {
    width: 32px;
    height: 32px;
    background: #d1fae5;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.section-title-icon svg {
    width: 16px;
    height: 16px;
    color: #059669;
}

/* ─── Prose styles ───────────────────────────────── */
.prose-custom {
    font-size: 0.9625rem;
    line-height: 1.78;
    color: #4b5563;
}

/* ─── Reveal animations ──────────────────────────── */
.reveal-block {
    opacity: 0;
    transform: translateY(18px);
    transition: opacity 0.6s cubic-bezier(0.22, 1, 0.36, 1), transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
}
.reveal-block.revealed {
    opacity: 1;
    transform: translateY(0);
}
.content-section {
    opacity: 0;
    transform: translateY(24px);
    transition: opacity 0.7s cubic-bezier(0.22, 1, 0.36, 1),
                transform 0.7s cubic-bezier(0.22, 1, 0.36, 1),
                box-shadow 0.4s ease;
}
.content-section.revealed {
    opacity: 1;
    transform: translateY(0);
}
.content-section.revealed:hover {
    transform: translateY(-2px);
}

/* ─── Sidebar card ───────────────────────────────── */
.sidebar-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid rgba(0,0,0,0.07);
    box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 8px 32px rgba(0,0,0,0.04);
    padding: 1.5rem;
    overflow: hidden;
}
.sidebar-card-header {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    font-weight: 600;
    color: #9ca3af;
    margin-bottom: 1rem;
}
.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.6rem 0;
    border-bottom: 1px solid rgba(0,0,0,0.04);
    font-size: 0.875rem;
}
.info-row:last-child { border-bottom: none; }
.info-label { color: #9ca3af; font-weight: 400; }
.info-value { color: #111827; font-weight: 500; }

/* ─── CTA button ─────────────────────────────────── */
.cta-btn {
    display: block;
    text-align: center;
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: #fff;
    padding: 0.875rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9375rem;
    letter-spacing: 0.01em;
    transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
    box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35);
    text-decoration: none;
}
.cta-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(5, 150, 105, 0.45);
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}
.cta-btn:active { transform: translateY(0); }

.cta-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    background: #fff;
    color: #059669;
    border: 1.5px solid #a7f3d0;
    padding: 0.625rem 1.25rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.25s ease;
    text-decoration: none;
}
.cta-secondary:hover {
    background: #ecfdf5;
    border-color: #059669;
}

/* ─── Badge pills ────────────────────────────────── */
.badge-type {
    background: rgba(5,150,105,0.92);
    backdrop-filter: blur(6px);
    color: #fff;
    padding: 0.375rem 1rem;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    border: 1px solid rgba(255,255,255,0.2);
}
.badge-region {
    background: rgba(37,99,235,0.85);
    backdrop-filter: blur(6px);
    color: #fff;
    padding: 0.375rem 1rem;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    border: 1px solid rgba(255,255,255,0.2);
}

/* ─── Gallery grid ───────────────────────────────── */
.gallery-item {
    overflow: hidden;
    border-radius: 12px;
    cursor: pointer;
    position: relative;
}
.gallery-item img {
    transition: transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
    width: 100%;
    height: 11rem;
    object-fit: cover;
    display: block;
}
.gallery-item:hover img { transform: scale(1.07); }
.gallery-item::after {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0);
    transition: background 0.3s ease;
}
.gallery-item:hover::after { background: rgba(0,0,0,0.12); }

/* ─── Related cards ──────────────────────────────── */
.related-card {
    border-radius: 14px;
    overflow: hidden;
    background: #fff;
    border: 1px solid rgba(0,0,0,0.06);
    transition: all 0.35s cubic-bezier(0.22, 1, 0.36, 1);
    text-decoration: none;
    display: block;
}
.related-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 36px rgba(0,0,0,0.1);
}
.related-card img {
    transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1);
}
.related-card:hover img { transform: scale(1.05); }

/* ─── Gallery modal ──────────────────────────────── */
#galleryModal {
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}
.modal-inner {
    background: #111;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 40px 120px rgba(0,0,0,0.8);
    max-width: 56rem;
    width: 100%;
}

/* ─── About card top ─────────────────────────────── */
.about-card {
    background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
    border: 1px solid #a7f3d0;
    border-radius: 20px;
    padding: 1.75rem;
}

@media (max-width: 768px) {
    .parallax-header { height: 70vw; min-height: 360px; max-height: 520px; }
    .content-section { padding: 1.5rem; }
}
</style>
@endpush

@section('page-header')
{{-- ═══════════════════════════════════════════════════ --}}
{{--   PARALLAX HERO HEADER                             --}}
{{-- ═══════════════════════════════════════════════════ --}}
<header class="parallax-header" id="parallax-header">
    @if($destination->featured_image)
        <img id="parallax-bg" class="parallax-bg"
             src="{{ asset('storage/' . $destination->featured_image) }}"
             alt="{{ $destination->name }}">
    @elseif($destination->image)
        <img id="parallax-bg" class="parallax-bg"
             src="{{ asset('storage/' . $destination->image) }}"
             alt="{{ $destination->name }}">
    @else
        <div class="parallax-bg" style="background: linear-gradient(135deg, #064e3b 0%, #065f46 40%, #1d4ed8 100%);"></div>
    @endif

    {{-- Deep multi-stop gradient overlay --}}
    <div class="parallax-gradient"></div>

    {{-- Hero content --}}
    <div class="parallax-content">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="hero-badges flex flex-wrap gap-2 mb-5">
                @if($destination->type)
                    <span class="badge-type">{{ $destination->type }}</span>
                @endif
                @if($destination->region)
                    <span class="badge-region">{{ $destination->region }}</span>
                @endif
            </div>

            <h1 class="hero-title text-4xl lg:text-6xl font-extrabold text-white mb-4 leading-tight tracking-tight" style="text-shadow: 0 2px 20px rgba(0,0,0,0.3);">
                {{ $destination->name }}
            </h1>

            @if($destination->description)
                <p class="hero-subtitle max-w-2xl text-white/80 text-base lg:text-lg font-light leading-relaxed">
                    {{ \Illuminate\Support\Str::limit(strip_tags($destination->description), 200) }}
                </p>
            @endif
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="scroll-indicator" id="scroll-indicator" onclick="document.getElementById('main-content').scrollIntoView({behavior:'smooth'})">
        <span>Scroll</span>
        <div class="scroll-indicator-arrow"></div>
    </div>
</header>
@endsection

@section('content')

{{-- Reading progress bar --}}
<div id="reading-progress"></div>

{{-- ─── Breadcrumb bar ─────────────────────────────────── --}}
<nav class="breadcrumb-bar" id="breadcrumb-bar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
        <div class="flex items-center space-x-2 text-sm">
            <a href="{{ route('index') }}" class="text-gray-400 hover:text-emerald-600 transition-colors duration-200">Home</a>
            <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('destinations.index') }}" class="text-gray-400 hover:text-emerald-600 transition-colors duration-200">Destinations</a>
            <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-700 font-medium">{{ $destination->name }}</span>
        </div>
    </div>
</nav>

{{-- ─── Main content ────────────────────────────────────── --}}
<main id="main-content" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        {{-- ══════════════════════════════════════════
             LEFT: Article
        ═══════════════════════════════════════════ --}}
        <article class="lg:col-span-2 space-y-7">

            {{-- About card --}}
            <div class="about-card reveal-section">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-1">About {{ $destination->name }}</h2>
                        @if($destination->best_season)
                            <p class="text-sm text-gray-500 flex items-center gap-1.5 mt-2">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Best time to visit: <strong class="text-emerald-700 font-semibold">{{ $destination->best_season }}</strong>
                            </p>
                        @endif
                    </div>
                  
                </div>
            </div>

            {{-- Overview --}}
            @if($hasSectionContent('overview'))
                <section id="overview" class="content-section">
                    <h2 class="section-title">
                        <span class="section-title-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                        </span>
                        Overview
                    </h2>
                    <div>{!! $renderBlocksHtml($getSectionBlocks('overview')) !!}</div>
                </section>
            @endif

            {{-- Activities --}}
            @if($hasSectionContent('activities'))
                <section id="activities" class="content-section">
                    <h2 class="section-title">
                        <span class="section-title-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </span>
                        Things to Do
                    </h2>
                    <div>{!! $renderBlocksHtml($getSectionBlocks('activities')) !!}</div>
                </section>
            @endif

            {{-- Wildlife --}}
            @if($hasSectionContent('wildlife'))
                <section id="wildlife" class="content-section">
                    <h2 class="section-title">
                        <span class="section-title-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </span>
                        Wildlife &amp; Highlights
                    </h2>
                    <div>{!! $renderBlocksHtml($getSectionBlocks('wildlife')) !!}</div>
                </section>
            @endif

            {{-- Geography --}}
            @if($hasSectionContent('geography'))
                <section id="geography" class="content-section">
                    <h2 class="section-title">
                        <span class="section-title-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        Geography &amp; Landscape
                    </h2>
                    <div>{!! $renderBlocksHtml($getSectionBlocks('geography')) !!}</div>
                </section>
            @endif

            {{-- Practical --}}
            @if($hasSectionContent('practical'))
                <section id="practical" class="content-section">
                    <h2 class="section-title">
                        <span class="section-title-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        Practical Information
                    </h2>
                    <div>{!! $renderBlocksHtml($getSectionBlocks('practical')) !!}</div>
                </section>
            @endif

            {{-- Accommodation --}}
            @if($hasSectionContent('accommodation'))
                <section id="accommodation" class="content-section">
                    <h2 class="section-title">
                        <span class="section-title-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </span>
                        Accommodation
                    </h2>
                    <div>{!! $renderBlocksHtml($getSectionBlocks('accommodation')) !!}</div>
                </section>
            @endif

            {{-- Extras --}}
            @if($hasSectionContent('extras'))
                <section id="extras" class="content-section">
                    <h2 class="section-title">
                        <span class="section-title-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </span>
                        Interesting Facts
                    </h2>
                    <div>{!! $renderBlocksHtml($getSectionBlocks('extras')) !!}</div>
                </section>
            @endif

            {{-- Photo Gallery --}}
            @php
                $galleryImages = collect();
                if (is_array($destination->gallery_images) && !empty($destination->gallery_images)) {
                    $galleryImages = collect($destination->gallery_images)->pluck('image')->filter()->values();
                }
            @endphp

            @if($galleryImages->isNotEmpty())
                <section id="gallery" class="content-section">
                    <h2 class="section-title">
                        <span class="section-title-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </span>
                        Photo Gallery
                    </h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($galleryImages as $i => $imgPath)
                            <div class="gallery-item reveal-block" style="transition-delay: {{ ($i % 8) * 60 }}ms" onclick="openGallery({{ $i }})">
                                <img src="{{ asset('storage/' . $imgPath) }}" alt="{{ $destination->name }} photo {{ $i+1 }}" loading="lazy">
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Related Destinations --}}
            @if(!empty($relatedDestinations) && $relatedDestinations->count())
                <section class="content-section">
                    <h2 class="section-title">
                        <span class="section-title-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        </span>
                        You Might Also Like
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($relatedDestinations as $rel)
                            <a href="{{ route('destinations.show', $rel->slug) }}" class="related-card reveal-block">
                                <div class="h-40 bg-gray-100 overflow-hidden">
                                    @if($rel->featured_image || $rel->image)
                                        <img src="{{ asset('storage/' . ($rel->featured_image ?? $rel->image)) }}" alt="{{ $rel->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-emerald-300 to-blue-400 flex items-center justify-center text-white font-bold text-sm">
                                            {{ \Illuminate\Support\Str::limit($rel->name, 18) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h4 class="font-semibold text-gray-900 text-sm mb-1">{{ $rel->name }}</h4>
                                    <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">{{ \Illuminate\Support\Str::limit($rel->description ?? '', 100) }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

        </article>

        {{-- ══════════════════════════════════════════
             RIGHT: Sidebar
        ═══════════════════════════════════════════ --}}
        <aside class="lg:col-span-1">
            <div class="sticky top-20 space-y-5">

                {{-- Quick Info --}}
                <div class="sidebar-card reveal-section" style="transition-delay: 150ms;">
                    <p class="sidebar-card-header">Quick Info</p>
                    <div class="space-y-0">
                        <div class="info-row">
                            <span class="info-label">Location</span>
                            <span class="info-value">{{ $destination->region ?? ($destination->country->name ?? '—') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Type</span>
                            <span class="info-value">{{ $destination->type ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Area</span>
                            <span class="info-value">{{ $destination->area_size ? number_format($destination->area_size) . ' km²' : '—' }}</span>
                        </div>
                        @if($destination->latitude && $destination->longitude)
                            <div class="info-row">
                                <span class="info-label">Coordinates</span>
                                <span class="info-value text-xs tabular-nums">{{ number_format($destination->latitude,4) }}, {{ number_format($destination->longitude,4) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="mt-5">
                        <a href="{{ route('custom-tour-requests.create') }}" class="cta-btn">
                            Plan Your Trip &rarr;
                        </a>
                    </div>
                </div>

                {{-- Contact --}}
                @if($destination->phone || $destination->email || $destination->website)
                    <div class="sidebar-card reveal-section" style="transition-delay: 250ms;">
                        <p class="sidebar-card-header">Contact</p>
                        <div class="space-y-3 text-sm">
                            @if($destination->phone)
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </div>
                                    <a href="tel:{{ $destination->phone }}" class="text-emerald-600 hover:text-emerald-700 font-medium transition-colors">{{ $destination->phone }}</a>
                                </div>
                            @endif
                            @if($destination->email)
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <a href="mailto:{{ $destination->email }}" class="text-blue-600 hover:text-blue-700 font-medium transition-colors break-all">{{ $destination->email }}</a>
                                </div>
                            @endif
                            @if($destination->website)
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </div>
                                    <a href="{{ $destination->website }}" target="_blank" rel="noopener" class="text-purple-600 hover:text-purple-700 font-medium transition-colors">{{ parse_url($destination->website, PHP_URL_HOST) }}</a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </aside>
    </div>
</main>

{{-- ─── Gallery Modal ───────────────────────────────────── --}}
@if(isset($galleryImages) && $galleryImages->isNotEmpty())
<div id="galleryModal" class="fixed inset-0 bg-black/85 z-50 hidden items-center justify-center p-4"
     onclick="if(event.target===this)closeGallery()">
    <div class="modal-inner relative">
        <button onclick="closeGallery()" class="absolute -top-3 -right-3 z-20 w-9 h-9 bg-white rounded-full flex items-center justify-center shadow-lg hover:bg-gray-100 transition-colors">
            <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <img id="galleryImage" src="" alt="Gallery image" class="w-full h-auto max-h-[80vh] object-contain">
        <div class="flex items-center justify-between px-4 py-3 bg-black/30">
            <button id="galleryPrev" class="flex items-center gap-1.5 text-white/80 hover:text-white text-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Previous
            </button>
            <span id="galleryCounter" class="text-white/60 text-xs tabular-nums tracking-wider"></span>
            <button id="galleryNext" class="flex items-center gap-1.5 text-white/80 hover:text-white text-sm transition-colors">
                Next
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
/* ═══════════════════════════════════════════════════════════
   1. PARALLAX SCROLLING
   Uses requestAnimationFrame for buttery-smooth 60fps movement.
   The image is taller than the header (130%) and starts offset
   so it slides gently upward as the user scrolls down.
═══════════════════════════════════════════════════════════ */
(function () {
    const bg         = document.getElementById('parallax-bg');
    const header     = document.getElementById('parallax-header');
    const indicator  = document.getElementById('scroll-indicator');
    if (!bg || !header) return;

    const SPEED = 0.38; // 0 = no parallax, 1 = locked to page scroll
    let   ticking = false;

    function applyParallax() {
        const scrollY      = window.scrollY;
        const headerBottom = header.offsetTop + header.offsetHeight;

        // Only run while the header is visible
        if (scrollY < headerBottom) {
            const offset = scrollY * SPEED;
            bg.style.transform = `translate3d(0, ${offset}px, 0)`;
        }

        // Fade out scroll indicator
        if (indicator) {
            const fade = Math.max(0, 1 - scrollY / 200);
            indicator.style.opacity = fade;
        }

        ticking = false;
    }

    window.addEventListener('scroll', function () {
        if (!ticking) {
            requestAnimationFrame(applyParallax);
            ticking = true;
        }
    }, { passive: true });

    // Run once on load
    applyParallax();
})();


/* ═══════════════════════════════════════════════════════════
   2. READING PROGRESS BAR
═══════════════════════════════════════════════════════════ */
(function () {
    const bar = document.getElementById('reading-progress');
    if (!bar) return;

    window.addEventListener('scroll', function () {
        const scrollTop  = window.scrollY;
        const docHeight  = document.documentElement.scrollHeight - window.innerHeight;
        const pct        = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
        bar.style.width  = Math.min(pct, 100) + '%';
    }, { passive: true });
})();


/* ═══════════════════════════════════════════════════════════
   3. STICKY BREADCRUMB SHADOW ON SCROLL
═══════════════════════════════════════════════════════════ */
(function () {
    const nav = document.getElementById('breadcrumb-bar');
    if (!nav) return;

    window.addEventListener('scroll', function () {
        nav.classList.toggle('scrolled', window.scrollY > 60);
    }, { passive: true });
})();


/* ═══════════════════════════════════════════════════════════
   4. INTERSECTION OBSERVER — scroll reveal animations
   Sections and blocks fade+slide up as they enter the viewport.
═══════════════════════════════════════════════════════════ */
(function () {
    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold:  0.08,
        rootMargin: '0px 0px -40px 0px'
    });

    document.querySelectorAll('.content-section, .reveal-block, .reveal-section').forEach(function (el) {
        observer.observe(el);
    });
})();


/* ═══════════════════════════════════════════════════════════
   5. INLINE SECTION IMAGE SLIDER
═══════════════════════════════════════════════════════════ */
function sliderApply(id, index) {
    const el = document.getElementById(id);
    if (!el) return;

    const track   = el.querySelector('.slider-track');
    const dots    = el.querySelectorAll('.slider-dot');
    const counter = el.querySelector('.slider-counter');
    const count   = parseInt(el.dataset.count || 1);

    index = ((index % count) + count) % count;
    el.dataset.current = index;

    track.style.transform = `translateX(-${index * 100}%)`;

    dots.forEach(function (dot, i) {
        if (i === index) {
            dot.classList.remove('bg-white/40', 'w-2');
            dot.classList.add('bg-white', 'w-6');
        } else {
            dot.classList.remove('bg-white', 'w-6');
            dot.classList.add('bg-white/40', 'w-2');
        }
    });

    if (counter) counter.textContent = `${index + 1} / ${count}`;
}

function sliderMove(id, dir) {
    const el = document.getElementById(id);
    if (!el) return;
    sliderApply(id, parseInt(el.dataset.current || 0) + dir);
}

function sliderGoTo(id, index) {
    sliderApply(id, index);
}

// Touch / swipe support for sliders
(function () {
    let startX = 0, startId = null;

    document.addEventListener('touchstart', function (e) {
        const s = e.target.closest('.section-img-slider');
        if (s) { startX = e.touches[0].clientX; startId = s.id; }
    }, { passive: true });

    document.addEventListener('touchend', function (e) {
        if (!startId) return;
        const diff = startX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 40) sliderMove(startId, diff > 0 ? 1 : -1);
        startId = null;
    }, { passive: true });
})();


/* ═══════════════════════════════════════════════════════════
   6. GALLERY MODAL
═══════════════════════════════════════════════════════════ */
const galleryImgs = @json($galleryImages->all() ?? []);
const baseUrl     = '{{ asset("storage/") }}/';

function _galleryShow(idx) {
    const img     = document.getElementById('galleryImage');
    const counter = document.getElementById('galleryCounter');
    if (!img) return;

    idx = ((idx % galleryImgs.length) + galleryImgs.length) % galleryImgs.length;
    img.dataset.index = idx;
    img.src           = baseUrl + galleryImgs[idx];

    if (counter) counter.textContent = `${idx + 1} / ${galleryImgs.length}`;
}

function openGallery(index) {
    if (!galleryImgs.length) return;
    const modal = document.getElementById('galleryModal');
    if (!modal) return;
    _galleryShow(parseInt(index) || 0);
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeGallery() {
    const modal = document.getElementById('galleryModal');
    const img   = document.getElementById('galleryImage');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    if (img) img.src = '';
    document.body.style.overflow = '';
}

document.addEventListener('click', function (e) {
    if (e.target.closest('#galleryPrev')) {
        const img = document.getElementById('galleryImage');
        if (img) _galleryShow(parseInt(img.dataset.index || 0) - 1);
    } else if (e.target.closest('#galleryNext')) {
        const img = document.getElementById('galleryImage');
        if (img) _galleryShow(parseInt(img.dataset.index || 0) + 1);
    }
});

document.addEventListener('keydown', function (e) {
    const modal = document.getElementById('galleryModal');
    if (!modal || modal.classList.contains('hidden')) return;
    if (e.key === 'Escape')     closeGallery();
    if (e.key === 'ArrowLeft')  document.getElementById('galleryPrev')?.click();
    if (e.key === 'ArrowRight') document.getElementById('galleryNext')?.click();
});
</script>
@endpush
@endsection