@extends('layouts.app')

@section('title', $gallery->title . ' - Safari Photo Gallery')
@section('meta_description', $gallery->meta_description)
@section('meta_keywords', $gallery->meta_keywords)

@push('structured_data')
<script type="application/ld+json">
{!! json_encode($gallery->structured_data) !!}
</script>
@endpush

@push('meta_tags')
<!-- Open Graph / Facebook -->
<meta property="og:type" content="article">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $gallery->title }} - Safari Photo">
<meta property="og:description" content="{{ $gallery->meta_description }}">
<meta property="og:image" content="{{ $gallery->image_url }}">
<meta property="og:image:alt" content="{{ $gallery->alt_text }}">
<meta property="og:site_name" content="Calm Africa Safaris">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ url()->current() }}">
<meta property="twitter:title" content="{{ $gallery->title }}">
<meta property="twitter:description" content="{{ $gallery->meta_description }}">
<meta property="twitter:image" content="{{ $gallery->image_url }}">

<!-- Additional SEO tags -->
<meta name="author" content="Calm Africa Safaris">
<meta name="robots" content="index, follow, max-image-preview:large">
<link rel="canonical" href="{{ url()->current() }}">
@endpush

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-50 border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('gallery.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                            Photo Gallery
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $gallery->category_label }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Image -->
        <div class="lg:col-span-2">
            <article class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Image -->
                <div class="relative">
                    <img src="{{ $gallery->image_url }}" 
                         alt="{{ $gallery->alt_text }}"
                         title="{{ $gallery->title }}"
                         class="w-full h-auto max-h-[80vh] object-contain bg-gray-100"
                         itemprop="image">
                    
                    <!-- Image Actions -->
                    <div class="absolute top-4 right-4 flex space-x-2">
                        <!-- Download Button -->
                        <a href="{{ $gallery->image_url }}" 
                           download="{{ $gallery->slug }}.jpg"
                           class="bg-black/50 hover:bg-black/70 text-white p-2 rounded-full transition-colors"
                           title="Download image">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </a>
                        
                        <!-- Share Button -->
                        <button id="shareBtn" 
                                class="bg-black/50 hover:bg-black/70 text-white p-2 rounded-full transition-colors"
                                title="Share image">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Category Badge -->
                    <div class="absolute bottom-4 left-4">
                        <span class="inline-block bg-green-600/90 backdrop-blur-sm text-white text-sm font-medium px-3 py-1 rounded-full">
                            {{ $gallery->category_label }}
                        </span>
                    </div>
                </div>

                <!-- Image Details -->
                <div class="p-6">
                    <header class="mb-6">
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            {{ $gallery->title }}
                        </h1>
                        
                        @if($gallery->caption)
                            <p class="text-lg text-gray-700 leading-relaxed">
                                {{ $gallery->caption }}
                            </p>
                        @endif
                        
                        <!-- Meta Information -->
                        <div class="flex flex-wrap items-center gap-4 mt-4 text-sm text-gray-600">
                            <time datetime="{{ $gallery->created_at->toISOString() }}" class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $gallery->created_at->format('F j, Y') }}
                            </time>
                            
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                {{ $gallery->category_label }}
                            </span>
                        </div>
                    </header>

                    <!-- Tags -->
                    @if($gallery->formatted_tags)
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($gallery->formatted_tags as $tag)
                                    <span class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-3 py-1 rounded-full transition-colors cursor-default">
                                        {{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Social Sharing -->
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Share this photo</h3>
                        <div class="flex space-x-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                Facebook
                            </a>
                            
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($gallery->title) }}" 
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-blue-400 hover:bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                                Twitter
                            </a>
                            
                            <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}&media={{ urlencode($gallery->image_url) }}&description={{ urlencode($gallery->title) }}" 
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.943-1.378l-.805 3.071c-.287 1.121-1.107 2.522-1.638 3.37 1.24.375 2.533.573 3.888.573 6.624 0 11.99-5.367 11.99-11.987C24.007 5.367 18.641.001 12.017.001z"/>
                                </svg>
                                Pinterest
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Navigation -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Gallery Navigation</h3>
                <div class="space-y-3">
                    <a href="{{ route('gallery.index') }}" 
                       class="flex items-center text-green-600 hover:text-green-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Gallery
                    </a>
                    
                    <a href="{{ route('gallery.index', ['category' => $gallery->category]) }}" 
                       class="flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        More {{ $gallery->category_label }} Photos
                    </a>
                </div>
            </div>

            <!-- Related Images -->
            @if($relatedImages->count() > 0)
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Related Photos</h3>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($relatedImages as $related)
                            <a href="{{ route('gallery.show', $related->slug) }}" 
                               class="group relative aspect-square overflow-hidden rounded-lg">
                                <img src="{{ $related->image_url }}" 
                                     alt="{{ $related->alt_text }}"
                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-300"></div>
                                <div class="absolute bottom-2 left-2 right-2">
                                    <p class="text-white text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 truncate">
                                        {{ $related->title }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('gallery.index', ['category' => $gallery->category]) }}" 
                           class="inline-flex items-center text-sm text-green-600 hover:text-green-700 transition-colors">
                            View all {{ $gallery->category_label }} photos
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="bg-gradient-to-r from-green-600 to-green-700 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">
            Experience This Beauty in Person
        </h2>
        <p class="text-xl text-green-100 mb-8 max-w-2xl mx-auto">
            Join us on a safari adventure and create your own unforgettable memories in Africa's stunning wilderness.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('tours.index') }}" 
               class="inline-flex items-center px-8 py-4 bg-white text-green-700 font-semibold rounded-lg hover:bg-green-50 transition-colors">
                View Safari Tours
            </a>
            <a href="{{ route('contact') }}" 
               class="inline-flex items-center px-8 py-4 bg-green-800 text-white font-semibold rounded-lg hover:bg-green-900 transition-colors">
                Plan Your Safari
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Share functionality
    const shareBtn = document.getElementById('shareBtn');
    
    if (shareBtn && navigator.share) {
        shareBtn.addEventListener('click', async () => {
            try {
                await navigator.share({
                    title: '{{ $gallery->title }}',
                    text: '{{ $gallery->meta_description }}',
                    url: window.location.href
                });
            } catch (err) {
                // Fallback to copy URL
                copyToClipboard();
            }
        });
    } else if (shareBtn) {
        shareBtn.addEventListener('click', copyToClipboard);
    }
    
    function copyToClipboard() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            // Show success message
            showNotification('Link copied to clipboard!');
        });
    }
    
    function showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
});
</script>
@endpush

@push('styles')
<style>
    .aspect-square {
        aspect-ratio: 1 / 1;
    }
</style>
@endpush
@endsection