@extends('layouts.app')

@section('title', $accommodation->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    {{-- BREADCRUMB --}}
    <nav class="text-xs text-gray-500 mb-4">
        <a href="{{ route('accommodations.index') }}" class="hover:text-green-700">Accommodations</a>
        @if($accommodation->country)
            <span class="mx-1">/</span>
            <span>{{ $accommodation->country->name }}</span>
        @endif
        @if($accommodation->destination)
            <span class="mx-1">/</span>
            <span>{{ $accommodation->destination->name }}</span>
        @endif
    </nav>

    {{-- HEADER WITH FEATURED IMAGE --}}
    <div class="mb-6">
        <div class="relative overflow-hidden rounded-2xl shadow-lg h-56 md:h-72 bg-gray-200">
            <img
                src="{{ $accommodation->featured_image_url ?? ($accommodation->images->first()->url ?? asset('images/placeholder-wide.jpg')) }}"
                alt="{{ $accommodation->name }}"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>

            <div class="absolute bottom-4 left-4 md:bottom-6 md:left-6 text-white max-w-xl">
                <p class="text-xs md:text-sm text-gray-200 mb-1">
                    @if($accommodation->category)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] mr-1
                            @if($accommodation->category === 'budget') bg-blue-100 text-blue-800
                            @elseif($accommodation->category === 'mid-range') bg-green-100 text-green-800
                            @else bg-purple-100 text-purple-800 @endif">
                            {{ ucfirst($accommodation->category) }}
                        </span>
                    @endif

                    @if($accommodation->type) {{ $accommodation->type }} · @endif
                    @if($accommodation->country) {{ $accommodation->country->name }} @endif
                    @if($accommodation->destination) · {{ $accommodation->destination->name }} @endif
                </p>
                <h1 class="text-2xl md:text-3xl font-bold">
                    {{ $accommodation->name }}
                </h1>
                @if($accommodation->location)
                    <p class="mt-1 text-xs md:text-sm text-gray-200">
                        <i class="fas fa-map-marker-alt mr-1 text-red-400"></i>
                        {{ $accommodation->location }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    {{-- MAIN LAYOUT --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        {{-- MAIN CONTENT COLUMN --}}
        <div class="md:col-span-2 space-y-8">

            {{-- OVERVIEW SECTION --}}
            <section>
                <h2 class="text-lg font-semibold text-gray-900 mb-2 border-b border-gray-100 pb-1">
                    Overview
                </h2>

                @if($accommodation->short_description)
                    <p class="text-sm text-gray-700 leading-relaxed">
                        {{ $accommodation->short_description }}
                    </p>
                @else
                    <p class="text-sm text-gray-500">
                        A comfortable stay option in {{ optional($accommodation->destination)->name ?? optional($accommodation->country)->name }}.
                    </p>
                @endif
            </section>

            {{-- DETAILED DESCRIPTION SECTION --}}
            @if($accommodation->full_description)
                <section>
                    <h2 class="text-lg font-semibold text-gray-900 mb-2 border-b border-gray-100 pb-1">
                        Detailed Description
                    </h2>
                    <div class="prose prose-sm max-w-none text-gray-700">
                        {!! nl2br(e($accommodation->full_description)) !!}
                    </div>
                </section>
            @endif

            {{-- GALLERY SECTION (SLIDER WITH INDICATORS) --}}
            @php
                $gallery = collect($accommodation->images ?? []);
            @endphp

            @if($gallery->count())
                <section x-data="{ current: 0 }">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2 border-b border-gray-100 pb-1">
                        Photos
                    </h2>

                    {{-- Slider --}}
                    <div class="relative overflow-hidden rounded-2xl shadow-lg h-56 md:h-64 bg-gray-900 mb-3">
                        @foreach($gallery as $idx => $photo)
                            <div x-show="current === {{ $idx }}"
                                 x-transition
                                 class="absolute inset-0">
                                <img src="{{ $photo->url }}"
                                     alt="{{ $photo->alt_text ?? $accommodation->name }}"
                                     class="w-full h-full object-cover">
                            </div>
                        @endforeach

                        @if($gallery->count() > 1)
                            {{-- Controls --}}
                            <button type="button"
                                    @click="current = current === 0 ? {{ $gallery->count() - 1 }} : current - 1"
                                    class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm">
                                ‹
                            </button>
                            <button type="button"
                                    @click="current = current === {{ $gallery->count() - 1 }} ? 0 : current + 1"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm">
                                ›
                            </button>

                            {{-- Dot indicators --}}
                            <div class="absolute bottom-3 inset-x-0 flex justify-center space-x-1.5">
                                @foreach($gallery as $idx => $photo)
                                    <button type="button"
                                            @click="current = {{ $idx }}"
                                            :class="current === {{ $idx }} ? 'bg-white' : 'bg-white/50'"
                                            class="w-2.5 h-2.5 rounded-full border border-white/70"></button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Thumbnails --}}
                    @if($gallery->count() > 1)
                        <div class="flex gap-2 overflow-x-auto pb-1">
                            @foreach($gallery as $idx => $photo)
                                <button type="button"
                                        @click="current = {{ $idx }}"
                                        class="relative w-20 h-14 rounded-lg overflow-hidden border border-transparent hover:border-green-500"
                                        :class="current === {{ $idx }} ? 'ring-2 ring-green-500 border-green-500' : ''">
                                    <img src="{{ $photo->url }}"
                                         alt="{{ $photo->alt_text ?? $accommodation->name }}"
                                         class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </section>
            @endif

        </div>

        {{-- SIDEBAR COLUMN --}}
        <aside class="md:col-span-1 space-y-4">
            {{-- Price --}}
            <div class="bg-white rounded-xl shadow p-4">
                <h3 class="text-sm font-semibold text-gray-800 mb-2">
                    Price Guide
                </h3>
                @if($accommodation->price_from || $accommodation->price_to)
                    <p class="text-lg font-bold text-green-700">
                        {{ $accommodation->currency ?? 'USD' }}
                        {{ number_format($accommodation->price_from ?? 0) }}
                        @if($accommodation->price_to)
                            – {{ number_format($accommodation->price_to) }}
                        @endif
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        Per person per night (indicative). Exact price may vary by season and availability.
                    </p>
                @else
                    <p class="text-sm text-gray-600">
                        Price on request. Contact us for a detailed quote.
                    </p>
                @endif
            </div>

            {{-- Amenities --}}
            @if(is_array($accommodation->amenities) && count($accommodation->amenities))
                <div class="bg-white rounded-xl shadow p-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-2">
                        Amenities
                    </h3>
                    <ul class="text-xs text-gray-700 space-y-1">
                        @foreach($accommodation->amenities as $amenity)
                            <li class="flex items-start">
                                <span class="w-4 mt-0.5 text-green-600">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                                <span>{{ $amenity }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </aside>
    </div>

    {{-- RELATED ACCOMMODATIONS --}}
    @if(isset($related) && $related->count())
        <div class="mt-10">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">
                More stays {{ $accommodation->destination ? 'near ' . $accommodation->destination->name : 'you may like' }}
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($related as $rel)
                    <a href="{{ route('accommodations.show', $rel->slug) }}"
                       class="bg-white rounded-xl shadow hover:shadow-md transition block overflow-hidden">
                        <div class="h-28 bg-gray-100">
                            <img
                                src="{{ $rel->featured_image_url ?? ($rel->images->first()->url ?? asset('images/placeholder-wide.jpg')) }}"
                                alt="{{ $rel->name }}"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="p-3">
                            <p class="text-sm font-semibold text-gray-900 line-clamp-1">
                                {{ $rel->name }}
                            </p>
                            <p class="text-[11px] text-gray-500 mt-1">
                                @if($rel->category) {{ ucfirst($rel->category) }} @endif
                                @if($rel->type) · {{ $rel->type }} @endif
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection