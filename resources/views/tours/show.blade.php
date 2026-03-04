@extends('layouts.app')

@section('title', $tour->meta_title ?? $tour->title . ' - Safari Tours')
@section('meta_description', $tour->meta_description ?? Str::limit($tour->description, 160))
@section('meta_keywords', $tour->meta_keywords ?? 'safari tour, ' . $tour->category . ', ' . $tour->type . ', ' . $tour->destinations)

@section('page-header')
<!-- ══════════════════════════════════════════════════════
     HERO SECTION — full-bleed with overlay text
══════════════════════════════════════════════════════ -->
<header class="relative h-64 sm:h-80 md:h-96 lg:h-[520px] overflow-hidden">
    @if($tour->featured_image)
        <img src="{{ asset('storage/' . $tour->featured_image) }}"
             alt="{{ $tour->title }}"
             class="w-full h-full object-cover">
    @else
        <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-500"></div>
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>

    <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-6 lg:p-10">
        <div class="max-w-7xl mx-auto">
            <!-- Badges -->
            <div class="flex flex-wrap gap-2 mb-3">
                <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs sm:text-sm font-semibold">
                    {{ $tour->category }}
                </span>
                @if($tour->type)
                    <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs sm:text-sm font-semibold">
                        {{ $tour->type }}
                    </span>
                @endif
                <span class="bg-white/20 backdrop-blur text-white px-3 py-1 rounded-full text-xs sm:text-sm font-semibold">
                    {{ $tour->itineraries->count() ?: 'Multi' }} {{ $tour->itineraries->count() == 1 ? 'Day' : 'Days' }}
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-5xl font-bold text-white mb-3 leading-tight">{{ $tour->title }}</h1>
            <div class="flex items-center text-white/90 text-sm sm:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>{{ $tour->destinations }}</span>
            </div>
        </div>
    </div>
</header>
@endsection

@section('content')

<!-- ── Breadcrumb ── -->
<nav class="bg-white border-b py-3 overflow-x-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-1 text-xs sm:text-sm whitespace-nowrap">
            <a href="{{ route('index') }}" class="text-gray-500 hover:text-green-600">Home</a>
            <svg class="w-3 h-3 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('tours.index') }}" class="text-gray-500 hover:text-green-600">Tours</a>
            <svg class="w-3 h-3 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-900 font-medium truncate max-w-[150px] sm:max-w-none">{{ $tour->title }}</span>
        </div>
    </div>
</nav>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">

        <!-- ══════════════════════════════
             MAIN CONTENT (left 2/3)
        ══════════════════════════════ -->
        <div class="lg:col-span-2 space-y-10 lg:space-y-14">

            <!-- ── Overview ── -->
            <section>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4">Tour Overview</h2>
                <div class="prose prose-sm sm:prose max-w-none text-gray-700 leading-relaxed">
                    {!! nl2br(e($tour->description)) !!}
                </div>
            </section>

            <!-- ══════════════════════════════
                 ITINERARY ACCORDION
            ══════════════════════════════ -->
            @if($tour->itineraries->count() > 0)
            <section>
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Day by Day Itinerary</h2>
                    <div class="flex gap-3 text-xs sm:text-sm">
                        <button id="expandAll"   class="text-green-600 hover:underline font-medium">Expand all</button>
                        <button id="collapseAll" class="text-gray-500  hover:underline">Collapse all</button>
                    </div>
                </div>

                <div id="itineraryAccordion" class="space-y-4" aria-live="polite">
                    @foreach($tour->itineraries->sortBy('day_number') as $day)
                    @php
                        $panelId   = 'day-panel-' . ($day->id ?? $loop->index);
                        $buttonId  = 'day-btn-'   . ($day->id ?? $loop->index);
                        $acc       = $day->accommodationRecord ?? null;
                        $accImages = $acc?->images ?? collect();
                        $dayImages = $day->images ?? collect();
                    @endphp

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                        <!-- Accordion header -->
                        <button id="{{ $buttonId }}"
                                class="accordion-toggle w-full bg-gradient-to-r from-gray-600 to-gray-700 px-4 sm:px-6 py-4 text-left hover:from-green-700 hover:to-green-800 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                aria-expanded="false"
                                aria-controls="{{ $panelId }}"
                                type="button">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center flex-1 min-w-0 gap-3">
                                    <div class="bg-white/20 rounded-full w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-bold text-base sm:text-lg">{{ $day->day_number }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base sm:text-xl font-bold text-white leading-snug">
                                            Day {{ $day->day_number }}@if($day->day_title): {{ $day->day_title }}@endif
                                        </h3>
                                        @if($day->activity)
                                            <p class="text-gray-200 text-xs sm:text-sm mt-0.5 line-clamp-2">{{ Str::limit(strip_tags($day->activity), 90) }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white/10 flex items-center justify-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white transform transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </button>

                        <!-- Accordion panel -->
                        <div id="{{ $panelId }}" role="region" aria-labelledby="{{ $buttonId }}"
                             class="hidden bg-gray-50" data-day-number="{{ $day->day_number }}">
                            <div class="p-4 sm:p-6 space-y-6">

                                <!-- Activity text -->
                                <div class="prose prose-sm max-w-none text-gray-700">
                                    {!! nl2br(e($day->activity)) !!}
                                </div>

                                <!-- ── Day image SLIDER ── -->
                                @if($dayImages->count() > 0)
                                @php $sliderId = 'day-slider-' . ($day->id ?? $loop->index); @endphp
                                <div class="relative" id="{{ $sliderId }}-wrap">
                                    <!-- Track -->
                                    <div class="overflow-hidden rounded-xl">
                                        <div class="slider-track flex transition-transform duration-500 ease-in-out"
                                             id="{{ $sliderId }}-track"
                                             data-current="0"
                                             data-total="{{ $dayImages->count() }}">
                                            @foreach($dayImages as $img)
                                            @php $src = $img->thumbnail_path ?: $img->storage_path ?: $img->image_path ?? null; @endphp
                                            <div class="slider-slide flex-none w-full aspect-video sm:aspect-[16/7] overflow-hidden">
                                                <img data-src="{{ $src ? asset('storage/' . ltrim($src, '/')) : '' }}"
                                                     alt="{{ $img->caption ?? 'Day ' . $day->day_number . ' image' }}"
                                                     class="w-full h-full object-cover lazy-day-image">
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    @if($dayImages->count() > 1)
                                    <!-- Prev / Next -->
                                    <button onclick="sliderMove('{{ $sliderId }}', -1)"
                                            class="slider-btn absolute left-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center transition z-10">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    </button>
                                    <button onclick="sliderMove('{{ $sliderId }}', 1)"
                                            class="slider-btn absolute right-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center transition z-10">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                    <!-- Dots -->
                                    <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1.5 z-10" id="{{ $sliderId }}-dots">
                                        @foreach($dayImages as $img)
                                        <button onclick="sliderGoto('{{ $sliderId }}', {{ $loop->index }})"
                                                class="slider-dot w-2 h-2 rounded-full bg-white/60 hover:bg-white transition {{ $loop->first ? 'bg-white scale-125' : '' }}"
                                                aria-label="Go to image {{ $loop->iteration }}"></button>
                                        @endforeach
                                    </div>
                                    <!-- Counter -->
                                    <div class="absolute top-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded-full z-10" id="{{ $sliderId }}-counter">
                                        1 / {{ $dayImages->count() }}
                                    </div>
                                    @endif
                                </div>
                                @endif

                                <!-- Meals & Accommodation -->
                                @if($day->accommodation || $day->meals || $acc)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-4 border-t border-gray-200">
                                    @if($acc || $day->accommodation)
                                    <div class="flex items-start text-sm text-gray-600 bg-white rounded-lg p-3">
                                        <svg class="w-4 h-4 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2z"/>
                                        </svg>
                                        <div>
                                            <span class="font-semibold text-gray-800">Accommodation:</span>
                                            <span class="ml-1">{{ $acc?->name ?? $day->accommodation }}</span>
                                            @if($acc?->type) <span class="ml-1 text-xs text-gray-400">({{ $acc->type }})</span> @endif
                                            @if($acc?->location)
                                                <div class="text-xs text-gray-400 mt-0.5 flex items-center gap-0.5">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    {{ $acc->location }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                    @if($day->meals)
                                    <div class="flex items-center text-sm text-gray-600 bg-white rounded-lg p-3">
                                        <svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                                        </svg>
                                        <span><strong>Meals:</strong> {{ $day->meals }}</span>
                                    </div>
                                    @endif
                                </div>
                                @endif

                                <!-- ── Accommodation image SLIDER ── -->
                                @if($accImages->count() > 0)
                                @php $accSliderId = 'acc-slider-' . ($day->id ?? $loop->index); @endphp
                                <div>
                                    <div class="flex items-center gap-2 mb-3">
                                        <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <h4 class="text-sm font-semibold text-gray-700">
                                            {{ $acc->name }} — Photos
                                            <span class="text-xs font-normal text-gray-400 ml-1">({{ $accImages->count() }})</span>
                                        </h4>
                                    </div>

                                    <div class="relative" id="{{ $accSliderId }}-wrap">
                                        <div class="overflow-hidden rounded-xl">
                                            <div class="slider-track flex transition-transform duration-500 ease-in-out"
                                                 id="{{ $accSliderId }}-track"
                                                 data-current="0"
                                                 data-total="{{ $accImages->count() }}">
                                                @foreach($accImages as $accImg)
                                                @php
                                                    $accSrc = $accImg->url
                                                        ?? ($accImg->thumbnail_path ? asset('storage/' . ltrim($accImg->thumbnail_path, '/')) : null)
                                                        ?? ($accImg->image_path     ? asset('storage/' . ltrim($accImg->image_path, '/'))     : null)
                                                        ?? ($accImg->storage_path   ? asset('storage/' . ltrim($accImg->storage_path, '/'))   : null);
                                                @endphp
                                                <div class="slider-slide flex-none w-full aspect-video overflow-hidden">
                                                    <img data-src="{{ $accSrc }}"
                                                         alt="{{ $accImg->caption ?? ($acc->name ?? 'Accommodation') }}"
                                                         class="w-full h-full object-cover lazy-day-image">
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        @if($accImages->count() > 1)
                                        <button onclick="sliderMove('{{ $accSliderId }}', -1)"
                                                class="slider-btn absolute left-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center transition z-10">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                        </button>
                                        <button onclick="sliderMove('{{ $accSliderId }}', 1)"
                                                class="slider-btn absolute right-2 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center transition z-10">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </button>
                                        <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1.5 z-10" id="{{ $accSliderId }}-dots">
                                            @foreach($accImages as $accImg)
                                            <button onclick="sliderGoto('{{ $accSliderId }}', {{ $loop->index }})"
                                                    class="slider-dot w-2 h-2 rounded-full bg-white/60 hover:bg-white transition {{ $loop->first ? 'bg-white scale-125' : '' }}"
                                                    aria-label="Go to photo {{ $loop->iteration }}"></button>
                                            @endforeach
                                        </div>
                                        <div class="absolute top-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded-full z-10" id="{{ $accSliderId }}-counter">
                                            1 / {{ $accImages->count() }}
                                        </div>
                                        @endif
                                        @if($accImages->first()?->caption)
                                        <p class="text-xs text-gray-400 mt-2 italic" id="{{ $accSliderId }}-caption">{{ $accImages->first()->caption }}</p>
                                        @endif
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            <!-- ── Included / Excluded ── -->
            <section>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @if($tour->included)
                    <div class="bg-green-50 border border-green-100 rounded-xl p-5">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            What's Included
                        </h3>
                        <ul class="space-y-2">
                            @foreach(explode("\n", $tour->included) as $item)
                                @if(trim($item))
                                <li class="flex items-start text-sm sm:text-base">
                                    <svg class="w-4 h-4 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-gray-700">{{ trim($item) }}</span>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($tour->excluded)
                    <div class="bg-red-50 border border-red-100 rounded-xl p-5">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            What's Excluded
                        </h3>
                        <ul class="space-y-2">
                            @foreach(explode("\n", $tour->excluded) as $item)
                                @if(trim($item))
                                <li class="flex items-start text-sm sm:text-base">
                                    <svg class="w-4 h-4 text-red-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span class="text-gray-700">{{ trim($item) }}</span>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </section>

            <!-- ── Tour Photo Gallery SLIDER ── -->
            @if($tour->images && $tour->images->count() > 0)
            <section>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-5">Photo Gallery</h2>

                <!-- Main full slider -->
                <div class="relative" id="tour-gallery-wrap">
                    <div class="overflow-hidden rounded-xl shadow-lg">
                        <div class="slider-track flex transition-transform duration-500 ease-in-out"
                             id="tour-gallery-track"
                             data-current="0"
                             data-total="{{ $tour->images->count() }}">
                            @foreach($tour->images as $image)
                            <div class="slider-slide flex-none w-full aspect-video sm:aspect-[16/7]">
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                     alt="Gallery image {{ $loop->iteration }}"
                                     class="w-full h-full object-cover cursor-pointer"
                                     onclick="openGallery({{ $loop->index }})">
                            </div>
                            @endforeach
                        </div>
                    </div>

                    @if($tour->images->count() > 1)
                    <button onclick="sliderMove('tour-gallery', -1)"
                            class="slider-btn absolute left-2 sm:left-3 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white rounded-full w-9 h-9 sm:w-11 sm:h-11 flex items-center justify-center transition z-10">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button onclick="sliderMove('tour-gallery', 1)"
                            class="slider-btn absolute right-2 sm:right-3 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white rounded-full w-9 h-9 sm:w-11 sm:h-11 flex items-center justify-center transition z-10">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-2 z-10" id="tour-gallery-dots">
                        @foreach($tour->images as $image)
                        <button onclick="sliderGoto('tour-gallery', {{ $loop->index }})"
                                class="slider-dot w-2.5 h-2.5 rounded-full bg-white/50 hover:bg-white transition {{ $loop->first ? 'bg-white scale-125' : '' }}"
                                aria-label="Go to image {{ $loop->iteration }}"></button>
                        @endforeach
                    </div>
                    <div class="absolute top-3 right-3 bg-black/50 text-white text-xs px-2.5 py-1 rounded-full z-10" id="tour-gallery-counter">
                        1 / {{ $tour->images->count() }}
                    </div>
                    @endif
                </div>

                <!-- Thumbnail strip -->
                @if($tour->images->count() > 1)
                <div class="flex gap-2 mt-3 overflow-x-auto pb-1 snap-x" id="tour-gallery-thumbs">
                    @foreach($tour->images as $image)
                    <button onclick="sliderGoto('tour-gallery', {{ $loop->index }})"
                            class="gallery-thumb flex-none w-16 h-12 sm:w-20 sm:h-14 rounded-lg overflow-hidden snap-start ring-2 ring-transparent hover:ring-green-400 transition-all duration-200 {{ $loop->first ? 'ring-green-500' : '' }}"
                            data-index="{{ $loop->index }}"
                            aria-label="Thumbnail {{ $loop->iteration }}">
                        <img src="{{ asset('storage/' . $image->image_path) }}"
                             alt="Thumbnail {{ $loop->iteration }}"
                             class="w-full h-full object-cover">
                    </button>
                    @endforeach
                </div>
                @endif
            </section>
            @endif

        </div>{{-- end main col --}}

        <!-- ══════════════════════════════
             SIDEBAR (right 1/3)
        ══════════════════════════════ -->
        <div class="lg:col-span-1">
            <div class="lg:sticky lg:top-24 space-y-6">

                <!-- Mobile CTA (visible only on small screens, above sidebar) -->
                <div class="block lg:hidden">
                    <button onclick="scrollToBooking()"
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-xl font-semibold text-base transition-colors duration-300 shadow-md">
                        Book This Tour
                    </button>
                </div>

                <!-- Pricing Card -->
                @if($tour->prices && $tour->prices->count() > 0)
                <div class="bg-white rounded-xl shadow-lg p-5 sm:p-6 border border-gray-200">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-5">Tour Pricing</h3>
                    <div class="space-y-3">
                        @foreach($tour->prices->sortBy('group_size') as $price)
                        <div class="flex justify-between items-center p-3 sm:p-4 bg-gray-50 rounded-lg hover:bg-green-50 transition-colors cursor-pointer price-option"
                             data-group-size="{{ $price->group_size }}"
                             data-price="{{ $price->price }}">
                            <div class="font-semibold text-gray-900 text-sm sm:text-base">{{ $price->group_size }}</div>
                            <div class="text-right">
                                <div class="text-lg sm:text-2xl font-bold text-gray-700">${{ number_format($price->price) }}</div>
                                <div class="text-xs text-gray-500">per person</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-5 pt-5 border-t border-gray-200">
                        <button onclick="scrollToBooking()"
                                class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg font-semibold transition-colors duration-300 text-sm sm:text-base">
                            Book This Tour
                        </button>
                    </div>
                </div>
                @endif

                <!-- Quick Info -->
                <div class="bg-white rounded-xl shadow-lg p-5 sm:p-6 border border-gray-200">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-5">Quick Info</h3>
                    <div class="space-y-3 text-sm sm:text-base">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-gray-700">{{ $tour->itineraries->count() ?: 'Multi' }} Days Tour</span>
                        </div>
                        @if($tour->type)
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span class="text-gray-700">{{ $tour->type }}</span>
                        </div>
                        @endif
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-gray-700">{{ $tour->destinations }}</span>
                        </div>
                        @if($tour->category)
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span class="text-gray-700">{{ $tour->category }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Contact Card -->
                <div class="bg-gradient-to-br from-green-600 to-blue-600 rounded-xl shadow-lg p-5 sm:p-6 text-white">
                    <h3 class="text-lg sm:text-xl font-bold mb-3">Need Help?</h3>
                    <p class="text-green-100 mb-5 text-sm sm:text-base">Our travel experts are here to help you plan the perfect safari.</p>
                    <div class="space-y-3 text-sm sm:text-base">
                        <a href="tel:+256752088768" class="flex items-center gap-3 text-white hover:text-green-200 transition-colors">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            +256752088768
                        </a>
                        <a href="mailto:calmafricassafaris@gmail.com" class="flex items-center gap-3 text-white hover:text-green-200 transition-colors">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            calmafricasafaris@gmail.com
                        </a>
                        <a href="https://wa.me/256752088768" class="flex items-center gap-3 text-white hover:text-green-200 transition-colors">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                            </svg>
                            WhatsApp Chat
                        </a>
                    </div>
                </div>

            </div>
        </div>{{-- end sidebar --}}

    </div>
</div>

<!-- ══════════════════════════════════════════════════════
     RELATED TOURS
══════════════════════════════════════════════════════ -->
@if($relatedTours && $relatedTours->count() > 0)
<section class="bg-gray-50 py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 sm:mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">You Might Also Like</h2>
            <p class="text-base sm:text-lg text-gray-600">Discover more amazing safari experiences similar to {{ $tour->title }}</p>
        </div>

        <!-- Related tours SLIDER on mobile, grid on md+ -->
        <div class="relative" id="related-slider-wrap">
            <!-- Mobile: horizontal scroll slider -->
            <div class="flex gap-5 overflow-x-auto pb-4 snap-x snap-mandatory -mx-4 px-4 sm:mx-0 sm:px-0 sm:grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 sm:overflow-visible sm:gap-6 sm:snap-none"
                 id="related-track">
                @foreach($relatedTours as $relatedTour)
                <div class="flex-none w-72 sm:w-auto snap-start bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                    <div class="relative h-44 sm:h-48 overflow-hidden">
                        @if($relatedTour->featured_image)
                            <img src="{{ asset('storage/' . $relatedTour->featured_image) }}"
                                 alt="{{ $relatedTour->title }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                                <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="absolute top-3 left-3">
                            <span class="bg-green-600 text-white px-2 py-1 rounded-full text-xs font-semibold">{{ $relatedTour->category ?? 'Safari' }}</span>
                        </div>
                        <div class="absolute top-3 right-3">
                            <span class="bg-white/90 backdrop-blur text-gray-800 px-2 py-1 rounded-full text-xs font-semibold">
                                {{ $relatedTour->itineraries->count() ?: 'Multi' }} {{ $relatedTour->itineraries->count() == 1 ? 'Day' : 'Days' }}
                            </span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-2 line-clamp-2">{{ $relatedTour->title }}</h3>
                        <p class="text-gray-600 mb-3 text-xs sm:text-sm line-clamp-2">{{ Str::limit($relatedTour->description, 100) }}</p>
                        <div class="flex items-center mb-3 text-xs sm:text-sm text-gray-500">
                            <svg class="w-4 h-4 text-green-600 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="line-clamp-1">{{ $relatedTour->destinations ?: 'East Africa' }}</span>
                        </div>
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                @if($relatedTour->prices && $relatedTour->prices->count() > 0)
                                    @php $minPrice = $relatedTour->prices->min('price'); @endphp
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-lg sm:text-xl font-bold text-green-600">${{ number_format($minPrice) }}</span>
                                        <span class="text-gray-500 text-xs">/ person</span>
                                    </div>
                                    <p class="text-xs text-gray-400">Starting from</p>
                                @else
                                    <span class="text-green-600 font-semibold text-sm">Contact for Pricing</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-1">
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-xs text-gray-600">4.9</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('tours.show', $relatedTour->slug) }}"
                               class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 px-3 rounded-lg text-xs sm:text-sm font-semibold transition-colors duration-300">
                                View Details
                            </a>
                            <button onclick="quickBook('{{ $relatedTour->slug }}')"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg transition-colors duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="text-center mt-8 sm:mt-12">
            <a href="{{ route('tours.index') }}"
               class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 sm:px-8 py-3 rounded-lg font-semibold transition-colors duration-300 text-sm sm:text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                View All Tours
            </a>
        </div>
    </div>
</section>
@endif

<!-- ══════════════════════════════════════════════════════
     BOOKING SECTION
══════════════════════════════════════════════════════ -->
<section id="booking" class="bg-white py-12 lg:py-16 border-t border-gray-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 sm:mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">Book Your Adventure</h2>
            <p class="text-base sm:text-xl text-gray-600">Ready to experience {{ $tour->title }}? Fill out the form below and we'll get back to you within 24 hours.</p>
        </div>
        <div class="bg-gray-50 rounded-2xl p-5 sm:p-8">
            <form id="bookingForm" class="space-y-5 sm:space-y-6">
                @csrf
                <input type="hidden" name="tour_id" value="{{ $tour->id }}">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition text-sm sm:text-base"
                               placeholder="Enter your full name">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition text-sm sm:text-base"
                               placeholder="Enter your email">
                    </div>
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country <span class="text-red-500">*</span></label>
                        <input type="text" id="country" name="country" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition text-sm sm:text-base"
                               placeholder="Where are you from?">
                    </div>
                    <div>
                        <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-2">WhatsApp Number <span class="text-red-500">*</span></label>
                        <input type="tel" id="whatsapp" name="whatsapp" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition text-sm sm:text-base"
                               placeholder="e.g., +1 234 567 8900">
                    </div>
                </div>

                @if($tour->prices && $tour->prices->count() > 0)
                <div>
                    <label for="group_size" class="block text-sm font-medium text-gray-700 mb-2">Group Size <span class="text-red-500">*</span></label>
                    <select id="group_size" name="group_size" required onchange="calculateTotal()"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition text-sm sm:text-base">
                        <option value="">Select group size</option>
                        @foreach($tour->prices->sortBy('group_size') as $price)
                            <option value="{{ $price->group_size }}" data-price="{{ $price->price }}">
                                {{ $price->group_size }} — ${{ number_format($price->price) }} per person
                            </option>
                        @endforeach
                    </select>
                </div>
                <div id="totalCost" class="hidden bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex justify-between items-center">
                        <span class="text-base sm:text-lg font-semibold text-gray-900">Total Cost:</span>
                        <span id="totalAmount" class="text-xl sm:text-2xl font-bold text-green-600"></span>
                    </div>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1">*Final price may vary based on specific requirements</p>
                </div>
                @endif

                <div>
                    <label for="travel_date" class="block text-sm font-medium text-gray-700 mb-2">Preferred Travel Date <span class="text-red-500">*</span></label>
                    <input type="date" id="travel_date" name="travel_date" required
                           min="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition text-sm sm:text-base">
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Additional Requirements <span class="text-gray-400">(Optional)</span></label>
                    <textarea id="message" name="message" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition resize-y text-sm sm:text-base"
                              placeholder="Any special requirements or questions?"></textarea>
                </div>

                <div class="text-center">
                    <button type="submit"
                            class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg font-semibold text-base sm:text-lg transition-colors duration-300 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Send Booking Request
                    </button>
                    <p class="text-xs sm:text-sm text-gray-500 mt-3">We'll respond within 24 hours with detailed information.</p>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════════════════
     LIGHTBOXES
══════════════════════════════════════════════════════ -->
<!-- Tour gallery lightbox -->
@if($tour->images && $tour->images->count() > 0)
<div id="galleryModal" class="fixed inset-0 bg-black/95 z-[100] hidden items-center justify-center" role="dialog" aria-modal="true" aria-label="Image lightbox">
    <button onclick="closeGallery()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10 bg-black/30 rounded-full p-2">
        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
    <button onclick="galleryLightboxPrev()" class="absolute left-3 top-1/2 -translate-y-1/2 text-white bg-black/40 hover:bg-black/70 rounded-full p-2 z-10">
        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button onclick="galleryLightboxNext()" class="absolute right-3 top-1/2 -translate-y-1/2 text-white bg-black/40 hover:bg-black/70 rounded-full p-2 z-10">
        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>
    <div class="relative max-w-5xl w-full px-4">
        <img id="galleryImage" src="" alt="Gallery image" class="max-w-full max-h-[85vh] object-contain mx-auto rounded-lg shadow-2xl">
        <p id="galleryCounter" class="text-white/60 text-xs sm:text-sm text-center mt-3"></p>
    </div>
</div>
@endif

@push('styles')
<style>
    /* ── Utility ── */
    .line-clamp-1 { display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden; }
    .line-clamp-2 { display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden; }
    html { scroll-behavior: smooth; }

    /* ── Accordion chevron rotation ── */
    .accordion-toggle[aria-expanded="true"] svg { transform: rotate(180deg); }

    /* ── Slider ── */
    .slider-track { will-change: transform; }
    .slider-slide { min-width: 100%; }

    /* ── Touch swipe support ── */
    .overflow-hidden { -webkit-overflow-scrolling: touch; }

    /* ── Thumbnail active state ── */
    .gallery-thumb.active { ring-color: #16a34a; ring-width: 2px; }

    /* ── Thin scrollbar for related tours on mobile ── */
    #related-track { scrollbar-width: none; }
    #related-track::-webkit-scrollbar { display: none; }
</style>
@endpush

@push('scripts')
<script>
/* ══════════════════════════════════════════════════════════════
   UNIVERSAL SLIDER ENGINE
   sliderMove(id, dir)  — move by dir (+1 / -1)
   sliderGoto(id, idx)  — jump to index
══════════════════════════════════════════════════════════════ */
function _sliderUpdate(id, newIdx) {
    const track = document.getElementById(id + '-track');
    if (!track) return;
    const total = parseInt(track.dataset.total) || 0;
    if (total === 0) return;
    newIdx = ((newIdx % total) + total) % total; // wrap
    track.dataset.current = newIdx;
    track.style.transform = `translateX(-${newIdx * 100}%)`;

    // dots
    const dots = document.getElementById(id + '-dots');
    if (dots) {
        dots.querySelectorAll('.slider-dot').forEach((d, i) => {
            d.classList.toggle('bg-white', i === newIdx);
            d.classList.toggle('scale-125', i === newIdx);
            d.classList.toggle('bg-white/60', i !== newIdx);
        });
    }

    // counter
    const counter = document.getElementById(id + '-counter');
    if (counter) counter.textContent = `${newIdx + 1} / ${total}`;

    // gallery thumbnails
    const thumbs = document.getElementById(id + '-thumbs');
    if (thumbs) {
        thumbs.querySelectorAll('.gallery-thumb').forEach((t, i) => {
            t.classList.toggle('ring-green-500', i === newIdx);
            t.classList.toggle('ring-transparent', i !== newIdx);
        });
        // scroll thumb into view
        const activeThumb = thumbs.querySelectorAll('.gallery-thumb')[newIdx];
        if (activeThumb) activeThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    }

    // lazy-load current slide images
    const slide = track.querySelectorAll('.slider-slide')[newIdx];
    if (slide) {
        slide.querySelectorAll('img[data-src]').forEach(img => {
            if (img.dataset.src) { img.src = img.dataset.src; delete img.dataset.src; }
        });
    }
}

function sliderMove(id, dir) {
    const track = document.getElementById(id + '-track');
    if (!track) return;
    _sliderUpdate(id, parseInt(track.dataset.current || 0) + dir);
}

function sliderGoto(id, idx) { _sliderUpdate(id, idx); }

/* Touch / swipe support for all sliders */
(function() {
    let startX = 0, startY = 0, target = null;
    document.addEventListener('touchstart', e => {
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
        // find closest slider-track parent
        target = e.target.closest('[id$="-track"]');
    }, { passive: true });
    document.addEventListener('touchend', e => {
        if (!target) return;
        const dx = e.changedTouches[0].clientX - startX;
        const dy = e.changedTouches[0].clientY - startY;
        if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 40) {
            const id = target.id.replace('-track', '');
            sliderMove(id, dx < 0 ? 1 : -1);
        }
        target = null;
    }, { passive: true });
})();

/* ══════════════════════════════════════════════════════════════
   ACCORDION
══════════════════════════════════════════════════════════════ */
(function () {
    const accordion = document.getElementById('itineraryAccordion');
    if (!accordion) return;
    const toggles = accordion.querySelectorAll('.accordion-toggle');

    function openPanel(btn) {
        const panel = document.getElementById(btn.getAttribute('aria-controls'));
        if (!panel) return;
        btn.setAttribute('aria-expanded', 'true');
        panel.classList.remove('hidden');
        // lazy load first slide of every slider inside
        panel.querySelectorAll('.slider-track').forEach(track => {
            const slide = track.querySelectorAll('.slider-slide')[0];
            if (slide) {
                slide.querySelectorAll('img[data-src]').forEach(img => {
                    img.src = img.dataset.src; delete img.dataset.src;
                });
            }
        });
        // also load plain lazy images
        panel.querySelectorAll('img.lazy-day-image[data-src]').forEach(img => {
            img.src = img.dataset.src; delete img.dataset.src;
        });
    }

    function closePanel(btn) {
        const panel = document.getElementById(btn.getAttribute('aria-controls'));
        if (!panel) return;
        btn.setAttribute('aria-expanded', 'false');
        panel.classList.add('hidden');
    }

    toggles.forEach(btn => {
        btn.addEventListener('click', function () {
            const expanded = this.getAttribute('aria-expanded') === 'true';
            if (expanded) {
                closePanel(this);
            } else {
                toggles.forEach(o => { if (o !== this) closePanel(o); });
                openPanel(this);
                setTimeout(() => {
                    const panel = document.getElementById(this.getAttribute('aria-controls'));
                    if (panel) window.scrollTo({ top: panel.getBoundingClientRect().top + scrollY - 100, behavior: 'smooth' });
                }, 100);
            }
        });

        btn.addEventListener('keydown', function (e) {
            const list = Array.from(toggles), idx = list.indexOf(this);
            if (e.key === 'ArrowDown') { e.preventDefault(); (list[idx+1] || list[0]).focus(); }
            if (e.key === 'ArrowUp')   { e.preventDefault(); (list[idx-1] || list[list.length-1]).focus(); }
        });
    });

    document.getElementById('expandAll')?.addEventListener('click', () => toggles.forEach(openPanel));
    document.getElementById('collapseAll')?.addEventListener('click', () => {
        toggles.forEach(closePanel);
        window.scrollTo({ top: accordion.getBoundingClientRect().top + scrollY - 80, behavior: 'smooth' });
    });
})();

/* ══════════════════════════════════════════════════════════════
   TOUR GALLERY LIGHTBOX (uses the main slider index)
══════════════════════════════════════════════════════════════ */
@if($tour->images && $tour->images->count() > 0)
const _galleryImages = @json($tour->images->pluck('image_path'));
let _galleryIdx = 0;

function openGallery(i) {
    _galleryIdx = i;
    _renderGalleryLightbox();
    const modal = document.getElementById('galleryModal');
    modal.classList.remove('hidden'); modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function closeGallery() {
    const modal = document.getElementById('galleryModal');
    modal.classList.add('hidden'); modal.classList.remove('flex');
    document.body.style.overflow = '';
}
function galleryLightboxPrev() { _galleryIdx = ((_galleryIdx - 1) + _galleryImages.length) % _galleryImages.length; _renderGalleryLightbox(); }
function galleryLightboxNext() { _galleryIdx = (_galleryIdx + 1) % _galleryImages.length; _renderGalleryLightbox(); }
function _renderGalleryLightbox() {
    document.getElementById('galleryImage').src = '{{ asset("storage") }}/' + _galleryImages[_galleryIdx];
    const ctr = document.getElementById('galleryCounter');
    if (ctr) ctr.textContent = `${_galleryIdx + 1} / ${_galleryImages.length}`;
    // keep main slider in sync
    sliderGoto('tour-gallery', _galleryIdx);
}
@endif

/* ══════════════════════════════════════════════════════════════
   KEYBOARD NAVIGATION FOR LIGHTBOXES
══════════════════════════════════════════════════════════════ */
document.addEventListener('keydown', function (e) {
    const galleryOpen = !document.getElementById('galleryModal')?.classList.contains('hidden');
    if (galleryOpen) {
        if (e.key === 'ArrowRight') galleryLightboxNext?.();
        if (e.key === 'ArrowLeft')  galleryLightboxPrev?.();
        if (e.key === 'Escape')     closeGallery?.();
    }
});

/* ══════════════════════════════════════════════════════════════
   BOOKING HELPERS
══════════════════════════════════════════════════════════════ */
function scrollToBooking() {
    const el = document.getElementById('booking');
    if (!el) return;
    window.scrollTo({ behavior: 'smooth', top: el.getBoundingClientRect().top + scrollY - 80 });
}

function calculateTotal() {
    const sel = document.getElementById('group_size');
    const opt = sel?.options[sel.selectedIndex];
    const div = document.getElementById('totalCost');
    if (opt?.dataset.price) {
        const total = parseFloat(opt.dataset.price) * (parseInt(opt.value) || 1);
        document.getElementById('totalAmount').textContent = '$' + total.toLocaleString();
        div?.classList.remove('hidden');
    } else {
        div?.classList.add('hidden');
    }
}

document.querySelectorAll('.price-option').forEach(opt => {
    opt.addEventListener('click', function () {
        const sel = document.getElementById('group_size');
        if (!sel) return;
        for (const o of sel.options) { if (o.value === this.dataset.groupSize) { o.selected = true; break; } }
        calculateTotal();
        scrollToBooking();
    });
});

function quickBook(slug) { window.location.href = `/tours/${slug}#booking`; }

/* Booking form submission */
const bookingForm = document.getElementById('bookingForm');
if (bookingForm) {
    bookingForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        const orig = btn.innerHTML;
        btn.innerHTML = '<svg class="w-5 h-5 mr-2 inline animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Sending...';
        btn.disabled = true;
        try {
            const res = await fetch('{{ route("booking.store") }}', {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (res.ok && data.success) { window.location.href = '{{ route("booking.success") }}'; return; }
            if (data.errors) { alert('Please fix:\n\n' + Object.values(data.errors).map(e => '• ' + e[0]).join('\n')); }
            else alert(data.message || 'Something went wrong. Please try again.');
        } catch { alert('⚠️ Network error. Please check your connection.'); }
        finally { btn.innerHTML = orig; btn.disabled = false; }
    });
}
</script>
@endpush
@endsection