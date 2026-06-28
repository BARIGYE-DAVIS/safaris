@extends('layouts.app')

@section('title', 'Explore Safari Destinations in East Africa')
@section('meta_description', 'Discover amazing safari destinations across Uganda, Kenya, Tanzania, and Rwanda. Explore national parks, wildlife reserves, and breathtaking landscapes.')

@section('content')

{{-- ═══════════════════════════════════════════════════════
     HERO — Parallax Slideshow
═══════════════════════════════════════════════════════ --}}
<div class="relative hero-section bg-gray-900 overflow-hidden" role="banner">
    <div class="slideshow-container absolute inset-0">
        @php
            $heroDestinations = \App\Models\Destination::where('is_active', true)
                ->whereNotNull('featured_image')
                ->orWhereNotNull('image')
                ->inRandomOrder()
                ->limit(5)
                ->get();

            if($heroDestinations->isEmpty()) {
                $heroDestinations = $popularDestinations->take(5);
            }
        @endphp

        @foreach($heroDestinations as $index => $heroDestination)
        <div class="slide {{ $index === 0 ? 'slide-active' : '' }} absolute inset-0">
            <div class="parallax-bg absolute inset-0" style="top: -30%; height: 160%;">
                @if($heroDestination->featured_image)
                    <img src="{{ asset('storage/' . $heroDestination->featured_image) }}"
                         alt="Safari at {{ $heroDestination->name }}, {{ $heroDestination->country->name ?? 'East Africa' }} — wildlife destination for international tourists"
                         class="parallax-img w-full h-full object-cover">
                @elseif($heroDestination->image)
                    <img src="{{ asset('storage/' . $heroDestination->image) }}"
                         alt="Safari at {{ $heroDestination->name }}, {{ $heroDestination->country->name ?? 'East Africa' }} — wildlife destination for international tourists"
                         class="parallax-img w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-green-600 via-teal-600 to-blue-600"></div>
                @endif
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/50 to-black/20"></div>

            <div class="absolute inset-0 flex flex-col justify-end">
                <div class="container mx-auto px-4 pb-12 sm:pb-16 md:pb-20">
                    <div class="max-w-2xl">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="bg-green-600/90 backdrop-blur-sm text-white px-3 py-1.5 rounded-full text-xs sm:text-sm font-semibold">
                                {{ $heroDestination->country->flag_icon ?? '🌍' }} {{ $heroDestination->country->name ?? 'East Africa' }}
                            </span>
                            @if($heroDestination->type)
                            <span class="bg-white/20 backdrop-blur-sm text-white px-3 py-1.5 rounded-full text-xs sm:text-sm font-medium">
                                {{ $heroDestination->type }}
                            </span>
                            @endif
                        </div>
                        <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-3 drop-shadow-lg leading-tight">
                            {{ $heroDestination->name }}
                        </h2>
                        <p class="text-sm sm:text-base md:text-lg text-gray-200 mb-5 line-clamp-2 max-w-xl">
                            {{ $heroDestination->description }}
                        </p>
                        <a href="{{ route('destinations.show', $heroDestination->slug) }}"
                           class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-5 py-3 sm:px-7 sm:py-3.5 rounded-lg text-sm sm:text-base font-semibold transition transform hover:scale-105 shadow-lg">
                            Explore {{ $heroDestination->name }}
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <button class="slide-control prev absolute left-3 sm:left-5 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 backdrop-blur-sm text-white p-2.5 sm:p-3.5 rounded-full transition z-10" aria-label="Previous slide">
        <i class="fas fa-chevron-left text-sm sm:text-base text-white"></i>
    </button>
    <button class="slide-control next absolute right-3 sm:right-5 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 backdrop-blur-sm text-white p-2.5 sm:p-3.5 rounded-full transition z-10" aria-label="Next slide">
        <i class="fas fa-chevron-right text-sm sm:text-base text-white"></i>
    </button>

    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-10" role="tablist" aria-label="Slideshow navigation">
        @foreach($heroDestinations as $index => $dest)
        <button class="slide-indicator h-2 rounded-full bg-white/40 hover:bg-white transition-all duration-300 {{ $index === 0 ? 'active' : '' }}"
                data-slide="{{ $index }}"
                aria-label="Go to slide {{ $index + 1 }}: {{ $dest->name }}"></button>
        @endforeach
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     SEARCH BAR
═══════════════════════════════════════════════════════ --}}
<div class="bg-gray-50 border-b border-gray-200 py-5 px-4">
    <div class="container mx-auto max-w-3xl">
        <form method="GET" action="{{ route('destinations.index') }}"
              class="flex flex-col sm:flex-row gap-2 sm:gap-3"
              role="search"
              aria-label="Search safari destinations">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none" aria-hidden="true"></i>
                <input type="text" name="search"
                       placeholder="Search destinations, parks, countries..."
                       value="{{ request('search') }}"
                       aria-label="Search destinations"
                       class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:outline-none text-gray-800 text-sm sm:text-base bg-white shadow-sm">
            </div>
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 px-6 py-3 rounded-xl font-semibold transition text-white text-sm sm:text-base whitespace-nowrap flex items-center justify-center gap-2 shadow-sm">
                <i class="fas fa-search" aria-hidden="true"></i>
                <span>Search</span>
            </button>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     FILTERS
═══════════════════════════════════════════════════════ --}}
<div class="bg-white shadow-md sticky top-0 z-40 border-b">
    <div class="container mx-auto px-4 py-3 sm:py-4">
        <form method="GET" action="{{ route('destinations.index') }}"
              class="flex flex-wrap gap-2 sm:gap-4 items-center"
              aria-label="Filter destinations">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            <span class="hidden sm:inline-flex items-center text-gray-500 text-sm font-medium">
                <i class="fas fa-filter mr-2 text-green-600" aria-hidden="true"></i> Filter:
            </span>

            <select name="country"
                    aria-label="Filter by country"
                    class="flex-1 min-w-0 sm:flex-none px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 bg-white"
                    onchange="this.form.submit()">
                <option value="">🌍 All Countries</option>
                @foreach($countries as $country)
                    <option value="{{ $country->code }}" {{ request('country') == $country->code ? 'selected' : '' }}>
                        {{ $country->flag_icon }} {{ $country->name }}
                    </option>
                @endforeach
            </select>

            <select name="popular"
                    aria-label="Filter by popularity"
                    class="flex-1 min-w-0 sm:flex-none px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 bg-white"
                    onchange="this.form.submit()">
                <option value="">All Destinations</option>
                <option value="1" {{ request('popular') == '1' ? 'selected' : '' }}>⭐ Popular Only</option>
            </select>

            @if(request()->hasAny(['search', 'country', 'popular']))
                <a href="{{ route('destinations.index') }}"
                   class="text-red-600 hover:text-red-800 font-semibold px-3 py-2 text-sm border border-red-200 rounded-lg hover:bg-red-50 transition whitespace-nowrap">
                    <i class="fas fa-times mr-1" aria-hidden="true"></i> Clear
                </a>
            @endif

            <div class="ml-auto text-gray-600 text-xs sm:text-sm font-medium hidden md:block whitespace-nowrap">
                <i class="fas fa-map-marker-alt text-green-600 mr-1" aria-hidden="true"></i>
                {{ $destinations->total() }} Destination{{ $destinations->total() != 1 ? 's' : '' }} Found
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     COUNTRY QUICK-LINKS — internal link signals + UX
═══════════════════════════════════════════════════════ --}}
@if(!request()->hasAny(['search', 'country', 'popular']))
<nav class="bg-white border-b py-4 px-4" aria-label="Browse destinations by country">
    <div class="container mx-auto max-w-5xl">
        <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold mb-3 text-center">Browse by Country</p>
        <div class="flex flex-wrap justify-center gap-2 sm:gap-3">
            @foreach($countries as $country)
            <a href="{{ route('destinations.index', ['country' => $country->code]) }}"
               class="inline-flex items-center gap-2 bg-gray-50 hover:bg-green-50 border border-gray-200 hover:border-green-400 text-gray-700 hover:text-green-700 px-4 py-2 rounded-full text-sm font-semibold transition-all duration-200">
                <span>{{ $country->flag_icon }}</span>
                <span>{{ $country->name }} Safari</span>
            </a>
            @endforeach
        </div>
    </div>
</nav>
@endif

{{-- ═══════════════════════════════════════════════════════
     POPULAR DESTINATIONS
═══════════════════════════════════════════════════════ --}}
@if(!request()->hasAny(['search', 'country', 'popular']) && $popularDestinations->count() > 0)
<section class="bg-gradient-to-b from-green-50 to-white py-12 sm:py-16" aria-labelledby="popular-heading">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8 sm:mb-12">
            <span class="inline-block bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-xs sm:text-sm font-semibold mb-3">
                ⭐ Most Loved by International Travellers
            </span>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 mb-3">Popular Destinations</h2>
            <p class="text-gray-600 text-base sm:text-lg max-w-2xl mx-auto">Handpicked favorites by thousands of travelers</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-8">
            @foreach($popularDestinations as $destination)
            <article>
                <a href="{{ route('destinations.show', $destination->slug) }}" class="group block h-full">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-[1.02] hover:shadow-2xl h-full flex flex-col">
                        <div class="relative h-56 sm:h-64 md:h-72 overflow-hidden bg-gray-200 flex-shrink-0">
                            @if($destination->featured_image)
                                <img src="{{ asset('storage/' . $destination->featured_image) }}"
                                     alt="{{ $destination->name }} safari — wildlife destination in {{ $destination->country->name ?? 'East Africa' }} for international tourists"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                                     loading="lazy">
                            @elseif($destination->image)
                                <img src="{{ asset('storage/' . $destination->image) }}"
                                     alt="{{ $destination->name }} safari — wildlife destination in {{ $destination->country->name ?? 'East Africa' }} for international tourists"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                                     loading="lazy">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-green-400 via-teal-500 to-blue-500 flex items-center justify-center">
                                    <i class="fas fa-map-marker-alt text-white text-6xl opacity-50" aria-hidden="true"></i>
                                </div>
                            @endif

                            <div class="absolute top-3 right-3">
                                <span class="bg-yellow-500 text-white px-2.5 py-1 rounded-full text-xs font-bold shadow-lg flex items-center gap-1">
                                    <i class="fas fa-star text-xs" aria-hidden="true"></i> Popular
                                </span>
                            </div>

                            @if($destination->type)
                            <div class="absolute bottom-3 left-3">
                                <span class="bg-white/90 backdrop-blur-sm text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ $destination->type }}
                                </span>
                            </div>
                            @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <span class="mr-1">{{ $destination->country->flag_icon }}</span>
                                {{ $destination->country->name }}
                            </span>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2 group-hover:text-green-600 transition line-clamp-1">
                            {{ $destination->name }}
                        </h3>
                        <p class="text-gray-600 text-sm line-clamp-3 mb-4 flex-1">
                            {{ $destination->description }}
                        </p>
                        <div class="flex items-center justify-between pt-3 border-t mt-auto">
                            <div class="flex items-center text-green-600 font-semibold text-sm">
                                Explore Now
                                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════
     ALL DESTINATIONS GRID
═══════════════════════════════════════════════════════ --}}
<main id="all-destinations" class="container mx-auto px-4 py-10 sm:py-16">
    @if(!request()->hasAny(['search', 'country', 'popular']))
        <div class="text-center mb-8 sm:mb-12">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 mb-3">All Destinations</h2>
            <p class="text-gray-600 text-base sm:text-lg">Explore our complete collection of safari destinations</p>
        </div>
    @else
        <div class="mb-6 sm:mb-8">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-2">
                @if(request('country'))
                    <span>{{ $countries->firstWhere('code', request('country'))->flag_icon ?? '' }}</span>
                    Safari Destinations in {{ $countries->firstWhere('code', request('country'))->name ?? 'Selected Country' }}
                @elseif(request('popular'))
                    ⭐ Top-Rated Safari Destinations
                @elseif(request('search'))
                    Results for "{{ request('search') }}"
                @endif
            </h2>
            <p class="text-gray-600 text-sm sm:text-base">{{ $destinations->total() }} destination(s) found</p>
        </div>
    @endif

    @if($destinations->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
            @foreach($destinations as $destination)
            <article>
                <a href="{{ route('destinations.show', $destination->slug) }}" class="group block h-full">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden transform transition duration-300 hover:scale-[1.02] hover:shadow-xl h-full flex flex-col">
                        <div class="relative h-48 sm:h-52 md:h-56 overflow-hidden bg-gradient-to-br from-gray-200 to-gray-300 flex-shrink-0">
                            @if($destination->featured_image)
                                <img src="{{ asset('storage/' . $destination->featured_image) }}"
                                     alt="{{ $destination->name }} — {{ $destination->type ?? 'safari destination' }} in {{ $destination->country->name ?? 'East Africa' }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                                     loading="lazy">
                            @elseif($destination->image)
                                <img src="{{ asset('storage/' . $destination->image) }}"
                                     alt="{{ $destination->name }} — {{ $destination->type ?? 'safari destination' }} in {{ $destination->country->name ?? 'East Africa' }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                                     loading="lazy">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-green-400 via-teal-500 to-blue-500 flex items-center justify-center">
                                    <div class="text-center text-white">
                                        <i class="fas fa-map-marked-alt text-4xl sm:text-5xl mb-2 opacity-70" aria-hidden="true"></i>
                                        <p class="text-xs sm:text-sm font-semibold opacity-90 px-2">{{ $destination->name }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="absolute top-3 left-3 flex flex-col gap-1.5">
                                @if($destination->is_popular)
                                <span class="bg-yellow-500 text-white px-2 py-0.5 rounded-full text-xs font-bold shadow-md">
                                    <i class="fas fa-star text-xs" aria-hidden="true"></i>
                                </span>
                                @endif
                            </div>

                            @if($destination->type)
                            <div class="absolute bottom-2 right-2">
                                <span class="bg-white/90 backdrop-blur-sm text-gray-800 px-2.5 py-1 rounded-lg text-xs font-semibold shadow-md">
                                    {{ $destination->type }}
                                </span>
                            </div>
                            @endif
                        </div>

                    <div class="p-4 sm:p-5 flex-1 flex flex-col">
                        <div class="flex items-center mb-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                {{ $destination->country->flag_icon }} {{ $destination->country->name }}
                            </span>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-2 group-hover:text-green-600 transition line-clamp-2">
                            {{ $destination->name }}
                        </h3>
                        <p class="text-gray-600 text-xs sm:text-sm line-clamp-3 mb-3 flex-1">
                            {{ Str::limit($destination->description, 100) }}
                        </p>
                        <div class="flex items-center text-green-600 text-xs sm:text-sm font-semibold pt-3 border-t mt-auto">
                            View Details
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition"></i>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-8 sm:mt-12" id="pagination-links">
            {{ $destinations->appends(request()->query())->links() }}
        </div>
    @else
        <div class="text-center py-16 sm:py-20 bg-white rounded-2xl shadow-md">
            <div class="inline-block p-6 sm:p-8 bg-gray-100 rounded-full mb-5">
                <i class="fas fa-search text-gray-400 text-5xl sm:text-6xl" aria-hidden="true"></i>
            </div>
            <h3 class="text-2xl sm:text-3xl font-bold text-gray-700 mb-3">No destinations found</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto text-sm sm:text-base px-4">
                We couldn't find any destinations matching your criteria. Try adjusting your filters or browse all East Africa safari destinations below.
            </p>
            <a href="{{ route('destinations.index') }}"
               class="bg-green-600 hover:bg-green-700 text-white px-7 py-3.5 rounded-lg inline-flex items-center font-semibold transition shadow-md text-sm sm:text-base">
                <i class="fas fa-home mr-2" aria-hidden="true"></i> View All Destinations
            </a>
        </div>
    @endif
</div>

        <div class="space-y-4" id="faqAccordion">

            @php
            $faqs = [
                [
                    'q' => 'What are the best safari destinations in East Africa?',
                    'a' => 'East Africa\'s top safari destinations include Uganda\'s Bwindi Impenetrable Forest (mountain gorillas), Tanzania\'s Serengeti (Great Migration & Big Five), Kenya\'s Masai Mara (lions, cheetahs, wildebeest crossing), Rwanda\'s Volcanoes National Park (mountain gorillas) and Uganda\'s Queen Elizabeth National Park (tree-climbing lions, hippos, chimpanzees). Each destination offers a unique wildlife experience tailored to international visitors.',
                ],
                [
                    'q' => 'When is the best time to visit East Africa for a safari?',
                    'a' => 'The best time for an East Africa safari depends on your experience. For the Great Wildebeest Migration (Serengeti/Masai Mara), July to October is peak season. For gorilla trekking in Uganda and Rwanda, June–September and December–February offer drier conditions and clearer forest trails. For birding and lush landscapes, the green season (November–May) is excellent. East Africa is a year-round safari destination.',
                ],
                [
                    'q' => 'How do tourists from the USA, UK and Europe travel to East Africa?',
                    'a' => 'From the USA, fly into Entebbe International Airport (Uganda), Jomo Kenyatta International Airport (Nairobi, Kenya), or Kilimanjaro International Airport (Tanzania) via hubs like London Heathrow, Amsterdam, Dubai or Doha. From the UK, there are direct flights to Nairobi and Entebbe. From Europe (Germany, France, Netherlands, Italy, Spain), connect through Dubai, Doha or Istanbul. Flight times range from 9 to 14 hours depending on origin.',
                ],
                [
                    'q' => 'Do I need a visa to visit Uganda, Kenya, Tanzania or Rwanda?',
                    'a' => 'Most international travellers can obtain a visa on arrival or an e-Visa online. Uganda, Kenya, Rwanda and Tanzania offer East Africa Tourist Visas, allowing entry to multiple countries on a single permit — ideal for multi-country safari itineraries. Citizens from the USA, UK, Canada, Australia, EU countries and most other nations qualify. Always check your country\'s specific requirements before travel.',
                ],
                [
                    'q' => 'Is East Africa safe for international tourists?',
                    'a' => 'Yes. Uganda, Kenya, Tanzania and Rwanda are well-established, tourist-friendly safari destinations visited annually by hundreds of thousands of international travellers. National parks are staffed by professional rangers and guides. Our team provides full pre-trip safety briefings, accompanies all guests on game drives and treks, and maintains 24/7 emergency support throughout your safari.',
                ],
                [
                    'q' => 'What vaccinations and health precautions are needed for an East Africa safari?',
                    'a' => 'Recommended vaccinations include Yellow Fever (required for Uganda and some other East African countries), Typhoid, Hepatitis A & B, and Tetanus. Anti-malaria medication is strongly advised. Consult your GP or a travel health clinic at least 6–8 weeks before departure. Gorilla trekking requires visitors to be symptom-free of respiratory illnesses to protect the gorillas.',
                ],
            ];
            @endphp

            @foreach($faqs as $fi => $faq)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <button class="faq-toggle w-full px-5 sm:px-6 py-4 text-left flex items-center justify-between gap-4 hover:bg-gray-50 transition focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500"
                        aria-expanded="false"
                        aria-controls="faq-panel-{{ $fi }}"
                        id="faq-btn-{{ $fi }}"
                        type="button">
                    <span class="font-semibold text-gray-900 text-sm sm:text-base pr-2">{{ $faq['q'] }}</span>
                    <span class="faq-icon flex-shrink-0 w-7 h-7 rounded-full border-2 border-gray-300 flex items-center justify-center relative transition-colors duration-300">
                        <span class="faq-h absolute w-3 h-0.5 bg-gray-500 rounded transition-colors duration-300"></span>
                        <span class="faq-v absolute w-0.5 h-3 bg-gray-500 rounded transition-all duration-300 origin-center"></span>
                    </span>
                </button>
                <div id="faq-panel-{{ $fi }}"
                     role="region"
                     aria-labelledby="faq-btn-{{ $fi }}"
                     class="faq-panel"
                     style="max-height:0;overflow:hidden;opacity:0;transition:max-height 0.4s cubic-bezier(0.4,0,0.2,1),opacity 0.3s ease;">
                    <div class="px-5 sm:px-6 pb-5 pt-2 text-gray-600 text-sm sm:text-base leading-relaxed border-t border-gray-100">
                        {{ $faq['a'] }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════
     CALL TO ACTION
═══════════════════════════════════════════════════════ --}}
<section class="bg-gradient-to-r from-green-600 via-teal-600 to-blue-600 text-white py-12 sm:py-16" aria-labelledby="cta-heading">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl sm:text-4xl font-bold mb-3 sm:mb-4">Ready to Start Your Adventure?</h2>
        <p class="text-base sm:text-xl mb-6 sm:mb-8 max-w-2xl mx-auto opacity-90">Let our expert guides help you plan the perfect East African safari experience</p>
        <div class="flex flex-col sm:flex-row justify-center items-center gap-3 sm:gap-4">
            <a href="{{ route('contact') }}"
               class="w-full sm:w-auto bg-white text-green-600 px-7 py-3.5 rounded-lg font-bold hover:bg-gray-100 transition shadow-lg text-sm sm:text-base text-center">
                <i class="fas fa-envelope mr-2" aria-hidden="true"></i> Contact Us
            </a>
            <a href="{{ route('tours.index') }}"
               class="w-full sm:w-auto bg-transparent border-2 border-white text-white px-7 py-3.5 rounded-lg font-bold hover:bg-white hover:text-green-600 transition text-sm sm:text-base text-center">
                <i class="fas fa-binoculars mr-2" aria-hidden="true"></i> Browse Safari Tours
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script>
/* ── Slideshow ────────────────────────────────────────────── */
(function () {
    const slides     = Array.from(document.querySelectorAll('.slide'));
    const indicators = Array.from(document.querySelectorAll('.slide-indicator'));
    const total      = slides.length;
    if (total === 0) return;

    let current = 0, timer = null;

    function goTo(idx) {
        idx = ((idx % total) + total) % total;
        slides[current].classList.remove('slide-active');
        if (indicators[current]) indicators[current].classList.remove('active');
        current = idx;
        slides[current].classList.add('slide-active');
        if (indicators[current]) indicators[current].classList.add('active');
    }
    function startAutoplay() { stopAutoplay(); timer = setInterval(() => goTo(current + 1), 5000); }
    function stopAutoplay()  { if (timer) { clearInterval(timer); timer = null; } }

    document.querySelector('.slide-control.next')?.addEventListener('click', () => { goTo(current + 1); startAutoplay(); });
    document.querySelector('.slide-control.prev')?.addEventListener('click', () => { goTo(current - 1); startAutoplay(); });
    indicators.forEach((dot, i) => dot.addEventListener('click', () => { goTo(i); startAutoplay(); }));
    document.querySelector('.slideshow-container')?.addEventListener('mouseenter', stopAutoplay);
    document.querySelector('.slideshow-container')?.addEventListener('mouseleave', startAutoplay);

    goTo(0); startAutoplay();
})();

/* ── Parallax ────────────────────────────────────────────── */
(function () {
    const heroSection = document.querySelector('.hero-section');
    if (!heroSection) return;
    const isMobile = window.matchMedia('(max-width: 768px)').matches || /iPad|iPhone|iPod|Android/.test(navigator.userAgent);
    if (isMobile) return;
    const parallaxBgs = document.querySelectorAll('.parallax-bg');
    function applyParallax() {
        const heroRect = heroSection.getBoundingClientRect();
        const heroH    = heroSection.offsetHeight;
        if (heroRect.bottom < 0 || heroRect.top > window.innerHeight) return;
        const scrollProgress = Math.max(0, -heroRect.top) / heroH;
        parallaxBgs.forEach(bg => { bg.style.transform = `translateY(${scrollProgress * 30}%)`; });
    }
    window.addEventListener('scroll', applyParallax, { passive: true });
    applyParallax();
})();

/* ── Pagination scroll ───────────────────────────────────── */
(function () {
    const section = document.getElementById('all-destinations');
    if (!section) return;
    if (new URLSearchParams(window.location.search).has('page')) {
        setTimeout(() => section.scrollIntoView({ behavior: 'smooth', block: 'start' }), 120);
    }
    const paginationWrap = document.getElementById('pagination-links');
    if (!paginationWrap) return;
    paginationWrap.addEventListener('click', function (e) {
        const link = e.target.closest('a[href]');
        if (!link) return;
        e.preventDefault();
        window.location.href = new URL(link.href).toString();
    });
})();

/* ── FAQ Accordion ───────────────────────────────────────── */
(function () {
    const toggles = document.querySelectorAll('.faq-toggle');

    function openFaq(btn) {
        const panel = document.getElementById(btn.getAttribute('aria-controls'));
        if (!panel) return;
        btn.setAttribute('aria-expanded', 'true');
        btn.querySelector('.faq-icon').style.borderColor = '#16a34a';
        btn.querySelector('.faq-h').style.backgroundColor = '#16a34a';
        btn.querySelector('.faq-v').style.transform = 'translate(-50%, -50%) scaleY(0)';
        btn.querySelector('.faq-v').style.opacity = '0';
        panel.style.maxHeight = panel.scrollHeight + 'px';
        panel.style.opacity = '1';
    }

    function closeFaq(btn) {
        const panel = document.getElementById(btn.getAttribute('aria-controls'));
        if (!panel) return;
        btn.setAttribute('aria-expanded', 'false');
        btn.querySelector('.faq-icon').style.borderColor = '';
        btn.querySelector('.faq-h').style.backgroundColor = '';
        btn.querySelector('.faq-v').style.transform = '';
        btn.querySelector('.faq-v').style.opacity = '';
        panel.style.maxHeight = '0';
        panel.style.opacity = '0';
    }

    toggles.forEach(btn => {
        btn.addEventListener('click', function () {
            const isOpen = this.getAttribute('aria-expanded') === 'true';
            if (isOpen) {
                closeFaq(this);
            } else {
                toggles.forEach(o => { if (o !== this) closeFaq(o); });
                openFaq(this);
            }
        });
    });
})();
</script>

<style>
/* Hero height */
.hero-section {
    height: 75vw; min-height: 340px; max-height: 600px;
}
@media (min-width: 640px) {
    .hero-section { height: 60vw; min-height: 420px; max-height: 680px; }
}
@media (min-width: 1024px) {
    .hero-section { height: 80vh; min-height: 520px; max-height: 800px; }
}
.parallax-bg { will-change: transform; transition: transform 0.05s linear; }
.slide { opacity: 0; transition: opacity 1s ease-in-out; pointer-events: none; }
.slide.slide-active { opacity: 1; pointer-events: auto; }
.slide-indicator { width: 8px; background-color: rgba(255,255,255,0.4); transition: width 0.3s ease, background-color 0.3s ease; }
.slide-indicator.active { width: 28px; background-color: white !important; }
.line-clamp-1 { display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden; }
.line-clamp-2 { display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden; }
.line-clamp-3 { display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden; }

/* FAQ icon */
.faq-v {
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%, -50%) scaleY(1);
    transition: transform 0.3s ease, opacity 0.3s ease, background-color 0.25s ease;
    opacity: 1;
}
.faq-h {
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    transition: background-color 0.25s ease;
}
.faq-panel { will-change: max-height, opacity; }
</style>
@endpush

@endsection