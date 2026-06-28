@extends('layouts.app')

@section('title', 'Calm Africa Safaris - Premium Safari Tours & Wildlife Adventures in East Africa')
@section('meta_description', 'Experience unforgettable safari adventures in East Africa with Calm Africa Safaris. Expert guides, luxury accommodations, wildlife tours, cultural experiences. Book your dream African safari today!')
@section('meta_keywords', 'safari tours, East Africa safari, Tanzania safari, Kenya safari, Uganda safari, wildlife tours, luxury safari, family safari, honeymoon safari, Big Five safari, gorilla trekking, cultural tours, adventure tours, safari packages, African safari, wildlife photography, conservation tours, guided safari, custom safari, safari booking, Serengeti, Ngorongoro, Maasai Mara, Mount Kilimanjaro, safari holidays, eco tourism, responsible travel')

@section('page-header')
<!-- =========================================================
     HERO CAROUSEL  — parallax edition
     The .hero-bg-layer uses background-attachment:fixed so the
     image stays put while content scrolls over it.
     On mobile we fall back to scroll (fixed doesn't work inside
     overflow:hidden on iOS).
     ========================================================= -->
<section class="hero-section relative w-full overflow-visible"
         style="height: calc(100vh - var(--nav-height, 70px)); min-height: 520px; max-height: 960px;"
         aria-label="Uganda safari hero carousel">

    {{-- ── Carousel track ── --}}
    <div class="hero-carousel absolute inset-0 overflow-hidden rounded-b-none">

        <!-- Slide 1 — Bwindi -->
        <div class="hero-slide absolute inset-0 w-full h-full opacity-100 transition-opacity duration-1000 ease-in-out"
             style="background-image:linear-gradient(rgba(0,0,0,.45),rgba(0,0,0,.45)),url('{{ asset('images/BWINDI.jpg') }}');background-size:cover;background-position:center;">
            <div class="absolute inset-0 flex items-center justify-center px-4 sm:px-6 lg:px-8">
                <div class="text-center text-white w-full max-w-xs sm:max-w-lg md:max-w-2xl lg:max-w-4xl xl:max-w-5xl">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold mb-4 sm:mb-6 leading-tight">
                        Experience Wild Africa
                    </h1>
                    <p class="text-sm sm:text-base md:text-lg lg:text-xl mb-6 sm:mb-8 opacity-90 px-2 sm:px-0">
                        Embark on extraordinary safari adventures across East Africa's most iconic destinations
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                        <a href="{{ route('tours.index') }}"
                           class="bg-green-600 hover:bg-green-700 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold text-base sm:text-lg transition-all duration-300 hover:scale-105 text-center shadow-lg">
                            Explore Tours
                        </a>
                        <a href="#tour-packages"
                           class="border-2 border-white text-white hover:bg-white hover:text-green-600 px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold text-base sm:text-lg transition-all duration-300 text-center">
                            View Packages
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="hero-slide absolute inset-0 w-full h-full opacity-0 transition-opacity duration-1000 ease-in-out"
             style="background-image:linear-gradient(rgba(0,0,0,.45),rgba(0,0,0,.45)),url('{{ asset('images/BIG FIVE.jpg') }}');background-size:cover;background-position:center;">
            <div class="absolute inset-0 flex items-center justify-center px-4 sm:px-6 lg:px-8">
                <div class="text-center text-white w-full max-w-xs sm:max-w-lg md:max-w-2xl lg:max-w-4xl xl:max-w-5xl">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold mb-4 sm:mb-6 leading-tight">
                        Witness the Big Five
                    </h1>
                    <p class="text-sm sm:text-base md:text-lg lg:text-xl mb-6 sm:mb-8 opacity-90 px-2 sm:px-0">
                        Get up close with Africa's most magnificent wildlife in their natural habitat
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                        <a href="{{ route('tours.index') }}"
                           class="bg-orange-600 hover:bg-orange-700 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold text-base sm:text-lg transition-all duration-300 hover:scale-105 text-center shadow-lg">
                            Wildlife Safaris
                        </a>
                        <a href="{{ route('contact') }}"
                           class="border-2 border-white text-white hover:bg-white hover:text-orange-600 px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold text-base sm:text-lg transition-all duration-300 text-center">
                            Plan Custom Safari
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="hero-slide absolute inset-0 w-full h-full opacity-0 transition-opacity duration-1000 ease-in-out"
             style="background-image:linear-gradient(rgba(0,0,0,.45),rgba(0,0,0,.45)),url('https://images.unsplash.com/photo-1523805009345-7448845a9e53?ixlib=rb-4.0.3&auto=format&fit=crop&w=2400&q=80');background-size:cover;background-position:center;">
            <div class="absolute inset-0 flex items-center justify-center px-4 sm:px-6 lg:px-8">
                <div class="text-center text-white w-full max-w-xs sm:max-w-lg md:max-w-2xl lg:max-w-4xl xl:max-w-5xl">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold mb-4 sm:mb-6 leading-tight">
                        Luxury Safari Adventures
                    </h1>
                    <p class="text-sm sm:text-base md:text-lg lg:text-xl mb-6 sm:mb-8 opacity-90 px-2 sm:px-0">
                        Indulge in premium safari experiences with world-class accommodations
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                        <a href="{{ route('tours.index') }}"
                           class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold text-base sm:text-lg transition-all duration-300 hover:scale-105 text-center shadow-lg">
                            Luxury Tours
                        </a>
                        <a href="{{ route('tours.index') }}"
                           class="border-2 border-white text-white hover:bg-white hover:text-yellow-600 px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold text-base sm:text-lg transition-all duration-300 text-center">
                            Explore Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Dots --}}
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 sm:gap-3 z-20">
        <button class="hero-dot w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-white opacity-100 transition-opacity" data-slide="0" aria-label="Go to slide 1"></button>
        <button class="hero-dot w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-white opacity-50 transition-opacity"  data-slide="1" aria-label="Go to slide 2"></button>
        <button class="hero-dot w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-white opacity-50 transition-opacity"  data-slide="2" aria-label="Go to slide 3"></button>
    </div>

    {{-- Scroll cue --}}
    <div class="hidden sm:flex absolute bottom-6 right-6 sm:right-8 text-white z-20 animate-bounce">
        <a href="#introduction" class="scroll-to flex flex-col items-center text-xs opacity-80 hover:opacity-100 transition-opacity">
            <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
            Scroll
        </a>
    </div>
</section>
@endsection

@section('content')

<!-- ── INTRODUCTION ── -->
<section id="introduction" class="py-10 sm:py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 sm:mb-6 leading-tight">
       Uganda Gorilla Trekking Packages in  Bwindi Impenetrable Forest &amp; Mgahinga
        </h2>
        @include('partials.about-safari')
    </div>
</section>

<!-- ── DESTINATION QUICK LINKS ── -->
<section class="hidden py-6 bg-gray-50 border-y border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-center text-xs font-semibold text-gray-500 uppercase tracking-widest mb-4">Popular Destinations</p>
        <div class="flex flex-wrap justify-center gap-2">
            @foreach([
                'Bwindi Gorilla Trekking'  => 'Bwindi',
                'Kibale Chimp Tracking'    => 'Kibale',
                'Queen Elizabeth Safari'   => 'Queen Elizabeth',
                'Murchison Falls Tours'    => 'Murchison Falls',
                'Kidepo Valley Safari'     => 'Kidepo',
                'Rwanda Gorilla Trek'      => 'Rwanda',
                'Uganda Birding Tours'     => 'Birding',
            ] as $label => $dest)
            <a href="{{ route('tours.index', ['destination' => $dest]) }}"
               class="bg-white border border-green-200 text-green-800 text-xs font-medium px-3 py-1.5 rounded-full hover:bg-green-50 hover:border-green-400 transition-colors">
                {{ $label }}
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- ── TOUR PACKAGES ── -->
<section id="tour-packages" class="py-12 sm:py-16 lg:py-24 bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 sm:mb-16">
            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 sm:mb-6 leading-tight">
                Featured Uganda Safari Packages 2026
            </h2>
            <p class="text-base sm:text-lg text-gray-700 max-w-3xl mx-auto">
                Handpicked <strong>gorilla trekking</strong>, <strong>wildlife</strong> and <strong>adventure safari packages</strong>
                — from budget escapes to luxury expeditions across East Africa
            </p>
        </div>

        @php
            $featuredTours = \App\Models\Tour::published()->orderBy('created_at','desc')->limit(10)->get();
        @endphp

        @include('partials.tour-list', [
            'tours'             => $featuredTours,
            'limit'             => 10,
            'showExploreButton' => true,
            'columns'           => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
            'heading'           => 'Featured Uganda Safari Packages 2026',
            'subheading'        => 'Gorilla trekking, chimp tracking, Big Five safaris and more across East Africa',
        ])

        <div class="text-center mt-10 sm:mt-12">
            <a href="{{ route('tours.index') }}"
               class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold text-base sm:text-lg transition-all hover:scale-105 shadow-md w-full sm:w-auto">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Explore All Uganda Safari Tours
            </a>
        </div>
    </div>
</section>

@include('partials.destinations-carousel')
@include('partials.activities-carousel')
@include('partials.accommodation')

<!-- ── WHY CHOOSE US ── -->
<section class="py-12 sm:py-16 lg:py-24 bg-green-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 sm:mb-16">
            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-orange-600 mb-4 sm:mb-6 leading-tight">
                Why Choose Calm Africa Safaris for Your Uganda Safari?
            </h2>
            <p class="text-base sm:text-lg text-gray-700 max-w-3xl mx-auto">
                We're not just tour operators — we're conservation partners, community supporters, and dream makers
            </p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">

            <div class="flex flex-col items-center text-center group p-4 sm:p-6 rounded-xl hover:bg-gray-50 transition-colors duration-300">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform shadow-md flex-shrink-0">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3">Safety &amp; Security First</h3>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">Your safety is our top priority. All our guides are Uganda Wildlife Authority certified, our vehicles are regularly maintained, and we carry comprehensive insurance.</p>
            </div>

            <div class="flex flex-col items-center text-center group p-4 sm:p-6 rounded-xl hover:bg-gray-50 transition-colors duration-300">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform shadow-md flex-shrink-0">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3">Small Group Safari Experiences</h3>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">We keep groups small to ensure personalised attention, better wildlife viewing, and minimal environmental impact on Uganda's national parks.</p>
            </div>

            <div class="flex flex-col items-center text-center group p-4 sm:p-6 rounded-xl hover:bg-gray-50 transition-colors duration-300">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform shadow-md flex-shrink-0">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3">Community Impact</h3>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">Every safari supports local Ugandan communities through employment, fair wages, and community development projects. Travel with purpose and positive impact.</p>
            </div>

            <div class="flex flex-col items-center text-center group p-4 sm:p-6 rounded-xl hover:bg-gray-50 transition-colors duration-300">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform shadow-md flex-shrink-0">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3">Guaranteed Departures</h3>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">Once you book your Uganda safari, your departure is guaranteed regardless of group size. No last-minute cancellations or disappointments.</p>
            </div>

            <div class="flex flex-col items-center text-center group p-4 sm:p-6 rounded-xl hover:bg-gray-50 transition-colors duration-300">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform shadow-md flex-shrink-0">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3">24/7 Safari Support</h3>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">Our dedicated support team is available around the clock before, during, and after your Uganda safari. Peace of mind guaranteed throughout your journey.</p>
            </div>

            <div class="flex flex-col items-center text-center group p-4 sm:p-6 rounded-xl hover:bg-gray-50 transition-colors duration-300">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-full flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform shadow-md flex-shrink-0">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3">Best Value Promise</h3>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">Competitive pricing without compromising quality. Uganda gorilla trekking packages from $1,300 per person including permits, accommodation and transport.</p>
            </div>

        </div>
    </div>
</section>

<!-- ── CTA ── -->
<section class="relative py-12 sm:py-16 lg:py-24 overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('images/sun.webp') }}" alt="Safari sunset" class="w-full h-full object-cover object-center" loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-r from-black/65 via-black/45 to-black/55"></div>
    </div>
    <div class="relative max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4 sm:mb-6 leading-tight">
            Ready to Begin Your African Adventure?
        </h2>
        <p class="text-sm sm:text-base md:text-lg text-gray-100/90 mb-2 max-w-3xl mx-auto leading-relaxed">
            Our safari experts are ready to help you plan the perfect gorilla trekking or wildlife adventure.
            Packages from <strong>$1,300 per person</strong> including permits, accommodation and transport.
        </p>
        <p class="text-xs text-green-300 mb-6 sm:mb-8">We reply within 2 hours — call or WhatsApp us now.</p>
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center items-center">
            <a href="{{ route('custom-tour-requests.create') }}"
               class="w-full sm:w-auto bg-white/95 hover:bg-white text-gray-900 px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-bold text-base sm:text-lg transition-all hover:scale-[1.02] shadow-lg text-center">
                Plan My Safari — Free Quote
            </a>
            <a href="tel:+256752088768"
               class="w-full sm:w-auto border-2 border-white text-white hover:bg-white hover:text-green-700 px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-bold text-base sm:text-lg transition-all text-center">
                Call: +256 752 088 768
            </a>
            <a href="https://wa.me/256752088768"
               class="w-full sm:w-auto bg-green-500 hover:bg-green-400 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-bold text-base sm:text-lg transition-all flex items-center justify-center gap-2 shadow-lg">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/></svg>
                WhatsApp Chat
            </a>
        </div>
    </div>
</section>

@include('partials.partners')
@endsection

@push('styles')
<style>
    /* ── Global smooth scroll ── */
    html {
        scroll-behavior: smooth;
    }

    /* ── Hero: allow the overlap card to bleed out of the section ── */
    .hero-section {
        overflow: visible;
    }

    /* ════════════════════════════════════════════════
       PARALLAX — desktop only
       background-attachment:fixed makes the image stay
       in place while the page scrolls over it.
       On iOS/mobile, fixed backgrounds are broken inside
       overflow:hidden containers, so we fall back to
       background-attachment:scroll via the media query.
    ════════════════════════════════════════════════ */
    .hero-bg-layer {
        background-attachment: fixed;
        will-change: background-position; /* hint the GPU */
    }

    /* iOS / Android: disable fixed attachment — scroll instead */
    @media (max-width: 768px), (hover: none) {
        .hero-bg-layer {
            background-attachment: scroll;
        }
    }

    /* ── Hero text entrance animations ── */
    @keyframes heroFadeUp {
        from { opacity: 0; transform: translateY(28px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .hero-h1   { animation: heroFadeUp .75s cubic-bezier(.22,1,.36,1) .15s both; }
    .hero-sub  { animation: heroFadeUp .75s cubic-bezier(.22,1,.36,1) .35s both; }
    .hero-btns { animation: heroFadeUp .75s cubic-bezier(.22,1,.36,1) .55s both; }

    /* ── Overlap card entrance ── */
    @keyframes cardRise {
        from { opacity: 0; transform: translate(-50%, 80px); }
        to   { opacity: 1; transform: translate(-50%, 60px); }
    }
    .overlap-card {
        animation: cardRise .9s cubic-bezier(.22,1,.36,1) .8s both;
    }

    /* ── Scroll-reveal for sections below the hero ── */
    .reveal {
        opacity: 0;
        transform: translateY(32px);
        transition: opacity .65s cubic-bezier(.22,1,.36,1), transform .65s cubic-bezier(.22,1,.36,1);
    }
    .reveal.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* ── Misc ── */
    .line-clamp-3 { display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }

    @keyframes fadeInUp {
        from { opacity:0; transform:translateY(20px); }
        to   { opacity:1; transform:translateY(0); }
    }
    .animate-fade-in-up  { animation: fadeInUp 0.7s ease-out both; }
    .animation-delay-300 { animation-delay: .3s; }
    .animation-delay-600 { animation-delay: .6s; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ════════════════════════════════════════════════
       1.  Hero Carousel
    ════════════════════════════════════════════════ */
    const slides = document.querySelectorAll('.hero-slide');
    const dots   = document.querySelectorAll('.hero-dot');
    let current  = 0;

    function goTo(n) {
        slides.forEach((s, i) => s.style.opacity = i === n ? '1' : '0');
        dots.forEach((d, i)   => d.style.opacity = i === n ? '1' : '0.5');
        current = n;
    }
    dots.forEach((d, i) => d.addEventListener('click', () => goTo(i)));
    setInterval(() => goTo((current + 1) % slides.length), 6000);


    /* ════════════════════════════════════════════════
       2.  Smooth scroll — offset by fixed nav height
    ════════════════════════════════════════════════ */
    function getNavHeight() {
        return parseInt(
            getComputedStyle(document.documentElement)
                .getPropertyValue('--nav-height')
        ) || 70;
    }

    function smoothScrollTo(targetEl) {
        if (!targetEl) return;
        const top = targetEl.getBoundingClientRect().top + window.scrollY - getNavHeight();
        window.scrollTo({ top, behavior: 'smooth' });
    }

    document.querySelectorAll('a[href^="#"]').forEach(function (a) {
        a.addEventListener('click', function (e) {
            const id = this.getAttribute('href');
            if (id === '#') return;
            const target = document.querySelector(id);
            if (!target) return;
            e.preventDefault();
            smoothScrollTo(target);
        });
    });


    /* ════════════════════════════════════════════════
       3.  Scroll-reveal for sections below the hero
    ════════════════════════════════════════════════ */
    document.querySelectorAll('body section, body > .bg-green-700').forEach(el => {
        if (el.classList.contains('hero-section')) return;
        el.classList.add('reveal');
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.08 });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

});
</script>
@endpush