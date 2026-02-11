@extends('layouts.app')

@section('title', $destination->meta_title ?? $destination->name . ' - Safari Destination')
@section('meta_description', $destination->meta_description ?? Str::limit(strip_tags($destination->description), 160))
@section('meta_keywords', $destination->meta_keywords ?? $destination->name . ', safari, ' . $destination->country->name)

@section('content')
<!-- Hero Section with Featured Image -->
<div class="relative h-[60vh] md:h-[70vh] bg-gray-900 overflow-hidden">
    <!-- Background Image with Guaranteed Display -->
    @if($destination->featured_image)
        <img src="{{ asset('storage/' . $destination->featured_image) }}" 
             alt="{{ $destination->name }}" 
             class="absolute inset-0 w-full h-full object-cover">
    @elseif($destination->image)
        <img src="{{ asset('storage/' . $destination->image) }}" 
             alt="{{ $destination->name }}" 
             class="absolute inset-0 w-full h-full object-cover">
    @else
        <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-green-600 via-teal-600 to-blue-600"></div>
    @endif
    
    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
    
    <!-- Breadcrumb -->
    <nav class="absolute top-4 left-0 right-0 z-10">
        <div class="container mx-auto px-4">
            <ol class="flex items-center space-x-2 text-sm text-white/90">
                <li><a href="{{ route('index') }}" class="hover:text-white transition flex items-center">
                    <i class="fas fa-home mr-1"></i>Home
                </a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li><a href="{{ route('destinations.index') }}" class="hover:text-white transition">Destinations</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li><a href="{{ route('destinations.index', ['country' => $destination->country->code]) }}" class="hover:text-white transition">
                    {{ $destination->country->name }}
                </a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="text-white font-medium">{{ $destination->name }}</li>
            </ol>
        </div>
    </nav>
    
    <!-- Hero Content -->
    <div class="absolute inset-0 flex items-end">
        <div class="container mx-auto px-4 pb-12 md:pb-16">
            <div class="max-w-4xl">
                <!-- Badges -->
                <div class="flex flex-wrap gap-3 mb-4">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-600/90 backdrop-blur-sm text-white shadow-lg">
                        {{ $destination->country->flag_icon }} {{ $destination->country->name }}
                    </span>
                    @if($destination->type)
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-white/20 backdrop-blur-sm text-white shadow-lg">
                        <i class="fas fa-map-marked-alt mr-2"></i>{{ $destination->type }}
                    </span>
                    @endif
                    @if($destination->is_popular)
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-500/90 backdrop-blur-sm text-white shadow-lg">
                        <i class="fas fa-star mr-1"></i> Popular
                    </span>
                    @endif
                </div>
                
                <!-- Title -->
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-4 drop-shadow-lg">
                    {{ $destination->name }}
                </h1>
                
                <!-- Meta Info -->
                <div class="flex flex-wrap gap-4 text-white/90 text-sm md:text-base">
                    @if($destination->region)
                    <div class="flex items-center">
                        <i class="fas fa-location-dot text-green-400 mr-2"></i>
                        <span>{{ $destination->region }}</span>
                    </div>
                    @endif
                    
                    @if($destination->area_size)
                    <div class="flex items-center">
                        <i class="fas fa-expand text-blue-400 mr-2"></i>
                        <span>{{ number_format($destination->area_size) }} {{ $destination->area_unit }}</span>
                    </div>
                    @endif
                    
                    @if($destination->established_year)
                    <div class="flex items-center">
                        <i class="fas fa-calendar text-purple-400 mr-2"></i>
                        <span>Est. {{ $destination->established_year }}</span>
                    </div>
                    @endif
                    
                    <div class="flex items-center">
                        <i class="fas fa-hiking text-yellow-400 mr-2"></i>
                        <span>{{ $destination->activities->count() }} Activities</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Down Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <i class="fas fa-chevron-down text-white text-2xl"></i>
    </div>
</div>

@php
// Content formatting helper function
function formatContent($text) {
    if (empty($text)) return '';
    
    // Convert **text** to green headings
    $text = preg_replace_callback(
        '/\*\*([^*]+)\*\*/',
        function ($matches) {
            return '<h3 class="text-xl font-bold text-green-700 mt-6 mb-3 pb-2 border-b-2 border-green-200">' . trim($matches[1]) . '</h3>';
        },
        $text
    );
    
    // Replace - at start of lines with bullet points
    $text = preg_replace('/^[\s]*-[\s]+(.+)$/m', '• $1', $text);
    
    // Remove multiple hyphens (decorative separators)
    $text = preg_replace('/[-]{3,}/', "\n\n", $text);
    
    // Clean up multiple newlines
    $text = preg_replace('/\n{3,}/', "\n\n", $text);
    
    // Convert newlines to <br> tags
    $text = nl2br($text);
    
    return $text;
}
@endphp

<!-- Main Content -->
<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Quick Info Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @if($destination->entry_fee_foreign)
                <div class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition">
                    <i class="fas fa-ticket-alt text-green-600 text-3xl mb-2"></i>
                    <p class="text-xs text-gray-600 mb-1">Entry Fee</p>
                    <p class="font-bold text-gray-800">{{ $destination->currency }} {{ number_format($destination->entry_fee_foreign) }}</p>
                </div>
                @endif

                @if($destination->best_season)
                <div class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition">
                    <i class="fas fa-sun text-orange-600 text-3xl mb-2"></i>
                    <p class="text-xs text-gray-600 mb-1">Best Season</p>
                    <p class="font-bold text-gray-800 text-sm">{{ $destination->best_season }}</p>
                </div>
                @endif

                @if($destination->climate)
                <div class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition">
                    <i class="fas fa-cloud-sun text-blue-600 text-3xl mb-2"></i>
                    <p class="text-xs text-gray-600 mb-1">Climate</p>
                    <p class="font-bold text-gray-800 text-sm">{{ $destination->climate }}</p>
                </div>
                @endif

                @if($destination->altitude_min && $destination->altitude_max)
                <div class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition">
                    <i class="fas fa-mountain text-purple-600 text-3xl mb-2"></i>
                    <p class="text-xs text-gray-600 mb-1">Altitude</p>
                    <p class="font-bold text-gray-800 text-xs">{{ number_format($destination->altitude_min) }}-{{ number_format($destination->altitude_max) }}m</p>
                </div>
                @endif
            </div>

            <!-- Short Description -->
            @if($destination->description)
            <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                    Quick Overview
                </h2>
                <div class="formatted-content text-gray-700 leading-relaxed text-lg">
                    {!! formatContent($destination->description) !!}
                </div>
            </div>
            @endif

            <!-- Detailed Overview -->
            @if($destination->detailed_overview)
            <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-book-open text-green-600 mr-3"></i>
                    About {{ $destination->name }}
                </h2>
                
                <x-section-images :images="$destination->overview_images" :columns="2" />
                
                <div class="formatted-content text-gray-700 leading-relaxed">
                    {!! formatContent($destination->detailed_overview) !!}
                </div>
            </div>
            @endif

            <!-- Photo Gallery -->
            @php
                $galleryImages = $destination->gallery_images;
                if (is_string($galleryImages)) {
                    $galleryImages = json_decode($galleryImages, true);
                }
                $galleryImages = is_array($galleryImages) ? $galleryImages : [];
            @endphp

            @if(count($galleryImages) > 0)
            <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-images text-purple-600 mr-3"></i>
                    Photo Gallery ({{ count($galleryImages) }})
                </h2>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($galleryImages as $index => $galleryItem)
                        @php
                            $imagePath = is_array($galleryItem) ? ($galleryItem['image'] ?? $galleryItem['path'] ?? '') : $galleryItem;
                        @endphp
                        @if($imagePath)
                        <div class="relative group cursor-pointer overflow-hidden rounded-lg aspect-video bg-gray-200" 
                             onclick="openLightbox({{ $index }})">
                            <img src="{{ asset('storage/' . $imagePath) }}" 
                                 alt="Gallery {{ $index + 1 }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-300"
                                 loading="lazy">
                            
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition flex items-center justify-center">
                                <i class="fas fa-search-plus text-white text-3xl opacity-0 group-hover:opacity-100 transition"></i>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            <!-- What to See & Do -->
            @if($destination->what_to_see_do)
            <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-binoculars text-teal-600 mr-3"></i>
                    What to See & Do
                </h2>
                
                <x-section-images :images="$destination->activities_images" :columns="2" />
                
                <div class="formatted-content text-gray-700 leading-relaxed">
                    {!! formatContent($destination->what_to_see_do) !!}
                </div>
            </div>
            @endif

            <!-- Wildlife Highlights -->
            @if($destination->wildlife_highlights)
            <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-xl shadow-md p-6 md:p-8 border border-green-100">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-paw text-orange-600 mr-3"></i>
                    Wildlife Highlights
                </h2>
                
                <x-section-images :images="$destination->wildlife_images" :columns="3" />
                
                <div class="formatted-content text-gray-700 leading-relaxed">
                    {!! formatContent($destination->wildlife_highlights) !!}
                </div>
            </div>
            @endif

            <!-- Geography & Landscape -->
            @if($destination->geography_landscape)
            <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-mountain text-purple-600 mr-3"></i>
                    Geography & Landscape
                </h2>
                
                <x-section-images :images="$destination->landscape_images" :columns="2" />
                
                <div class="formatted-content text-gray-700 leading-relaxed">
                    {!! formatContent($destination->geography_landscape) !!}
                </div>
            </div>
            @endif

            <!-- Best Time to Visit -->
            @if($destination->best_time_visit)
            <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-600 mr-3"></i>
                    Best Time to Visit
                </h2>
                <div class="formatted-content text-gray-700 leading-relaxed">
                    {!! formatContent($destination->best_time_visit) !!}
                </div>
            </div>
            @endif

            <!-- How to Get There -->
            @if($destination->how_to_get_there)
            <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-route text-indigo-600 mr-3"></i>
                    How to Get There
                </h2>
                <div class="formatted-content text-gray-700 leading-relaxed">
                    {!! formatContent($destination->how_to_get_there) !!}
                </div>
            </div>
            @endif

            <!-- Accommodation Options -->
            @if($destination->accommodation_options)
            <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-hotel text-purple-600 mr-3"></i>
                    Accommodation Options
                </h2>
                
                <x-section-images :images="$destination->accommodation_images" :columns="3" />
                
                <div class="formatted-content text-gray-700 leading-relaxed">
                    {!! formatContent($destination->accommodation_options) !!}
                </div>
            </div>
            @endif

            <!-- Practical Information -->
            @if($destination->practical_information)
            <div class="bg-yellow-50 rounded-xl shadow-md p-6 md:p-8 border border-yellow-200">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-lightbulb text-yellow-600 mr-3"></i>
                    Practical Information
                </h2>
                <div class="formatted-content text-gray-700 leading-relaxed">
                    {!! formatContent($destination->practical_information) !!}
                </div>
            </div>
            @endif

            <!-- Cultural Significance -->
            @if($destination->cultural_significance)
            <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-users text-indigo-600 mr-3"></i>
                    Cultural Significance
                </h2>
                <div class="formatted-content text-gray-700 leading-relaxed">
                    {!! formatContent($destination->cultural_significance) !!}
                </div>
            </div>
            @endif

            <!-- Photography Tips -->
            @if($destination->photography_tips)
            <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-camera text-pink-600 mr-3"></i>
                    Photography Tips
                </h2>
                <div class="formatted-content text-gray-700 leading-relaxed">
                    {!! formatContent($destination->photography_tips) !!}
                </div>
            </div>
            @endif

            <!-- Nearby Attractions -->
            @if($destination->nearby_attractions)
            <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-map-signs text-teal-600 mr-3"></i>
                    Nearby Attractions & Extensions
                </h2>
                <div class="formatted-content text-gray-700 leading-relaxed">
                    {!! formatContent($destination->nearby_attractions) !!}
                </div>
            </div>
            @endif

            <!-- Interesting Facts -->
            @if($destination->interesting_facts)
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl shadow-md p-6 md:p-8 border border-purple-100">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-star text-yellow-500 mr-3"></i>
                    Interesting Facts & Trivia
                </h2>
                <div class="formatted-content text-gray-700 leading-relaxed">
                    {!! formatContent($destination->interesting_facts) !!}
                </div>
            </div>
            @endif

            <!-- Activities at this Destination -->
            @if($destination->activities->count() > 0)
            <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-hiking text-green-600 mr-3"></i>
                    Available Activities ({{ $destination->activities->where('is_active', true)->count() }})
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($destination->activities->where('is_active', true) as $activity)
                    <a href="{{ route('activities.show', $activity->slug) }}" class="group">
                        <div class="border-2 border-gray-200 rounded-lg p-5 hover:border-green-500 hover:shadow-lg transition h-full">
                            <div class="flex items-start gap-4">
                                @if($activity->icon)
                                    <div class="w-16 h-16 flex-shrink-0">
                                        <img src="{{ asset('storage/' . $activity->icon) }}" 
                                             alt="{{ $activity->name }}" 
                                             class="w-full h-full object-cover rounded-lg">
                                    </div>
                                @elseif($activity->image)
                                    <div class="w-16 h-16 flex-shrink-0">
                                        <img src="{{ asset('storage/' . $activity->image) }}" 
                                             alt="{{ $activity->name }}" 
                                             class="w-full h-full object-cover rounded-lg">
                                    </div>
                                @else
                                    <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center text-green-600 text-2xl flex-shrink-0">
                                        <i class="fas fa-hiking"></i>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-800 mb-2 group-hover:text-green-600 transition">
                                        {{ $activity->name }}
                                    </h3>
                                    @if($activity->category)
                                        <span class="inline-block px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full mb-2">
                                            @if($activity->category->icon)
                                            <i class="{{ $activity->category->icon }} mr-1"></i>
                                            @endif
                                            {{ $activity->category->name }}
                                        </span>
                                    @endif
                                    <p class="text-gray-600 text-sm line-clamp-2">
                                        {{ $activity->description }}
                                    </p>
                                    @if($activity->duration)
                                    <p class="text-xs text-gray-500 mt-2">
                                        <i class="far fa-clock mr-1"></i>{{ $activity->duration }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        <!-- Right Column - Sidebar -->
        <div class="lg:col-span-1">
            <div class="sticky top-24 space-y-6">
                
                <!-- Booking Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-green-600">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Plan Your Visit</h3>
                    
                    @if($destination->entry_fee_foreign)
                    <div class="mb-6 text-center bg-green-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Entry Fee (Foreign)</p>
                        <p class="text-3xl font-bold text-green-600">
                            {{ $destination->currency }} {{ number_format($destination->entry_fee_foreign) }}
                        </p>
                        @if($destination->entry_fee_resident)
                        <p class="text-xs text-gray-500 mt-1">
                            Residents: {{ $destination->currency }} {{ number_format($destination->entry_fee_resident) }}
                        </p>
                        @endif
                        @if($destination->entry_fee_local)
                        <p class="text-xs text-gray-500">
                            Locals: {{ $destination->currency }} {{ number_format($destination->entry_fee_local) }}
                        </p>
                        @endif
                    </div>
                    @endif

                    <div class="space-y-3">
                        <a href="{{ route('contact', ['destination' => $destination->slug]) }}" 
                           class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-3 rounded-lg font-semibold transition shadow-md">
                            <i class="fas fa-envelope mr-2"></i> Inquire Now
                        </a>
                        
                        @if($destination->phone)
                        <a href="tel:{{ $destination->phone }}" 
                           class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-3 rounded-lg font-semibold transition shadow-md">
                            <i class="fas fa-phone mr-2"></i> Call Us
                        </a>
                        @endif

                        <a href="https://wa.me/256700000000?text=Hi, I'm interested in {{ $destination->name }}" 
                           target="_blank"
                           class="block w-full bg-green-500 hover:bg-green-600 text-white text-center py-3 rounded-lg font-semibold transition shadow-md">
                            <i class="fab fa-whatsapp mr-2"></i> WhatsApp Us
                        </a>
                        
                        @if($destination->website)
                        <a href="{{ $destination->website }}" 
                           target="_blank"
                           class="block w-full border-2 border-green-600 text-green-600 hover:bg-green-50 text-center py-3 rounded-lg font-semibold transition">
                            <i class="fas fa-external-link-alt mr-2"></i> Official Website
                        </a>
                        @endif
                    </div>

                    @if($destination->opening_hours)
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-xs text-gray-600 mb-1">
                            <i class="far fa-clock mr-1"></i> Opening Hours
                        </p>
                        <p class="text-sm font-semibold text-gray-800">{{ $destination->opening_hours }}</p>
                    </div>
                    @endif
                </div>

                <!-- Quick Facts -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Quick Facts</h3>
                    
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt text-green-600 mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <p class="font-medium text-gray-700">Location</p>
                                <p class="text-gray-600">
                                    {{ $destination->region ?? $destination->country->name }}
                                </p>
                            </div>
                        </li>

                        @if($destination->area_size)
                        <li class="flex items-start">
                            <i class="fas fa-expand text-blue-600 mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <p class="font-medium text-gray-700">Area</p>
                                <p class="text-gray-600">{{ number_format($destination->area_size) }} {{ $destination->area_unit }}</p>
                            </div>
                        </li>
                        @endif

                        @if($destination->established_year)
                        <li class="flex items-start">
                            <i class="fas fa-calendar text-purple-600 mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <p class="font-medium text-gray-700">Established</p>
                                <p class="text-gray-600">{{ $destination->established_year }}</p>
                            </div>
                        </li>
                        @endif

                        @if($destination->altitude_min && $destination->altitude_max)
                        <li class="flex items-start">
                            <i class="fas fa-mountain text-teal-600 mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <p class="font-medium text-gray-700">Altitude Range</p>
                                <p class="text-gray-600">{{ number_format($destination->altitude_min) }}m - {{ number_format($destination->altitude_max) }}m</p>
                            </div>
                        </li>
                        @endif

                        <li class="flex items-start">
                            <i class="fas fa-hiking text-orange-600 mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <p class="font-medium text-gray-700">Activities</p>
                                <p class="text-gray-600">{{ $destination->activities->count() }} available</p>
                            </div>
                        </li>

                        @if($destination->climate)
                        <li class="flex items-start">
                            <i class="fas fa-cloud-sun text-blue-600 mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <p class="font-medium text-gray-700">Climate</p>
                                <p class="text-gray-600">{{ $destination->climate }}</p>
                            </div>
                        </li>
                        @endif

                        @if($destination->is_popular)
                        <li class="flex items-start">
                            <i class="fas fa-star text-yellow-600 mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <p class="font-medium text-gray-700">Status</p>
                                <p class="text-gray-600">Popular Destination</p>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>

                <!-- Share -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Share This Destination</h3>
                    
                    <div class="flex gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('destinations.show', $destination->slug)) }}" 
                           target="_blank"
                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-lg transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('destinations.show', $destination->slug)) }}&text={{ urlencode($destination->name) }}" 
                           target="_blank"
                           class="flex-1 bg-sky-500 hover:bg-sky-600 text-white text-center py-2 rounded-lg transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($destination->name . ' - ' . route('destinations.show', $destination->slug)) }}" 
                           target="_blank"
                           class="flex-1 bg-green-500 hover:bg-green-600 text-white text-center py-2 rounded-lg transition">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <button onclick="copyToClipboard()" 
                                class="flex-1 bg-gray-600 hover:bg-gray-700 text-white text-center py-2 rounded-lg transition">
                            <i class="fas fa-link"></i>
                        </button>
                    </div>
                </div>

                <!-- Related Destinations -->
                @if($relatedDestinations->count() > 0)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">More in {{ $destination->country->name }}</h3>
                    <div class="space-y-4">
                        @foreach($relatedDestinations as $related)
                        <a href="{{ route('destinations.show', $related->slug) }}" class="block group">
                            <div class="flex gap-3 hover:bg-gray-50 p-2 rounded-lg transition">
                                @if($related->image)
                                    <img src="{{ asset('storage/' . $related->image) }}" 
                                         alt="{{ $related->name }}" 
                                         class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                @else
                                    <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-mountain text-white text-xl"></i>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-800 group-hover:text-green-600 transition line-clamp-1">
                                        {{ $related->name }}
                                    </h4>
                                    <p class="text-sm text-gray-500 line-clamp-2">
                                        {{ Str::limit($related->description, 60) }}
                                    </p>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

<!-- Gallery Lightbox -->
<div id="lightbox" class="hidden fixed inset-0 bg-black/95 z-50 flex items-center justify-center p-4">
    <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300 z-10">
        <i class="fas fa-times"></i>
    </button>
    
    <button onclick="previousImage()" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-4xl hover:text-gray-300">
        <i class="fas fa-chevron-left"></i>
    </button>
    
    <button onclick="nextImage()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-4xl hover:text-gray-300">
        <i class="fas fa-chevron-right"></i>
    </button>
    
    <div class="max-w-5xl max-h-full">
        <img id="lightbox-image" src="" alt="" class="max-w-full max-h-[90vh] object-contain">
    </div>
</div>

<!-- Section Image Modal/Lightbox -->
<div id="section-image-modal" class="hidden fixed inset-0 bg-black/95 z-50 flex items-center justify-center p-4">
    <button onclick="closeSectionImageModal()" class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300 z-10">
        <i class="fas fa-times"></i>
    </button>
    
    <div class="max-w-5xl max-h-full text-center">
        <img id="section-modal-image" src="" alt="" class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl">
        <div id="section-modal-info" class="mt-4 text-white"></div>
    </div>
</div>

@push('scripts')
<script>
// Gallery Lightbox
const galleryImages = @json(array_map(function($item) {
    return is_array($item) ? ($item['image'] ?? $item['path'] ?? '') : $item;
}, $galleryImages));

let currentImageIndex = 0;

function openLightbox(index) {
    currentImageIndex = index;
    showImage();
    document.getElementById('lightbox').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function showImage() {
    const imagePath = galleryImages[currentImageIndex];
    document.getElementById('lightbox-image').src = "{{ asset('storage/') }}/" + imagePath;
}

function nextImage() {
    currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
    showImage();
}

function previousImage() {
    currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
    showImage();
}

// Section Image Modal
function openSectionImageModal(imageSrc, caption, section) {
    document.getElementById('section-modal-image').src = imageSrc;
    
    let infoHtml = '';
    if (section) {
        infoHtml += `<p class="text-lg font-semibold mb-1">${section}</p>`;
    }
    if (caption) {
        infoHtml += `<p class="text-sm text-gray-300 italic">${caption}</p>`;
    }
    
    document.getElementById('section-modal-info').innerHTML = infoHtml;
    document.getElementById('section-image-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeSectionImageModal() {
    document.getElementById('section-image-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (!document.getElementById('lightbox').classList.contains('hidden')) {
        if (e.key === 'ArrowRight') nextImage();
        if (e.key === 'ArrowLeft') previousImage();
        if (e.key === 'Escape') closeLightbox();
    }
    if (!document.getElementById('section-image-modal').classList.contains('hidden')) {
        if (e.key === 'Escape') closeSectionImageModal();
    }
});

// Close on outside click
document.getElementById('section-image-modal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeSectionImageModal();
    }
});

// Copy link function
function copyToClipboard() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        alert('Link copied to clipboard!');
    });
}
</script>
@endpush

<style>
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

/* Formatted Content Styles */
.formatted-content {
    line-height: 1.8;
}

.formatted-content h3 {
    color: #059669;
    font-size: 1.25rem;
    font-weight: 700;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #d1fae5;
}

.formatted-content p {
    margin-bottom: 1rem;
}

/* Style bullet points */
.formatted-content br + •,
.formatted-content • {
    color: #059669;
    font-weight: bold;
    font-size: 1.2em;
}
</style>
@endsection