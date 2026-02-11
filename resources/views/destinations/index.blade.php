@extends('layouts.app')

@section('title', 'Explore Safari Destinations in East Africa')
@section('meta_description', 'Discover amazing safari destinations across Uganda, Kenya, Tanzania, and Rwanda. Explore national parks, wildlife reserves, and breathtaking landscapes.')

@section('content')
<!-- Hero Section with Slideshow -->
<div class="relative h-[70vh] md:h-[80vh] bg-gray-900 overflow-hidden">
    <!-- Slideshow Container -->
    <div class="slideshow-container absolute inset-0">
        @php
            $heroDestinations = \App\Models\Destination::where('is_active', true)
                ->whereNotNull('featured_image')
                ->orWhereNotNull('image')
                ->inRandomOrder()
                ->limit(5)
                ->get();
            
            // Fallback if no destinations with images
            if($heroDestinations->isEmpty()) {
                $heroDestinations = $popularDestinations->take(5);
            }
        @endphp

        @foreach($heroDestinations as $index => $heroDestination)
        <div class="slide {{ $index === 0 ? 'active' : '' }} absolute inset-0 opacity-0 transition-opacity duration-1000">
            <!-- Background Image -->
            @if($heroDestination->featured_image)
                <img src="{{ asset('storage/' . $heroDestination->featured_image) }}" 
                     alt="{{ $heroDestination->name }}" 
                     class="absolute inset-0 w-full h-full object-cover">
            @elseif($heroDestination->image)
                <img src="{{ asset('storage/' . $heroDestination->image) }}" 
                     alt="{{ $heroDestination->name }}" 
                     class="absolute inset-0 w-full h-full object-cover">
            @else
                <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-green-600 via-teal-600 to-blue-600"></div>
            @endif
            
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-black/30"></div>
            
            <!-- Slide Content -->
            <div class="absolute inset-0 flex items-end">
                <div class="container mx-auto px-4 pb-16 md:pb-24">
                    <div class="max-w-3xl">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="bg-green-600/90 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-semibold">
                                {{ $heroDestination->country->flag_icon }} {{ $heroDestination->country->name }}
                            </span>
                            @if($heroDestination->type)
                            <span class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-medium">
                                {{ $heroDestination->type }}
                            </span>
                            @endif
                        </div>
                        <h2 class="text-4xl md:text-6xl font-bold text-white mb-4 drop-shadow-lg">
                            {{ $heroDestination->name }}
                        </h2>
                        <p class="text-lg md:text-xl text-gray-200 mb-6 line-clamp-2">
                            {{ $heroDestination->description }}
                        </p>
                        <a href="{{ route('destinations.show', $heroDestination->slug) }}" 
                           class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg font-semibold transition transform hover:scale-105 shadow-lg">
                            Explore {{ $heroDestination->name }}
                            <i class="fas fa-arrow-right ml-3"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Slideshow Controls -->
    <button class="slide-control prev absolute left-4 top-1/2 -translate-y-1/2 bg-indigo-600 hover:bg-white/50 backdrop-blur-sm text-white p-4 rounded-full transition z-10">
        <i class="fas fa-chevron-left text-white"></i>
    </button>
    <button class="slide-control next absolute right-4 top-1/2 -translate-y-1/2 bg-indigo-600 hover:bg-white/50 backdrop-blur-sm text-white p-4 rounded-full transition z-10">
        <i class="fas fa-chevron-right text-white"></i>
    </button>

    <!-- Slide Indicators -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-3 z-10">
        @foreach($heroDestinations as $index => $dest)
        <button class="slide-indicator w-3 h-3 rounded-full bg-indigo-600 hover:bg-white transition {{ $index === 0 ? 'active' : '' }}" 
                data-slide="{{ $index }}"></button>
        @endforeach
    </div>


</div>

<!-- Filters Section -->
<div class="bg-white shadow-md sticky top-0 z-40 border-b">
    <div class="container mx-auto px-4 py-4">
        <form method="GET" action="{{ route('destinations.index') }}" class="flex flex-wrap gap-4 items-center">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            <select name="country" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 bg-white" onchange="this.form.submit()">
                <option value="">🌍 All Countries</option>
                @foreach($countries as $country)
                    <option value="{{ $country->code }}" {{ request('country') == $country->code ? 'selected' : '' }}>
                        {{ $country->flag_icon }} {{ $country->name }}
                    </option>
                @endforeach
            </select>

            <select name="popular" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 bg-white" onchange="this.form.submit()">
                <option value="">All Destinations</option>
                <option value="1" {{ request('popular') == '1' ? 'selected' : '' }}>⭐ Popular Only</option>
            </select>

            @if(request()->hasAny(['search', 'country', 'popular']))
                <a href="{{ route('destinations.index') }}" class="text-red-600 hover:text-red-800 font-semibold px-4 py-2 border border-red-200 rounded-lg hover:bg-red-50 transition">
                    <i class="fas fa-times mr-2"></i> Clear Filters
                </a>
            @endif

            <div class="ml-auto text-gray-600 text-sm font-medium hidden md:block">
                <i class="fas fa-map-marker-alt text-green-600 mr-2"></i>
                {{ $destinations->total() }} Destination{{ $destinations->total() != 1 ? 's' : '' }} Found
            </div>
        </form>
    </div>
</div>

    <!-- Search Bar Overlay -->
    <div class="absolute hidden top-24 left-1/2 -translate-x-1/2 w-full max-w-3xl px-4 z-10 d-hidden md:block">
        <form method="GET" action="{{ route('destinations.index') }}" class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-4">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <i class="fas fa-search absolute text-gray-400"></i>
                    <input type="text" name="search" placeholder="Search destinations..." 
                        value="{{ request('search') }}"
                        class="w-full pl-12 pr-4 py-4 rounded-lg border-2 border-gray-200 focus:border-green-500 focus:outline-none text-gray-800">
                </div>
                <button type="submit" class="bg-green-600 hover:bg-green-700 px-8 py-4 rounded-lg font-semibold transition text-white whitespace-nowrap">
                    <i class="fas fa-search mr-2"></i> Search
                </button>
            </div>
        </form>
    </div>

<!-- Popular Destinations (if no filters) -->
@if(!request()->hasAny(['search', 'country', 'popular']) && $popularDestinations->count() > 0)
<div class="bg-gradient-to-b from-green-50 to-white py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="inline-block bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                ⭐ Most Loved
            </span>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Popular Destinations</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Handpicked favorites by thousands of travelers</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($popularDestinations as $destination)
            <a href="{{ route('destinations.show', $destination->slug) }}" class="group">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-2xl">
                    <div class="relative h-72 overflow-hidden bg-gray-200">
                        @if($destination->featured_image)
                            <img src="{{ asset('storage/' . $destination->featured_image) }}" 
                                 alt="{{ $destination->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        @elseif($destination->image)
                            <img src="{{ asset('storage/' . $destination->image) }}" 
                                 alt="{{ $destination->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-green-400 via-teal-500 to-blue-500 flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-white text-6xl opacity-50"></i>
                            </div>
                        @endif
                        
                        <div class="absolute top-4 right-4">
                            <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg flex items-center">
                                <i class="fas fa-star mr-1"></i> Popular
                            </span>
                        </div>

                        @if($destination->type)
                        <div class="absolute bottom-4 left-4">
                            <span class="bg-white/90 backdrop-blur-sm text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">
                                {{ $destination->type }}
                            </span>
                        </div>
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <span class="text-base mr-1">{{ $destination->country->flag_icon }}</span> 
                                {{ $destination->country->name }}
                            </span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-3 group-hover:text-green-600 transition line-clamp-1">
                            {{ $destination->name }}
                        </h3>
                        <p class="text-gray-600 line-clamp-3 mb-4">
                            {{ $destination->description }}
                        </p>
                        <div class="flex items-center justify-between pt-4 border-t">
                            <div class="flex items-center text-green-600 font-semibold">
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
</div>
@endif

<!-- All Destinations Grid -->
<div class="container mx-auto px-4 py-16">
    @if(!request()->hasAny(['search', 'country', 'popular']))
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">All Destinations</h2>
            <p class="text-gray-600 text-lg">Explore our complete collection of safari destinations</p>
        </div>
    @else
        <div class="mb-8">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">
                @if(request('country'))
                    <span class="text-2xl">{{ $countries->firstWhere('code', request('country'))->flag_icon ?? '' }}</span>
                    Destinations in {{ $countries->firstWhere('code', request('country'))->name ?? 'Selected Country' }}
                @elseif(request('popular'))
                    ⭐ Popular Destinations
                @elseif(request('search'))
                    Search Results for "{{ request('search') }}"
                @endif
            </h2>
            <p class="text-gray-600">{{ $destinations->total() }} destination(s) found</p>
        </div>
    @endif

    @if($destinations->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($destinations as $destination)
            <a href="{{ route('destinations.show', $destination->slug) }}" class="group">
                <div class="bg-white rounded-xl shadow-md overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl h-full flex flex-col">
                    <!-- Image with guaranteed display -->
                    <div class="relative h-56 overflow-hidden bg-gradient-to-br from-gray-200 to-gray-300">
                        @if($destination->featured_image)
                            <img src="{{ asset('storage/' . $destination->featured_image) }}" 
                                 alt="{{ $destination->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        @elseif($destination->image)
                            <img src="{{ asset('storage/' . $destination->image) }}" 
                                 alt="{{ $destination->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        @else
                            <!-- Fallback gradient with icon -->
                            <div class="w-full h-full bg-gradient-to-br from-green-400 via-teal-500 to-blue-500 flex items-center justify-center">
                                <div class="text-center text-white">
                                    <i class="fas fa-map-marked-alt text-5xl mb-2 opacity-70"></i>
                                    <p class="text-sm font-semibold opacity-90">{{ $destination->name }}</p>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Badges -->
                        <div class="absolute top-3 left-3 flex flex-col gap-2">
                            @if($destination->is_popular)
                            <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow-md">
                                <i class="fas fa-star"></i>
                            </span>
                            @endif
                        </div>

                        @if($destination->type)
                        <div class="absolute bottom-3 right-3">
                            <span class="bg-white/90 backdrop-blur-sm text-gray-800 px-3 py-1 rounded-lg text-xs font-semibold shadow-md">
                                {{ $destination->type }}
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-center mb-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                {{ $destination->country->flag_icon }} {{ $destination->country->name }}
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2 group-hover:text-green-600 transition line-clamp-2">
                            {{ $destination->name }}
                        </h3>
                        <p class="text-gray-600 text-sm line-clamp-3 mb-4 flex-1">
                            {{ Str::limit($destination->description, 100) }}
                        </p>
                        <div class="flex items-center text-green-600 text-sm font-semibold pt-3 border-t">
                            View Details 
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition"></i>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $destinations->appends(request()->query())->links() }}
        </div>
    @else
        <!-- No Results -->
        <div class="text-center py-20 bg-white rounded-2xl shadow-md">
            <div class="inline-block p-8 bg-gray-100 rounded-full mb-6">
                <i class="fas fa-search text-gray-400 text-6xl"></i>
            </div>
            <h3 class="text-3xl font-bold text-gray-700 mb-3">No destinations found</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">
                We couldn't find any destinations matching your criteria. Try adjusting your filters or search terms.
            </p>
            <a href="{{ route('destinations.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg inline-flex items-center font-semibold transition shadow-md">
                <i class="fas fa-home mr-2"></i> View All Destinations
            </a>
        </div>
    @endif
</div>

<!-- Call to Action -->
<div class="bg-gradient-to-r from-green-600 via-teal-600 to-blue-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold mb-4">Ready to Start Your Adventure?</h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto">Let our expert guides help you plan the perfect East African safari experience</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('contact') }}" class="bg-white text-green-600 px-8 py-4 rounded-lg font-bold hover:bg-gray-100 transition shadow-lg">
                <i class="fas fa-envelope mr-2"></i> Contact Us
            </a>
            <a href="{{ route('tours.index') }}" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-bold hover:bg-white hover:text-green-600 transition">
                <i class="fas fa-binoculars mr-2"></i> Browse Tours
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Slideshow functionality
let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const indicators = document.querySelectorAll('.slide-indicator');
const totalSlides = slides.length;

function showSlide(index) {
    slides.forEach(slide => {
        slide.classList.remove('active');
        slide.style.opacity = '0';
    });
    
    indicators.forEach(indicator => {
        indicator.classList.remove('active', 'bg-white');
        indicator.classList.add('bg-white/50');
    });
    
    if (slides[index]) {
        slides[index].classList.add('active');
        slides[index].style.opacity = '1';
    }
    
    if (indicators[index]) {
        indicators[index].classList.add('active', 'bg-white');
        indicators[index].classList.remove('bg-white/50');
    }
    
    currentSlide = index;
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    showSlide(currentSlide);
}

function prevSlide() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    showSlide(currentSlide);
}

// Auto-play slideshow
let autoplay = setInterval(nextSlide, 5000);

// Control buttons
document.querySelector('.slide-control.next')?.addEventListener('click', () => {
    clearInterval(autoplay);
    nextSlide();
    autoplay = setInterval(nextSlide, 5000);
});

document.querySelector('.slide-control.prev')?.addEventListener('click', () => {
    clearInterval(autoplay);
    prevSlide();
    autoplay = setInterval(nextSlide, 5000);
});

// Indicator clicks
indicators.forEach((indicator, index) => {
    indicator.addEventListener('click', () => {
        clearInterval(autoplay);
        showSlide(index);
        autoplay = setInterval(nextSlide, 5000);
    });
});

// Pause on hover
document.querySelector('.slideshow-container')?.addEventListener('mouseenter', () => {
    clearInterval(autoplay);
});

document.querySelector('.slideshow-container')?.addEventListener('mouseleave', () => {
    autoplay = setInterval(nextSlide, 5000);
});

// Initialize first slide
showSlide(0);
</script>

<style>
.slide.active {
    opacity: 1 !important;
}

.slide-indicator.active {
    width: 2rem;
    background-color: white !important;
}

.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush
@endsection