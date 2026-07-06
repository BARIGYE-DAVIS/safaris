@extends('layouts.app')

@section('title', $accommodation->meta_title ?? $accommodation->name)
@section('meta_description', $accommodation->meta_description ?? $accommodation->short_description ?? '')
@section('meta_keywords', $accommodation->focus_keyword ?? '')

@section('content')
<div class="bg-gray-50">

    {{-- ═══ HERO SECTION ══════════════════════════════════════════════════ --}}
    <section class="relative w-full flex items-center justify-center bg-no-repeat bg-center"
             style="min-height: 100vh; min-height: 100svh;
                    background-image: url('{{ $accommodation->featured_image_url ?? asset('images/placeholder-wide.jpg') }}');
                    background-size: cover;
                    background-attachment: fixed;
                    background-color: #1a3c34;">
        <div class="absolute inset-0 bg-black/60"></div>

        <div class="relative z-10 container mx-auto px-4 text-center text-white py-16 md:py-24">
            <div class="max-w-4xl mx-auto">
                @if($accommodation->category)
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold mb-4
                        @if($accommodation->category === 'budget') bg-blue-500/80 text-white
                        @elseif($accommodation->category === 'mid-range') bg-green-500/80 text-white
                        @else bg-purple-500/80 text-white @endif">
                        {{ ucfirst($accommodation->category) }}
                    </span>
                @endif

                <h1 class="text-4xl md:text-5xl lg:text-7xl font-bold text-white leading-tight mb-4">
                    {{ $accommodation->name }}
                </h1>

                <div class="flex flex-wrap justify-center gap-3 mt-4">
                    @if($accommodation->type)
                        <span class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-xs md:text-sm font-semibold text-white border border-white/30">
                            {{ $accommodation->type }}
                        </span>
                    @endif
                    @if($accommodation->country)
                        <span class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-xs md:text-sm font-semibold text-white border border-white/30">
                            {{ $accommodation->country->name }}
                        </span>
                    @endif
                    @if($accommodation->destination)
                        <span class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-xs md:text-sm font-semibold text-white border border-white/30">
                            {{ $accommodation->destination->name }}
                        </span>
                    @endif
                    @if($accommodation->location)
                        <span class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-xs md:text-sm font-semibold text-white border border-white/30">
                            <i class="fas fa-map-marker-alt mr-1"></i> {{ $accommodation->location }}
                        </span>
                    @endif
                </div>

                <nav class="mt-6 text-sm md:text-base">
                    <ol class="flex justify-center flex-wrap gap-1 text-green-200">
                        <li><a href="{{ route('index') }}" class="hover:text-white transition-colors text-green-300">Home</a></li>
                        <li class="mx-1">/</li>
                        <li><a href="{{ route('accommodations.index') }}" class="hover:text-white transition-colors text-green-300">Accommodations</a></li>
                        <li class="mx-1">/</li>
                        <li class="text-white font-medium">{{ $accommodation->name }}</li>
                    </ol>
                </nav>

                {{-- Scroll hint --}}
                <div class="mt-12 animate-bounce">
                    <i class="fas fa-chevron-down text-white/70 text-2xl"></i>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ CONTENT SECTION ═══════════════════════════════════════════════ --}}
    <section class="relative py-6 md:py-8 bg-white mt-[-2px] rounded-t-3xl shadow-2xl">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="p-6 md:p-8 lg:p-10">

                    {{-- SHORT DESCRIPTION --}}
                    @if($accommodation->short_description)
                        <div class="text-gray-700 leading-relaxed mb-8">
                            <p class="text-lg md:text-xl lg:text-2xl font-light text-gray-600 leading-relaxed border-l-4 border-green-500 pl-6">
                                {{ $accommodation->short_description }}
                            </p>
                        </div>
                    @endif

                    {{-- CONTENT BLOCKS --}}
                    @php
                        $blocks = $accommodation->content_blocks ?? [];
                        $allBlockImages = collect();

                        if (is_string($blocks)) {
                            $blocks = json_decode($blocks, true);
                        }
                        if (!is_array($blocks)) {
                            $blocks = [];
                        }
                    @endphp

                    @forelse($blocks as $block)
                        @php $type = $block['type'] ?? 'text'; @endphp

                        {{-- ─── HEADING ──────────────────────────────────── --}}
                        @if($type === 'heading')
                            @php
                                $level = $block['heading_level'] ?? 'h2';
                                $headingClasses = [
                                    'h1' => 'text-3xl md:text-4xl lg:text-5xl font-extrabold text-green-700 mb-4 mt-8',
                                    'h2' => 'text-2xl md:text-3xl lg:text-4xl font-bold text-green-600 mb-3 mt-6',
                                    'h3' => 'text-xl md:text-2xl lg:text-3xl font-bold text-green-500 mb-3 mt-5',
                                    'h4' => 'text-lg md:text-xl lg:text-2xl font-semibold text-green-400 mb-2 mt-4',
                                    'h5' => 'text-base md:text-lg lg:text-xl font-semibold text-green-300 mb-2 mt-3',
                                    'h6' => 'text-sm md:text-base lg:text-lg font-semibold text-green-200 mb-2 mt-3',
                                ];
                            @endphp
                            <{{ $level }} class="{{ $headingClasses[$level] ?? $headingClasses['h2'] }}">
                                {{ $block['content'] ?? '' }}
                            </{{ $level }}>

                        {{-- ─── TEXT ─────────────────────────────────────── --}}
                        @elseif($type === 'text')
                            <div class="text-gray-700 leading-relaxed mb-4 text-base md:text-lg lg:text-xl space-y-3">
                                {!! $block['content'] ?? '' !!}
                            </div>

                        {{-- ─── LIST ─────────────────────────────────────── --}}
                        @elseif($type === 'list')
                            @php
                                $listType = $block['list_type'] ?? 'ul';
                                $listClasses = 'mb-4 space-y-2 text-gray-700 text-base md:text-lg lg:text-xl';
                            @endphp
                            @if($listType === 'ul')
                                <ul class="list-disc list-inside {{ $listClasses }}">
                                    {!! $block['content'] ?? '' !!}
                                </ul>
                            @else
                                <ol class="list-decimal list-inside {{ $listClasses }}">
                                    {!! $block['content'] ?? '' !!}
                                </ol>
                            @endif

                        {{-- ─── IMAGE ─────────────────────────────────────── --}}
                        @elseif($type === 'image')
                            @php
                                $blockId    = $block['id'] ?? null;
                                $blockImages = collect();

                                if ($blockId) {
                                    $blockImages = $accommodation->images->filter(
                                        fn($img) => $img->block_id === $blockId
                                    );
                                }

                                $count = $blockImages->count();

                                if ($count === 1) {
                                    $gridClass = 'grid grid-cols-1';
                                    $imgHeight = 'h-72 md:h-[28rem]';
                                } elseif ($count === 2) {
                                    $gridClass = 'grid grid-cols-1 md:grid-cols-2';
                                    $imgHeight = 'h-64 md:h-80 lg:h-[24rem]';
                                } elseif ($count >= 3) {
                                    $gridClass = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3';
                                    $imgHeight = 'h-56 md:h-64 lg:h-80';
                                } else {
                                    $gridClass = 'grid grid-cols-1';
                                    $imgHeight = 'h-72';
                                }
                            @endphp

                            @if($blockImages->isNotEmpty())
                                <div class="{{ $gridClass }} gap-4 mb-6">
                                    @foreach($blockImages as $image)
                                        <div class="relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 group cursor-zoom-in"
                                             onclick="openImageModal('{{ $image->url }}', '{{ addslashes($image->alt_text ?? $block['caption'] ?? $accommodation->name) }}')">
                                            <img src="{{ $image->url }}"
                                                 alt="{{ $image->alt_text ?? $block['caption'] ?? $accommodation->name }}"
                                                 class="w-full {{ $imgHeight }} object-cover transition-transform duration-500 group-hover:scale-105 pointer-events-none">
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/25 transition-all duration-300 flex items-center justify-center pointer-events-none">
                                                <i class="fas fa-search-plus text-white text-3xl opacity-0 group-hover:opacity-100 transition-all duration-300 drop-shadow-lg"></i>
                                            </div>
                                            @if($image->alt_text || !empty($block['caption']))
                                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent text-white text-xs md:text-sm p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                                                    {{ $block['caption'] ?? $image->alt_text }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @php $allBlockImages = $allBlockImages->merge($blockImages); @endphp

                        {{-- ─── TABLE ─────────────────────────────────────── --}}
                        @elseif($type === 'table')
                            @php
                                $tableClasses = 'w-full text-sm md:text-base border-collapse';
                                if ($block['striped']  ?? false) $tableClasses .= ' striped';
                                if ($block['bordered'] ?? true)  $tableClasses .= ' bordered';
                                if ($block['hoverable']?? false) $tableClasses .= ' hoverable';
                                if ($block['small']    ?? false) $tableClasses .= ' small';

                                $headerBg    = $block['header_bg_color']  ?? '#1a3c34';
                                $headerText  = $block['header_text_color']?? '#ffffff';
                                $rowBg       = $block['row_bg_color']     ?? '#ffffff';
                                $rowAltBg    = $block['row_bg_alt_color'] ?? '#f8fafc';
                                $rowText     = $block['row_text_color']   ?? '#1e293b';
                                $borderColor = $block['border_color']     ?? '#e2e8f0';
                            @endphp

                            <div class="mb-6 overflow-x-auto">
                                @if(!empty($block['caption']))
                                    <p class="text-sm text-gray-500 mb-2 italic">{{ $block['caption'] }}</p>
                                @endif
                                <table class="min-w-full {{ $tableClasses }}" style="border-color: {{ $borderColor }};">
                                    @if(!empty($block['headers']) && is_array($block['headers']))
                                        <thead>
                                            <tr>
                                                @foreach($block['headers'] as $header)
                                                    <th style="background-color:{{ $headerBg }};color:{{ $headerText }};border-color:{{ $borderColor }};padding:0.6rem 1rem;text-align:left;font-weight:600;">
                                                        {{ $header }}
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                    @endif
                                    @if(!empty($block['rows']) && is_array($block['rows']))
                                        <tbody>
                                            @foreach($block['rows'] as $rowIndex => $row)
                                                <tr>
                                                    @foreach($row as $cell)
                                                        @php
                                                            $bgColor = ($block['striped'] ?? false)
                                                                ? (($rowIndex % 2 === 0) ? $rowBg : $rowAltBg)
                                                                : $rowBg;
                                                        @endphp
                                                        <td style="background-color:{{ $bgColor }};color:{{ $rowText }};border-color:{{ $borderColor }};padding:0.6rem 1rem;">
                                                            {{ $cell }}
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @endif
                                </table>
                            </div>

                        {{-- ─── BUTTONS ───────────────────────────────────── --}}
                        @elseif($type === 'buttons')
                            @php
                                $alignment = $block['alignment'] ?? 'left';
                                $direction = $block['direction'] ?? 'horizontal';
                                $gap       = $block['gap']       ?? 'medium';

                                $gapClasses       = ['small'=>'gap-2','medium'=>'gap-3','large'=>'gap-4'];
                                $alignmentClasses = ['left'=>'justify-start','center'=>'justify-center','right'=>'justify-end','justify'=>'justify-between'];
                                $directionClasses = ['horizontal'=>'flex-row','vertical'=>'flex-col'];
                                $buttonSizeClasses= ['small'=>'px-4 py-2 text-sm','medium'=>'px-6 py-3 text-base','large'=>'px-8 py-4 text-lg'];
                            @endphp

                            @if(!empty($block['buttons']))
                                <div class="flex flex-wrap {{ $directionClasses[$direction] ?? 'flex-row' }} {{ $gapClasses[$gap] ?? 'gap-3' }} {{ $alignmentClasses[$alignment] ?? 'justify-start' }} mb-6">
                                    @foreach($block['buttons'] as $button)
                                        @php
                                            $bgColor      = $button['bg_color']         ?? '#2563eb';
                                            $textColor    = $button['text_color']       ?? '#ffffff';
                                            $hoverBg      = $button['hover_bg_color']   ?? $bgColor;
                                            $hoverText    = $button['hover_text_color'] ?? $textColor;
                                            $borderRadius = $button['border_radius']    ?? '8px';
                                            $size         = $button['size']             ?? 'medium';
                                            $url          = $button['url']              ?? '#';
                                            $target       = $button['target']           ?? '_self';
                                            $rel          = $button['rel']              ?? '';
                                            $icon         = $button['icon']             ?? null;
                                        @endphp
                                        <a href="{{ $url }}" target="{{ $target }}" rel="{{ $rel }}"
                                           class="inline-flex items-center font-semibold transition-all duration-300 hover:shadow-lg {{ $buttonSizeClasses[$size] ?? 'px-6 py-3 text-base' }}"
                                           style="background-color:{{ $bgColor }};color:{{ $textColor }};border-radius:{{ $borderRadius }};border:none;min-width:{{ $direction==='vertical'?'100%':'120px' }};{{ $direction==='vertical'?'width:100%;':'' }}{{ $alignment==='justify'?'flex:1;':'' }}"
                                           onmouseover="this.style.backgroundColor='{{ $hoverBg }}';this.style.color='{{ $hoverText }}';"
                                           onmouseout="this.style.backgroundColor='{{ $bgColor }}';this.style.color='{{ $textColor }}';">
                                            @if(!empty($icon))<i class="{{ $icon }} mr-2" style="color:{{ $textColor }};"></i>@endif
                                            {{ $button['text'] ?? 'Button' }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        @endif

                    @empty
                        @if($accommodation->full_description)
                            <div class="text-gray-700 leading-relaxed mb-4 text-base md:text-lg lg:text-xl space-y-3">
                                {!! nl2br(e($accommodation->full_description)) !!}
                            </div>
                        @endif
                    @endforelse

                    {{-- ═══ PHOTO GALLERY SLIDER ══════════════════════════ --}}
                    @if($allBlockImages->isNotEmpty())
                        @php $galleryCount = $allBlockImages->count(); @endphp

                        <div class="mt-8 pt-6 border-t-2 border-gray-200">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-4">
                                <i class="fas fa-images mr-2 text-green-500"></i>Photo Gallery
                            </h2>

                            {{--
                                FIX 1: Single x-data scope wraps BOTH the slider AND the thumbnails.
                                        The thumbnail strip no longer uses its own isolated x-data.
                                FIX 2: Slide container uses a tall, explicit height so images aren't clipped.
                            --}}
                            <div x-data="{
                                    current: 0,
                                    total: {{ $galleryCount }},
                                    autoPlay: true,
                                    timer: null,
                                    startAuto() {
                                        this.timer = setInterval(() => {
                                            if (this.autoPlay) this.next();
                                        }, 4000);
                                    },
                                    stopAuto()  { clearInterval(this.timer); this.autoPlay = false; },
                                    next()      { this.current = (this.current + 1) % this.total; },
                                    prev()      { this.current = this.current === 0 ? this.total - 1 : this.current - 1; },
                                    goTo(i)     { this.current = i; this.stopAuto(); }
                                 }"
                                 x-init="startAuto()">

                                {{-- SLIDER WRAPPER --}}
                                <div id="gallery-slider"
                                     class="relative overflow-hidden rounded-2xl shadow-xl bg-gray-900 select-none"
                                     @mouseenter="stopAuto()"
                                     @mouseleave="if(!autoPlay){ autoPlay=true; startAuto(); }">

                                    {{-- SLIDES
                                         FIX 2: height bumped — 420px mobile → 560px md → 680px lg
                                                 so portrait shots still fill without looking tiny.
                                    --}}
                                    <div class="relative" style="height: 420px;">
                                        <style>
                                            @media (min-width: 768px)  { #gallery-slider .gallery-slide-wrap { height: 560px !important; } }
                                            @media (min-width: 1024px) { #gallery-slider .gallery-slide-wrap { height: 680px !important; } }
                                        </style>
                                        <div class="gallery-slide-wrap relative w-full h-full">
                                            @foreach($allBlockImages as $index => $image)
                                                <div x-show="current === {{ $index }}"
                                                     x-transition:enter="transition ease-in-out duration-500"
                                                     x-transition:enter-start="opacity-0 scale-105"
                                                     x-transition:enter-end="opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in-out duration-300"
                                                     x-transition:leave-start="opacity-100 scale-100"
                                                     x-transition:leave-end="opacity-0 scale-95"
                                                     class="absolute inset-0 cursor-zoom-in"
                                                     onclick="openImageModal('{{ $image->url }}', '{{ addslashes($image->alt_text ?? $accommodation->name) }}')">
                                                    <img src="{{ $image->url }}"
                                                         alt="{{ $image->alt_text ?? $accommodation->name }}"
                                                         class="w-full h-full object-cover pointer-events-none">
                                                    <div class="absolute inset-0 bg-black/0 hover:bg-black/20 transition-all duration-300 flex items-center justify-center pointer-events-none">
                                                        <i class="fas fa-search-plus text-white text-5xl opacity-0 hover:opacity-100 transition-all duration-300 drop-shadow-2xl"></i>
                                                    </div>
                                                    @if($image->alt_text)
                                                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent text-white px-5 py-4 pointer-events-none">
                                                            <p class="text-sm md:text-base font-medium">{{ $image->alt_text }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- PREV --}}
                                    <button @click="prev(); stopAuto();"
                                            class="absolute left-3 md:left-5 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/80 text-white rounded-full w-11 h-11 md:w-14 md:h-14 flex items-center justify-center text-2xl md:text-3xl transition-all duration-200 z-20 shadow-xl hover:scale-110"
                                            aria-label="Previous image">&#8249;</button>

                                    {{-- NEXT --}}
                                    <button @click="next(); stopAuto();"
                                            class="absolute right-3 md:right-5 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/80 text-white rounded-full w-11 h-11 md:w-14 md:h-14 flex items-center justify-center text-2xl md:text-3xl transition-all duration-200 z-20 shadow-xl hover:scale-110"
                                            aria-label="Next image">&#8250;</button>

                                    {{-- COUNTER --}}
                                    <div class="absolute top-3 right-4 bg-black/60 text-white text-xs md:text-sm font-semibold px-3 py-1.5 rounded-full z-20 backdrop-blur-sm">
                                        <span x-text="current + 1"></span>&thinsp;/&thinsp;{{ $galleryCount }}
                                    </div>

                                    {{-- AUTOPLAY INDICATOR --}}
                                    <div class="absolute top-3 left-4 z-20">
                                        <button @click="autoPlay ? stopAuto() : (autoPlay=true, startAuto())"
                                                class="bg-black/50 hover:bg-black/70 text-white text-xs px-3 py-1.5 rounded-full backdrop-blur-sm transition flex items-center gap-1.5">
                                            <i :class="autoPlay ? 'fas fa-pause' : 'fas fa-play'" class="text-xs"></i>
                                            <span x-text="autoPlay ? 'Auto' : 'Paused'"></span>
                                        </button>
                                    </div>

                                    {{-- DOT INDICATORS --}}
                                    @if($galleryCount > 1)
                                        <div class="absolute bottom-5 left-0 right-0 flex justify-center gap-2 z-20">
                                            @foreach($allBlockImages as $index => $image)
                                                <button @click="goTo({{ $index }})"
                                                        :class="current === {{ $index }}
                                                            ? 'bg-white w-7 md:w-9'
                                                            : 'bg-white/50 w-3 md:w-3.5 hover:bg-white/80'"
                                                        class="h-3 md:h-3.5 rounded-full border border-white/40 transition-all duration-300 shadow"
                                                        aria-label="Go to image {{ $index + 1 }}">
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                {{-- END SLIDER WRAPPER --}}

                                {{-- THUMBNAIL STRIP
                                     FIX 1: No separate x-data — calls goTo() on the parent scope directly.
                                     FIX 3: Active thumb gets a green ring via :class binding.
                                --}}
                                @if($galleryCount > 1)
                                    <div class="flex gap-2 mt-3 overflow-x-auto pb-2 snap-x snap-mandatory" id="gallery-thumbs">
                                        @foreach($allBlockImages as $index => $image)
                                            <button @click="goTo({{ $index }})"
                                                    :class="current === {{ $index }} ? 'ring-green-500 opacity-100' : 'ring-transparent opacity-60 hover:opacity-90'"
                                                    class="flex-shrink-0 w-20 h-16 md:w-28 md:h-20 rounded-lg overflow-hidden transition-all duration-200 snap-start ring-2">
                                                <img src="{{ $image->url }}"
                                                     alt="{{ $image->alt_text ?? $accommodation->name }}"
                                                     class="w-full h-full object-cover pointer-events-none">
                                            </button>
                                        @endforeach
                                    </div>
                                @endif

                            </div>
                            {{-- END x-data wrapper --}}
                        </div>
                    @endif
                    {{-- ═══ END GALLERY ════════════════════════════════════ --}}

                    {{-- AMENITIES --}}
                    @if(is_array($accommodation->amenities) && count($accommodation->amenities))
                        <div class="mt-8">
                            <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-3">Amenities</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach($accommodation->amenities as $amenity)
                                    <div class="flex items-center space-x-2 text-sm text-gray-700">
                                        <i class="fas fa-check-circle text-green-500"></i>
                                        <span>{{ $amenity }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- PRICE SUMMARY --}}
                    @if($accommodation->price_from || $accommodation->price_to)
                        <div class="mt-8 p-5 bg-green-50 rounded-2xl border border-green-200">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Price Guide</p>
                                    <p class="text-2xl font-bold text-green-700">
                                        {{ $accommodation->currency ?? 'USD' }}
                                        {{ number_format($accommodation->price_from ?? 0) }}
                                        @if($accommodation->price_to)
                                            – {{ number_format($accommodation->price_to) }}
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">Per person per night (indicative). Exact price may vary by season and availability.</p>
                                </div>
                                <a href="{{ route('contact') }}"
                                   class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-2xl">
                                    <i class="fas fa-envelope mr-2"></i> Enquire Now
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- REQUEST QUOTE --}}
                    <div class="mt-8 pt-6 border-t-2 border-gray-200">
                        <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl p-6 md:p-8 text-center">
                            <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Ready to Book Your Stay?</h3>
                            <p class="text-gray-600 text-base md:text-lg mb-4 max-w-2xl mx-auto">Contact us today and let our expert team help you plan the perfect safari experience.</p>
                            <a href="{{ route('contact') }}"
                               class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-6 py-3 md:px-8 md:py-4 rounded-xl font-bold text-base md:text-lg transition-all duration-300 shadow-lg hover:shadow-2xl">
                                Request a Quote
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

{{-- ═══ IMAGE ZOOM MODAL ══════════════════════════════════════════════════ --}}
<div id="imageModal"
     class="fixed inset-0 z-[999] hidden items-center justify-center bg-black/95"
     onclick="closeImageModal()">
    <div class="relative max-w-6xl w-full max-h-[95vh] mx-4 flex flex-col items-center"
         onclick="event.stopPropagation()">
        <button onclick="closeImageModal()"
                class="absolute -top-12 right-0 text-white text-4xl hover:text-gray-300 transition leading-none"
                aria-label="Close">
            &times;
        </button>
        <img id="modalImage" src="" alt=""
             class="max-w-full max-h-[82vh] object-contain rounded-xl shadow-2xl">
        <p id="modalCaption" class="text-white/80 text-center mt-4 text-sm md:text-base px-4"></p>
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
    /* ── IMAGE MODAL ──────────────────────────────────────────────────── */
    function openImageModal(url, caption) {
        document.getElementById('modalImage').src = url;
        document.getElementById('modalCaption').textContent = caption || '';
        const modal = document.getElementById('imageModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        document.getElementById('modalImage').src = '';
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeImageModal();
    });

    /* ── TOUCH SWIPE FOR GALLERY ──────────────────────────────────────── */
    document.addEventListener('DOMContentLoaded', function () {
        const slider = document.getElementById('gallery-slider');
        if (!slider) return;

        let startX = 0;

        slider.addEventListener('touchstart', function (e) {
            startX = e.touches[0].clientX;
        }, { passive: true });

        slider.addEventListener('touchend', function (e) {
            const diff = startX - e.changedTouches[0].clientX;
            if (Math.abs(diff) < 50) return;

            if (diff > 0) {
                slider.querySelector('button[aria-label="Next image"]')?.click();
            } else {
                slider.querySelector('button[aria-label="Previous image"]')?.click();
            }
        }, { passive: true });

        /* Mouse drag */
        let mouseStartX = 0;
        let isDragging  = false;

        slider.addEventListener('mousedown', function (e) {
            mouseStartX = e.clientX;
            isDragging  = true;
        });

        slider.addEventListener('mouseup', function (e) {
            if (!isDragging) return;
            isDragging = false;
            const diff = mouseStartX - e.clientX;
            if (Math.abs(diff) < 60) return;

            if (diff > 0) {
                slider.querySelector('button[aria-label="Next image"]')?.click();
            } else {
                slider.querySelector('button[aria-label="Previous image"]')?.click();
            }
        });

        slider.addEventListener('mouseleave', () => { isDragging = false; });
    });
</script>

@endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

    * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }

    html { scroll-behavior: smooth; }

    /* Hero parallax — disable on mobile to avoid iOS glitch */
    @media (min-width: 768px) {
        .bg-fixed { background-attachment: fixed; }
    }
    @media (max-width: 767px) {
        .bg-fixed { background-attachment: scroll; }
    }

    .rounded-t-3xl {
        border-top-left-radius: 1.5rem;
        border-top-right-radius: 1.5rem;
    }

    /* Gallery slider cursor & select */
    #gallery-slider { cursor: grab; }
    #gallery-slider:active { cursor: grabbing; }

    /* Responsive slide height */
    #gallery-slider .gallery-slide-wrap {
        height: 420px;
    }
    @media (min-width: 768px) {
        #gallery-slider .gallery-slide-wrap {
            height: 560px;
        }
    }
    @media (min-width: 1024px) {
        #gallery-slider .gallery-slide-wrap {
            height: 680px;
        }
    }

    /* Thumbnail scrollbar */
    #gallery-thumbs {
        scrollbar-width: thin;
        scrollbar-color: #d1d5db transparent;
    }
    #gallery-thumbs::-webkit-scrollbar { height: 4px; }
    #gallery-thumbs::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 4px;
    }

    /* Modal blur */
    #imageModal { backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); }

    /* Prose */
    .prose { max-width: 100%; }
    .prose p { margin-bottom: 1.25rem; line-height: 1.8; font-size: 1.125rem; }
    .prose strong { color: #1e293b; font-weight: 600; }
    .prose em { color: #475569; font-style: italic; }
    .prose ul, .prose ol { margin: 1rem 0; padding-left: 1.5rem; }
    .prose li { margin-bottom: 0.5rem; line-height: 1.8; }
    .prose blockquote {
        border-left: 4px solid #059669;
        padding: 1rem 1.5rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #475569;
        background: #f0fdf4;
        border-radius: 0.5rem;
    }
    .prose blockquote p { margin: 0; }
    .prose a, .text-gray-700 a {
        color: #2563eb !important;
        text-decoration: none;
        font-weight: 500;
        border-bottom: 2px solid transparent;
        display: inline !important;
        transition: all 0.3s ease;
    }
    .prose a:hover, .text-gray-700 a:hover {
        color: #1d4ed8 !important;
        border-bottom-color: #2563eb;
    }

    /* Tables */
    .striped tbody tr:nth-child(even) { background-color: #f8fafc; }
    .bordered th, .bordered td { border: 1px solid #e2e8f0; }
    .hoverable tbody tr:hover { background-color: #f1f5f9 !important; }
    .small th, .small td { padding: 0.5rem 0.75rem !important; font-size: 0.75rem !important; }

    /* Scroll bounce for hero */
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(10px); }
    }
    .animate-bounce { animation: bounce 2s infinite; }

    @media (max-width: 640px) {
        #imageModal .relative { max-width: 100%; }
    }

    @media (prefers-reduced-motion: reduce) {
        *, .animate-bounce {
            animation-duration: 0.01ms !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>
@endpush