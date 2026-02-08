@extends('layouts.app')

@section('title', 'Explore Destinations')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
    <div class="container mx-auto px-4">
        <h1 class="text-5xl font-bold mb-4 text-center">Explore Amazing Destinations</h1>
        <p class="text-xl text-center mb-8">Discover the most beautiful places in East Africa</p>
        
        <!-- Search Bar -->
        <div class="max-w-2xl mx-auto">
            <form method="GET" action="{{ route('destinations.index') }}" class="flex gap-2">
                <input type="text" name="search" placeholder="Search destinations..." 
                    value="{{ request('search') }}"
                    class="flex-1 px-6 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-500">
                <button type="submit" class="bg-green-600 hover:bg-green-700 px-8 py-3 rounded-lg font-semibold transition">
                    Search
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Filters Section -->
<div class="bg-white shadow-md sticky top-0 z-10">
    <div class="container mx-auto px-4 py-4">
        <form method="GET" action="{{ route('destinations.index') }}" class="flex flex-wrap gap-4 items-center">
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
                    Reset Filters
                </a>
            @endif

            <!-- Results Count -->
            <div class="ml-auto text-gray-600">
                Showing {{ $destinations->total() }} destination(s)
            </div>
        </form>
    </div>
</div>

<!-- Popular Destinations (if no filters) -->
@if(!request()->hasAny(['search', 'country', 'popular']) && $popularDestinations->count() > 0)
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">⭐ Popular Destinations</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($popularDestinations as $destination)
            <a href="{{ route('destinations.show', $destination->slug) }}" class="group">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition hover:scale-105 hover:shadow-2xl">
                    @if($destination->image)
                        <img src="{{ asset('storage/' . $destination->image) }}" alt="{{ $destination->name }}" class="w-full h-56 object-cover">
                    @else
                        <div class="w-full h-56 bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-white text-6xl"></i>
                        </div>
                    @endif
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">
                                {{ $destination->country->flag_icon }} {{ $destination->country->name }}
                            </span>
                            <span class="text-yellow-500 text-sm">⭐ Popular</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-indigo-600 transition">
                            {{ $destination->name }}
                        </h3>
                        <p class="text-gray-600 text-sm line-clamp-2">
                            {{ $destination->description }}
                        </p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- All Destinations Grid -->
<div class="container mx-auto px-4 py-12">
    @if(!request()->hasAny(['search', 'country', 'popular']))
        <h2 class="text-3xl font-bold text-gray-800 mb-8">All Destinations</h2>
    @endif

    @if($destinations->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($destinations as $destination)
            <a href="{{ route('destinations.show', $destination->slug) }}" class="group">
                <div class="bg-white rounded-xl shadow-md overflow-hidden transform transition hover:scale-105 hover:shadow-xl">
                    @if($destination->image)
                        <img src="{{ asset('storage/' . $destination->image) }}" alt="{{ $destination->name }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                            <i class="fas fa-mountain text-white text-5xl"></i>
                        </div>
                    @endif
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">
                                {{ $destination->country->flag_icon }} {{ $destination->country->name }}
                            </span>
                            @if($destination->is_popular)
                                <span class="text-yellow-500 text-xs">⭐</span>
                            @endif
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2 group-hover:text-indigo-600 transition">
                            {{ $destination->name }}
                        </h3>
                        <p class="text-gray-600 text-sm line-clamp-2">
                            {{ Str::limit($destination->description, 80) }}
                        </p>
                        <div class="mt-4 flex items-center text-indigo-600 text-sm font-semibold">
                            Explore <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition"></i>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $destinations->links() }}
        </div>
    @else
        <!-- No Results -->
        <div class="text-center py-20">
            <i class="fas fa-search text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-700 mb-2">No destinations found</h3>
            <p class="text-gray-500 mb-6">Try adjusting your search or filters</p>
            <a href="{{ route('destinations.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg inline-block">
                View All Destinations
            </a>
        </div>
    @endif
</div>
@endsection