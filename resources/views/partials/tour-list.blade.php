@php
    // Default values if not passed
    $limit = $limit ?? 10;
    $showExploreButton = $showExploreButton ?? true;
    $columns = $columns ?? 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3';
    $heading = $heading ?? 'Featured Safari Tours';
    $subheading = $subheading ?? 'Discover our most popular safari experiences';
@endphp

<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        

        <!-- Tours Grid -->
        <div class="grid {{ $columns }} gap-6 md:gap-8">
            @forelse($tours as $tour)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                <!-- Tour Image -->
                <div class="relative h-64 overflow-hidden">
                    @if($tour->featured_image)
                        <img src="{{ asset('storage/' . $tour->featured_image) }}" 
                             alt="{{ $tour->title }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @elseif($tour->images && $tour->images->first())
                        <img src="{{ asset('storage/' . $tour->images->first()->image_path) }}" 
                             alt="{{ $tour->title }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Category Badge -->
                    <div class="absolute top-3 left-3">
                        <span class="bg-green-600 text-white px-3 py-1 rounded-full text-sm font-semibold capitalize">
                            {{ $tour->category_label ?? 'Safari' }}
                        </span>
                    </div>
                    
                    <!-- Duration Badge -->
                    <div class="absolute top-3 right-3">
                        <span class="bg-white/90 backdrop-blur text-gray-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $tour->duration_text }}
                        </span>
                    </div>

                    <!-- Type Badge -->
                    @if($tour->type)
                    <div class="absolute bottom-3 left-3">
                        <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-semibold capitalize">
                            {{ $tour->type_label }}
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Tour Content -->
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                        {{ $tour->title }}
                    </h3>
                    
                    <p class="text-gray-600 mb-4 text-base line-clamp-3">
                        {{ Str::limit($tour->description, 120) }}
                    </p>

                    <!-- Destinations -->
                    @if($tour->destinations)
                    <div class="flex items-center mb-4 text-sm text-gray-500">
                        <svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="line-clamp-1">{{ is_array($tour->destinations) ? implode(', ', $tour->destinations_array) : ($tour->destinations ?: 'East Africa') }}</span>
                    </div>
                    @endif

                    <!-- Pricing -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            @if($tour->price && $tour->price > 0)
                                <div class="flex items-baseline">
                                    <span class="text-2xl font-bold text-green-600">{{ $tour->formatted_price }}</span>
                                    <span class="text-gray-500 text-sm ml-1">per person</span>
                                </div>
                                @if($tour->prices && $tour->prices->count() > 1)
                                    <p class="text-xs text-gray-400">Starting from</p>
                                @endif
                            @elseif($tour->prices && $tour->prices->count() > 0)
                                @php $minPrice = $tour->prices->min('price'); @endphp
                                <div class="flex items-baseline">
                                    <span class="text-2xl font-bold text-green-600">${{ number_format($minPrice) }}</span>
                                    <span class="text-gray-500 text-sm ml-1">per person</span>
                                </div>
                                <p class="text-xs text-gray-400">Starting from</p>
                            @else
                                <span class="text-green-600 font-semibold text-base">Contact for Pricing</span>
                            @endif
                        </div>
                        
                        <!-- Rating -->
                        <div class="flex items-center">
                            <div class="flex text-yellow-400 mr-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-600">{{ $tour->average_rating }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <a href="{{ route('tours.show', $tour->slug) }}" 
                           class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-3 px-4 rounded-lg font-semibold transition-colors duration-300">
                            View Details
                        </a>
                        <a href="{{ route('booking.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-semibold transition-colors duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-600 mb-2">No Tours Available</h3>
                <p class="text-gray-500">Check back soon for amazing safari adventures!</p>
            </div>
            @endforelse
        </div>

        <!-- Explore More Button -->
        @if($showExploreButton && $tours->count() >= $limit)
        <div class="text-center mt-12">
            <a href="{{ route('tours.index') }}" 
               class="inline-flex items-center px-8 py-4 bg-green-600 hover:bg-green-700 text-white font-semibold text-lg rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Explore More Tours
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
        @endif
    </div>
</section>

@push('styles')
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
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush