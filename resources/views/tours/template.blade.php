@extends('layouts.app')

{{-- ============================================================
     SEO META — consistent across all tags
     ============================================================ --}}
@section('title', 'Uganda Safari Tours 2026 | Gorilla Trekking & Wildlife Packages | Calm Africa Safaris')
@section('meta_description', 'Book Uganda safari tours from $1,300. Gorilla trekking Bwindi, chimp tracking Kibale, Big Five Queen Elizabeth & Murchison Falls. Expert guides, small groups, guaranteed departures. Plan your East Africa safari today!')
@section('meta_keywords', 'Uganda safari tours, gorilla trekking Uganda, Bwindi gorilla trek, Uganda wildlife safari, chimp tracking Kibale, Queen Elizabeth safari, Murchison Falls tour, East Africa safari packages 2026')
@section('canonical', 'https://calmafricasafaris.com/tours')

{{-- Open Graph — year and price consistent with meta --}}
@section('og_title', 'Uganda Safari Tours 2026 | Gorilla Trekking Packages | Calm Africa Safaris')
@section('og_description', 'Explore Uganda gorilla trekking, chimp tracking, Big Five safaris and more. Budget to luxury packages from $1,300. Book with East Africa\'s trusted safari operator.')
@section('og_image', asset('images/BIG FIVE.jpg'))
@section('og_type', 'website')

{{-- ============================================================
     STRUCTURED DATA — all in @verbatim so $ signs are safe
     ============================================================ --}}
@push('scripts')
@verbatim
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "TouristInformationCenter",
  "name": "Calm Africa Safaris",
  "url": "https://calmafricasafaris.com",
  "telephone": "+256752088768",
  "email": "info@calmafricasafaris.com",
  "address": {
    "@type": "PostalAddress",
    "addressCountry": "UG",
    "addressLocality": "Kampala"
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.9",
    "reviewCount": "147",
    "bestRating": "5"
  },
  "priceRange": "$1300 - $8000",
  "description": "Uganda's leading gorilla trekking and wildlife safari operator offering budget to luxury safari packages in Bwindi, Kibale, Queen Elizabeth, Murchison Falls and Kidepo Valley.",
  "areaServed": ["Uganda", "Rwanda", "Kenya", "Tanzania"]
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "name": "Home",        "item": "https://calmafricasafaris.com" },
    { "@type": "ListItem", "position": 2, "name": "Safari Tours","item": "https://calmafricasafaris.com/tours" }
  ]
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "How much does a gorilla trekking safari in Uganda cost?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "A gorilla trekking safari in Uganda starts from $1,300 per person including the gorilla permit ($800), accommodation, meals and transport. Midrange 7-day packages typically cost $2,500-$4,500 per person. Luxury Uganda safaris start from $5,000 per person."
      }
    },
    {
      "@type": "Question",
      "name": "What is the best time to visit Uganda for a gorilla trekking safari?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "The best time for Uganda gorilla trekking safaris is during the dry seasons: June to September and December to February. During these months forest trails are drier and gorilla trekking conditions are better. However gorilla permits are available all year and the wet season offers lush green scenery and fewer tourists."
      }
    },
    {
      "@type": "Question",
      "name": "What is the difference between gorilla trekking in Uganda vs Rwanda?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Uganda gorilla trekking permits cost $800 per person while Rwanda gorilla permits cost $1,500 per person. Uganda's Bwindi Impenetrable Forest is home to half the world's mountain gorilla population. Rwanda's Volcanoes National Park offers shorter treks and luxury lodges. Uganda is better value for budget and midrange travelers."
      }
    },
    {
      "@type": "Question",
      "name": "How do I book a Uganda safari tour?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "You can book a Uganda safari with Calm Africa Safaris by completing our online booking form, calling +256 752 088 768, or messaging on WhatsApp. We handle gorilla trekking permits, accommodation, airport transfers and park fees. Book at least 3 months in advance to secure gorilla permits for peak season."
      }
    },
    {
      "@type": "Question",
      "name": "What animals can I see on a Uganda safari?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "On a Uganda safari you can see mountain gorillas in Bwindi Impenetrable Forest, chimpanzees in Kibale National Park, the Big Five (lions, elephants, buffaloes, leopards and rhinos) in Queen Elizabeth National Park and Ziwa Rhino Sanctuary, hippos and Nile crocodiles in Murchison Falls, and over 1,070 bird species making Uganda Africa's top birding destination."
      }
    },
    {
      "@type": "Question",
      "name": "Do I need a visa to visit Uganda for a safari?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Most nationalities require a visa to enter Uganda. You can apply online for an East Africa Tourist Visa which covers Uganda, Kenya and Rwanda, or obtain a single-entry Uganda visa on arrival. We advise applying online at least 2 weeks before travel. Calm Africa Safaris can assist with visa guidance."
      }
    }
  ]
}
</script>
@endverbatim
@endpush

@section('page-header')
<header id="tours-hero" class="relative w-full overflow-hidden" style="height:420px;">
    <img id="hero-img"
         src="{{ $headerImage ?? asset('images/BIG FIVE.jpg') }}"
         alt="Uganda safari tours 2026 — gorilla trekking in Bwindi Impenetrable Forest and wildlife safaris in East Africa"
         class="absolute inset-0 w-full h-full object-cover object-center"
         style="transform:translateY(0); will-change:transform;"
         onerror="this.style.display='none'">
    <div class="absolute inset-0 bg-black/55"></div>
    <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4 sm:px-6">
        <span class="inline-block bg-green-600/90 text-white text-xs font-semibold px-3 py-1 rounded-full mb-3 tracking-wide uppercase">
            East Africa's Trusted Safari Operator
        </span>
        <h1 class="text-2xl sm:text-4xl md:text-5xl font-bold text-white mb-2 sm:mb-3 drop-shadow-lg leading-tight">
            Uganda Safari Tours &amp; Gorilla Trekking Packages 2026
        </h1>
        <p class="text-sm sm:text-lg text-white/90 max-w-2xl mb-4 px-2">
            Gorilla trekking · Chimp tracking · Big Five safaris · Birdwatching · Cultural tours
        </p>
        <div class="flex flex-wrap justify-center gap-2 mb-4">
            <span class="bg-white/20 backdrop-blur text-white text-xs px-3 py-1 rounded-full">From $1,300/person</span>
            <span class="bg-white/20 backdrop-blur text-white text-xs px-3 py-1 rounded-full">Small Groups</span>
            <span class="bg-white/20 backdrop-blur text-white text-xs px-3 py-1 rounded-full">Permits Included</span>
            <span class="bg-white/20 backdrop-blur text-white text-xs px-3 py-1 rounded-full">Expert Local Guides</span>
        </div>
        <nav aria-label="Breadcrumb">
            <ol class="flex items-center gap-2 text-xs sm:text-sm text-green-200" itemscope itemtype="https://schema.org/BreadcrumbList">
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a href="{{ route('index') }}" itemprop="item" class="hover:text-white transition-colors">
                        <span itemprop="name">Home</span>
                    </a>
                    <meta itemprop="position" content="1">
                </li>
                <li class="text-white/50">/</li>
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span itemprop="name" class="text-white font-medium">Safari Tours</span>
                    <meta itemprop="position" content="2">
                </li>
            </ol>
        </nav>
    </div>
</header>
@endsection

@section('content')
<div class="w-full overflow-x-hidden">

    {{-- ── INTRO — keyword-rich server-rendered text (Googlebot reads this directly) ── --}}
    <section class="py-10 sm:py-14 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-10">
                <h2 class="text-xl sm:text-3xl md:text-4xl font-bold text-green-900 mb-3 sm:mb-4 leading-tight">
                    Uganda Gorilla Trekking, Wildlife &amp; Adventure Safari Packages 2026
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-gray-700 max-w-4xl mx-auto leading-relaxed">
                    Plan your <strong>Uganda safari</strong> with Calm Africa Safaris East Africa's trusted
                    <strong>gorilla trekking</strong> and wildlife tour operator. Choose from
                    <strong>budget, midrange and luxury safari packages</strong> covering
                    <strong>Bwindi Impenetrable Forest</strong>, <strong>Kibale National Park</strong>,
                    <strong>Queen Elizabeth National Park</strong>, <strong>Murchison Falls National Park</strong>,
                    <strong>Kidepo Valley National Park</strong> and more. All tours include expert local guides,
                    accommodation and airport transfers. We also specialise in
                    <strong>gorilla trekking in Rwanda</strong>, Kenya safaris, and Tanzania safari packages.
                    Uganda gorilla trekking permits cost <strong>$800 per person</strong> and our all-inclusive
                    packages start from <strong>$1,300 per person</strong>.
                </p>
            </div>

            {{-- Destination quick-links — internal linking boost --}}
            <div class="flex flex-wrap justify-center gap-2 mb-8 sm:mb-10">
                @foreach([
                    'Bwindi Gorilla Trekking',
                    'Kibale Chimp Tracking',
                    'Queen Elizabeth Safari',
                    'Murchison Falls Tours',
                    'Kidepo Valley Safari',
                    'Rwanda Gorilla Trek',
                    'Uganda Birding Tours',
                ] as $dest)
                <span class="bg-green-50 border border-green-200 text-green-800 text-xs font-medium px-3 py-1.5 rounded-full">
                    {{ $dest }}
                </span>
                @endforeach
            </div>

            {{-- Category cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-8 sm:mb-10">
                <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 text-center hover:shadow-lg transition-shadow">
                    <div class="w-10 h-10 sm:w-14 sm:h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-5 h-5 sm:w-7 sm:h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-xs sm:text-base font-semibold text-gray-900 mb-1">Family Safari Uganda</h3>
                    <p class="text-gray-500 text-xs hidden sm:block">Child-friendly Uganda safaris with educational wildlife experiences for all ages.</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 text-center hover:shadow-lg transition-shadow">
                    <div class="w-10 h-10 sm:w-14 sm:h-14 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-5 h-5 sm:w-7 sm:h-7 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <h3 class="text-xs sm:text-base font-semibold text-gray-900 mb-1">Honeymoon Safari Uganda</h3>
                    <p class="text-gray-500 text-xs hidden sm:block">Romantic gorilla trekking and luxury safari packages perfect for honeymoons.</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 text-center hover:shadow-lg transition-shadow">
                    <div class="w-10 h-10 sm:w-14 sm:h-14 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-5 h-5 sm:w-7 sm:h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h3 class="text-xs sm:text-base font-semibold text-gray-900 mb-1">Educational Wildlife Tours</h3>
                    <p class="text-gray-500 text-xs hidden sm:block">Primate research and conservation-focused expeditions for students and researchers.</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 text-center hover:shadow-lg transition-shadow">
                    <div class="w-10 h-10 sm:w-14 sm:h-14 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-5 h-5 sm:w-7 sm:h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-xs sm:text-base font-semibold text-gray-900 mb-1">Adventure Safaris Uganda</h3>
                    <p class="text-gray-500 text-xs hidden sm:block">White-water rafting Nile, Rwenzori trekking, bungee jumping and extreme wildlife safaris.</p>
                </div>
            </div>

            {{-- Why us --}}
            <div class="bg-white rounded-xl shadow-lg p-5 sm:p-8">
                <h3 class="text-lg sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6 text-center">
                    Why Book Your Uganda Safari With Calm Africa Safaris?
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
                    <div class="text-center">
                        <div class="w-9 h-9 sm:w-11 sm:h-11 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1 text-sm sm:text-base">Certified Uganda Wildlife Guides</h4>
                        <p class="text-gray-500 text-xs sm:text-sm">Uganda Wildlife Authority certified guides with decades of gorilla trekking and wildlife experience.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-9 h-9 sm:w-11 sm:h-11 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1 text-sm sm:text-base">Gorilla Permit Booking</h4>
                        <p class="text-gray-500 text-xs sm:text-sm">We secure your Uganda gorilla trekking permits ($800/person), Rwanda permits and all national park fees.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-9 h-9 sm:w-11 sm:h-11 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1 text-sm sm:text-base">Tailor-Made Itineraries</h4>
                        <p class="text-gray-500 text-xs sm:text-sm">Custom safari packages built around your budget, travel dates, group size and wildlife interests.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── FILTER BAR ── --}}
    <section id="tour-filters"
             class="py-4 sm:py-6 bg-white border-b shadow-sm sticky z-40"
             style="top: var(--nav-height, 70px);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-3 mb-3 sm:mb-4">
                <div>
                    <h2 class="text-lg sm:text-2xl font-bold text-gray-900 leading-tight">Browse Uganda Safari Packages 2026</h2>
                    <p class="text-gray-500 text-xs sm:text-sm mt-0.5">{{ $tours->total() }} safari adventures available</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <a href="{{ route('tours.budget') }}" class="text-xs sm:text-sm text-green-600 hover:text-green-800 font-medium whitespace-nowrap">
                        Budget Safaris &rarr;
                    </a>
                    <button id="mobile-filter-toggle"
                            class="md:hidden flex items-center gap-1.5 px-3 py-2 bg-green-600 text-white rounded-lg text-xs font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                        </svg>
                        Filters
                    </button>
                </div>
            </div>

            <div id="filter-form-wrap" class="hidden md:block">
                <form id="filter-form" method="GET" action="{{ route('tours.index') }}#tour-filters"
                      class="flex flex-col gap-3 md:flex-row md:flex-wrap md:items-end">

                    @foreach([
                        ['name'=>'category',    'label'=>'Category',     'placeholder'=>'All Categories',  'items'=>$availableCategories,   'fmt'=>'ucfirst'],
                        ['name'=>'type',        'label'=>'Tour Type',    'placeholder'=>'All Types',        'items'=>$availableTypes,        'fmt'=>'ucfirst'],
                        ['name'=>'destination', 'label'=>'Destination',  'placeholder'=>'All Destinations', 'items'=>$availableDestinations, 'fmt'=>'none'],
                    ] as $f)
                    <div class="w-full md:flex-1 md:min-w-[120px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1 md:hidden">{{ $f['label'] }}</label>
                        <select name="{{ $f['name'] }}" class="tour-filter w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 bg-white">
                            <option value="">{{ $f['placeholder'] }}</option>
                            @foreach($f['items'] as $item)
                                <option value="{{ $item }}" {{ request($f['name'])==$item ? 'selected' : '' }}>
                                    {{ $f['fmt']==='ucfirst' ? ucfirst($item) : $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endforeach

                    <div class="w-full md:flex-1 md:min-w-[110px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1 md:hidden">Duration</label>
                        <select name="duration" class="tour-filter w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 bg-white">
                            <option value="">Any Duration</option>
                            @foreach($availableDurations as $d)
                                <option value="{{ $d }}" {{ request('duration')==$d ? 'selected' : '' }}>
                                    {{ $d }} {{ $d==1 ? 'Day' : 'Days' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full md:flex-1 md:min-w-[120px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1 md:hidden">Price Range</label>
                        <select name="price_range" class="tour-filter w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 bg-white">
                            <option value="">Any Price</option>
                            @if($priceRanges['min'] > 0 && $priceRanges['max'] > 0)
                                @php
                                    $step   = ($priceRanges['max'] - $priceRanges['min']) / 4;
                                    $ranges = [
                                        'low'      => [$priceRanges['min'],         $priceRanges['min']+$step],
                                        'mid-low'  => [$priceRanges['min']+$step,   $priceRanges['min']+$step*2],
                                        'mid-high' => [$priceRanges['min']+$step*2, $priceRanges['min']+$step*3],
                                        'high'     => [$priceRanges['min']+$step*3, $priceRanges['max']],
                                    ];
                                @endphp
                                @foreach($ranges as $key=>[$mn,$mx])
                                    <option value="{{ $key }}" {{ request('price_range')==$key ? 'selected' : '' }}>
                                        ${{ number_format($mn) }} &ndash; ${{ number_format($mx) }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="w-full md:flex-1 md:min-w-[120px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1 md:hidden">Sort By</label>
                        <select name="sort" class="tour-filter w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 bg-white">
                            <option value="">Sort By</option>
                            @foreach([
                                'price_low'       => 'Price: Low to High',
                                'price_high'      => 'Price: High to Low',
                                'duration_short'  => 'Duration: Short to Long',
                                'duration_long'   => 'Duration: Long to Short',
                                'newest'          => 'Newest First',
                                'title_az'        => 'Title: A-Z',
                                'title_za'        => 'Title: Z-A',
                            ] as $val=>$lbl)
                                <option value="{{ $val }}" {{ request('sort')==$val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2 md:hidden">
                        <button type="submit" class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium">Apply</button>
                        @if(request()->hasAny(['category','type','destination','duration','price_range','sort']))
                            <a href="{{ route('tours.index') }}#tour-filters" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg text-sm font-medium">Clear</a>
                        @endif
                    </div>

                    @if(request()->hasAny(['category','type','destination','duration','price_range','sort']))
                        <a href="{{ route('tours.index') }}#tour-filters"
                           class="hidden md:inline-flex items-center gap-1 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium whitespace-nowrap">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Clear All
                        </a>
                    @endif
                </form>

                @if(request()->hasAny(['category','type','destination','duration','price_range','sort']))
                    <div class="mt-3 flex flex-wrap gap-2 items-center">
                        <span class="text-xs text-gray-500 font-medium">Active filters:</span>
                        @if(request('category'))
                            <span class="inline-flex items-center bg-green-100 text-green-800 px-2 py-0.5 rounded-full text-xs">
                                {{ ucfirst(request('category')) }}
                                <a href="{{ request()->fullUrlWithQuery(['category'=>null]) }}#tour-filters" class="ml-1 font-bold">&times;</a>
                            </span>
                        @endif
                        @if(request('type'))
                            <span class="inline-flex items-center bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full text-xs">
                                {{ ucfirst(request('type')) }}
                                <a href="{{ request()->fullUrlWithQuery(['type'=>null]) }}#tour-filters" class="ml-1 font-bold">&times;</a>
                            </span>
                        @endif
                        @if(request('destination'))
                            <span class="inline-flex items-center bg-purple-100 text-purple-800 px-2 py-0.5 rounded-full text-xs">
                                {{ request('destination') }}
                                <a href="{{ request()->fullUrlWithQuery(['destination'=>null]) }}#tour-filters" class="ml-1 font-bold">&times;</a>
                            </span>
                        @endif
                        @if(request('duration'))
                            <span class="inline-flex items-center bg-orange-100 text-orange-800 px-2 py-0.5 rounded-full text-xs">
                                {{ request('duration') }} {{ request('duration')==1 ? 'Day' : 'Days' }}
                                <a href="{{ request()->fullUrlWithQuery(['duration'=>null]) }}#tour-filters" class="ml-1 font-bold">&times;</a>
                            </span>
                        @endif
                        @if(request('price_range'))
                            <span class="inline-flex items-center bg-pink-100 text-pink-800 px-2 py-0.5 rounded-full text-xs">
                                {{ ucfirst(str_replace('-',' ',request('price_range'))) }}
                                <a href="{{ request()->fullUrlWithQuery(['price_range'=>null]) }}#tour-filters" class="ml-1 font-bold">&times;</a>
                            </span>
                        @endif
                        @if(request('sort'))
                            <span class="inline-flex items-center bg-gray-100 text-gray-800 px-2 py-0.5 rounded-full text-xs">
                                {{ ucfirst(str_replace('_',' ',request('sort'))) }}
                                <a href="{{ request()->fullUrlWithQuery(['sort'=>null]) }}#tour-filters" class="ml-1 font-bold">&times;</a>
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ── TOURS GRID ── --}}
    <section id="tours-results" class="py-8 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="md:hidden mb-4 flex justify-center">
                <div class="bg-gray-100 p-1 rounded-lg inline-flex">
                    <button id="grid-view"       class="px-3 py-2 rounded-md text-xs font-medium bg-green-600 text-white">Grid View</button>
                    <button id="horizontal-view" class="px-3 py-2 rounded-md text-xs font-medium text-gray-600">Slide View</button>
                </div>
            </div>

            {{-- Grid --}}
            <div id="grid-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
                @forelse($tours as $tour)
                <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group flex flex-col"
                         itemscope itemtype="https://schema.org/TouristTrip">
                    <div class="relative h-44 sm:h-52 md:h-64 overflow-hidden shrink-0">
                        @if($tour->featured_image)
                            <img src="{{ asset('storage/'.$tour->featured_image) }}"
                                 alt="{{ $tour->title }} — Uganda safari tour package in {{ $tour->destinations ?: 'East Africa' }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                 loading="lazy"
                                 itemprop="image">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                                <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                        <span class="absolute top-2 left-2 bg-green-600 text-white px-2 py-0.5 rounded-full text-xs font-semibold">{{ $tour->category ?? 'Safari' }}</span>
                        <span class="absolute top-2 right-2 bg-white/90 backdrop-blur text-gray-800 px-2 py-0.5 rounded-full text-xs font-semibold">
                            {{ $tour->itineraries->count() ?: 'Multi' }} {{ $tour->itineraries->count()==1 ? 'Day' : 'Days' }}
                        </span>
                        @if($tour->type)
                            <span class="absolute bottom-2 left-2 bg-blue-600 text-white px-2 py-0.5 rounded-full text-xs font-semibold">{{ $tour->type }}</span>
                        @endif
                    </div>
                    <div class="p-3 sm:p-4 md:p-6 flex flex-col flex-1">
                        <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-2 line-clamp-2 leading-snug" itemprop="name">
                            {{ $tour->title }}
                        </h3>
                        <p class="text-gray-500 mb-3 text-xs sm:text-sm line-clamp-2 flex-1" itemprop="description">
                            {{ Str::limit($tour->description, 120) }}
                        </p>
                        <div class="flex items-center mb-3 text-xs text-gray-500">
                            <svg class="w-3.5 h-3.5 text-green-600 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="line-clamp-1" itemprop="touristType">{{ $tour->destinations ?: 'Uganda, East Africa' }}</span>
                        </div>
                        <div class="flex items-center justify-between mb-3" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                            <div>
                                @if($tour->prices && $tour->prices->count() > 0)
                                    @php $minPrice = $tour->prices->min('price'); @endphp
                                    <meta itemprop="priceCurrency" content="USD">
                                    <meta itemprop="price" content="{{ $minPrice }}">
                                    <meta itemprop="availability" content="https://schema.org/InStock">
                                    <span class="text-lg sm:text-xl font-bold text-green-600">${{ number_format($minPrice) }}</span>
                                    <span class="text-gray-400 text-xs"> / person</span>
                                    <p class="text-xs text-gray-400">Starting from</p>
                                @else
                                    <span class="text-green-600 font-semibold text-xs">Contact for Pricing</span>
                                @endif
                            </div>
                            <div class="flex text-yellow-400 items-center gap-0.5"
                                 itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                                <meta itemprop="ratingValue" content="4.9">
                                <meta itemprop="reviewCount" content="47">
                                <meta itemprop="bestRating" content="5">
                                @for($i=1;$i<=5;$i++)
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                                <span class="text-xs text-gray-500 ml-1">4.9</span>
                            </div>
                        </div>
                        <div class="flex gap-2 mt-auto">
                            <a href="{{ route('tours.show', $tour->slug) }}"
                               class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-semibold transition-colors"
                               itemprop="url">
                                View Details
                            </a>
                            <button onclick="quickBook('{{ $tour->slug }}')"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg transition-colors"
                                    title="Quick Book {{ $tour->title }}">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </button>
                        </div>
                    </div>
                </article>
                @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <h3 class="text-lg font-bold text-gray-500 mb-2">No Tours Found</h3>
                    @if(request()->hasAny(['category','type','destination','duration','price_range','sort']))
                        <p class="text-gray-400 mb-4 text-sm">No Uganda safari tours match your current filters. Try broadening your search.</p>
                        <a href="{{ route('tours.index') }}#tour-filters" class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 text-sm">View All Safari Tours</a>
                    @else
                        <p class="text-gray-400 text-sm">Check back soon for amazing Uganda safari adventures!</p>
                    @endif
                </div>
                @endforelse
            </div>

            {{-- Slide view --}}
            <div id="horizontal-container" class="hidden">
                <div class="flex gap-3 overflow-x-auto pb-4 snap-x snap-mandatory scrollbar-hide -mx-4 px-4">
                    @forelse($tours as $tour)
                    <div class="flex-shrink-0 w-64 snap-start bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="relative h-36 overflow-hidden">
                            @if($tour->featured_image)
                                <img src="{{ asset('storage/'.$tour->featured_image) }}"
                                     alt="{{ $tour->title }} — Uganda safari"
                                     class="w-full h-full object-cover"
                                     loading="lazy">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                                    <svg class="w-10 h-10 text-white opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                            <span class="absolute top-2 left-2 bg-green-600 text-white px-2 py-0.5 rounded text-xs">{{ $tour->category ?? 'Safari' }}</span>
                        </div>
                        <div class="p-3">
                            <h3 class="text-sm font-bold text-gray-900 mb-1 line-clamp-1">{{ $tour->title }}</h3>
                            <div class="flex items-center mb-2 text-xs text-gray-500">
                                <svg class="w-3 h-3 text-green-600 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                <span class="line-clamp-1">{{ $tour->destinations ?: 'Uganda, East Africa' }}</span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                @if($tour->prices && $tour->prices->count() > 0)
                                    @php $minPrice = $tour->prices->min('price'); @endphp
                                    <span class="text-sm font-bold text-green-600">${{ number_format($minPrice) }}<span class="text-xs text-gray-400 font-normal"> /pp</span></span>
                                @else
                                    <span class="text-green-600 text-xs font-semibold">Ask for Price</span>
                                @endif
                                <div class="flex text-yellow-400">
                                    @for($i=1;$i<=5;$i++)
                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('tours.show', $tour->slug) }}" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 rounded-lg text-xs font-semibold">View Details</a>
                                <button onclick="quickBook('{{ $tour->slug }}')" class="bg-blue-600 hover:bg-blue-700 text-white px-2.5 py-2 rounded-lg" title="Book {{ $tour->title }}">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="w-full text-center py-10"><p class="text-gray-400 text-sm">No tours available</p></div>
                    @endforelse
                </div>
            </div>

            @if($tours->hasPages())
            <div class="mt-8 sm:mt-10 flex justify-center">
                {{ $tours->appends(request()->query())->links() }}
            </div>
            @endif
        </div>

        @include('partials.reviews', [
            'title'    => 'TripAdvisor Reviews',
            'subtitle' => 'Real stories from travelers who toured with Calm Africa Safaris',
            'reviews'  => $reviews ?? []
        ])

    </section>

    {{-- ── ABOUT SECTION — extra indexable keyword-rich body copy ── --}}
    <section class="py-12 sm:py-16 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6 text-center">
                About Uganda Safari Tours with Calm Africa Safaris
            </h2>
            <div class="prose prose-green max-w-none text-gray-700 text-sm sm:text-base leading-relaxed space-y-4">
                <p>
                    <strong>Uganda</strong> is one of Africa's most extraordinary safari destinations, offering a unique combination of
                    <strong>mountain gorilla trekking</strong>, chimpanzee tracking, Big Five game drives and world-class birdwatching — all within a compact, accessible country.
                    Calm Africa Safaris has been operating <strong>Uganda safari tours</strong> since 2010, specialising in small-group and private safaris that deliver genuine wildlife encounters and authentic cultural experiences.
                </p>
                <p>
                    Our most popular experience is <strong>gorilla trekking in Bwindi Impenetrable National Park</strong>, home to over 400 habituated mountain gorillas — nearly half the world's entire population.
                    Uganda gorilla trekking permits cost <strong>$800 per person</strong> and must be booked in advance. We also offer <strong>gorilla trekking in Mgahinga Gorilla National Park</strong> and, for those with a larger budget, <strong>gorilla trekking in Rwanda's Volcanoes National Park</strong> where permits cost $1,500 per person.
                </p>
                <p>
                    Beyond gorillas, our <strong>Uganda wildlife safaris</strong> cover <strong>Kibale National Park</strong> for chimpanzee tracking,
                    <strong>Queen Elizabeth National Park</strong> for tree-climbing lions and the famous Kazinga Channel boat cruise,
                    <strong>Murchison Falls National Park</strong> for Nile hippos, crocodiles and the iconic waterfall, and
                    <strong>Kidepo Valley National Park</strong> — Uganda's most remote and rewarding wilderness destination.
                    With over 1,070 recorded bird species, Uganda is also rated Africa's number one <strong>birding safari</strong> destination.
                </p>
                <p>
                    All our Uganda safari packages include airport transfers from Entebbe International Airport, comfortable accommodation, all meals on safari, national park entrance fees, and the services of our expert local guides.
                    We offer <strong>budget Uganda safaris</strong> using mid-range lodges and tented camps, <strong>midrange safari packages</strong> with superior lodges, and <strong>luxury Uganda safaris</strong> at exclusive boutique properties and high-end tented camps.
                </p>
            </div>
        </div>
    </section>

    {{-- ── FAQ — ── --}}
    <section class="py-12 sm:py-16 bg-gray-50" itemscope itemtype="https://schema.org/FAQPage">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-8 text-center">
                Frequently Asked Questions About Uganda Safari Tours
            </h2>
            <div class="space-y-4">

                @php
                $faqs = [
                    [
                        'q' => 'How much does a gorilla trekking safari in Uganda cost?',
                        'a' => 'A gorilla trekking safari in Uganda starts from $1,300 per person for a budget 3-day package, which includes the gorilla permit ($800), accommodation, meals and transport. Midrange 7-day Uganda safari packages typically cost $2,500 to $4,500 per person. Luxury Uganda safaris start from $5,000 per person. All our packages include the gorilla permit, accommodation, meals and airport transfers.',
                    ],
                    [
                        'q' => 'What is the best time to visit Uganda for a gorilla trekking safari?',
                        'a' => 'Uganda can be visited year-round for gorilla trekking. The best time for Uganda safaris is during the dry seasons: June to September and December to February. During these months forest trails are drier, wildlife is easier to spot and gorilla trekking conditions are better. The wet season offers lush green scenery, fewer tourists and lower accommodation rates.',
                    ],
                    [
                        'q' => 'What is the difference between gorilla trekking in Uganda vs Rwanda?',
                        'a' => 'Uganda gorilla trekking permits cost $800 per person while Rwanda gorilla permits cost $1,500 per person. Uganda\'s Bwindi Impenetrable Forest is home to half the world\'s mountain gorilla population across four sectors: Buhoma, Ruhija, Rushaga and Nkuringo. Rwanda\'s Volcanoes National Park offers shorter treks and more luxury lodges nearby. Uganda is significantly better value for budget and midrange travelers.',
                    ],
                    [
                        'q' => 'How do I book a Uganda safari tour with Calm Africa Safaris?',
                        'a' => 'You can book a Uganda safari with Calm Africa Safaris by filling in our online booking form, calling +256 752 088 768, or messaging us on WhatsApp. We handle everything including gorilla trekking permit reservations, accommodation bookings, airport transfers and national park fees. We recommend booking at least 3 months in advance to secure gorilla permits, especially for peak season travel in June to September.',
                    ],
                    [
                        'q' => 'What animals can I see on a Uganda safari?',
                        'a' => 'Uganda is home to extraordinary wildlife diversity. On a Uganda safari you can see mountain gorillas in Bwindi, chimpanzees in Kibale Forest, the Big Five including lions, elephants, buffaloes, leopards and rhinos in Queen Elizabeth National Park and Ziwa Rhino Sanctuary, tree-climbing lions in Queen Elizabeth\'s Ishasha sector, hippos and Nile crocodiles in Murchison Falls, and over 1,070 bird species making Uganda Africa\'s top birding safari destination.',
                    ],
                    [
                        'q' => 'Do I need a visa to visit Uganda for a safari?',
                        'a' => 'Most international visitors require a visa to enter Uganda. You can apply online for an East Africa Tourist Visa covering Uganda, Kenya and Rwanda, or obtain a Uganda single-entry visa on arrival at Entebbe International Airport. We recommend applying online at least 2 weeks before travel. Calm Africa Safaris can assist with visa guidance and pre-departure information.',
                    ],
                ];
                @endphp

                @foreach($faqs as $faq)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"
                     itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                    <button class="faq-toggle w-full text-left px-5 py-4 font-semibold text-gray-900 flex justify-between items-center hover:bg-gray-50 transition-colors text-sm sm:text-base">
                        <span itemprop="name">{{ $faq['q'] }}</span>
                        <svg class="faq-icon w-5 h-5 text-green-600 flex-shrink-0 ml-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="faq-answer hidden px-5 pb-4 text-gray-600 text-sm leading-relaxed"
                         itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <p itemprop="text">{{ $faq['a'] }}</p>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </section>

    {{-- ── CTA ── --}}
    <section class="relative py-14 sm:py-20 overflow-hidden">
        <img src="{{ asset('images/BIG FIVE.jpg') }}"
             alt="Uganda Big Five safari — lions, elephants and wildlife in Queen Elizabeth National Park"
             aria-hidden="true"
             class="absolute inset-0 w-full h-full object-cover object-center pointer-events-none"
             onerror="this.style.display='none'">
        <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(22,101,52,.82) 0%,rgba(29,78,216,.78) 100%);"></div>
        <div class="relative z-10 max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl sm:text-3xl md:text-4xl font-bold text-white mb-2 sm:mb-3 leading-tight drop-shadow-lg">
                Ready to Book Your Uganda Gorilla Trekking Safari?
            </h2>
            <p class="text-sm sm:text-base md:text-lg text-green-100 mb-2 max-w-2xl mx-auto">
                Our Uganda safari experts are ready to help you plan your perfect gorilla trekking or wildlife adventure.
                Packages from <strong>$1,300 per person</strong> including permits, accommodation and transport.
            </p>
            <p class="text-xs text-green-200 mb-6 sm:mb-8">
                Call or WhatsApp us — we reply within 2 hours.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                <a href="{{ route('contact') }}"
                   class="w-full sm:w-auto bg-white text-green-600 px-6 sm:px-7 py-3 rounded-lg font-bold hover:bg-gray-100 hover:scale-105 transition-all text-sm text-center">
                    Plan My Safari — Free Quote
                </a>
                <a href="tel:+256752088768"
                   class="w-full sm:w-auto border-2 border-white text-white px-6 sm:px-7 py-3 rounded-lg font-bold hover:bg-white hover:text-green-600 transition-all text-sm text-center">
                    Call: +256 752 088 768
                </a>
                <a href="https://wa.me/256777143020"
                   class="w-full sm:w-auto bg-green-500 hover:bg-green-400 text-white px-6 sm:px-7 py-3 rounded-lg font-bold transition-all flex items-center justify-center gap-2 text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/></svg>
                    WhatsApp Us Now
                </a>
            </div>
        </div>
    </section>

</div>

@push('styles')
<style>
    .scrollbar-hide { -ms-overflow-style:none; scrollbar-width:none; }
    .scrollbar-hide::-webkit-scrollbar { display:none; }
    .line-clamp-1 { display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical; overflow:hidden; }
    .line-clamp-2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
</style>
@endpush

@push('scripts')
<script>
(function () {

    /* ── Hero parallax ── */
    var img  = document.getElementById('hero-img');
    var hero = document.getElementById('tours-hero');
    if (img && hero) {
        window.addEventListener('scroll', function () {
            if (window.scrollY < hero.offsetTop + hero.offsetHeight + 200) {
                img.style.transform = 'translateY(' + (window.scrollY * 0.25) + 'px)';
            }
        }, { passive: true });
    }

    document.addEventListener('DOMContentLoaded', function () {

        /* Scroll to filter bar offset by nav */
        if (window.location.hash === '#tour-filters') {
            setTimeout(function () {
                var el   = document.getElementById('tour-filters');
                var navH = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--nav-height')) || 70;
                if (el) window.scrollTo({ top: el.getBoundingClientRect().top + window.scrollY - navH, behavior: 'smooth' });
            }, 100);
        }

        /* Mobile filter toggle */
        var toggle = document.getElementById('mobile-filter-toggle');
        var wrap   = document.getElementById('filter-form-wrap');
        if (toggle) toggle.addEventListener('click', function () {
            wrap.classList.toggle('hidden');
            wrap.classList.toggle('block');
        });

        /* Desktop auto-submit on filter change */
        document.querySelectorAll('.tour-filter').forEach(function (s) {
            s.addEventListener('change', function () {
                if (window.innerWidth >= 768) document.getElementById('filter-form').submit();
            });
        });

        /* Mobile grid / slide toggle */
        var gridBtn  = document.getElementById('grid-view');
        var slideBtn = document.getElementById('horizontal-view');
        var gridC    = document.getElementById('grid-container');
        var slideC   = document.getElementById('horizontal-container');
        if (gridBtn) {
            gridBtn.addEventListener('click', function () {
                gridBtn.classList.add('bg-green-600','text-white'); gridBtn.classList.remove('text-gray-600');
                slideBtn.classList.remove('bg-green-600','text-white'); slideBtn.classList.add('text-gray-600');
                gridC.classList.remove('hidden'); slideC.classList.add('hidden');
            });
            slideBtn.addEventListener('click', function () {
                slideBtn.classList.add('bg-green-600','text-white'); slideBtn.classList.remove('text-gray-600');
                gridBtn.classList.remove('bg-green-600','text-white'); gridBtn.classList.add('text-gray-600');
                slideC.classList.remove('hidden'); gridC.classList.add('hidden');
            });
        }

        /* FAQ accordion */
        document.querySelectorAll('.faq-toggle').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var answer = this.nextElementSibling;
                var icon   = this.querySelector('.faq-icon');
                answer.classList.toggle('hidden');
                icon.style.transform = answer.classList.contains('hidden') ? '' : 'rotate(180deg)';
            });
        });

        window.quickBook = function (slug) { window.location.href = '/tours/' + slug + '#booking'; };
    });

})();
</script>
@endpush

@endsection