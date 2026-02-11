@extends('layouts.app')

@section('title', $activity->meta_title ?? $activity->name . ' - Safari Activity in East Africa')
@section('meta_description', $activity->meta_description ?? Str::limit(strip_tags($activity->description), 160))
@section('meta_keywords', $activity->meta_keywords ?? 'safari, ' . $activity->name . ', east africa, ' . ($activity->destination->name ?? ''))
@section('og_title', $activity->name . ' | ' . config('app.name'))
@section('og_description', Str::limit(strip_tags($activity->overview ?? $activity->description), 200))
@section('og_image', $activity->featured_image_url)

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

@section('content')
<div class="bg-gray-50">
    <!-- Hero Section with Featured Image -->
    <div class="relative h-[60vh] md:h-[70vh] overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0">
            <img src="{{ $activity->featured_image_url }}" 
                 alt="{{ $activity->name }}" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
        </div>

        <!-- Hero Content -->
        <div class="absolute inset-0 flex items-end">
            <div class="container mx-auto px-4 pb-12">
                <div class="max-w-4xl">
                    <!-- Breadcrumb -->
                    <nav class="mb-4">
                        <ol class="flex items-center space-x-2 text-sm text-white/80">
                            <li><a href="{{ route('index') }}" class="hover:text-white transition">
                                <i class="fas fa-home"></i> Home
                            </a></li>
                            <li><i class="fas fa-chevron-right text-xs"></i></li>
                            <li><a href="{{ route('activities.index') }}" class="hover:text-white transition">Activities</a></li>
                            @if($activity->category)
                            <li><i class="fas fa-chevron-right text-xs"></i></li>
                            <li>{{ $activity->category->name }}</li>
                            @endif
                        </ol>
                    </nav>

                    <!-- Badges -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        @if($activity->is_popular)
                        <span class="bg-yellow-500 text-white px-4 py-2 rounded-full text-sm font-bold">
                            <i class="fas fa-star mr-1"></i> Popular Activity
                        </span>
                        @endif

                        @if($activity->category)
                        <span class="bg-purple-600/90 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-medium">
                            @if($activity->category->icon)
                            <i class="{{ $activity->category->icon }} mr-1"></i>
                            @endif
                            {{ $activity->category->name }}
                        </span>
                        @endif

                        @if($activity->difficulty_level)
                        <span class="backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-bold
                            {{ $activity->difficulty_level == 'easy' ? 'bg-green-600/90' : '' }}
                            {{ $activity->difficulty_level == 'moderate' ? 'bg-blue-600/90' : '' }}
                            {{ $activity->difficulty_level == 'challenging' ? 'bg-orange-600/90' : '' }}
                            {{ $activity->difficulty_level == 'extreme' ? 'bg-red-600/90' : '' }}">
                            <i class="fas fa-chart-line mr-1"></i> {{ ucfirst($activity->difficulty_level) }}
                        </span>
                        @endif
                    </div>

                    <!-- Title -->
                    <h1 class="text-4xl md:text-6xl font-bold text-white mb-4">
                        {{ $activity->name }}
                    </h1>

                    <!-- Meta Info -->
                    <div class="flex flex-wrap gap-4 text-white/90 text-sm md:text-base">
                        @if($activity->destination)
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-green-400 mr-2"></i>
                            <span>{{ $activity->destination->name }}, {{ $activity->destination->country->name }}</span>
                        </div>
                        @endif

                        @if($activity->duration)
                        <div class="flex items-center">
                            <i class="far fa-clock text-blue-400 mr-2"></i>
                            <span>{{ $activity->duration }}</span>
                        </div>
                        @endif

                        @if($activity->min_age)
                        <div class="flex items-center">
                            <i class="fas fa-child text-yellow-400 mr-2"></i>
                            <span>Min Age: {{ $activity->min_age }} years</span>
                        </div>
                        @endif

                        @if($activity->max_group_size)
                        <div class="flex items-center">
                            <i class="fas fa-users text-purple-400 mr-2"></i>
                            <span>Max {{ $activity->max_group_size }} people</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Down Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <i class="fas fa-chevron-down text-white text-2xl"></i>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Main Content -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Quick Info Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @if($activity->duration)
                    <div class="bg-white rounded-lg shadow-md p-4 text-center">
                        <i class="far fa-clock text-blue-600 text-3xl mb-2"></i>
                        <p class="text-xs text-gray-600 mb-1">Duration</p>
                        <p class="font-bold text-gray-800">{{ $activity->duration }}</p>
                    </div>
                    @endif

                    @if($activity->difficulty_level)
                    <div class="bg-white rounded-lg shadow-md p-4 text-center">
                        <i class="fas fa-hiking text-orange-600 text-3xl mb-2"></i>
                        <p class="text-xs text-gray-600 mb-1">Difficulty</p>
                        <p class="font-bold text-gray-800">{{ ucfirst($activity->difficulty_level) }}</p>
                    </div>
                    @endif

                    @if($activity->min_age)
                    <div class="bg-white rounded-lg shadow-md p-4 text-center">
                        <i class="fas fa-child text-green-600 text-3xl mb-2"></i>
                        <p class="text-xs text-gray-600 mb-1">Min Age</p>
                        <p class="font-bold text-gray-800">{{ $activity->min_age }}+ years</p>
                    </div>
                    @endif

                    @if($activity->max_group_size)
                    <div class="bg-white rounded-lg shadow-md p-4 text-center">
                        <i class="fas fa-users text-purple-600 text-3xl mb-2"></i>
                        <p class="text-xs text-gray-600 mb-1">Group Size</p>
                        <p class="font-bold text-gray-800">Max {{ $activity->max_group_size }}</p>
                    </div>
                    @endif
                </div>

                <!-- Short Description -->
                @if($activity->description)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                        Quick Overview
                    </h2>
                    <div class="formatted-content text-gray-700 leading-relaxed text-lg">
                        {!! formatContent($activity->description) !!}
                    </div>
                </div>
                @endif

                <!-- Overview -->
                @if($activity->overview)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-book-open text-green-600 mr-3"></i>
                        Detailed Overview
                    </h2>
                    <div class="formatted-content text-gray-700 leading-relaxed">
                        {!! formatContent($activity->overview) !!}
                    </div>
                </div>
                @endif

                <!-- Gallery -->
                @if($activity->images->count() > 0)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-images text-purple-600 mr-3"></i>
                        Photo Gallery ({{ $activity->images->count() }})
                    </h2>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($activity->images as $image)
                        <div class="relative group cursor-pointer overflow-hidden rounded-lg aspect-video bg-gray-200" 
                             onclick="openLightbox({{ $loop->index }})">
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                 alt="{{ $image->title ?? $activity->name }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-300"
                                 loading="lazy">
                            
                            @if($image->is_featured)
                            <div class="absolute top-2 left-2">
                                <span class="bg-yellow-500 text-white px-2 py-1 rounded text-xs font-bold">
                                    <i class="fas fa-star"></i> Featured
                                </span>
                            </div>
                            @endif

                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition flex items-center justify-center">
                                <i class="fas fa-search-plus text-white text-3xl opacity-0 group-hover:opacity-100 transition"></i>
                            </div>
                            
                            @if($image->title)
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3 opacity-0 group-hover:opacity-100 transition">
                                <p class="text-white text-sm font-medium">{{ $image->title }}</p>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- What to Expect -->
                @if($activity->what_to_expect)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-binoculars text-teal-600 mr-3"></i>
                        What to Expect
                    </h2>
                    <div class="formatted-content text-gray-700 leading-relaxed">
                        {!! formatContent($activity->what_to_expect) !!}
                    </div>
                </div>
                @endif

                <!-- Highlights -->
                @if($activity->highlights)
                <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-xl shadow-md p-6 border border-green-100">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-star text-yellow-500 mr-3"></i>
                        Highlights
                    </h2>
                    <div class="formatted-content text-gray-700 leading-relaxed">
                        {!! formatContent($activity->highlights) !!}
                    </div>
                </div>
                @endif

                <!-- Inclusions & Exclusions -->
                @if($activity->inclusions || $activity->exclusions)
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Inclusions -->
                    @if($activity->inclusions)
                    <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-green-500">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            What's Included
                        </h3>
                        <ul class="space-y-2">
                            @foreach($activity->inclusions as $inclusion)
                            <li class="flex items-start text-gray-700">
                                <i class="fas fa-check text-green-600 mt-1 mr-3 flex-shrink-0"></i>
                                <span>{{ $inclusion }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Exclusions -->
                    @if($activity->exclusions)
                    <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-red-500">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-times-circle text-red-600 mr-2"></i>
                            What's NOT Included
                        </h3>
                        <ul class="space-y-2">
                            @foreach($activity->exclusions as $exclusion)
                            <li class="flex items-start text-gray-700">
                                <i class="fas fa-times text-red-600 mt-1 mr-3 flex-shrink-0"></i>
                                <span>{{ $exclusion }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Equipment & What to Bring -->
                @if($activity->equipment_provided || $activity->what_to_bring)
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Equipment Provided -->
                    @if($activity->equipment_provided)
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-toolbox text-blue-600 mr-2"></i>
                            Equipment Provided
                        </h3>
                        <ul class="space-y-2">
                            @foreach($activity->equipment_provided as $equipment)
                            <li class="flex items-start text-gray-700">
                                <i class="fas fa-wrench text-blue-600 mt-1 mr-3 flex-shrink-0"></i>
                                <span>{{ $equipment }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- What to Bring -->
                    @if($activity->what_to_bring)
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-backpack text-orange-600 mr-2"></i>
                            What to Bring
                        </h3>
                        <ul class="space-y-2">
                            @foreach($activity->what_to_bring as $item)
                            <li class="flex items-start text-gray-700">
                                <i class="fas fa-luggage-cart text-orange-600 mt-1 mr-3 flex-shrink-0"></i>
                                <span>{{ $item }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Regulations -->
                @if($activity->regulations)
                <div class="bg-yellow-50 rounded-xl shadow-md p-6 border border-yellow-200">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                        Rules & Regulations
                    </h2>
                    <div class="formatted-content text-gray-700 leading-relaxed">
                        {!! formatContent($activity->regulations) !!}
                    </div>
                </div>
                @endif

                <!-- Safety Information -->
                @if($activity->safety_info)
                <div class="bg-red-50 rounded-xl shadow-md p-6 border border-red-200">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-shield-alt text-red-600 mr-3"></i>
                        Safety Information
                    </h2>
                    <div class="formatted-content text-gray-700 leading-relaxed">
                        {!! formatContent($activity->safety_info) !!}
                    </div>
                </div>
                @endif

                <!-- Health Requirements -->
                @if($activity->health_requirements)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-heartbeat text-pink-600 mr-3"></i>
                        Health Requirements
                    </h2>
                    <div class="formatted-content text-gray-700 leading-relaxed">
                        {!! formatContent($activity->health_requirements) !!}
                    </div>
                </div>
                @endif

                <!-- Cultural Experience -->
                @if($activity->cultural_experience)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-users text-indigo-600 mr-3"></i>
                        Cultural Experience
                    </h2>
                    <div class="formatted-content text-gray-700 leading-relaxed">
                        {!! formatContent($activity->cultural_experience) !!}
                    </div>
                </div>
                @endif

                <!-- Conservation Info -->
                @if($activity->conservation_info)
                <div class="bg-green-50 rounded-xl shadow-md p-6 border border-green-200">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-leaf text-green-600 mr-3"></i>
                        Conservation & Sustainability
                    </h2>
                    <div class="formatted-content text-gray-700 leading-relaxed">
                        {!! formatContent($activity->conservation_info) !!}
                    </div>
                </div>
                @endif

                <!-- Special Notes -->
                @if($activity->special_notes)
                <div class="bg-blue-50 rounded-xl shadow-md p-6 border border-blue-200">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-sticky-note text-blue-600 mr-3"></i>
                        Important Notes
                    </h2>
                    <div class="formatted-content text-gray-700 leading-relaxed">
                        {!! formatContent($activity->special_notes) !!}
                    </div>
                </div>
                @endif

            </div>

            <!-- Right Column - Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    
                    <!-- Booking Card -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-green-600">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Book This Activity</h3>
                        
                        <!-- Price -->
                        @if($activity->price_from)
                        <div class="mb-6 text-center bg-green-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Starting from</p>
                            <p class="text-4xl font-bold text-green-600">
                                {{ $activity->currency }} {{ number_format($activity->price_from, 0) }}
                            </p>
                            @if($activity->price_to)
                            <p class="text-sm text-gray-600 mt-1">
                                up to {{ $activity->currency }} {{ number_format($activity->price_to, 0) }}
                            </p>
                            @endif
                            <p class="text-xs text-gray-500 mt-2">per person</p>
                        </div>
                        @else
                        <div class="mb-6 text-center bg-gray-50 rounded-lg p-4">
                            <p class="text-lg font-medium text-gray-700">Price on Request</p>
                            <p class="text-sm text-gray-500 mt-1">Contact us for pricing details</p>
                        </div>
                        @endif

                        <!-- CTA Buttons -->
                        <div class="space-y-3">
                            <a href="{{ route('contact', ['activity' => $activity->slug]) }}" 
                               class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-3 px-6 rounded-lg font-bold transition shadow-md">
                                <i class="fas fa-envelope mr-2"></i> Inquire Now
                            </a>
                            
                            <a href="https://wa.me/256700000000?text=Hi, I'm interested in {{ $activity->name }}" 
                               target="_blank"
                               class="block w-full bg-green-500 hover:bg-green-600 text-white text-center py-3 px-6 rounded-lg font-bold transition shadow-md">
                                <i class="fab fa-whatsapp mr-2"></i> WhatsApp Us
                            </a>

                            <a href="tel:+256700000000" 
                               class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-3 px-6 rounded-lg font-bold transition shadow-md">
                                <i class="fas fa-phone mr-2"></i> Call Us
                            </a>
                        </div>

                        <p class="text-xs text-gray-500 text-center mt-4">
                            <i class="fas fa-lock mr-1"></i> Secure booking process
                        </p>
                    </div>

                    <!-- Quick Facts -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Quick Facts</h3>
                        
                        <ul class="space-y-3 text-sm">
                            @if($activity->destination)
                            <li class="flex items-start">
                                <i class="fas fa-map-marker-alt text-green-600 mt-1 mr-3 flex-shrink-0"></i>
                                <div>
                                    <p class="font-medium text-gray-700">Location</p>
                                    <p class="text-gray-600">{{ $activity->destination->name }}, {{ $activity->destination->country->name }}</p>
                                </div>
                            </li>
                            @endif

                            @if($activity->duration)
                            <li class="flex items-start">
                                <i class="far fa-clock text-blue-600 mt-1 mr-3 flex-shrink-0"></i>
                                <div>
                                    <p class="font-medium text-gray-700">Duration</p>
                                    <p class="text-gray-600">{{ $activity->duration }}</p>
                                </div>
                            </li>
                            @endif

                            @if($activity->difficulty_level)
                            <li class="flex items-start">
                                <i class="fas fa-hiking text-orange-600 mt-1 mr-3 flex-shrink-0"></i>
                                <div>
                                    <p class="font-medium text-gray-700">Difficulty</p>
                                    <p class="text-gray-600">{{ ucfirst($activity->difficulty_level) }}</p>
                                </div>
                            </li>
                            @endif

                            @if($activity->min_age)
                            <li class="flex items-start">
                                <i class="fas fa-child text-purple-600 mt-1 mr-3 flex-shrink-0"></i>
                                <div>
                                    <p class="font-medium text-gray-700">Minimum Age</p>
                                    <p class="text-gray-600">{{ $activity->min_age }} years</p>
                                </div>
                            </li>
                            @endif

                            @if($activity->max_group_size)
                            <li class="flex items-start">
                                <i class="fas fa-users text-teal-600 mt-1 mr-3 flex-shrink-0"></i>
                                <div>
                                    <p class="font-medium text-gray-700">Max Group Size</p>
                                    <p class="text-gray-600">{{ $activity->max_group_size }} people</p>
                                </div>
                            </li>
                            @endif

                            @if($activity->category)
                            <li class="flex items-start">
                                <i class="fas fa-tag text-pink-600 mt-1 mr-3 flex-shrink-0"></i>
                                <div>
                                    <p class="font-medium text-gray-700">Category</p>
                                    <p class="text-gray-600">{{ $activity->category->name }}</p>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>

                    <!-- Share -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Share This Activity</h3>
                        
                        <div class="flex gap-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('activities.show', $activity->slug)) }}" 
                               target="_blank"
                               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-lg transition">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('activities.show', $activity->slug)) }}&text={{ urlencode($activity->name) }}" 
                               target="_blank"
                               class="flex-1 bg-sky-500 hover:bg-sky-600 text-white text-center py-2 rounded-lg transition">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($activity->name . ' - ' . route('activities.show', $activity->slug)) }}" 
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

                    <!-- Need Help -->
                    <div class="bg-gradient-to-br from-blue-500 to-purple-600 text-white rounded-xl shadow-md p-6">
                        <h3 class="text-xl font-bold mb-2">Need Help?</h3>
                        <p class="text-sm mb-4 text-white/90">Our travel experts are here to help plan your perfect adventure</p>
                        <a href="{{ route('contact') }}" class="block w-full bg-white text-blue-600 text-center py-2 px-4 rounded-lg font-bold hover:bg-gray-100 transition">
                            Contact Us
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Related Activities -->
    @if($relatedActivities->count() > 0)
    <div class="bg-white py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Similar Activities You Might Like</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedActivities as $related)
                <a href="{{ route('activities.show', $related->slug) }}" class="group">
                    <article class="bg-gray-50 rounded-xl shadow-md overflow-hidden hover:shadow-xl transition">
                        <div class="h-48 bg-gray-200 overflow-hidden">
                            @if($related->image)
                            <img src="{{ asset('storage/' . $related->image) }}" 
                                 alt="{{ $related->name }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            @else
                            <div class="w-full h-full bg-gradient-to-br from-green-300 to-blue-400 flex items-center justify-center">
                                <i class="fas fa-hiking text-white text-4xl"></i>
                            </div>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <h3 class="font-bold text-gray-800 group-hover:text-green-600 transition line-clamp-2">
                                {{ $related->name }}
                            </h3>
                            
                            @if($related->price_from)
                            <p class="text-sm text-green-600 font-bold mt-2">
                                From {{ $related->currency }} {{ number_format($related->price_from, 0) }}
                            </p>
                            @endif
                        </div>
                    </article>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Lightbox for Gallery -->
<div id="lightbox" class="hidden fixed inset-0 bg-black/95 z-50 flex items-center justify-center p-4">
    <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300">
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
        <p id="lightbox-caption" class="text-white text-center mt-4 text-lg"></p>
    </div>
</div>

@push('scripts')
<script>
const images = @json($activity->images->map(function($img) {
    return [
        'url' => asset('storage/' . $img->image_path),
        'title' => $img->title,
        'caption' => $img->caption
    ];
}));

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
    const image = images[currentImageIndex];
    document.getElementById('lightbox-image').src = image.url;
    document.getElementById('lightbox-caption').textContent = image.title || '';
}

function nextImage() {
    currentImageIndex = (currentImageIndex + 1) % images.length;
    showImage();
}

function previousImage() {
    currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
    showImage();
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (!document.getElementById('lightbox').classList.contains('hidden')) {
        if (e.key === 'ArrowRight') nextImage();
        if (e.key === 'ArrowLeft') previousImage();
        if (e.key === 'Escape') closeLightbox();
    }
});

// Copy link function
function copyToClipboard() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        alert('Link copied to clipboard!');
    });
}

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
</script>
@endpush

<style>
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

.prose {
    max-width: 65ch;
}

.prose p {
    margin-bottom: 1em;
}

@keyframes fade-in {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}
</style>
@endsection