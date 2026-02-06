@extends('layouts.app')

@section('title', 'Safari Photo Gallery - Stunning Wildlife & Landscape Photography')
@section('meta_description', 'Explore our breathtaking safari photo gallery featuring stunning wildlife photography, African landscapes, and unforgettable safari moments.')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-green-800 via-green-700 to-green-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
            Safari Photo Gallery
        </h1>
        <p class="text-xl md:text-2xl text-green-100 mb-8 max-w-3xl mx-auto">
            Experience the breathtaking beauty of African wildlife through our stunning photography collection.
        </p>
        
        <!-- Search Bar -->
        <div class="max-w-md mx-auto">
            <form method="GET" action="{{ route('gallery.index') }}" class="flex">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Search images..." 
                       class="flex-1 px-4 py-3 rounded-l-lg border-0 focus:ring-2 focus:ring-white">
                <button type="submit" 
                        class="bg-green-900 hover:bg-green-800 text-white px-6 py-3 rounded-r-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Category Filter -->
<div class="bg-white border-b border-gray-200 sticky top-0 z-10 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between py-4 overflow-x-auto">
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-gray-700 whitespace-nowrap">Filter by:</span>
                <div class="flex flex-nowrap gap-2">
                    <a href="{{ route('gallery.index') }}" 
                       class="px-4 py-2 rounded-full text-sm font-medium transition-colors whitespace-nowrap {{ !request('category') ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        All Photos
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('gallery.index', ['category' => $cat]) }}" 
                           class="px-4 py-2 rounded-full text-sm font-medium transition-colors whitespace-nowrap {{ request('category') == $cat ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ ucfirst(str_replace('_', ' ', $cat)) }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="text-sm text-gray-600 whitespace-nowrap ml-4">
                {{ $images->total() }} images
            </div>
        </div>
    </div>
</div>

<!-- Gallery Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($images->count() > 0)
        <!-- Active Filters -->
        @if(request('search') || request('category'))
            <div class="mb-8">
                <div class="flex items-center flex-wrap gap-2">
                    @if(request('search'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            Search: "{{ request('search') }}"
                            <a href="{{ route('gallery.index', ['category' => request('category')]) }}" class="ml-2 text-blue-600 hover:text-blue-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        </span>
                    @endif
                    
                    @if(request('category'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Category: {{ ucfirst(str_replace('_', ' ', request('category'))) }}
                            <a href="{{ route('gallery.index', ['search' => request('search')]) }}" class="ml-2 text-green-600 hover:text-green-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        </span>
                    @endif
                    
                    @if(request('search') || request('category'))
                        <a href="{{ route('gallery.index') }}" class="text-sm text-gray-600 hover:text-gray-800 underline">
                            Clear all filters
                        </a>
                    @endif
                </div>
            </div>
        @endif

        <!-- Image Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
            @foreach($images as $image)
                <article class="group relative bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Image Container -->
                    <div class="relative overflow-hidden h-64">
                        <img src="{{ $image->image_url }}" 
                             alt="{{ $image->alt_text }}"
                             title="{{ $image->title }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                             loading="lazy">
                        
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <!-- View Button -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <a href="{{ route('gallery.show', $image->slug) }}" 
                               class="bg-white/90 backdrop-blur-sm text-gray-900 px-4 py-2 rounded-full font-medium hover:bg-white transition-colors">
                                View Details
                            </a>
                        </div>
                        
                        <!-- Category Badge -->
                        <div class="absolute top-3 left-3">
                            <span class="inline-block bg-green-600/90 backdrop-blur-sm text-white text-xs font-medium px-2 py-1 rounded-full">
                                {{ ucfirst(str_replace('_', ' ', $image->category)) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Image Info -->
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-2 group-hover:text-green-600 transition-colors">
                            <a href="{{ route('gallery.show', $image->slug) }}" class="line-clamp-2">
                                {{ $image->title }}
                            </a>
                        </h3>
                        
                        @if($image->caption)
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                {{ $image->caption }}
                            </p>
                        @endif
                        
                        <!-- Tags -->
                        @if($image->formatted_tags && count($image->formatted_tags) > 0)
                            <div class="flex flex-wrap gap-1 mb-3">
                                @foreach(array_slice($image->formatted_tags, 0, 3) as $tag)
                                    <span class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded">
                                        {{ $tag }}
                                    </span>
                                @endforeach
                                @if(count($image->formatted_tags) > 3)
                                    <span class="text-xs text-gray-400">+{{ count($image->formatted_tags) - 3 }}</span>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Date -->
                        <p class="text-xs text-gray-500">
                            <time datetime="{{ $image->created_at->toISOString() }}">
                                {{ $image->created_at->format('F j, Y') }}
                            </time>
                        </p>
                    </div>
                </article>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $images->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            
            <h3 class="text-xl font-medium text-gray-900 mb-2">No images found</h3>
            <p class="text-gray-600 mb-6">
                @if(request('search') || request('category'))
                    Try adjusting your filters or search terms.
                @else
                    Our photo gallery is currently being updated. Check back soon!
                @endif
            </p>
            
            @if(request('search') || request('category'))
                <a href="{{ route('gallery.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    View All Images
                </a>
            @endif
        </div>
    @endif
</div>

<!-- Call to Action Section -->
<div class="bg-gradient-to-r from-green-600 to-green-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
            Ready to Create Your Own Safari Memories?
        </h2>
        <p class="text-xl text-green-100 mb-8 max-w-2xl mx-auto">
            Join us on an unforgettable safari adventure and capture moments like these yourself.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('tours.index') }}" 
               class="inline-flex items-center px-8 py-4 bg-white text-green-700 font-semibold rounded-lg hover:bg-green-50 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                View Safari Tours
            </a>
            <a href="{{ route('contact') }}" 
               class="inline-flex items-center px-8 py-4 bg-green-800 text-white font-semibold rounded-lg hover:bg-green-900 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                Plan Custom Safari
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
@endsection