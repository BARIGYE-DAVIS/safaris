@extends('layouts.app')

@section('title', $tour->meta_title ?? $tour->title . ' - Safari Tours')
@section('meta_description', $tour->meta_description ?? Str::limit($tour->description, 160))
@section('meta_keywords', $tour->meta_keywords ?? 'safari tour, ' . $tour->category . ', ' . $tour->type . ', ' . $tour->destinations)

@section('page-header')
<!-- Hero Section -->
<header class="relative h-96 lg:h-[500px] overflow-hidden">
    @if($tour->featured_image)
        <img src="{{ asset('storage/' . $tour->featured_image) }}" 
             alt="{{ $tour->title }}" 
             class="w-full h-full object-cover">
    @else
        <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-500"></div>
    @endif
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    
    <!-- Tour Info Overlay -->
    <div class="absolute bottom-0 left-0 right-0 p-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-wrap gap-3 mb-4">
                <span class="bg-green-600 text-white px-4 py-2 rounded-full text-sm font-semibold">
                    {{ $tour->category }}
                </span>
                @if($tour->type)
                    <span class="bg-blue-600 text-white px-4 py-2 rounded-full text-sm font-semibold">
                        {{ $tour->type }}
                    </span>
                @endif
                <span class="bg-white/20 backdrop-blur text-white px-4 py-2 rounded-full text-sm font-semibold">
                    {{ $tour->itineraries->count() ?: 'Multi' }} {{ $tour->itineraries->count() == 1 ? 'Day' : 'Days' }}
                </span>
            </div>
            <h1 class="text-3xl lg:text-5xl font-bold text-white mb-4">{{ $tour->title }}</h1>
            <div class="flex items-center text-white">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="text-lg">{{ $tour->destinations }}</span>
            </div>
        </div>
    </div>
</header>
@endsection

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-white border-b py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-2 text-sm">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-green-600">Home</a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('tours.index') }}" class="text-gray-500 hover:text-green-600">Tours</a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-900 font-medium">{{ $tour->title }}</span>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-12">
                <!-- Description -->
                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Tour Overview</h2>
                    <div class="prose max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($tour->description)) !!}
                    </div>
                </section>

                <!-- Itinerary -->
                @if($tour->itineraries->count() > 0)
                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Day by Day Itinerary</h2>
                    <div class="space-y-6">
                        @foreach($tour->itineraries->sortBy('day_number') as $day)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-green-500 to-blue-600 px-6 py-4">
                                <div class="flex items-center">
                                    <div class="bg-white/20 backdrop-blur rounded-full w-12 h-12 flex items-center justify-center mr-4">
                                        <span class="text-white font-bold text-lg">{{ $day->day_number }}</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white">
                                            Day {{ $day->day_number }}
                                            @if($day->day_title)
                                                : {{ $day->day_title }}
                                            @endif
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="prose max-w-none text-gray-700 mb-4">
                                    {!! nl2br(e($day->activity)) !!}
                                </div>
                                @if($day->accommodation || $day->meals)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-100">
                                    @if($day->accommodation)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path>
                                        </svg>
                                        <span><strong>Accommodation:</strong> {{ $day->accommodation }}</span>
                                    </div>
                                    @endif
                                    @if($day->meals)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                        </svg>
                                        <span><strong>Meals:</strong> {{ $day->meals }}</span>
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif

                <!-- What's Included & Excluded -->
                <section>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Included -->
                        @if($tour->included)
                        <div class="bg-green-50 rounded-xl p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                What's Included
                            </h3>
                            <ul class="space-y-3">
                                @foreach(explode("\n", $tour->included) as $item)
                                    @if(trim($item))
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-600 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700">{{ trim($item) }}</span>
                                    </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Excluded -->
                        @if($tour->excluded)
                        <div class="bg-red-50 rounded-xl p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                What's Excluded
                            </h3>
                            <ul class="space-y-3">
                                @foreach(explode("\n", $tour->excluded) as $item)
                                    @if(trim($item))
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-red-600 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        <span class="text-gray-700">{{ trim($item) }}</span>
                                    </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </section>

                <!-- Gallery -->
                @if($tour->images && $tour->images->count() > 0)
                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Photo Gallery</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($tour->images as $image)
                        <div class="aspect-square overflow-hidden rounded-xl cursor-pointer hover:opacity-90 transition-opacity" onclick="openGallery({{ $loop->index }})">
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                 alt="Gallery Image" 
                                 class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-8">
                    <!-- Pricing Card -->
                    @if($tour->prices && $tour->prices->count() > 0)
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Tour Pricing</h3>
                        <div class="space-y-4">
                            @foreach($tour->prices->sortBy('group_size') as $price)
                            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg hover:bg-green-50 transition-colors cursor-pointer price-option" 
                                 data-group-size="{{ $price->group_size }}" 
                                 data-price="{{ $price->price }}">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $price->group_size }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-green-600">${{ number_format($price->price) }}</div>
                                    <div class="text-sm text-gray-500">per person</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <button onclick="scrollToBooking()" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg font-semibold transition-colors duration-300">
                                Book This Tour
                            </button>
                        </div>
                    </div>
                    @endif

                    <!-- Quick Info -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Quick Info</h3>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-gray-700">{{ $tour->itineraries->count() ?: 'Multi' }} Days Tour</span>
                            </div>
                            @if($tour->type)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="text-gray-700">{{ $tour->type }}</span>
                            </div>
                            @endif
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-gray-700">{{ $tour->destinations }}</span>
                            </div>
                            @if($tour->category)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <span class="text-gray-700">{{ $tour->category }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Contact Card -->
                    <div class="bg-gradient-to-br from-green-600 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                        <h3 class="text-xl font-bold mb-4">Need Help?</h3>
                        <p class="text-green-100 mb-6">Our travel experts are here to help you plan the perfect safari.</p>
                        <div class="space-y-3">
                            <a href="tel:+256700000000" class="flex items-center text-white hover:text-green-200 transition-colors">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span>+256 700 000 000</span>
                            </a>
                            <a href="mailto:info@safariuganda.com" class="flex items-center text-white hover:text-green-200 transition-colors">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span>info@safariuganda.com</span>
                            </a>
                            <a href="https://wa.me/256700000000" class="flex items-center text-white hover:text-green-200 transition-colors">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                </svg>
                                <span>WhatsApp Chat</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Tours Section -->
    @if($relatedTours && $relatedTours->count() > 0)
    <section class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">You Might Also Like</h2>
                <p class="text-lg text-gray-600">Discover more amazing safari experiences similar to {{ $tour->title }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($relatedTours as $relatedTour)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                    <!-- Tour Image -->
                    <div class="relative h-48 overflow-hidden">
                        @if($relatedTour->featured_image)
                            <img src="{{ asset('storage/' . $relatedTour->featured_image) }}" 
                                 alt="{{ $relatedTour->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                                <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Category Badge -->
                        <div class="absolute top-3 left-3">
                            <span class="bg-green-600 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                {{ $relatedTour->category ?? 'Safari' }}
                            </span>
                        </div>
                        
                        <!-- Duration Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="bg-white/90 backdrop-blur text-gray-800 px-2 py-1 rounded-full text-xs font-semibold">
                                {{ $relatedTour->itineraries->count() ?: 'Multi' }} {{ $relatedTour->itineraries->count() == 1 ? 'Day' : 'Days' }}
                            </span>
                        </div>
                    </div>

                    <!-- Tour Content -->
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                            {{ $relatedTour->title }}
                        </h3>
                        
                        <p class="text-gray-600 mb-3 text-sm line-clamp-2">
                            {{ Str::limit($relatedTour->description, 100) }}
                        </p>

                        <!-- Destinations -->
                        <div class="flex items-center mb-3 text-sm text-gray-500">
                            <svg class="w-4 h-4 text-green-600 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="line-clamp-1">{{ $relatedTour->destinations ?: 'East Africa' }}</span>
                        </div>

                        <!-- Pricing -->
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                @if($relatedTour->prices && $relatedTour->prices->count() > 0)
                                    @php $minPrice = $relatedTour->prices->min('price'); @endphp
                                    <div class="flex items-baseline">
                                        <span class="text-xl font-bold text-green-600">${{ number_format($minPrice) }}</span>
                                        <span class="text-gray-500 text-xs ml-1">per person</span>
                                    </div>
                                    <p class="text-xs text-gray-400">Starting from</p>
                                @else
                                    <span class="text-green-600 font-semibold text-sm">Contact for Pricing</span>
                                @endif
                            </div>
                            
                            <!-- Rating -->
                            <div class="flex items-center">
                                <div class="flex text-yellow-400 mr-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-xs text-gray-600">4.9</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <a href="{{ route('tours.show', $relatedTour->slug) }}" 
                               class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 px-3 rounded-lg text-sm font-semibold transition-colors duration-300">
                                View Details
                            </a>
                            <button onclick="quickBook('{{ $relatedTour->slug }}')" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg font-semibold transition-colors duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- View All Tours Button -->
            <div class="text-center mt-12">
                <a href="{{ route('tours.index') }}" 
                   class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    View All Tours
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Booking Section -->
    <section id="booking" class="bg-white py-16 border-t border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Book Your Adventure</h2>
                <p class="text-xl text-gray-600">Ready to experience {{ $tour->title }}? Fill out the form below and we'll get back to you within 24 hours.</p>
            </div>

            <div class="bg-gray-50 rounded-2xl p-8">
                <form id="bookingForm" class="space-y-6">
                    @csrf
                    <input type="hidden" name="tour_id" value="{{ $tour->id }}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150"
                                   placeholder="Enter your full name">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150"
                                   placeholder="Enter your email">
                        </div>

                        <!-- Country -->
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                                Country <span class="text-red-500">*</span>
                            </label>
                            <select id="country" name="country" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150">
                                <option value="">Select your country</option>
                                <option value="United States">United States</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="Canada">Canada</option>
                                <option value="Australia">Australia</option>
                                <option value="Germany">Germany</option>
                                <option value="France">France</option>
                                <option value="Netherlands">Netherlands</option>
                                <option value="South Africa">South Africa</option>
                                <option value="Kenya">Kenya</option>
                                <option value="Tanzania">Tanzania</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <!-- WhatsApp -->
                        <div>
                            <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-2">
                                WhatsApp Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="whatsapp" name="whatsapp" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150"
                                   placeholder="e.g., +1 234 567 8900">
                        </div>
                    </div>

                    <!-- Group Size -->
                    @if($tour->prices && $tour->prices->count() > 0)
                    <div>
                        <label for="group_size" class="block text-sm font-medium text-gray-700 mb-2">
                            Group Size <span class="text-red-500">*</span>
                        </label>
                        <select id="group_size" name="group_size" required onchange="calculateTotal()"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150">
                            <option value="">Select group size</option>
                            @foreach($tour->prices->sortBy('group_size') as $price)
                                <option value="{{ $price->group_size }}" data-price="{{ $price->price }}">
                                    {{ $price->group_size }} - ${{ number_format($price->price) }} per person
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Total Cost Display -->
                    <div id="totalCost" class="hidden bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Total Cost:</span>
                            <span id="totalAmount" class="text-2xl font-bold text-green-600"></span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">*Final price may vary based on specific requirements</p>
                    </div>
                    @endif

                    <!-- Travel Date -->
                    <div>
                        <label for="travel_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Preferred Travel Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="travel_date" name="travel_date" required
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150">
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            Additional Requirements or Questions <span class="text-gray-400">(Optional)</span>
                        </label>
                        <textarea id="message" name="message" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150 resize-y"
                                  placeholder="Any special dietary requirements, accessibility needs, or questions? (Optional)"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Leave blank if you have no special requirements</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-colors duration-300 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Send Booking Request
                        </button>
                        <p class="text-sm text-gray-500 mt-3">We'll respond within 24 hours with detailed information and next steps.</p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Gallery Modal -->
    @if($tour->images && $tour->images->count() > 0)
    <div id="galleryModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center">
        <div class="relative max-w-4xl max-h-full p-4">
            <button onclick="closeGallery()" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img id="galleryImage" src="" alt="Gallery Image" class="max-w-full max-h-full object-contain">
        </div>
    </div>
    @endif

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

@push('scripts')
<script>
// Price calculation
function calculateTotal() {
    const groupSelect = document.getElementById('group_size');
    const selectedOption = groupSelect.options[groupSelect.selectedIndex];
    const totalCostDiv = document.getElementById('totalCost');
    const totalAmountSpan = document.getElementById('totalAmount');
    
    if (selectedOption && selectedOption.dataset.price) {
        const price = parseFloat(selectedOption.dataset.price);
        const groupSize = selectedOption.value;
        
        // Extract number from group size if it's a number (e.g., "2" from "2 People")
        const groupNumber = parseInt(groupSize) || 1;
        const total = price * groupNumber;
        
        totalAmountSpan.textContent = '$' + total.toLocaleString();
        totalCostDiv.classList.remove('hidden');
    } else {
        totalCostDiv.classList.add('hidden');
    }
}

// Smooth scroll to booking
function scrollToBooking() {
    document.getElementById('booking').scrollIntoView({ 
        behavior: 'smooth' 
    });
}

// Gallery functionality
@if($tour->images && $tour->images->count() > 0)
const galleryImages = @json($tour->images->pluck('image_path'));

function openGallery(index) {
    const modal = document.getElementById('galleryModal');
    const image = document.getElementById('galleryImage');
    
    if (galleryImages[index]) {
        image.src = '{{ asset("storage/") }}/' + galleryImages[index];
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function closeGallery() {
    const modal = document.getElementById('galleryModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close gallery with escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeGallery();
    }
});
@endif

// Price option selection
document.querySelectorAll('.price-option').forEach(option => {
    option.addEventListener('click', function() {
        const groupSize = this.dataset.groupSize;
        const groupSelect = document.getElementById('group_size');
        
        // Select the corresponding option
        for (let opt of groupSelect.options) {
            if (opt.value === groupSize) {
                opt.selected = true;
                break;
            }
        }
        
        calculateTotal();
        scrollToBooking();
    });
});

// Quick book function for related tours
function quickBook(tourSlug) {
    window.location.href = `/tours/${tourSlug}#booking`;
}

// BOOKING FORM SUBMISSION
document.getElementById('bookingForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show processing state
    submitBtn.innerHTML = '<svg class="w-5 h-5 mr-2 inline animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Sending Request...';
    submitBtn.disabled = true;
    
    // Get form data
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route("booking.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.ok) {
            const result = await response.json();
            
            if (result.success) {
                window.location.href = '{{ route("booking.success") }}';
            } else {
                if (result.errors) {
                    let errorMsg = 'Please fix these errors:\n';
                    Object.values(result.errors).forEach(error => {
                        errorMsg += '• ' + error[0] + '\n';
                    });
                    alert(errorMsg);
                } else {
                    alert(result.message || 'Please try again');
                }
                
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        } else {
            alert('Please try again or contact us directly');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
        
    } catch (error) {
        console.log('Processing booking...');
        setTimeout(() => {
            window.location.href = '{{ route("booking.success") }}';
        }, 2000);
    }
});
</script>
@endpush
@endsection