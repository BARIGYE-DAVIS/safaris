@extends('layouts.app')

@section('title', 'Accommodations')

@section('content')
{{-- FULL-WIDTH HERO SLIDER --}}
@php
    $sliderItems = $accommodations->take(5);
@endphp

@if($sliderItems->count())
    <div x-data="{ current: 0 }" class="mb-10">
        <div class="relative overflow-hidden shadow-lg h-[60vh] min-h-[320px] bg-gray-900">
            @foreach($sliderItems as $idx => $acc)
                <a href="{{ route('accommodations.show', $acc->slug) }}"
                   x-show="current === {{ $idx }}"
                   x-transition
                   class="absolute inset-0 block">
                    <img
                        src="{{ $acc->featured_image_url ?? ($acc->images->first()->url ?? asset('images/placeholder-wide.jpg')) }}"
                        alt="{{ $acc->name }}"
                        class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

                    <div class="absolute bottom-8 left-4 md:left-12 text-white max-w-2xl">
                        <p class="text-xs md:text-sm uppercase tracking-wider text-green-300 mb-1">
                            {{ ucfirst($acc->category ?? 'Accommodation') }}
                            @if($acc->country) · {{ $acc->country->name }} @endif
                            @if($acc->destination) · {{ $acc->destination->name }} @endif
                        </p>
                        <h2 class="text-2xl md:text-3xl font-bold mb-2">
                            {{ $acc->name }}
                        </h2>
                        @if($acc->short_description)
                            <p class="text-sm md:text-base text-gray-100 line-clamp-3">
                                {{ $acc->short_description }}
                            </p>
                        @endif
                        @if($acc->price_from || $acc->price_to)
                            <p class="mt-3 text-sm md:text-base font-semibold text-green-200">
                                From {{ $acc->currency ?? 'USD' }}
                                {{ number_format($acc->price_from ?? 0) }}
                                @if($acc->price_to)
                                    – {{ number_format($acc->price_to) }}
                                @endif
                                <span class="font-normal text-xs text-gray-200">per person / night</span>
                            </p>
                        @endif
                    </div>
                </a>
            @endforeach

            {{-- Slider controls --}}
            <button type="button"
                    @click="current = current === 0 ? {{ $sliderItems->count() - 1 }} : current - 1"
                    class="absolute left-2 md:left-4 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white rounded-full w-9 h-9 flex items-center justify-center text-lg">
                ‹
            </button>
            <button type="button"
                    @click="current = current === {{ $sliderItems->count() - 1 }} ? 0 : current + 1"
                    class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white rounded-full w-9 h-9 flex items-center justify-center text-lg">
                ›
            </button>

            {{-- Indicators --}}
            <div class="absolute bottom-4 inset-x-0 flex justify-center space-x-1.5">
                @foreach($sliderItems as $idx => $acc)
                    <button type="button"
                            @click="current = {{ $idx }}"
                            :class="current === {{ $idx }} ? 'bg-white' : 'bg-white/50'"
                            class="w-3 h-3 rounded-full border border-white/70"></button>
                @endforeach
            </div>
        </div>
    </div>
@endif

<div class="max-w-6xl mx-auto px-4 py-8 pt-0">

    {{-- LIVE FILTERS (AUTO-SUBMIT) --}}
    <form id="filtersForm" method="GET" action="{{ route('accommodations.index') }}"
          class="mb-6 bg-white rounded-xl shadow px-4 py-3">
        <div class="flex flex-wrap items-end gap-4">

            {{-- Category --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Category</label>
                <select name="category"
                        class="px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-green-500 live-filter">
                    <option value="">All</option>
                    <option value="budget"    {{ request('category') === 'budget' ? 'selected' : '' }}>Budget</option>
                    <option value="mid-range" {{ request('category') === 'mid-range' ? 'selected' : '' }}>Mid-range</option>
                    <option value="high-end"  {{ request('category') === 'high-end' ? 'selected' : '' }}>High-end / Luxury</option>
                </select>
            </div>

            {{-- Type --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Type</label>
                <select name="type"
                        class="px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-green-500 live-filter">
                    <option value="">All</option>
                    <option value="Lodge"       {{ request('type') === 'Lodge' ? 'selected' : '' }}>Lodge</option>
                    <option value="Tented Camp" {{ request('type') === 'Tented Camp' ? 'selected' : '' }}>Tented Camp</option>
                    <option value="Hotel"       {{ request('type') === 'Hotel' ? 'selected' : '' }}>Hotel</option>
                    <option value="Guesthouse"  {{ request('type') === 'Guesthouse' ? 'selected' : '' }}>Guesthouse</option>
                    <option value="City Hotel"  {{ request('type') === 'City Hotel' ? 'selected' : '' }}>City Hotel</option>
                </select>
            </div>

            {{-- Country text search --}}
            <div class="flex-1 min-w-[160px]">
                <label class="block text-xs font-semibold text-gray-600 mb-1">
                    Country (name or ID)
                </label>
                <input type="text" name="country" value="{{ request('country') }}"
                       class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-green-500 live-search"
                       placeholder="Uganda, Kenya, etc.">
            </div>

            {{-- OPTIONAL: strict country dropdown (uses country_id) --}}
            {{-- If you have a countries list, you can use this instead of text search  --}}
            {{-- 
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Country</label>
                <select name="country_id"
                        class="px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-green-500 live-filter">
                    <option value="">All</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}"
                            {{ request('country_id') == $country->id ? 'selected' : '' }}>
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            --}}

            {{-- Reset link --}}
            <div class="ml-auto">
                <a href="{{ route('accommodations.index') }}"
                   class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-200">
                    Reset
                </a>
            </div>
        </div>
    </form>

    {{-- LIST --}}
    @if($accommodations->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($accommodations as $acc)
                <article class="bg-white rounded-xl shadow hover:shadow-lg transition flex flex-col overflow-hidden">
                    <a href="{{ route('accommodations.show', $acc->slug) }}" class="block relative h-44 bg-gray-100">
                        <img
                            src="{{ $acc->featured_image_url ?? ($acc->images->first()->url ?? asset('images/placeholder-wide.jpg')) }}"
                            alt="{{ $acc->name }}"
                            class="w-full h-full object-cover">
                        @if($acc->category)
                            <span class="absolute top-2 left-2 text-xs px-2 py-0.5 rounded-full
                                @if($acc->category === 'budget') bg-blue-100 text-blue-800
                                @elseif($acc->category === 'mid-range') bg-green-100 text-green-800
                                @else bg-purple-100 text-purple-800 @endif">
                                {{ ucfirst($acc->category) }}
                            </span>
                        @endif
                    </a>

                    <div class="flex-1 flex flex-col p-4">
                        <a href="{{ route('accommodations.show', $acc->slug) }}"
                           class="text-base font-semibold text-gray-900 hover:text-green-700">
                            {{ $acc->name }}
                        </a>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($acc->country) {{ $acc->country->name }} @endif
                            @if($acc->country && $acc->destination) · @endif
                            @if($acc->destination) {{ $acc->destination->name }} @endif
                            @if($acc->type) · {{ $acc->type }} @endif
                        </p>

                        @if($acc->short_description)
                            <p class="mt-2 text-sm text-gray-600 line-clamp-3">
                                {{ $acc->short_description }}
                            </p>
                        @endif

                        <div class="mt-3 flex items-center justify-between">
                            @if($acc->price_from || $acc->price_to)
                                <p class="text-sm font-semibold text-green-700">
                                    {{ $acc->currency ?? 'USD' }}
                                    {{ number_format($acc->price_from ?? 0) }}
                                    @if($acc->price_to)
                                        – {{ number_format($acc->price_to) }}
                                    @endif
                                </p>
                            @else
                                <p class="text-xs text-gray-400">Price on request</p>
                            @endif

                            <a href="{{ route('accommodations.show', $acc->slug) }}"
                               class="text-xs font-semibold text-green-700 hover:text-green-800">
                                View details →
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $accommodations->withQueryString()->links() }}
        </div>
    @else
        <p class="text-sm text-gray-500">No accommodations found for these filters.</p>
    @endif
</div>

{{-- Alpine for hero slider (if not globally loaded) --}}
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
    // LIVE FILTERING: auto-submit on change / typing (with small delay)
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('filtersForm');
        if (!form) return;

        const selects = form.querySelectorAll('.live-filter');
        const searchInputs = form.querySelectorAll('.live-search');

        // Auto-submit on select change
        selects.forEach(sel => {
            sel.addEventListener('change', () => {
                form.submit();
            });
        });

        // Debounced submit for text search
        let typingTimer;
        const debounceMs = 400;

        searchInputs.forEach(input => {
            input.addEventListener('input', () => {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    form.submit();
                }, debounceMs);
            });
        });
    });
</script>
@endsection