@extends('layouts.app')

@section('title', 'Safari Tours - Explore Our Amazing Tour Packages')
@section('meta_description', 'Browse our collection of safari tours including wildlife safaris, cultural experiences, and adventure tours in East Africa.')
@section('meta_keywords', 'safari tours, wildlife safaris, East Africa tours, Tanzania safaris, Kenya safaris, Uganda safaris, adventure tours, cultural tours, luxury safaris, budget safaris, family safaris, honeymoon safaris, group tours, private tours, Big Five safaris, gorilla trekking, mountain climbing, Serengeti tours, Ngorongoro crater, Maasai Mara, Kilimanjaro climbing, safari packages, African wildlife, game drives, bush camping, lodge safaris, tented camps, safari holidays, vacation packages, wildlife photography, bird watching, nature tours, eco tourism, responsible travel, safari booking, tour operator, travel agency, African adventures')

@section('page-header')
{{-- ═══════════════════════════════════════════════════════════════
     HERO HEADER
     — swap the image by changing the src in the <img> tag below
     — or pass $headerImage from your controller
═══════════════════════════════════════════════════════════════ --}}
{{--
    PARALLAX HERO
    The image moves slower than the page scroll, so content slides
    "over" the image as you scroll down, and the image re-appears
    as you scroll back up.  Change the image src here to any file.
--}}
<header id="tours-hero" class="relative h-72 md:h-[480px]" style="overflow: visible;">

    {{-- Parallax image container — clipped separately so the image can extend beyond header height --}}
    <div class="absolute inset-0 overflow-hidden" style="z-index:0;">
        <img
            id="hero-img"
            src="{{ $headerImage ?? asset('images/BIG FIVE.jpg') }}"
            alt="Safari Tours"
            class="absolute top-0 left-0 w-full object-cover object-center"
            style="height: 130%; transform: translateY(0px); will-change: transform;"
            onerror="this.style.display='none'">
        {{-- Dark overlay --}}
        <div class="absolute inset-0 bg-black/55"></div>
    </div>

    {{-- Hero text content --}}
    <div class="relative h-full flex flex-col items-center justify-center text-center px-4" style="z-index:1;">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-3 drop-shadow-lg">Our Safari Packages</h1>
        <p class="text-lg md:text-xl text-white/90 max-w-xl mb-5">
            Choose from our carefully crafted safari experiences
        </p>
        <nav aria-label="Breadcrumb">
            <ol class="flex items-center gap-2 text-sm text-green-200">
                <li><a href="{{ route('index') }}" class="hover:text-white transition-colors">Home</a></li>
                <li class="text-white/50">/</li>
                <li class="text-white font-medium">Tours</li>
            </ol>
        </nav>
    </div>
</header>
@endsection

@section('content')
<div style="scroll-behavior: smooth;">

    {{-- ═══════════════════════════════════════════════════════════
         INTRO SECTION
    ═══════════════════════════════════════════════════════════ --}}
    <section class="py-14 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Discover East Africa's Premier Safari Destinations
                </h2>
                <p class="text-lg text-gray-700 max-w-4xl mx-auto leading-relaxed">
                    Welcome to the ultimate safari experience in East Africa, where breathtaking wildlife encounters meet unparalleled adventure.
                    Our expertly curated tours take you through the continent's most iconic destinations, from the vast plains of the
                    Serengeti to the misty mountains of Rwanda.
                </p>
            </div>

            {{-- Tourist category cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-1">Family Adventures</h3>
                    <p class="text-gray-500 text-sm">Child-friendly safaris with educational experiences for all ages.</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-1">Romantic Getaways</h3>
                    <p class="text-gray-500 text-sm">Intimate safaris perfect for couples, honeymoons and anniversaries.</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-1">Educational Tours</h3>
                    <p class="text-gray-500 text-sm">Learning-focused expeditions for students, researchers and curious minds.</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-1">Adventure Seekers</h3>
                    <p class="text-gray-500 text-sm">Thrilling expeditions including climbing, trekking and extreme wildlife.</p>
                </div>
            </div>

            {{-- Why choose us --}}
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Why Choose Our Safari Tours?</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-11 h-11 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">Expert Local Guides</h4>
                        <p class="text-gray-500 text-sm">Professional naturalists with decades of experience and intimate wildlife knowledge.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-11 h-11 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">Conservation Focus</h4>
                        <p class="text-gray-500 text-sm">Responsible tourism that directly supports wildlife and local communities.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-11 h-11 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">Flexible Itineraries</h4>
                        <p class="text-gray-500 text-sm">Customisable tours adapted to your interests, fitness level and schedule.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════
         FILTER SECTION
         id="tour-filters" — this is the scroll anchor used by JS
    ═══════════════════════════════════════════════════════════ --}}
    <section id="tour-filters" class="py-8 bg-white border-b shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-5">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Browse Our Safari Collection</h2>
                    <p class="text-gray-500 text-sm mt-0.5">{{ $tours->total() }} incredible adventures await</p>
                </div>
                <div class="flex gap-2 items-center">
                    <a href="{{ route('tours.budget') }}" class="text-sm text-green-600 hover:text-green-800 font-medium whitespace-nowrap">
                        View Budget Options →
                    </a>
                    {{-- Mobile filter toggle --}}
                    <button id="mobile-filter-toggle"
                            class="md:hidden flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white rounded-lg text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/></svg>
                        Filters
                    </button>
                </div>
            </div>

            {{-- Filter form — hidden on mobile until toggled --}}
            <div id="filter-form-wrap" class="hidden md:block">
                <form id="filter-form"
                      method="GET"
                      action="{{ route('tours.index') }}#tour-filters"
                      class="flex flex-col gap-3 md:flex-row md:flex-wrap md:items-end">

                    <div class="flex-1 min-w-[140px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1 md:hidden">Category</label>
                        <select name="category" class="tour-filter w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                            <option value="">All Categories</option>
                            @foreach($availableCategories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ ucfirst($category) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-1 min-w-[140px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1 md:hidden">Tour Type</label>
                        <select name="type" class="tour-filter w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                            <option value="">All Types</option>
                            @foreach($availableTypes as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-1 min-w-[140px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1 md:hidden">Destination</label>
                        <select name="destination" class="tour-filter w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                            <option value="">All Destinations</option>
                            @foreach($availableDestinations as $destination)
                                <option value="{{ $destination }}" {{ request('destination') == $destination ? 'selected' : '' }}>{{ $destination }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-1 min-w-[130px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1 md:hidden">Duration</label>
                        <select name="duration" class="tour-filter w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                            <option value="">Any Duration</option>
                            @foreach($availableDurations as $duration)
                                <option value="{{ $duration }}" {{ request('duration') == $duration ? 'selected' : '' }}>{{ $duration }} {{ $duration == 1 ? 'Day' : 'Days' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-1 min-w-[140px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1 md:hidden">Price Range</label>
                        <select name="price_range" class="tour-filter w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                            <option value="">Any Price</option>
                            @if($priceRanges['min'] > 0 && $priceRanges['max'] > 0)
                                @php
                                    $step = ($priceRanges['max'] - $priceRanges['min']) / 4;
                                    $ranges = [
                                        'low'      => ['min' => $priceRanges['min'],              'max' => $priceRanges['min'] + $step],
                                        'mid-low'  => ['min' => $priceRanges['min'] + $step,      'max' => $priceRanges['min'] + $step * 2],
                                        'mid-high' => ['min' => $priceRanges['min'] + $step * 2,  'max' => $priceRanges['min'] + $step * 3],
                                        'high'     => ['min' => $priceRanges['min'] + $step * 3,  'max' => $priceRanges['max']],
                                    ];
                                @endphp
                                @foreach($ranges as $key => $range)
                                    <option value="{{ $key }}" {{ request('price_range') == $key ? 'selected' : '' }}>
                                        ${{ number_format($range['min']) }} – ${{ number_format($range['max']) }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="flex-1 min-w-[140px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1 md:hidden">Sort By</label>
                        <select name="sort" class="tour-filter w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                            <option value="">Sort By</option>
                            <option value="price_low"       {{ request('sort') == 'price_low'       ? 'selected' : '' }}>Price: Low → High</option>
                            <option value="price_high"      {{ request('sort') == 'price_high'      ? 'selected' : '' }}>Price: High → Low</option>
                            <option value="duration_short"  {{ request('sort') == 'duration_short'  ? 'selected' : '' }}>Duration: Short → Long</option>
                            <option value="duration_long"   {{ request('sort') == 'duration_long'   ? 'selected' : '' }}>Duration: Long → Short</option>
                            <option value="newest"          {{ request('sort') == 'newest'          ? 'selected' : '' }}>Newest First</option>
                            <option value="title_az"        {{ request('sort') == 'title_az'        ? 'selected' : '' }}>Title: A–Z</option>
                            <option value="title_za"        {{ request('sort') == 'title_za'        ? 'selected' : '' }}>Title: Z–A</option>
                        </select>
                    </div>

                    {{-- Buttons (shown on mobile; desktop auto-submits) --}}
                    <div class="flex gap-2 md:hidden">
                        <button type="submit"
                                class="flex-1 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors">
                            Apply Filters
                        </button>
                        @if(request()->hasAny(['category','type','destination','duration','price_range','sort']))
                        <a href="{{ route('tours.index') }}#tour-filters"
                           class="px-5 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg text-sm font-medium transition-colors">
                            Clear
                        </a>
                        @endif
                    </div>

                    {{-- Desktop clear button --}}
                    @if(request()->hasAny(['category','type','destination','duration','price_range','sort']))
                    <a href="{{ route('tours.index') }}#tour-filters"
                       class="hidden md:inline-flex items-center gap-1.5 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors whitespace-nowrap">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Clear All
                    </a>
                    @endif
                </form>

                {{-- Active filter pills --}}
                @if(request()->hasAny(['category','type','destination','duration','price_range','sort']))
                <div class="mt-4 flex flex-wrap gap-2 items-center">
                    <span class="text-xs text-gray-500 font-medium">Active:</span>
                    @if(request('category'))
                    <span class="inline-flex items-center bg-green-100 text-green-800 px-2.5 py-1 rounded-full text-xs">
                        Category: {{ ucfirst(request('category')) }}
                        <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}#tour-filters" class="ml-1.5 text-green-600 hover:text-green-800 font-bold leading-none">×</a>
                    </span>
                    @endif
                    @if(request('type'))
                    <span class="inline-flex items-center bg-blue-100 text-blue-800 px-2.5 py-1 rounded-full text-xs">
                        Type: {{ ucfirst(request('type')) }}
                        <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}#tour-filters" class="ml-1.5 text-blue-600 hover:text-blue-800 font-bold leading-none">×</a>
                    </span>
                    @endif
                    @if(request('destination'))
                    <span class="inline-flex items-center bg-purple-100 text-purple-800 px-2.5 py-1 rounded-full text-xs">
                        Destination: {{ request('destination') }}
                        <a href="{{ request()->fullUrlWithQuery(['destination' => null]) }}#tour-filters" class="ml-1.5 text-purple-600 hover:text-purple-800 font-bold leading-none">×</a>
                    </span>
                    @endif
                    @if(request('duration'))
                    <span class="inline-flex items-center bg-orange-100 text-orange-800 px-2.5 py-1 rounded-full text-xs">
                        Duration: {{ request('duration') }} {{ request('duration') == 1 ? 'Day' : 'Days' }}
                        <a href="{{ request()->fullUrlWithQuery(['duration' => null]) }}#tour-filters" class="ml-1.5 text-orange-600 hover:text-orange-800 font-bold leading-none">×</a>
                    </span>
                    @endif
                    @if(request('price_range'))
                    <span class="inline-flex items-center bg-pink-100 text-pink-800 px-2.5 py-1 rounded-full text-xs">
                        Price: {{ ucfirst(str_replace('-', ' ', request('price_range'))) }}
                        <a href="{{ request()->fullUrlWithQuery(['price_range' => null]) }}#tour-filters" class="ml-1.5 text-pink-600 hover:text-pink-800 font-bold leading-none">×</a>
                    </span>
                    @endif
                    @if(request('sort'))
                    <span class="inline-flex items-center bg-gray-100 text-gray-800 px-2.5 py-1 rounded-full text-xs">
                        Sort: {{ ucfirst(str_replace('_', ' ', request('sort'))) }}
                        <a href="{{ request()->fullUrlWithQuery(['sort' => null]) }}#tour-filters" class="ml-1.5 text-gray-600 hover:text-gray-800 font-bold leading-none">×</a>
                    </span>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════
         TOURS GRID
         id="tours-results" — also used as an anchor
    ═══════════════════════════════════════════════════════════ --}}
    <section id="tours-results" class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Mobile view toggle --}}
            <div class="md:hidden mb-5 flex justify-center">
                <div class="bg-gray-100 p-1 rounded-lg inline-flex">
                    <button id="grid-view"
                            class="px-4 py-2 rounded-md text-sm font-medium transition-colors bg-green-600 text-white">
                        Grid View
                    </button>
                    <button id="horizontal-view"
                            class="px-4 py-2 rounded-md text-sm font-medium transition-colors text-gray-600 hover:text-gray-900">
                        Slide View
                    </button>
                </div>
            </div>

            {{-- Grid --}}
            <div id="grid-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @forelse($tours as $tour)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group flex flex-col">
                    <div class="relative h-48 md:h-64 overflow-hidden shrink-0">
                        @if($tour->featured_image)
                            <img src="{{ asset('storage/'.$tour->featured_image) }}" alt="{{ $tour->title }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                                <svg class="w-14 h-14 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                        <div class="absolute top-3 left-3">
                            <span class="bg-green-600 text-white px-2.5 py-1 rounded-full text-xs font-semibold">{{ $tour->category ?? 'Safari' }}</span>
                        </div>
                        <div class="absolute top-3 right-3">
                            <span class="bg-white/90 backdrop-blur text-gray-800 px-2.5 py-1 rounded-full text-xs font-semibold">
                                {{ $tour->itineraries->count() ?: 'Multi' }} {{ $tour->itineraries->count() == 1 ? 'Day' : 'Days' }}
                            </span>
                        </div>
                        @if($tour->type)
                        <div class="absolute bottom-3 left-3">
                            <span class="bg-blue-600 text-white px-2.5 py-1 rounded-full text-xs font-semibold">{{ $tour->type }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="p-4 md:p-6 flex flex-col flex-1">
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2 line-clamp-2">{{ $tour->title }}</h3>
                        <p class="text-gray-500 mb-4 text-sm line-clamp-2 md:line-clamp-3 flex-1">{{ Str::limit($tour->description, 120) }}</p>

                        <div class="flex items-center mb-4 text-sm text-gray-500">
                            <svg class="w-4 h-4 text-green-600 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="line-clamp-1">{{ $tour->destinations ?: 'East Africa' }}</span>
                        </div>

                        <div class="flex items-center justify-between mb-5">
                            <div>
                                @if($tour->prices && $tour->prices->count() > 0)
                                    @php $minPrice = $tour->prices->min('price'); @endphp
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-xl md:text-2xl font-bold text-green-600">${{ number_format($minPrice) }}</span>
                                        <span class="text-gray-400 text-xs">per person</span>
                                    </div>
                                    <p class="text-xs text-gray-400">Starting from</p>
                                @else
                                    <span class="text-green-600 font-semibold text-sm">Contact for Pricing</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-1">
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)<svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                                </div>
                                <span class="text-xs text-gray-500">4.9</span>
                            </div>
                        </div>

                        <div class="flex gap-2 mt-auto">
                            <a href="{{ route('tours.show', $tour->slug) }}"
                               class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2.5 px-4 rounded-lg text-sm font-semibold transition-colors">
                                View Details
                            </a>
                            <button onclick="quickBook('{{ $tour->slug }}')"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3.5 py-2.5 rounded-lg transition-colors"
                                    title="Quick Book">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-16">
                    <svg class="w-20 h-20 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <h3 class="text-xl font-bold text-gray-500 mb-2">No Tours Found</h3>
                    @if(request()->hasAny(['category','type','destination','duration','price_range','sort']))
                        <p class="text-gray-400 mb-4">No tours match your current filters.</p>
                        <a href="{{ route('tours.index') }}#tour-filters" class="bg-green-600 text-white px-6 py-2.5 rounded-lg hover:bg-green-700 transition-colors text-sm">View All Tours</a>
                    @else
                        <p class="text-gray-400">Check back soon for amazing safari adventures!</p>
                    @endif
                </div>
                @endforelse
            </div>

            {{-- Mobile horizontal scroll --}}
            <div id="horizontal-container" class="hidden">
                <div class="flex gap-4 overflow-x-auto pb-6 snap-x snap-mandatory scrollbar-hide">
                    @forelse($tours as $tour)
                    <div class="flex-shrink-0 w-72 snap-start bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="relative h-40 overflow-hidden">
                            @if($tour->featured_image)
                                <img src="{{ asset('storage/'.$tour->featured_image) }}" alt="{{ $tour->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                            <div class="absolute top-2 left-2">
                                <span class="bg-green-600 text-white px-2 py-0.5 rounded text-xs font-medium">{{ $tour->category ?? 'Safari' }}</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-base font-bold text-gray-900 mb-2 line-clamp-1">{{ $tour->title }}</h3>
                            <div class="flex items-center mb-2 text-xs text-gray-500">
                                <svg class="w-3.5 h-3.5 text-green-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                <span class="line-clamp-1">{{ $tour->destinations ?: 'East Africa' }}</span>
                            </div>
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    @if($tour->prices && $tour->prices->count() > 0)
                                        @php $minPrice = $tour->prices->min('price'); @endphp
                                        <span class="text-base font-bold text-green-600">${{ number_format($minPrice) }}</span>
                                        <span class="text-xs text-gray-400"> /person</span>
                                    @else
                                        <span class="text-green-600 font-semibold text-xs">Contact for Pricing</span>
                                    @endif
                                </div>
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('tours.show', $tour->slug) }}" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 rounded-lg text-xs font-semibold transition-colors">View Details</a>
                                <button onclick="quickBook('{{ $tour->slug }}')" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="flex-shrink-0 w-full text-center py-12"><p class="text-gray-400">No tours available</p></div>
                    @endforelse
                </div>
            </div>

            {{-- Pagination --}}
            @if($tours->hasPages())
            <div class="mt-10 flex justify-center">
                {{ $tours->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════
         CTA
    ═══════════════════════════════════════════════════════════ --}}
    {{-- CTA with parallax background image --}}
    <section id="cta-section" class="relative py-20 overflow-hidden">

        {{-- Parallax image — change src to any image you like --}}
        <img id="cta-img"
             src="{{ asset('images/BIG FIVE.jpg') }}"
             alt=""
             aria-hidden="true"
             class="absolute left-0 w-full object-cover object-center pointer-events-none"
             style="top: -20%; height: 140%; will-change: transform;"
             onerror="this.style.display='none'">

        {{-- Dark + green tint overlay --}}
        <div class="absolute inset-0 bg-black/60" style="background: linear-gradient(135deg, rgba(22,101,52,0.80) 0%, rgba(29,78,216,0.75) 100%);"></div>

        {{-- Content --}}
        <div class="relative z-10 max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-3 drop-shadow-lg">Ready to Begin Your African Adventure?</h2>
            <p class="text-lg text-green-100 mb-8 max-w-2xl mx-auto">
                Our safari experts are standing by to help you plan the perfect journey.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('contact') }}"
                   class="bg-white text-green-600 px-7 py-3.5 rounded-lg font-bold hover:bg-gray-100 transition-all hover:scale-105 text-sm md:text-base">
                    Plan My Safari
                </a>
                <a href="tel:+256752088768"
                   class="border-2 border-white text-white px-7 py-3.5 rounded-lg font-bold hover:bg-white hover:text-green-600 transition-all text-sm md:text-base">
                    Call: +256 752 088 768
                </a>
                <a href="https://wa.me/256777143020"
                   class="bg-green-500 text-white px-7 py-3.5 rounded-lg font-bold hover:bg-green-400 transition-all flex items-center justify-center gap-2 text-sm md:text-base">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/></svg>
                    WhatsApp
                </a>
            </div>
        </div>
    </section>

</div>{{-- /smooth scroll wrapper --}}

@push('styles')
<style>
    html { scroll-behavior: smooth; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .line-clamp-1 { display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical; overflow:hidden; }
    .line-clamp-2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
    .line-clamp-3 { display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }
</style>
@endpush

@push('scripts')
<script>
// ── Parallax hero ─────────────────────────────────────────────
(function () {
    // Hero parallax
    var heroImg = document.getElementById('hero-img');
    var heroEl  = document.getElementById('tours-hero');

    // CTA parallax
    var ctaImg  = document.getElementById('cta-img');
    var ctaEl   = document.getElementById('cta-section');

    function parallax() {
        var scrolled = window.scrollY;

        // Hero — moves at 40% scroll speed
        if (heroImg && heroEl) {
            var heroBottom = heroEl.offsetTop + heroEl.offsetHeight;
            if (scrolled < heroBottom) {
                heroImg.style.transform = 'translateY(' + (scrolled * 0.4) + 'px)';
            }
        }

        // CTA — image moves relative to how far into the section we've scrolled
        if (ctaImg && ctaEl) {
            var ctaTop      = ctaEl.offsetTop;
            var ctaHeight   = ctaEl.offsetHeight;
            var ctaBottom   = ctaTop + ctaHeight;
            var winH        = window.innerHeight;

            // Only animate when section is in/near viewport
            if (scrolled + winH > ctaTop && scrolled < ctaBottom) {
                var progress = scrolled - ctaTop + winH;  // px scrolled past section top
                ctaImg.style.transform = 'translateY(' + (progress * -0.2) + 'px)';
            }
        }
    }

    window.addEventListener('scroll', parallax, { passive: true });
    parallax();
})();

document.addEventListener('DOMContentLoaded', function () {

    // ── Scroll to #tour-filters smoothly on page load if hash present ──
    if (window.location.hash === '#tour-filters') {
        setTimeout(function () {
            const el = document.getElementById('tour-filters');
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 80);
    }

    // ── Mobile filter toggle ────────────────────────────────────
    const mobileToggle  = document.getElementById('mobile-filter-toggle');
    const filterWrap    = document.getElementById('filter-form-wrap');

    if (mobileToggle) {
        mobileToggle.addEventListener('click', function () {
            filterWrap.classList.toggle('hidden');
            filterWrap.classList.toggle('block');
        });
    }

    // ── Desktop: auto-submit on dropdown change ─────────────────
    document.querySelectorAll('.tour-filter').forEach(function (sel) {
        sel.addEventListener('change', function () {
            if (window.innerWidth >= 768) {
                // Append anchor so page stays at filters after submit
                const form = document.getElementById('filter-form');
                form.action = '{{ route('tours.index') }}' + '#tour-filters';
                form.submit();
            }
        });
    });

    // ── Mobile view toggle ──────────────────────────────────────
    const gridBtn        = document.getElementById('grid-view');
    const horizBtn       = document.getElementById('horizontal-view');
    const gridContainer  = document.getElementById('grid-container');
    const horizContainer = document.getElementById('horizontal-container');

    if (gridBtn) {
        gridBtn.addEventListener('click', function () {
            gridBtn.classList.add('bg-green-600','text-white'); gridBtn.classList.remove('text-gray-600');
            horizBtn.classList.remove('bg-green-600','text-white'); horizBtn.classList.add('text-gray-600');
            gridContainer.classList.remove('hidden');
            horizContainer.classList.add('hidden');
        });
        horizBtn.addEventListener('click', function () {
            horizBtn.classList.add('bg-green-600','text-white'); horizBtn.classList.remove('text-gray-600');
            gridBtn.classList.remove('bg-green-600','text-white'); gridBtn.classList.add('text-gray-600');
            horizContainer.classList.remove('hidden');
            gridContainer.classList.add('hidden');
        });
    }

    // ── Quick book ──────────────────────────────────────────────
    window.quickBook = function (slug) {
        window.location.href = '/tours/' + slug + '#booking';
    };
});
</script>
@endpush
@endsection