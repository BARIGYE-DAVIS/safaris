@extends('layouts.app')

@section('title', 'Explore Destinations')

@section('content')
<!-- Hero Section with Background Image -->
<div class="relative h-96 bg-gray-900">
    <!-- Background Image -->
    <img src="{{ asset('images/elizabeth.jpg') }}" alt="Destinations" class="absolute inset-0 w-full h-full object-cover opacity-50">
    
    <!-- Overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-indigo-900/70 to-purple-900/70"></div>
    
    <!-- Content -->
    <div class="relative container mx-auto px-4 h-full flex items-center justify-center">
        <div class="text-center text-white max-w-3xl">
            <h1 class="text-5xl md:text-6xl font-bold mb-4">Explore Amazing Destinations</h1>
            <p class="text-xl md:text-2xl mb-8">Discover the most beautiful places in East Africa</p>
            
            <!-- Search Bar -->
            <form method="GET" action="{{ route('destinations.index') }}" class="flex flex-col sm:flex-row gap-2 max-w-2xl mx-auto">
                <input type="text" name="search" placeholder="Search destinations..." 
                    value="{{ request('search') }}"
                    class="flex-1 px-6 py-4 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-500 text-lg">
                <button type="submit" class="bg-green-600 hover:bg-green-700 px-8 py-4 rounded-lg font-semibold transition text-lg">
                    <i class="fas fa-search mr-2"></i> Search
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Filters Section -->
<div class="bg-white shadow-md sticky top-0 z-10">
    <div class="container mx-auto px-4 py-4">
        <form method="GET" action="{{ route('destinations.index') }}" class="flex flex-wrap gap-4 items-center">
            <!-- Keep search value -->
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            <!-- Country Filter -->
            <select name="country" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" onchange="this.form.submit()">
                <option value="">All Countries</option>
                @foreach($countries as $country)
                    <option value="{{ $country->code }}" {{ request('country') == $country->code ? 'selected' : '' }}>
                        {{ $country->flag_icon }} {{ $country->name }}
                    </option>
                @endforeach
            </select>

            <!-- Popular Filter -->
            <select name="popular" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" onchange="this.form.submit()">
                <option value="">All Destinations</option>
                <option value="1" {{ request('popular') == '1' ? 'selected' : '' }}>⭐ Popular Only</option>
            </select>

            <!-- Reset -->
            @if(request()->hasAny(['search', 'country', 'popular']))
                <a href="{{ route('destinations.index') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                    <i class="fas fa-redo mr-1"></i> Reset Filters
                </a>
            @endif

            <!-- Results Count -->
            <div class="ml-auto text-gray-600 hidden sm:block">
                <i class="fas fa-map-marker-alt mr-1"></i>
                Showing {{ $destinations->total() }} destination(s)
            </div>
        </form>
    </div>
</div>

<!-- Popular Destinations (if no filters) -->
@if(!request()->hasAny(['search', 'country', 'popular']) && $popularDestinations->count() > 0)
<div class="bg-gradient-to-b from-gray-50 to-white py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">⭐ Popular Destinations</h2>
            <p class="text-gray-600 text-lg">Most loved destinations by our travelers</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($popularDestinations as $destination)
            <a href="{{ route('destinations.show', $destination->slug) }}" class="group">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-2xl">
                    <div class="relative h-64 overflow-hidden">
                        @if($destination->image)
                            <img src="{{ asset('storage/' . $destination->image) }}" alt="{{ $destination->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-white text-6xl"></i>
                            </div>
                        @endif
                        <!-- Overlay Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                ⭐ Popular
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">
                                <span class="text-lg mr-1">{{ $destination->country->flag_icon }}</span> {{ $destination->country->name }}
                            </span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-3 group-hover:text-indigo-600 transition">
                            {{ $destination->name }}
                        </h3>
                        <p class="text-gray-600 line-clamp-3 mb-4">
                            {{ $destination->description }}
                        </p>
                        <div class="flex items-center text-indigo-600 font-semibold">
                            Explore Now <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition"></i>
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
            <h2 class="text-4xl font-bold text-gray-800 mb-4">All Destinations</h2>
            <p class="text-gray-600 text-lg">Browse through our complete collection</p>
        </div>
    @else
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800">
                @if(request('country'))
                    Destinations in {{ $countries->firstWhere('code', request('country'))->name ?? 'Selected Country' }}
                @elseif(request('popular'))
                    Popular Destinations
                @elseif(request('search'))
                    Search Results for "{{ request('search') }}"
                @endif
            </h2>
        </div>
    @endif

    @if($destinations->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($destinations as $destination)
            <a href="{{ route('destinations.show', $destination->slug) }}" class="group">
                <div class="bg-white rounded-xl shadow-md overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="relative h-48 overflow-hidden">
                        @if($destination->image)
                            <img src="{{ asset('storage/' . $destination->image) }}" alt="{{ $destination->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                                <i class="fas fa-mountain text-white text-5xl"></i>
                            </div>
                        @endif
                        @if($destination->is_popular)
                            <div class="absolute top-2 right-2">
                                <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-bold">⭐</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <div class="flex items-center mb-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">
                                {{ $destination->country->flag_icon }} {{ $destination->country->name }}
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2 group-hover:text-indigo-600 transition">
                            {{ $destination->name }}
                        </h3>
                        <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                            {{ Str::limit($destination->description, 80) }}
                        </p>
                        <div class="flex items-center text-indigo-600 text-sm font-semibold">
                            View Details <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition"></i>
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
        <div class="text-center py-20">
            <i class="fas fa-search text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-700 mb-2">No destinations found</h3>
            <p class="text-gray-500 mb-6">Try adjusting your search or filters</p>
            <a href="{{ route('destinations.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg inline-block font-semibold">
                <i class="fas fa-home mr-2"></i> View All Destinations
            </a>
        </div>
    @endif
</div>
@endsection