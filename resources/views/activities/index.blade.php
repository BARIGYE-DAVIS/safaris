@extends('layouts.app')

@section('title', 'Safari Activities in East Africa | Wildlife Tours & Adventures')
@section('meta_description', 'Discover exciting safari activities across East Africa. From gorilla trekking to wildlife safaris, game drives, and cultural experiences in Uganda, Kenya, Tanzania & Rwanda.')
@section('meta_keywords', 'safari activities, gorilla trekking, wildlife safari, game drives, east africa tours, uganda activities, kenya wildlife, adventure tours')
@section('og_title', 'Explore Safari Activities & Adventures in East Africa')
@section('og_description', 'Experience thrilling safari activities across Uganda, Kenya, Tanzania, and Rwanda. Wildlife encounters, adventure tours, and unforgettable experiences await.')
@section('og_image', asset('images/activities-hero.jpg'))

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero Carousel Section -->
    <div class="relative h-[70vh] md:h-[80vh] overflow-hidden">
        <!-- Carousel Slides -->
        <div id="hero-carousel" class="relative h-full">
            @php
                $heroActivities = $featuredActivities ?? $activities->take(5);
                $totalSlides = $heroActivities->count();
                // Group indicators: show max 5 dots regardless of number of slides
                $maxIndicators = 5;
                $showGroupedIndicators = $totalSlides > $maxIndicators;
            @endphp

            @foreach($heroActivities as $index => $heroActivity)
            <div class="carousel-slide {{ $index === 0 ? 'active' : '' }} absolute inset-0 transition-opacity duration-1000 ease-in-out {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">
                <!-- Background Image -->
                <div class="absolute inset-0">
                    @if($heroActivity->featured_image)
                        <img src="{{ asset('storage/' . $heroActivity->featured_image) }}" 
                             alt="{{ $heroActivity->name }}" 
                             class="w-full h-full object-cover">
                    @elseif($heroActivity->image)
                        <img src="{{ asset('storage/' . $heroActivity->image) }}" 
                             alt="{{ $heroActivity->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-green-600 via-teal-600 to-blue-600"></div>
                    @endif
                </div>

                <!-- Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-black/20"></div>

                <!-- Content -->
                <div class="absolute inset-0 flex items-center">
                    <div class="container mx-auto px-4 md:px-8 lg:px-16">
                        <div class="max-w-3xl">
                            <!-- Badges -->
                            <div class="flex flex-wrap gap-2 mb-4 animate-fade-in">
                                @if($heroActivity->is_popular)
                                <span class="bg-yellow-500 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg">
                                    <i class="fas fa-star mr-1"></i> Popular
                                </span>
                                @endif

                                @if($heroActivity->category)
                                <span class="bg-purple-600/90 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-medium">
                                    @if($heroActivity->category->icon)
                                    <i class="{{ $heroActivity->category->icon }} mr-1"></i>
                                    @endif
                                    {{ $heroActivity->category->name }}
                                </span>
                                @endif

                                @if($heroActivity->difficulty_level)
                                <span class="backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-bold
                                    {{ $heroActivity->difficulty_level == 'easy' ? 'bg-green-600/90' : '' }}
                                    {{ $heroActivity->difficulty_level == 'moderate' ? 'bg-blue-600/90' : '' }}
                                    {{ $heroActivity->difficulty_level == 'challenging' ? 'bg-orange-600/90' : '' }}
                                    {{ $heroActivity->difficulty_level == 'extreme' ? 'bg-red-600/90' : '' }}">
                                    <i class="fas fa-chart-line mr-1"></i> {{ ucfirst($heroActivity->difficulty_level) }}
                                </span>
                                @endif
                            </div>

                            <!-- Title -->
                            <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold text-white mb-4 leading-tight drop-shadow-lg animate-slide-up">
                                {{ $heroActivity->name }}
                            </h1>

                            <!-- Description -->
                            <p class="text-base md:text-lg lg:text-xl text-white/90 mb-6 leading-relaxed max-w-2xl animate-slide-up" style="animation-delay: 0.2s">
                                {{ Str::limit($heroActivity->description ?? $heroActivity->overview, 150) }}
                            </p>

                            <!-- Meta Info -->
                            <div class="flex flex-wrap gap-4 text-white/90 text-sm md:text-base mb-6 animate-slide-up" style="animation-delay: 0.3s">
                                @if($heroActivity->destination)
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt text-green-400 mr-2"></i>
                                    <span>{{ $heroActivity->destination->name }}</span>
                                </div>
                                @endif

                                @if($heroActivity->duration)
                                <div class="flex items-center">
                                    <i class="far fa-clock text-blue-400 mr-2"></i>
                                    <span>{{ $heroActivity->duration }}</span>
                                </div>
                                @endif

                                @if($heroActivity->price_from)
                                <div class="flex items-center">
                                    <i class="fas fa-tag text-yellow-400 mr-2"></i>
                                    <span>From {{ $heroActivity->currency }} {{ number_format($heroActivity->price_from, 0) }}</span>
                                </div>
                                @endif
                            </div>

                            <!-- CTA Buttons -->
                            <div class="flex flex-wrap gap-3 md:gap-4 animate-slide-up" style="animation-delay: 0.4s">
                                <a href="{{ route('activities.show', $heroActivity->slug) }}" 
                                   class="bg-green-600 hover:bg-green-700 text-white px-6 md:px-8 py-2 md:py-3 rounded-lg font-bold transition shadow-lg inline-flex items-center text-sm md:text-base">
                                    <i class="fas fa-info-circle mr-2"></i> Learn More
                                </a>
                                <a href="{{ route('contact', ['activity' => $heroActivity->slug]) }}" 
                                   class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-6 md:px-8 py-2 md:py-3 rounded-lg font-bold transition border-2 border-white inline-flex items-center text-sm md:text-base">
                                    <i class="fas fa-envelope mr-2"></i> Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Navigation Arrows - FIXED POSITIONING -->
        @if($totalSlides > 1)
        <button onclick="previousSlide()" 
                class="absolute left-2 md:left-4 lg:left-8 top-1/2 -translate-y-1/2  hover:bg-green-700 text-white-100 w-12 h-12 md:w-14 md:h-14 lg:w-16 lg:h-16 rounded-full backdrop-blur-sm transition-all z-30 group shadow-lg hover:scale-110">
            <i class="fas fa-chevron-left text-lg  text-white md:text-xl lg:text-2xl"></i>
        </button>
        
        <button onclick="nextSlide()" 
                class="absolute right-2 md:right-4 lg:right-8 top-1/2 -translate-y-1/2  hover:bg-green-700 text-white-100 w-12 h-12 md:w-14 md:h-14 lg:w-16 lg:h-16 rounded-full backdrop-blur-sm transition-all z-30 group shadow-lg hover:scale-110">
            <i class="fas fa-chevron-right text-lg text-white md:text-xl lg:text-2xl"></i>
        </button>
        @endif

        <!-- Indicators - PROFESSIONAL GROUPED STYLE -->
        @if($totalSlides > 1)
        <div class="absolute bottom-20 md:bottom-24 lg:bottom-28 left-1/2 -translate-x-1/2 z-30">
            <div class="bg-black/40 backdrop-blur-md rounded-full px-4 md:px-6 py-3 md:py-4">
                <div class="flex items-center gap-2 md:gap-3">
                    @if($showGroupedIndicators)
                        <!-- Show 5 grouped indicators for many slides -->
                        @for($i = 0; $i < $maxIndicators; $i++)
                            <button onclick="goToSlideGroup({{ $i }})" 
                                    class="carousel-indicator-group transition-all rounded-full
                                           {{ $i === 0 ? 'bg-white w-8 md:w-10 h-2' : 'bg-white/40 w-6 md:w-8 h-2' }}"
                                    aria-label="Go to slide group {{ $i + 1 }}"
                                    data-group="{{ $i }}">
                            </button>
                        @endfor
                        <!-- Slide counter -->
                        <span class="text-white text-xs md:text-sm font-medium ml-2 md:ml-3 min-w-[60px] md:min-w-[70px] text-center">
                            <span id="current-slide">1</span> / {{ $totalSlides }}
                        </span>
                    @else
                        <!-- Show individual indicators for few slides (≤5) -->
                        @foreach($heroActivities as $index => $heroActivity)
                            <button onclick="goToSlide({{ $index }})" 
                                    class="carousel-indicator transition-all rounded-full
                                           {{ $index === 0 ? 'bg-white w-8 md:w-10 h-2' : 'bg-white/40 w-6 md:w-8 h-2' }}"
                                    aria-label="Go to slide {{ $index + 1 }}"
                                    data-index="{{ $index }}">
                            </button>
                        @endforeach
                        <!-- Slide counter for clarity -->
                        <span class="text-white text-xs md:text-sm font-medium ml-2 md:ml-3">
                            <span id="current-slide">1</span> / {{ $totalSlides }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Stats Overlay -->
        <div class="absolute bottom-4 md:bottom-6 right-2 md:right-4 lg:right-8 z-20">
            <div class="bg-black/50 backdrop-blur-md rounded-xl p-3 md:p-4 lg:p-6 text-white">
                <div class="flex flex-col md:flex-row gap-3 md:gap-4 lg:gap-6">
                    <div class="text-center">
                        <div class="text-xl md:text-2xl lg:text-3xl font-bold">{{ $activities->total() }}+</div>
                        <div class="text-[10px] md:text-xs lg:text-sm text-white/80">Activities</div>
                    </div>
                    <div class="hidden md:block w-px bg-white/30"></div>
                    <div class="text-center">
                        <div class="text-xl md:text-2xl lg:text-3xl font-bold">4</div>
                        <div class="text-[10px] md:text-xs lg:text-sm text-white/80">Countries</div>
                    </div>
                    <div class="hidden md:block w-px bg-white/30"></div>
                    <div class="text-center">
                        <div class="text-xl md:text-2xl lg:text-3xl font-bold">100%</div>
                        <div class="text-[10px] md:text-xs lg:text-sm text-white/80">Adventure</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Down Indicator -->
        <div class="absolute bottom-2 md:bottom-4 left-1/2 -translate-x-1/2 animate-bounce z-20 hidden md:block">
            <i class="fas fa-chevron-down text-white text-xl md:text-2xl opacity-70"></i>
        </div>
    </div>

    <!-- Breadcrumb -->
    <nav class="bg-white py-3 border-b shadow-sm">
        <div class="container mx-auto px-4">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('index') }}" class="text-green-600 hover:underline flex items-center">
                    <i class="fas fa-home mr-1"></i>Home
                </a></li>
                <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
                <li class="text-gray-700 font-medium">Activities</li>
            </ol>
        </div>
    </nav>

    <!-- Search & Filters -->
    <div class="bg-white shadow-md py-6 sticky top-0 z-40">
        <div class="container mx-auto px-4">
            <form method="GET" action="{{ route('activities.index') }}" class="space-y-4">
                <!-- Search Bar -->
                <div class="flex flex-wrap gap-3">
                    <div class="flex-1 min-w-[250px]">
                        <div class="relative">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input 
                                type="text" 
                                name="search" 
                                placeholder="Search activities (e.g., gorilla, birding, safari)..." 
                                value="{{ request('search') }}"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                    </div>

                    <select name="category" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 min-w-[180px]">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="destination" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 min-w-[180px]">
                        <option value="">All Destinations</option>
                        @foreach($destinations as $destination)
                            <option value="{{ $destination->id }}" {{ request('destination') == $destination->id ? 'selected' : '' }}>
                                {{ $destination->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="difficulty" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 min-w-[150px]">
                        <option value="">All Levels</option>
                        <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                        <option value="moderate" {{ request('difficulty') == 'moderate' ? 'selected' : '' }}>Moderate</option>
                        <option value="challenging" {{ request('difficulty') == 'challenging' ? 'selected' : '' }}>Challenging</option>
                        <option value="extreme" {{ request('difficulty') == 'extreme' ? 'selected' : '' }}>Extreme</option>
                    </select>

                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition font-medium shadow-md">
                        <i class="fas fa-search mr-2"></i> Search
                    </button>

                    @if(request()->hasAny(['search', 'category', 'destination', 'difficulty']))
                        <a href="{{ route('activities.index') }}" class="text-red-600 px-6 py-3 hover:bg-red-50 rounded-lg transition border border-red-200 flex items-center">
                            <i class="fas fa-times mr-2"></i> Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Active Filters Display -->
    @if(request()->hasAny(['search', 'category', 'destination', 'difficulty']))
    <div class="bg-blue-50 border-b border-blue-100 py-3">
        <div class="container mx-auto px-4">
            <div class="flex items-center flex-wrap gap-2">
                <span class="text-sm text-gray-700 font-medium">Active Filters:</span>
                
                @if(request('search'))
                <span class="inline-flex items-center bg-white px-3 py-1 rounded-full text-sm border">
                    <i class="fas fa-search text-green-600 mr-2"></i>
                    "{{ request('search') }}"
                    <a href="{{ route('activities.index', array_merge(request()->except('search'))) }}" class="ml-2 text-red-500 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
                @endif

                @if(request('category'))
                <span class="inline-flex items-center bg-white px-3 py-1 rounded-full text-sm border">
                    <i class="fas fa-tag text-purple-600 mr-2"></i>
                    {{ $categories->find(request('category'))->name ?? 'Category' }}
                    <a href="{{ route('activities.index', array_merge(request()->except('category'))) }}" class="ml-2 text-red-500 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
                @endif

                @if(request('destination'))
                <span class="inline-flex items-center bg-white px-3 py-1 rounded-full text-sm border">
                    <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                    {{ $destinations->find(request('destination'))->name ?? 'Destination' }}
                    <a href="{{ route('activities.index', array_merge(request()->except('destination'))) }}" class="ml-2 text-red-500 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
                @endif

                @if(request('difficulty'))
                <span class="inline-flex items-center bg-white px-3 py-1 rounded-full text-sm border">
                    <i class="fas fa-chart-line text-orange-600 mr-2"></i>
                    {{ ucfirst(request('difficulty')) }}
                    <a href="{{ route('activities.index', array_merge(request()->except('difficulty'))) }}" class="ml-2 text-red-500 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Activities Grid -->
    <div class="container mx-auto px-4 py-12">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800">
                @if(request('search'))
                    Search Results for "{{ request('search') }}"
                @elseif(request('category'))
                    {{ $categories->find(request('category'))->name ?? 'Category' }} Activities
                @elseif(request('destination'))
                    Activities in {{ $destinations->find(request('destination'))->name ?? 'Destination' }}
                @else
                    All Safari Activities
                @endif
            </h2>
            <p class="text-gray-600 mt-2 flex items-center gap-2">
                <i class="fas fa-info-circle text-green-600"></i>
                Showing <strong>{{ $activities->count() }}</strong> of <strong>{{ $activities->total() }}</strong> adventure activities
            </p>
        </div>

        @if($activities->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($activities as $activity)
                    <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 flex flex-col">
                        <a href="{{ route('activities.show', $activity->slug) }}" class="block flex-1 flex flex-col">
                            <!-- Image -->
                            <div class="relative h-52 bg-gradient-to-br from-gray-200 to-gray-300 overflow-hidden">
                                @if($activity->featured_image)
                                    <img src="{{ asset('storage/' . $activity->featured_image) }}" 
                                         alt="{{ $activity->name }} - Safari Activity in East Africa" 
                                         class="w-full h-full object-cover hover:scale-110 transition duration-500"
                                         loading="lazy">
                                @elseif($activity->image)
                                    <img src="{{ asset('storage/' . $activity->image) }}" 
                                         alt="{{ $activity->name }} - Safari Activity in East Africa" 
                                         class="w-full h-full object-cover hover:scale-110 transition duration-500"
                                         loading="lazy">
                                @elseif($activity->icon)
                                    <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                                        <img src="{{ asset('storage/' . $activity->icon) }}" alt="{{ $activity->name }}" class="w-24 h-24 object-contain">
                                    </div>
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-green-300 to-teal-400 flex items-center justify-center">
                                        <i class="fas fa-hiking text-white text-6xl opacity-50"></i>
                                    </div>
                                @endif
                                
                                <!-- Badges -->
                                <div class="absolute top-3 left-3 flex flex-col gap-2">
                                    @if($activity->is_popular)
                                        <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg flex items-center">
                                            <i class="fas fa-star mr-1"></i> Popular
                                        </span>
                                    @endif
                                    
                                    @if($activity->difficulty_level)
                                        <span class="px-3 py-1 rounded-full text-xs font-bold shadow-lg
                                            {{ $activity->difficulty_level == 'easy' ? 'bg-green-500 text-white' : '' }}
                                            {{ $activity->difficulty_level == 'moderate' ? 'bg-blue-500 text-white' : '' }}
                                            {{ $activity->difficulty_level == 'challenging' ? 'bg-orange-500 text-white' : '' }}
                                            {{ $activity->difficulty_level == 'extreme' ? 'bg-red-500 text-white' : '' }}">
                                            {{ ucfirst($activity->difficulty_level) }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Image Count -->
                                @if($activity->images->count() > 0)
                                <div class="absolute bottom-3 right-3">
                                    <span class="bg-black/70 backdrop-blur-sm text-white px-2 py-1 rounded-lg text-xs flex items-center">
                                        <i class="fas fa-images mr-1"></i> {{ $activity->images->count() }}
                                    </span>
                                </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="p-5 flex-1 flex flex-col">
                                <!-- Category -->
                                @if($activity->category)
                                    <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-800 text-xs rounded-full mb-3 self-start">
                                        @if($activity->category->icon)
                                        <i class="{{ $activity->category->icon }} mr-1"></i>
                                        @endif
                                        {{ $activity->category->name }}
                                    </span>
                                @endif

                                <!-- Title -->
                                <h3 class="text-xl font-bold text-gray-800 mb-2 hover:text-green-600 transition line-clamp-2">
                                    {{ $activity->name }}
                                </h3>

                                <!-- Location -->
                                @if($activity->destination)
                                    <p class="text-sm text-gray-600 mb-3 flex items-center">
                                        <i class="fas fa-map-marker-alt text-green-600 mr-2"></i> 
                                        {{ $activity->destination->name }}
                                        @if($activity->destination->country)
                                            , {{ $activity->destination->country->name }}
                                        @endif
                                    </p>
                                @endif

                                <!-- Description -->
                                <p class="text-sm text-gray-600 line-clamp-3 mb-4 flex-1">
                                    {{ $activity->overview ? Str::limit($activity->overview, 100) : Str::limit($activity->description, 100) }}
                                </p>

                                <!-- Meta Info -->
                                <div class="flex flex-wrap gap-2 mb-4 text-xs text-gray-600">
                                    @if($activity->duration)
                                    <span class="flex items-center bg-gray-100 px-2 py-1 rounded">
                                        <i class="far fa-clock text-blue-600 mr-1"></i> {{ $activity->duration }}
                                    </span>
                                    @endif

                                    @if($activity->min_age)
                                    <span class="flex items-center bg-gray-100 px-2 py-1 rounded">
                                        <i class="fas fa-child text-green-600 mr-1"></i> {{ $activity->min_age }}+ years
                                    </span>
                                    @endif

                                    @if($activity->max_group_size)
                                    <span class="flex items-center bg-gray-100 px-2 py-1 rounded">
                                        <i class="fas fa-users text-purple-600 mr-1"></i> Max {{ $activity->max_group_size }}
                                    </span>
                                    @endif
                                </div>

                                <!-- Price & CTA -->
                                <div class="flex items-center justify-between pt-4 border-t">
                                    @if($activity->price_from)
                                    <div class="text-left">
                                        <p class="text-xs text-gray-500">From</p>
                                        <p class="text-lg font-bold text-green-600">
                                            {{ $activity->currency }} {{ number_format($activity->price_from, 0) }}
                                        </p>
                                    </div>
                                    @else
                                    <div class="text-left">
                                        <p class="text-sm text-gray-500 italic">Price on request</p>
                                    </div>
                                    @endif

                                    <div class="flex items-center text-green-600 text-sm font-semibold hover:text-green-700">
                                        View Details <i class="fas fa-arrow-right ml-2"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <nav class="mt-12" aria-label="Activities pagination">
                {{ $activities->appends(request()->query())->links() }}
            </nav>
        @else
            <!-- No Results -->
            <div class="text-center py-20 bg-white rounded-xl shadow-md">
                <div class="inline-block p-8 bg-gray-100 rounded-full mb-6">
                    <i class="fas fa-search text-gray-400 text-6xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-700 mb-3">No activities found</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">
                    We couldn't find any activities matching your search criteria. 
                    Try adjusting your filters or search terms.
                </p>
                <div class="flex justify-center gap-3 flex-wrap">
                    <a href="{{ route('activities.index') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg inline-flex items-center hover:bg-green-700 transition shadow-md">
                        <i class="fas fa-list mr-2"></i> View All Activities
                    </a>
                    <a href="{{ route('contact') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg inline-flex items-center hover:bg-blue-700 transition shadow-md">
                        <i class="fas fa-envelope mr-2"></i> Contact Us
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Call to Action Section -->
    <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-4">Ready for Your East African Adventure?</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                Let us create a customized safari experience tailored to your interests and preferences
            </p>
            <div class="flex justify-center gap-4 flex-wrap">
                <a href="{{ route('contact') }}" class="bg-white text-green-600 px-8 py-4 rounded-lg font-bold hover:bg-gray-100 transition shadow-lg">
                    <i class="fas fa-paper-plane mr-2"></i> Plan Your Safari
                </a>
                <a href="{{ route('tours.index') }}" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-bold hover:bg-white hover:text-green-600 transition">
                    <i class="fas fa-binoculars mr-2"></i> Browse Tours
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Hero Carousel
let currentSlide = 0;
const slides = document.querySelectorAll('.carousel-slide');
const totalSlides = slides.length;
const maxIndicators = 5;
const showGroupedIndicators = totalSlides > maxIndicators;
let autoPlayInterval;

function updateSlideCounter() {
    const counter = document.getElementById('current-slide');
    if (counter) {
        counter.textContent = currentSlide + 1;
    }
}

function updateIndicators() {
    if (showGroupedIndicators) {
        // Update grouped indicators
        const groups = document.querySelectorAll('.carousel-indicator-group');
        const groupSize = Math.ceil(totalSlides / maxIndicators);
        const activeGroup = Math.floor(currentSlide / groupSize);
        
        groups.forEach((group, index) => {
            if (index === activeGroup) {
                group.classList.remove('bg-white/40', 'w-6', 'md:w-8');
                group.classList.add('bg-white', 'w-8', 'md:w-10');
            } else {
                group.classList.remove('bg-white', 'w-8', 'md:w-10');
                group.classList.add('bg-white/40', 'w-6', 'md:w-8');
            }
        });
    } else {
        // Update individual indicators
        const indicators = document.querySelectorAll('.carousel-indicator');
        indicators.forEach((indicator, index) => {
            if (index === currentSlide) {
                indicator.classList.remove('bg-white/40', 'w-6', 'md:w-8');
                indicator.classList.add('bg-white', 'w-8', 'md:w-10');
            } else {
                indicator.classList.remove('bg-white', 'w-8', 'md:w-10');
                indicator.classList.add('bg-white/40', 'w-6', 'md:w-8');
            }
        });
    }
}

function showSlide(index) {
    // Hide all slides
    slides.forEach(slide => {
        slide.classList.remove('active', 'opacity-100');
        slide.classList.add('opacity-0');
    });
    
    // Show current slide
    if (slides[index]) {
        slides[index].classList.add('active', 'opacity-100');
        slides[index].classList.remove('opacity-0');
    }
    
    updateIndicators();
    updateSlideCounter();
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    showSlide(currentSlide);
    resetAutoPlay();
}

function previousSlide() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    showSlide(currentSlide);
    resetAutoPlay();
}

function goToSlide(index) {
    currentSlide = index;
    showSlide(currentSlide);
    resetAutoPlay();
}

function goToSlideGroup(groupIndex) {
    const groupSize = Math.ceil(totalSlides / maxIndicators);
    const targetSlide = groupIndex * groupSize;
    currentSlide = Math.min(targetSlide, totalSlides - 1);
    showSlide(currentSlide);
    resetAutoPlay();
}

function startAutoPlay() {
    if (totalSlides > 1) {
        autoPlayInterval = setInterval(() => {
            nextSlide();
        }, 5000);
    }
}

function resetAutoPlay() {
    clearInterval(autoPlayInterval);
    startAutoPlay();
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowLeft') {
        previousSlide();
    } else if (e.key === 'ArrowRight') {
        nextSlide();
    }
});

// Touch/Swipe support for mobile
let touchStartX = 0;
let touchEndX = 0;

const carousel = document.getElementById('hero-carousel');

if (carousel) {
    carousel.addEventListener('touchstart', e => {
        touchStartX = e.changedTouches[0].screenX;
    });

    carousel.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });
}

function handleSwipe() {
    if (touchEndX < touchStartX - 50) {
        nextSlide();
    }
    if (touchEndX > touchStartX + 50) {
        previousSlide();
    }
}

// Start autoplay when page loads
if (totalSlides > 1) {
    startAutoPlay();
}

// Pause autoplay when user hovers over carousel (desktop)
if (carousel) {
    carousel.addEventListener('mouseenter', () => {
        clearInterval(autoPlayInterval);
    });

    carousel.addEventListener('mouseleave', () => {
        if (totalSlides > 1) {
            startAutoPlay();
        }
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateIndicators();
    updateSlideCounter();
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
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Carousel Animations */
.carousel-slide {
    transition: opacity 1000ms ease-in-out;
}

.carousel-slide.active {
    opacity: 1 !important;
}

/* Slide-up Animation */
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.animate-slide-up {
    animation: slideUp 0.8s ease-out forwards;
    opacity: 0;
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-out forwards;
}

/* Indicator hover effect */
.carousel-indicator,
.carousel-indicator-group {
    transition: all 0.3s ease;
    cursor: pointer;
}

.carousel-indicator:hover,
.carousel-indicator-group:hover {
    background-color: rgba(255, 255, 255, 0.7) !important;
    transform: scaleY(1.3);
}

/* Navigation button styles */
button[onclick*="Slide"] {
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .carousel-slide h1 {
        font-size: 1.875rem;
        line-height: 1.2;
    }
    
    .carousel-slide p {
        font-size: 0.875rem;
    }
}

@media (max-width: 640px) {
    .carousel-slide h1 {
        font-size: 1.5rem;
    }
}
</style>
@endsection