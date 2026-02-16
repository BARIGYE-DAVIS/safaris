<div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
    <!-- Tour Image -->
     <h1 class="sr-only">HOW ARE YOU</h1>
    <div class="relative h-64 overflow-hidden">
        @if($tour->featured_image)
            <img src="{{ asset('storage/' . $tour->featured_image) }}" 
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
        <div class="absolute top-4 left-4">
            <span class="bg-green-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                {{ $tour->category ?? 'Safari' }}
            </span>
        </div>
        
        <!-- Duration Badge -->
        <div class="absolute top-4 right-4">
            <span class="bg-white/90 backdrop-blur text-gray-800 px-3 py-1 rounded-full text-sm font-semibold">
                {{ isset($tour->itineraries) ? count($tour->itineraries) : '5' }} Days
            </span>
        </div>

        <!-- Type Badge -->
        @if(isset($tour->type))
        <div class="absolute bottom-4 left-4">
            <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
                {{ $tour->type }}
            </span>
        </div>
        @endif
    </div>

    <!-- Tour Content -->
    <div class="p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
            {{ $tour->title }}
        </h3>
        
        <p class="text-gray-600 mb-4 line-clamp-3">
            {{ Str::limit($tour->description, 120) }}
        </p>

        <!-- Destinations -->
        <div class="flex items-center mb-4 text-sm text-gray-500">
            <svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span class="line-clamp-1">{{ $tour->destinations ?? 'East Africa' }}</span>
        </div>

        <!-- Pricing -->
        <div class="flex items-center justify-between mb-6">
            <div>
                @if(isset($tour->prices) && $tour->prices->count() > 0)
                    @php $minPrice = $tour->prices->min('price'); @endphp
                    <div class="flex items-baseline">
                        <span class="text-2xl font-bold text-green-600">${{ number_format($minPrice) }}</span>
                        <span class="text-gray-500 text-sm ml-1">per person</span>
                    </div>
                    <p class="text-xs text-gray-400">Starting from</p>
                @else
                    <span class="text-green-600 font-semibold">Contact for Pricing</span>
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
                <span class="text-sm text-gray-600">4.9</span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3">
            <a href="{{ route('tours.show', $tour->slug) }}" 
               class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-3 px-4 rounded-lg font-semibold transition-colors duration-300">
                View Details
            </a>
            <button onclick="quickBook('{{ $tour->slug }}')" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-semibold transition-colors duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </button>
        </div>
    </div>
</div>